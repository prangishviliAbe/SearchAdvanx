<?php
/**
 * SearchAdvanx Database class
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * SearchAdvanx Database Class
 */
class SearchAdvanx_Database {
    
    /**
     * Initialize database
     */
    public function init() {
        // Database is initialized during activation
    }
    
    /**
     * Create database tables
     */
    public function create_tables() {
        $this->create_search_logs_table();
    }
    
    /**
     * Create search logs table
     */
    private function create_search_logs_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'searchadvanx_logs';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            search_query varchar(255) NOT NULL,
            results_found int(11) NOT NULL DEFAULT 0,
            search_date datetime DEFAULT CURRENT_TIMESTAMP,
            ip_address varchar(45),
            user_agent text,
            PRIMARY KEY (id),
            KEY search_query (search_query),
            KEY search_date (search_date)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Get total number of searches
     */
    public function get_total_searches() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'searchadvanx_logs';
        
        // Check if table exists first
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return 0;
        }
        
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }
    
    /**
     * Get search analytics
     */
    public function get_search_analytics() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'searchadvanx_logs';
        
        return array(
            'total_searches' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
            'recent_searches' => $wpdb->get_results("SELECT * FROM $table_name ORDER BY search_date DESC LIMIT 20"),
            'popular_searches' => $wpdb->get_results("SELECT search_query, COUNT(*) as count FROM $table_name GROUP BY search_query ORDER BY search_query DESC LIMIT 10"),
        );
    }
}