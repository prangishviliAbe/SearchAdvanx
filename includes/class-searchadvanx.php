<?php
/**
 * Main SearchAdvanx class
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Main SearchAdvanx Class
 */
class SearchAdvanx {
    
    /**
     * Single instance of SearchAdvanx
     *
     * @var SearchAdvanx
     */
    private static $instance = null;
    
    /**
     * SearchAdvanx API instance
     *
     * @var SearchAdvanx_API
     */
    public $api;
    
    /**
     * SearchAdvanx Admin instance
     *
     * @var SearchAdvanx_Admin
     */
    public $admin;
    
    /**
     * SearchAdvanx Database instance
     *
     * @var SearchAdvanx_Database
     */
    public $database;
    
    /**
     * Get single instance of SearchAdvanx
     *
     * @return SearchAdvanx
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
        $this->init_classes();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('pre_get_posts', array($this, 'modify_search_query'));
        add_shortcode('searchadvanx', array($this, 'search_shortcode'));
        
        // AJAX hooks
        add_action('wp_ajax_searchadvanx_search', array($this, 'ajax_search'));
        add_action('wp_ajax_nopriv_searchadvanx_search', array($this, 'ajax_search'));
        
        // Integration hooks
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widgets'));
        
        // JetEngine integration
        add_filter('jet-engine/listing/custom-query', array($this, 'jet_engine_custom_query'), 10, 2);
        add_filter('jet-engine/query-builder/types/search-query', array($this, 'jet_engine_query_type'));
    }
    
    /**
     * Initialize classes
     */
    private function init_classes() {
        try {
            if (class_exists('SearchAdvanx_Database')) {
                $this->database = new SearchAdvanx_Database();
            }
            
            if (class_exists('SearchAdvanx_API')) {
                $this->api = new SearchAdvanx_API();
            }
            
            if (is_admin() && class_exists('SearchAdvanx_Admin')) {
                $this->admin = new SearchAdvanx_Admin();
            }
        } catch (Exception $e) {
            error_log('SearchAdvanx class initialization error: ' . $e->getMessage());
        }
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain
        load_plugin_textdomain('searchadvanx', false, dirname(SEARCHADVANX_PLUGIN_BASENAME) . '/languages');
        
        // Initialize database
        $this->database->init();
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_script('jquery');
        
        wp_enqueue_script(
            'searchadvanx-js',
            SEARCHADVANX_PLUGIN_URL . 'assets/js/searchadvanx.js',
            array('jquery'),
            SEARCHADVANX_VERSION,
            true
        );
        
        wp_enqueue_style(
            'searchadvanx-css',
            SEARCHADVANX_PLUGIN_URL . 'assets/css/searchadvanx.css',
            array(),
            SEARCHADVANX_VERSION
        );
        
        wp_enqueue_style(
            'searchadvanx-display-styles',
            SEARCHADVANX_PLUGIN_URL . 'assets/css/display-styles.css',
            array(),
            SEARCHADVANX_VERSION
        );
        
        wp_localize_script('searchadvanx-js', 'searchadvanx_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('searchadvanx_nonce'),
            'rest_url' => rest_url('searchadvanx/v1/')
        ));
    }
    
    /**
     * AJAX search handler
     */
    public function ajax_search() {
        check_ajax_referer('searchadvanx_nonce', 'nonce');
        
        $query = sanitize_text_field($_POST['query'] ?? '');
        $post_type = sanitize_text_field($_POST['post_type'] ?? 'post');
        $include_external = isset($_POST['include_external']) && $_POST['include_external'] === 'true';
        $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
        
        // Debug logging
        error_log('SearchAdvanx AJAX Search Debug:');
        error_log('Query: ' . $query);
        error_log('Include External: ' . ($include_external ? 'true' : 'false'));
        error_log('POST data: ' . print_r($_POST, true));
        
        if ($include_external) {
            // Use external search endpoint for combined results
            $request = new WP_REST_Request('POST', '/searchadvanx/v1/external-search');
            $request->set_param('query', $query);
            $request->set_param('filters', array(
                'post_type' => $post_type,
                'posts_per_page' => intval($_POST['posts_per_page'] ?? 10)
            ));
            
            // Get external sites configuration
            $options = get_option('searchadvanx_options', array());
            $external_sites = isset($options['external_sites_config']) ? $options['external_sites_config'] : array();
            if (!empty($external_sites)) {
                $sites_config = array();
                foreach ($external_sites as $site) {
                    if (!empty($site['url']) && !empty($site['api_key'])) {
                        $sites_config[] = array(
                            'url' => $site['url'],
                            'api_key' => $site['api_key'],
                            'name' => $site['name'] ?? '',
                            'endpoint' => $site['endpoint'] ?? 'wp-json/searchadvanx/v1/search'
                        );
                    }
                }
                $request->set_param('sites', $sites_config);
            }
            
            $response = $this->api->external_search($request);
        } else {
            // Use local search endpoint
            $request = new WP_REST_Request('GET', '/searchadvanx/v1/search');
            $request->set_param('query', $query);
            $request->set_param('post_type', $post_type);
            
            if (!empty($filters['meta_query'])) {
                $request->set_param('meta_query', $filters['meta_query']);
            }
            
            $response = $this->api->rest_search($request);
        }
        
        wp_send_json($response->get_data());
    }
    
    /**
     * Modify main search query
     */
    public function modify_search_query($query) {
        if (!is_admin() && $query->is_main_query() && $query->is_search()) {
            // Extend search to include custom fields
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => '_searchadvanx_searchable_content',
                    'value' => $query->get('s'),
                    'compare' => 'LIKE',
                ),
            );
            
            $query->set('meta_query', $meta_query);
        }
        
        return $query;
    }
    
    /**
     * Search shortcode
     */
    public function search_shortcode($atts) {
        $atts = shortcode_atts(array(
            'post_type' => 'post',
            'placeholder' => 'Search...',
            'button_text' => 'Search',
            'show_filters' => 'false',
            'results_per_page' => '10',
            'template' => 'default',
            'include_external' => 'false',
            'display_style' => 'list',
        ), $atts);
        
        ob_start();
        include SEARCHADVANX_PLUGIN_DIR . 'templates/search-form.php';
        return ob_get_clean();
    }
    
    /**
     * Register Elementor widgets
     */
    public function register_elementor_widgets() {
        if (class_exists('Elementor\Widget_Base')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new SearchAdvanx_Elementor_Widget());
        }
    }
    
    /**
     * JetEngine custom query integration
     */
    public function jet_engine_custom_query($query, $settings) {
        if (isset($settings['searchadvanx_enabled']) && $settings['searchadvanx_enabled'] === 'yes') {
            $search_query = isset($settings['searchadvanx_query']) ? $settings['searchadvanx_query'] : '';
            
            if (!empty($search_query)) {
                $query['s'] = $search_query;
                
                // Add meta query for custom fields search
                if (!isset($query['meta_query'])) {
                    $query['meta_query'] = array();
                }
                
                $query['meta_query'][] = array(
                    'key' => '_searchadvanx_searchable_content',
                    'value' => $search_query,
                    'compare' => 'LIKE',
                );
            }
        }
        
        return $query;
    }
    
    /**
     * JetEngine query type integration
     */
    public function jet_engine_query_type($query_types) {
        $query_types['searchadvanx'] = __('SearchAdvanx Query', 'searchadvanx');
        return $query_types;
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        $this->database->create_tables();
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Plugin uninstall
     */
    public static function uninstall() {
        global $wpdb;
        
        // Drop database tables
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}searchadvanx_logs");
        
        // Delete options
        delete_option('searchadvanx_options');
        
        // Clear any cached data
        wp_cache_flush();
    }
}