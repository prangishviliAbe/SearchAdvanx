<?php
/**
 * Plugin Name: SearchAdvanx (Safe Mode)
 * Plugin URI: https://github.com/prangishviliAbe/SearchAdvanx
 * Description: Advanced search engine plugin with Elementor and JetEngine support, including REST API functionality for cross-site searching.
 * Version: 1.0.1
 * Author: Abe Prangishvili
 * Author URI: https://github.com/prangishviliAbe
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('SEARCHADVANX_VERSION', '1.0.1');
define('SEARCHADVANX_PLUGIN_FILE', __FILE__);
define('SEARCHADVANX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SEARCHADVANX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEARCHADVANX_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Check if all required files exist before loading
 */
function searchadvanx_check_files() {
    $required_files = [
        'includes/class-database.php',
        'includes/class-api.php',
        'includes/admin/class-admin.php', 
        'includes/integrations/class-elementor-widget.php',
        'includes/class-searchadvanx.php'
    ];
    
    $missing_files = [];
    foreach ($required_files as $file) {
        if (!file_exists(SEARCHADVANX_PLUGIN_DIR . $file)) {
            $missing_files[] = $file;
        }
    }
    
    return $missing_files;
}

/**
 * Load plugin files safely
 */
function searchadvanx_load_files() {
    // Check for missing files first
    $missing_files = searchadvanx_check_files();
    if (!empty($missing_files)) {
        add_action('admin_notices', function() use ($missing_files) {
            echo '<div class="notice notice-error"><p>';
            echo 'SearchAdvanx Error: Missing files: ' . implode(', ', $missing_files);
            echo '</p></div>';
        });
        return false;
    }
    
    // Load files with error handling
    $files_to_load = [
        'includes/class-database.php',
        'includes/class-api.php',
        'includes/admin/class-admin.php',
        'includes/integrations/class-elementor-widget.php',
        'includes/class-searchadvanx.php'
    ];
    
    foreach ($files_to_load as $file) {
        $file_path = SEARCHADVANX_PLUGIN_DIR . $file;
        try {
            require_once $file_path;
        } catch (Exception $e) {
            add_action('admin_notices', function() use ($file, $e) {
                echo '<div class="notice notice-error"><p>';
                echo 'SearchAdvanx Error loading ' . $file . ': ' . $e->getMessage();
                echo '</p></div>';
            });
            return false;
        }
    }
    
    return true;
}

/**
 * Main instance of SearchAdvanx.
 *
 * @return SearchAdvanx|false
 */
function searchadvanx() {
    if (!class_exists('SearchAdvanx')) {
        return false;
    }
    
    try {
        return SearchAdvanx::get_instance();
    } catch (Exception $e) {
        error_log('SearchAdvanx Error: ' . $e->getMessage());
        add_action('admin_notices', function() use ($e) {
            echo '<div class="notice notice-error"><p>';
            echo 'SearchAdvanx initialization error: ' . $e->getMessage();
            echo '</p></div>';
        });
        return false;
    }
}

// Initialize the plugin safely
add_action('plugins_loaded', function() {
    // Load files first
    if (!searchadvanx_load_files()) {
        return;
    }
    
    // Initialize plugin
    $instance = searchadvanx();
    if (!$instance) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>SearchAdvanx: Failed to initialize plugin. Please check error logs.</p></div>';
        });
    }
});

// Activation hook with safety checks
register_activation_hook(__FILE__, function() {
    if (searchadvanx_load_files() && function_exists('searchadvanx')) {
        $instance = searchadvanx();
        if ($instance && method_exists($instance, 'activate')) {
            $instance->activate();
        }
    }
});

// Deactivation hook with safety checks
register_deactivation_hook(__FILE__, function() {
    if (searchadvanx_load_files() && function_exists('searchadvanx')) {
        $instance = searchadvanx();
        if ($instance && method_exists($instance, 'deactivate')) {
            $instance->deactivate();
        }
    }
});

// Uninstall hook
register_uninstall_hook(__FILE__, array('SearchAdvanx', 'uninstall'));