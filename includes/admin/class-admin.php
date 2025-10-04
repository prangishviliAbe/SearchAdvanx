<?php
/**
 * SearchAdvanx Admin class
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * SearchAdvanx Admin Class
 */
class SearchAdvanx_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function admin_menu() {
        add_options_page(
            'SearchAdvanx Settings',
            'SearchAdvanx',
            'manage_options',
            'searchadvanx',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Initialize admin settings
     */
    public function admin_init() {
        register_setting('searchadvanx_settings', 'searchadvanx_options');
        
        // Main Settings Section
        add_settings_section(
            'searchadvanx_main',
            'Main Settings',
            array($this, 'settings_section_callback'),
            'searchadvanx'
        );
        
        // API Management Section
        add_settings_section(
            'searchadvanx_api',
            'REST API Management',
            array($this, 'api_section_callback'),
            'searchadvanx'
        );
        
        $this->add_settings_fields();
    }
    
    /**
     * Add settings fields
     */
    private function add_settings_fields() {
        // Main settings fields
        add_settings_field(
            'api_key',
            'API Key',
            array($this, 'api_key_callback'),
            'searchadvanx',
            'searchadvanx_main'
        );
        
        add_settings_field(
            'search_post_types',
            'Searchable Post Types',
            array($this, 'search_post_types_callback'),
            'searchadvanx',
            'searchadvanx_main'
        );
        
        // API management fields
        add_settings_field(
            'external_sites_manager',
            'External Sites Manager',
            array($this, 'external_sites_manager_callback'),
            'searchadvanx',
            'searchadvanx_api'
        );
        
        add_settings_field(
            'api_endpoints',
            'Custom API Endpoints',
            array($this, 'api_endpoints_callback'),
            'searchadvanx',
            'searchadvanx_api'
        );
        
        add_settings_field(
            'api_timeout',
            'API Timeout (seconds)',
            array($this, 'api_timeout_callback'),
            'searchadvanx',
            'searchadvanx_api'
        );
        
        add_settings_field(
            'api_cache_duration',
            'API Cache Duration (minutes)',
            array($this, 'api_cache_duration_callback'),
            'searchadvanx',
            'searchadvanx_api'
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        if ($hook !== 'settings_page_searchadvanx') {
            return;
        }
        
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'searchadvanx-admin',
            SEARCHADVANX_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            SEARCHADVANX_VERSION,
            true
        );
        
        wp_enqueue_style(
            'searchadvanx-admin',
            SEARCHADVANX_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            SEARCHADVANX_VERSION
        );
        
        wp_localize_script('searchadvanx-admin', 'searchadvanx_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('searchadvanx_test_nonce'),
        ));
    }
    
    /**
     * Admin page callback
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>SearchAdvanx Settings</h1>
            
            <div class="nav-tab-wrapper">
                <a href="#settings" class="nav-tab nav-tab-active">Settings</a>
                <a href="#api-management" class="nav-tab">REST API Management</a>
                <a href="#analytics" class="nav-tab">Analytics</a>
                <a href="#api-docs" class="nav-tab">API Documentation</a>
            </div>
            
            <div id="settings" class="tab-content">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('searchadvanx_settings');
                    do_settings_sections('searchadvanx');
                    submit_button();
                    ?>
                </form>
            </div>
            
            <div id="api-management" class="tab-content" style="display: none;">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('searchadvanx_settings');
                    echo '<h2>REST API Configuration</h2>';
                    do_settings_sections('searchadvanx');
                    submit_button('Save API Settings');
                    ?>
                </form>
            </div>
            
            <div id="analytics" class="tab-content" style="display: none;">
                <?php $this->display_analytics(); ?>
            </div>
            
            <div id="api-docs" class="tab-content" style="display: none;">
                <?php $this->display_api_docs(); ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Settings section callback
     */
    public function settings_section_callback() {
        echo '<p>Configure your SearchAdvanx settings below.</p>';
    }
    
