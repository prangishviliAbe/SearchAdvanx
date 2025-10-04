<?php
/**
 * Plugin Name: SearchAdvanx
 * Plugin URI: https://github.com/abeprangishvili/SearchAdvanx
 * Description: Advanced search engine plugin with Elementor and JetEngine support, including REST API functionality for cross-site searching.
 * Version: 1.0.0
 * Author: Abe Prangishvili
 * Author URI: https://abeprangishvili.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: searchadvanx
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * Network: false
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('SEARCHADVANX_VERSION', '1.0.0');
define('SEARCHADVANX_PLUGIN_FILE', __FILE__);
define('SEARCHADVANX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SEARCHADVANX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEARCHADVANX_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader
spl_autoload_register(function ($class) {
    if (strpos($class, 'SearchAdvanx') === 0) {
        $class_file = str_replace('_', '-', strtolower($class));
        $class_file = str_replace('searchadvanx-', '', $class_file);
        
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

// Include required files
require_once SEARCHADVANX_PLUGIN_DIR . 'includes/class-database.php';
require_once SEARCHADVANX_PLUGIN_DIR . 'includes/class-api.php';
require_once SEARCHADVANX_PLUGIN_DIR . 'includes/admin/class-admin.php';
require_once SEARCHADVANX_PLUGIN_DIR . 'includes/integrations/class-elementor-widget.php';

// Include core files
require_once SEARCHADVANX_PLUGIN_DIR . 'includes/class-searchadvanx.php';

/**
 * Main instance of SearchAdvanx.
 *
 * @return SearchAdvanx
 */
function searchadvanx() {
    return SearchAdvanx::get_instance();
}

// Initialize the plugin
add_action('plugins_loaded', 'searchadvanx');

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