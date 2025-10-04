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
        // Add main menu page in the left sidebar
        add_menu_page(
            'SearchAdvanx',                    // Page title
            'SearchAdvanx',                    // Menu title
            'manage_options',                  // Capability
            'searchadvanx',                    // Menu slug
            array($this, 'admin_page'),        // Callback function
            'dashicons-search',                // Icon (search icon)
            30                                 // Position (after Comments)
        );
        
        // Add submenu pages
        add_submenu_page(
            'searchadvanx',                    // Parent slug
            'Settings',                        // Page title
            'Settings',                        // Menu title
            'manage_options',                  // Capability
            'searchadvanx',                    // Menu slug (same as parent for main page)
            array($this, 'admin_page')         // Callback function
        );
        
        add_submenu_page(
            'searchadvanx',                    // Parent slug
            'External Sites',                  // Page title
            'External Sites',                 // Menu title
            'manage_options',                  // Capability
            'searchadvanx-sites',             // Menu slug
            array($this, 'sites_page')        // Callback function
        );
        
        add_submenu_page(
            'searchadvanx',                    // Parent slug
            'Analytics',                       // Page title
            'Analytics',                      // Menu title
            'manage_options',                  // Capability
            'searchadvanx-analytics',         // Menu slug
            array($this, 'analytics_page')    // Callback function
        );
        
        add_submenu_page(
            'searchadvanx',                    // Parent slug
            'API Documentation',               // Page title
            'API Docs',                       // Menu title
            'manage_options',                  // Capability
            'searchadvanx-api-docs',          // Menu slug
            array($this, 'api_docs_page')     // Callback function
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
        // Load assets on all SearchAdvanx admin pages
        $searchadvanx_pages = array(
            'toplevel_page_searchadvanx',
            'searchadvanx_page_searchadvanx-sites',
            'searchadvanx_page_searchadvanx-analytics', 
            'searchadvanx_page_searchadvanx-api-docs'
        );
        
        if (!in_array($hook, $searchadvanx_pages)) {
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
        $options = get_option('searchadvanx_options');
        $database = new SearchAdvanx_Database();
        $total_searches = $database->get_total_searches();
        ?>
        <div class="wrap">
            <h1>
                <span class="dashicons dashicons-search" style="font-size: 1.2em; margin-right: 8px;"></span>
                SearchAdvanx Dashboard
            </h1>
            
            <!-- Quick Stats Cards -->
            <div class="searchadvanx-dashboard-cards" style="display: flex; gap: 20px; margin: 20px 0;">
                <div class="card" style="flex: 1; padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px;">
                    <h3 style="margin-top: 0;">Total Searches</h3>
                    <p style="font-size: 2em; margin: 0; color: #0073aa;"><?php echo number_format($total_searches); ?></p>
                </div>
                <div class="card" style="flex: 1; padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px;">
                    <h3 style="margin-top: 0;">Plugin Version</h3>
                    <p style="font-size: 2em; margin: 0; color: #00a32a;"><?php echo SEARCHADVANX_VERSION; ?></p>
                </div>
                <div class="card" style="flex: 1; padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px;">
                    <h3 style="margin-top: 0;">Status</h3>
                    <p style="font-size: 2em; margin: 0; color: #00a32a;">Active</p>
                </div>
            </div>
            
            <!-- Quick Settings Form -->
            <div class="searchadvanx-quick-settings" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin: 20px 0;">
                <h2>Quick Settings</h2>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('searchadvanx_settings');
                    do_settings_sections('searchadvanx');
                    submit_button('Save Settings');
                    ?>
                </form>
            </div>
            
            <!-- Quick Access Links -->
            <div class="searchadvanx-quick-links" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
                <div class="card" style="padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px; text-align: center;">
                    <h3><span class="dashicons dashicons-admin-site-alt3"></span> External Sites</h3>
                    <p>Manage external WordPress sites for cross-site searching.</p>
                    <a href="<?php echo admin_url('admin.php?page=searchadvanx-sites'); ?>" class="button button-primary">Manage Sites</a>
                </div>
                <div class="card" style="padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px; text-align: center;">
                    <h3><span class="dashicons dashicons-chart-line"></span> Analytics</h3>
                    <p>View search statistics and performance metrics.</p>
                    <a href="<?php echo admin_url('admin.php?page=searchadvanx-analytics'); ?>" class="button button-primary">View Analytics</a>
                </div>
                <div class="card" style="padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px; text-align: center;">
                    <h3><span class="dashicons dashicons-media-code"></span> API Documentation</h3>
                    <p>Learn how to use the SearchAdvanx REST API.</p>
                    <a href="<?php echo admin_url('admin.php?page=searchadvanx-api-docs'); ?>" class="button button-primary">View API Docs</a>
                </div>
            </div>
            
            <!-- Usage Instructions -->
            <div class="searchadvanx-instructions" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin: 20px 0;">
                <h2>How to Use SearchAdvanx</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <h4>🎯 Shortcode Usage</h4>
                        <p>Add the search form to any post or page:</p>
                        <code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">[searchadvanx]</code>
                    </div>
                    <div>
                        <h4>🧩 Elementor Widget</h4>
                        <p>Find "SearchAdvanx" in your Elementor widgets and drag it to your page.</p>
                    </div>
                    <div>
                        <h4>⚡ JetEngine Integration</h4>
                        <p>Use "SearchAdvanx Query" as a custom query type in JetEngine listings.</p>
                    </div>
                    <div>
                        <h4>🔌 REST API</h4>
                        <p>Use the API endpoints for custom integrations:</p>
                        <code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">/wp-json/searchadvanx/v1/search</code>
                    </div>
                </div>
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
    
    /**
     * Sites management page callback
     */
    public function sites_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div class="searchadvanx-admin-content">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('searchadvanx_settings');
                    echo '<h2>External WordPress Sites Configuration</h2>';
                    echo '<p>Configure external WordPress sites for cross-site searching functionality.</p>';
                    $this->external_sites_manager_callback();
                    submit_button('Save External Sites');
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Analytics page callback
     */
    public function analytics_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div class="searchadvanx-admin-content">
                <?php $this->display_analytics(); ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * API documentation page callback
     */
    public function api_docs_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div class="searchadvanx-admin-content">
                <?php $this->display_api_docs(); ?>
            </div>
        </div>
        <?php
    }
}