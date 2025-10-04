<?php
/**
 * Analytics Template
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<h2>Search Analytics</h2>

<div class="analytics-stats">
    <div class="stat-box">
        <h3>Total Searches</h3>
        <p class="stat-number"><?php echo esc_html($analytics['total_searches']); ?></p>
    </div>
</div>

<h3>Popular Search Terms</h3>
<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th>Search Query</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($analytics['popular_searches'])): ?>
            <?php foreach ($analytics['popular_searches'] as $search): ?>
            <tr>
                <td><?php echo esc_html($search->search_query); ?></td>
                <td><?php echo esc_html($search->count); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">No search data available yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h3>Recent Searches</h3>
<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th>Search Query</th>
            <th>Results Found</th>
            <th>Date</th>
            <th>IP Address</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($analytics['recent_searches'])): ?>
            <?php foreach ($analytics['recent_searches'] as $search): ?>
            <tr>
                <td><?php echo esc_html($search->search_query); ?></td>
                <td><?php echo esc_html($search->results_found); ?></td>
                <td><?php echo esc_html($search->search_date); ?></td>
                <td><?php echo esc_html($search->ip_address); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No search data available yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>