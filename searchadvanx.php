<?php
/**
 * Plugin Name: SearchAdvanx
 * Plugin URI: https://github.com/abeprangishvili/SearchAdvanx
 * Description: Advanced search engine plugin with Elementor and JetEngine support, including REST API functionality for cross-site searching.
 * Version: 1.1.0
 * Author: Abe Prangishvili
 * Author URI: https://github.com/prangishviliAbe
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('SEARCHADVANX_VERSION', '1.1.0');
define('SEARCHADVANX_PLUGIN_FILE', __FILE__);
define('SEARCHADVANX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SEARCHADVANX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEARCHADVANX_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Initialize Plugin Update Checker
require_once SEARCHADVANX_PLUGIN_DIR . 'plugin-update-checker-master/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Make update checker globally accessible
global $searchadvanx_update_checker;
$searchadvanx_update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/prangishviliAbe/SearchAdvanx/',
    __FILE__,
    'SearchAdvanx'
);

// Set the branch that contains the stable release (optional, defaults to 'master')
$searchadvanx_update_checker->setBranch('main');

// Enable release assets - this allows downloading from GitHub releases
$searchadvanx_update_checker->getVcsApi()->enableReleaseAssets();

// Allow users to configure update settings
add_action('init', function() {
    $options = get_option('searchadvanx_options');
    
    // Check if auto updates are disabled
    if (isset($options['auto_updates']) && !$options['auto_updates']) {
        // Disable automatic update checks
        global $searchadvanx_update_checker;
        if ($searchadvanx_update_checker) {
            remove_action('load-plugins.php', array($searchadvanx_update_checker, 'handleManualCheck'));
        }
    }
    
    // Set custom branch if configured
    if (isset($options['update_branch']) && $options['update_branch'] !== 'main') {
        global $searchadvanx_update_checker;
        if ($searchadvanx_update_checker) {
            $searchadvanx_update_checker->setBranch($options['update_branch']);
        }
    }
});

// Autoloader
spl_autoload_register(function ($class) {
    if (strpos($class, 'SearchAdvanx') === 0) {
        // Handle different class naming patterns
        $class_file = strtolower($class);
        $class_file = str_replace('_', '-', $class_file);
        $class_file = str_replace('searchadvanx-', '', $class_file);
        
        // Map specific classes to their files
        $class_map = [
            'SearchAdvanx_API' => 'includes/class-api.php',
            'SearchAdvanx_Database' => 'includes/class-database.php',
            'SearchAdvanx_Admin' => 'includes/admin/class-admin.php',
            'SearchAdvanx_Elementor_Widget' => 'includes/integrations/class-elementor-widget.php',
            'SearchAdvanx' => 'includes/class-searchadvanx.php'
        ];
        
        if (isset($class_map[$class])) {
            $file_path = SEARCHADVANX_PLUGIN_DIR . $class_map[$class];
            if (file_exists($file_path)) {
                require_once $file_path;
                return;
            }
        }
        
        // Fallback to original pattern matching
        $possible_paths = [
            SEARCHADVANX_PLUGIN_DIR . 'includes/class-' . $class_file . '.php',
            SEARCHADVANX_PLUGIN_DIR . 'includes/admin/class-' . $class_file . '.php',
            SEARCHADVANX_PLUGIN_DIR . 'includes/integrations/class-' . $class_file . '.php',
        ];
        
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                break;
            }
        }
    }
});

// Core files will be loaded by autoloader when needed

/**
 * Main instance of SearchAdvanx.
 *
 * @return SearchAdvanx|false
 */
function searchadvanx() {
    try {
        return SearchAdvanx::get_instance();
    } catch (Exception $e) {
        error_log('SearchAdvanx Error: ' . $e->getMessage());
        return false;
    }
}

// Initialize the plugin
add_action('plugins_loaded', function() {
    if (class_exists('SearchAdvanx')) {
        searchadvanx();
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>SearchAdvanx: Failed to load plugin classes. Please check your installation.</p></div>';
        });
    }
});

// Activation hook
register_activation_hook(__FILE__, function() {
    searchadvanx()->activate();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    searchadvanx()->deactivate();
});

// Uninstall hook
register_uninstall_hook(__FILE__, array('SearchAdvanx', 'uninstall'));