    /**
     * API section callback
     */
    public function api_section_callback() {
        echo '<p>Manage external WordPress sites and REST API settings for cross-site searching.</p>';
    }
    
    /**
     * API key field callback
     */
    public function api_key_callback() {
        $options = get_option('searchadvanx_options');
        $api_key = isset($options['api_key']) ? $options['api_key'] : '';
        echo '<input type="password" name="searchadvanx_options[api_key]" value="' . esc_attr($api_key) . '" class="regular-text" />';
        echo '<p class="description">API key for external site authentication.</p>';
    }
    
    /**
     * Search post types field callback
     */
    public function search_post_types_callback() {
        $options = get_option('searchadvanx_options');
        $selected_post_types = isset($options['search_post_types']) ? $options['search_post_types'] : array('post', 'page');
        
        $post_types = get_post_types(array('public' => true), 'objects');
        
        foreach ($post_types as $post_type) {
            $checked = in_array($post_type->name, $selected_post_types) ? 'checked' : '';
            echo '<label><input type="checkbox" name="searchadvanx_options[search_post_types][]" value="' . esc_attr($post_type->name) . '" ' . $checked . '> ' . esc_html($post_type->label) . '</label><br>';
        }
    }
    
    /**
     * External sites manager callback
     */
    public function external_sites_manager_callback() {
        include SEARCHADVANX_PLUGIN_DIR . 'includes/admin/sites-manager.php';
    }
    
    /**
     * API endpoints callback
     */
    public function api_endpoints_callback() {
        $options = get_option('searchadvanx_options');
        $api_endpoints = isset($options['api_endpoints']) ? $options['api_endpoints'] : array(
            'search' => 'wp-json/searchadvanx/v1/search',
            'external_search' => 'wp-json/searchadvanx/v1/external-search'
        );
        
        ?>
        <table class="form-table">
            <tr>
                <th><label>Search Endpoint</label></th>
                <td>
                    <input type="text" name="searchadvanx_options[api_endpoints][search]" value="<?php echo esc_attr($api_endpoints['search']); ?>" class="regular-text" />
                    <p class="description">Default: wp-json/searchadvanx/v1/search</p>
                </td>
            </tr>
            <tr>
                <th><label>External Search Endpoint</label></th>
                <td>
                    <input type="text" name="searchadvanx_options[api_endpoints][external_search]" value="<?php echo esc_attr($api_endpoints['external_search']); ?>" class="regular-text" />
                    <p class="description">Default: wp-json/searchadvanx/v1/external-search</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * API timeout callback
     */
    public function api_timeout_callback() {
        $options = get_option('searchadvanx_options');
        $timeout = isset($options['api_timeout']) ? $options['api_timeout'] : 30;
        echo '<input type="number" name="searchadvanx_options[api_timeout]" value="' . esc_attr($timeout) . '" min="5" max="120" class="small-text" />';
        echo '<p class="description">Timeout for external API requests (5-120 seconds).</p>';
    }
    
    /**
     * API cache duration callback
     */
    public function api_cache_duration_callback() {
        $options = get_option('searchadvanx_options');
        $cache_duration = isset($options['api_cache_duration']) ? $options['api_cache_duration'] : 5;
        echo '<input type="number" name="searchadvanx_options[api_cache_duration]" value="' . esc_attr($cache_duration) . '" min="0" max="1440" class="small-text" />';
        echo '<p class="description">Cache duration for API responses (0-1440 minutes, 0 = no cache).</p>';
    }
    
    /**
     * Display analytics
     */
    private function display_analytics() {
        $database = new SearchAdvanx_Database();
        $analytics = $database->get_search_analytics();
        
        include SEARCHADVANX_PLUGIN_DIR . 'includes/admin/analytics.php';
    }
    
    /**
     * Display API documentation
     */
    private function display_api_docs() {
        include SEARCHADVANX_PLUGIN_DIR . 'includes/admin/api-docs.php';
    }
}