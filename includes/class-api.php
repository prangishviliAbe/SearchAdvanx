<?php
/**
 * SearchAdvanx API class
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * SearchAdvanx API Class
 */
class SearchAdvanx_API {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        add_action('wp_ajax_searchadvanx_test_connection', array($this, 'ajax_test_connection'));
    }
    
    /**
     * Register REST API routes
     */
    public function register_rest_routes() {
        register_rest_route('searchadvanx/v1', '/search', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_search'),
            'permission_callback' => '__return_true',
            'args' => array(
                'query' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'post_type' => array(
                    'required' => false,
                    'type' => 'string',
                    'default' => 'post',
                ),
                'meta_query' => array(
                    'required' => false,
                    'type' => 'array',
                ),
                'posts_per_page' => array(
                    'required' => false,
                    'type' => 'integer',
                    'default' => 10,
                ),
                'site_url' => array(
                    'required' => false,
                    'type' => 'string',
                ),
            ),
        ));
        
        register_rest_route('searchadvanx/v1', '/external-search', array(
            'methods' => 'POST',
            'callback' => array($this, 'external_search'),
            'permission_callback' => array($this, 'check_api_permissions'),
            'args' => array(
                'sites' => array(
                    'required' => false,
                    'type' => 'array',
                ),
                'query' => array(
                    'required' => true,
                    'type' => 'string',
                ),
                'filters' => array(
                    'required' => false,
                    'type' => 'array',
                ),
            ),
        ));
    }
    
    /**
     * REST API search endpoint
     */
    public function rest_search($request) {
        $query = $request->get_param('query');
        $post_type = $request->get_param('post_type');
        $meta_query = $request->get_param('meta_query');
        $posts_per_page = $request->get_param('posts_per_page');
        $site_url = $request->get_param('site_url');
        
        // If external site search
        if ($site_url && $site_url !== home_url()) {
            return $this->search_external_site($site_url, $query, $post_type, $meta_query, $posts_per_page);
        }
        
        $args = array(
            's' => $query,
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'post_status' => 'publish',
        );
        
        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }
        
        $search_query = new WP_Query($args);
        $results = array();
        
        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                $post_id = get_the_ID();
                
                $result = array(
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'content' => get_the_excerpt(),
                    'url' => get_permalink(),
                    'date' => get_the_date('c'),
                    'author' => get_the_author(),
                    'featured_image' => get_the_post_thumbnail_url($post_id, 'medium'),
                    'post_type' => get_post_type(),
                );
                
                // Add custom fields for JetEngine compatibility
                $custom_fields = get_post_meta($post_id);
                $result['meta'] = array();
                foreach ($custom_fields as $key => $value) {
                    if (!str_starts_with($key, '_')) {
                        $result['meta'][$key] = is_array($value) ? $value[0] : $value;
                    }
                }
                
                $results[] = $result;
            }
        }
        
        wp_reset_postdata();
        
        // Log the search
        $this->log_search($query, count($results));
        
        return new WP_REST_Response(array(
            'success' => true,
            'results' => $results,
            'total' => $search_query->found_posts,
            'query' => $query,
        ), 200);
    }
    
    /**
     * External search endpoint
     */
    public function external_search($request) {
        $sites = $request->get_param('sites');
        $query = $request->get_param('query');
        $filters = $request->get_param('filters');
        
        // Debug logging
        error_log('SearchAdvanx External Search Debug:');
        error_log('Query: ' . $query);
        error_log('Sites provided: ' . print_r($sites, true));
        
        // Initialize results array
        $all_results = array();
        
        // Start with local search results
        $local_request = new WP_REST_Request('GET', '/searchadvanx/v1/search');
        $local_request->set_param('query', $query);
        $local_request->set_param('post_type', $filters['post_type'] ?? 'post');
        $local_request->set_param('posts_per_page', $filters['posts_per_page'] ?? 10);
        
        $local_response = $this->rest_search($local_request);
        $local_data = $local_response->get_data();
        
        if (isset($local_data['results'])) {
            foreach ($local_data['results'] as $result) {
                $result['source_name'] = 'Local';
                $result['source_site'] = home_url();
                $all_results[] = $result;
            }
        }
        
        error_log('Local search results: ' . count($all_results));
        
        // If no sites provided, use configured sites
        if (empty($sites)) {
            $options = get_option('searchadvanx_options');
            $configured_sites = isset($options['external_sites_config']) ? $options['external_sites_config'] : array();
            error_log('Configured sites from options: ' . print_r($configured_sites, true));
            $sites = array();
            
            foreach ($configured_sites as $site_config) {
                if (!empty($site_config['url']) && !empty($site_config['api_key'])) {
                    $sites[] = $site_config;
                }
            }
            error_log('Valid sites for searching: ' . print_r($sites, true));
        }
        
        $options = get_option('searchadvanx_options');
        $timeout = isset($options['api_timeout']) ? intval($options['api_timeout']) : 30;
        $cache_duration = isset($options['api_cache_duration']) ? intval($options['api_cache_duration']) : 5;
        
        foreach ($sites as $site) {
            $site_url = trailingslashit($site['url']);
            $api_key = isset($site['api_key']) ? $site['api_key'] : '';
            $endpoint = isset($site['endpoint']) ? $site['endpoint'] : 'wp-json/searchadvanx/v1/search';
            
            // Build search URL
            $search_url = $site_url . ltrim($endpoint, '/');
            
            // Prepare request parameters
            $request_params = array(
                'query' => $query,
                'post_type' => isset($filters['post_type']) ? $filters['post_type'] : (isset($site['default_post_type']) ? $site['default_post_type'] : 'post'),
                'posts_per_page' => isset($filters['posts_per_page']) ? $filters['posts_per_page'] : 10,
            );
            
            // Add custom parameters if configured
            if (!empty($site['custom_params'])) {
                $custom_params = json_decode($site['custom_params'], true);
                if (is_array($custom_params)) {
                    $request_params = array_merge($request_params, $custom_params);
                }
            }
            
            // Check cache first
            $cache_key = 'searchadvanx_' . md5($search_url . serialize($request_params));
            if ($cache_duration > 0) {
                $cached_result = get_transient($cache_key);
                if ($cached_result !== false) {
                    $all_results = array_merge($all_results, $cached_result);
                    continue;
                }
            }
            
            $args = array(
                'timeout' => $timeout,
                'headers' => array(),
            );
            
            if (!empty($api_key)) {
                $args['headers']['Authorization'] = 'Bearer ' . $api_key;
            }
            
            $response = wp_remote_get($search_url . '?' . http_build_query($request_params), $args);
            
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $body = json_decode(wp_remote_retrieve_body($response), true);
                if (isset($body['results'])) {
                    $site_results = array();
                    foreach ($body['results'] as $result) {
                        $result['source_site'] = $site_url;
                        $result['source_name'] = isset($site['name']) ? $site['name'] : $site_url;
                        $site_results[] = $result;
                    }
                    
                    // Cache the results
                    if ($cache_duration > 0) {
                        set_transient($cache_key, $site_results, $cache_duration * 60);
                    }
                    
                    $all_results = array_merge($all_results, $site_results);
                }
            }
        }
        
        return new WP_REST_Response(array(
            'success' => true,
            'results' => $all_results,
            'total' => count($all_results),
            'sites_searched' => count($sites),
        ), 200);
    }
    
    /**
     * Search external site
     */
    private function search_external_site($site_url, $query, $post_type, $meta_query, $posts_per_page) {
        $api_url = trailingslashit($site_url) . 'wp-json/searchadvanx/v1/search';
        
        $args = array(
            'timeout' => 30,
            'body' => array(
                'query' => $query,
                'post_type' => $post_type,
                'posts_per_page' => $posts_per_page,
            ),
        );
        
        if (!empty($meta_query)) {
            $args['body']['meta_query'] = $meta_query;
        }
        
        $response = wp_remote_get($api_url . '?' . http_build_query($args['body']), $args);
        
        if (is_wp_error($response)) {
            return new WP_REST_Response(array(
                'success' => false,
                'error' => 'Failed to connect to external site',
            ), 500);
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return new WP_REST_Response($body, wp_remote_retrieve_response_code($response));
    }
    
    /**
     * AJAX test connection handler
     */
    public function ajax_test_connection() {
        check_ajax_referer('searchadvanx_test_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $test_url = sanitize_url($_POST['test_url']);
        $api_key = sanitize_text_field($_POST['api_key']);
        
        $args = array(
            'timeout' => 10,
            'headers' => array(),
        );
        
        if (!empty($api_key)) {
            $args['headers']['Authorization'] = 'Bearer ' . $api_key;
        }
        
        $response = wp_remote_get($test_url, $args);
        
        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($response_code === 200) {
            if (isset($body['success']) || isset($body['results'])) {
                wp_send_json_success('API endpoint is working correctly');
            } else {
                wp_send_json_error('API responded but format is unexpected');
            }
        } else {
            wp_send_json_error('HTTP ' . $response_code . ': ' . wp_remote_retrieve_response_message($response));
        }
    }
    
    /**
     * Check API permissions
     */
    public function check_api_permissions($request) {
        $api_key = $request->get_header('Authorization');
        
        if (empty($api_key)) {
            return true; // Allow public access for now
        }
        
        // Remove 'Bearer ' prefix if present
        $api_key = str_replace('Bearer ', '', $api_key);
        
        $options = get_option('searchadvanx_options');
        $stored_api_key = isset($options['api_key']) ? $options['api_key'] : '';
        
        return !empty($stored_api_key) && $api_key === $stored_api_key;
    }
    
    /**
     * Log search query
     */
    private function log_search($query, $results_found) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'searchadvanx_logs';
        
        $wpdb->insert(
            $table_name,
            array(
                'search_query' => $query,
                'results_found' => $results_found,
                'ip_address' => $this->get_client_ip(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            ),
            array('%s', '%d', '%s', '%s')
        );
    }
    
    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }
}