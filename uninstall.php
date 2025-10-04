<?php
/**
 * SearchAdvanx Uninstall
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Clean up plugin data on uninstall
 */
function searchadvanx_uninstall_cleanup() {
    global $wpdb;
    
    // Drop database tables
    $table_name = $wpdb->prefix . 'searchadvanx_logs';
    $wpdb->query("DROP TABLE IF EXISTS {$table_name}");
    
    // Delete options
    delete_option('searchadvanx_options');
    delete_option('searchadvanx_version');
    
    // Clear any cached data
    wp_cache_flush();
    
    // Delete transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_searchadvanx_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_searchadvanx_%'");
    
    // Clear any scheduled events
    wp_clear_scheduled_hook('searchadvanx_cleanup');
}

// Run cleanup
searchadvanx_uninstall_cleanup();