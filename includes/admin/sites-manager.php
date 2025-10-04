<?php
/**
 * Sites Manager Template
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$options = get_option('searchadvanx_options');
$external_sites = isset($options['external_sites_config']) ? $options['external_sites_config'] : array();
?>

<div id="searchadvanx-sites-manager">
    <div id="sites-list">
        <?php if (!empty($external_sites)): ?>
            <?php foreach ($external_sites as $index => $site): ?>
                <div class="site-config" data-index="<?php echo esc_attr($index); ?>">
                    <h4>Site <?php echo esc_html($index + 1); ?> <button type="button" class="remove-site">Remove</button></h4>
                    <table class="form-table">
                        <tr>
                            <th><label>Site Name</label></th>
                            <td><input type="text" name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][name]" value="<?php echo esc_attr($site['name'] ?? ''); ?>" class="regular-text" placeholder="My WordPress Site" /></td>
                        </tr>
                        <tr>
                            <th><label>Site URL</label></th>
                            <td><input type="url" name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][url]" value="<?php echo esc_attr($site['url'] ?? ''); ?>" class="regular-text" placeholder="https://example.com" required /></td>
                        </tr>
                        <tr>
                            <th><label>API Key</label></th>
                            <td><input type="password" name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][api_key]" value="<?php echo esc_attr($site['api_key'] ?? ''); ?>" class="regular-text" placeholder="Optional API Key" /></td>
                        </tr>
                        <tr>
                            <th><label>Custom Endpoint</label></th>
                            <td><input type="text" name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][endpoint]" value="<?php echo esc_attr($site['endpoint'] ?? 'wp-json/searchadvanx/v1/search'); ?>" class="regular-text" placeholder="wp-json/searchadvanx/v1/search" /></td>
                        </tr>
                        <tr>
                            <th><label>Default Post Type</label></th>
                            <td>
                                <select name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][default_post_type]">
                                    <option value="">Any Post Type</option>
                                    <option value="post" <?php selected($site['default_post_type'] ?? '', 'post'); ?>>Posts</option>
                                    <option value="page" <?php selected($site['default_post_type'] ?? '', 'page'); ?>>Pages</option>
                                    <option value="product" <?php selected($site['default_post_type'] ?? '', 'product'); ?>>Products</option>
                                    <option value="custom" <?php selected($site['default_post_type'] ?? '', 'custom'); ?>>Custom</option>
                                </select>
                                <input type="text" name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][custom_post_type]" value="<?php echo esc_attr($site['custom_post_type'] ?? ''); ?>" placeholder="Custom post type" style="margin-left: 10px; <?php echo ($site['default_post_type'] ?? '') !== 'custom' ? 'display:none;' : ''; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th><label>Custom Parameters</label></th>
                            <td>
                                <textarea name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][custom_params]" rows="3" cols="50" placeholder='{"meta_key": "value", "orderby": "date"}'><?php echo esc_textarea($site['custom_params'] ?? ''); ?></textarea>
                                <p class="description">JSON format for additional API parameters</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Active</label></th>
                            <td><input type="checkbox" name="searchadvanx_options[external_sites_config][<?php echo esc_attr($index); ?>][active]" value="1" <?php checked($site['active'] ?? false, 1); ?> /> Enable this site for searches</td>
                        </tr>
                    </table>
                    <button type="button" class="test-site-connection" data-index="<?php echo esc_attr($index); ?>">Test Connection</button>
                    <div class="connection-result" id="result-<?php echo esc_attr($index); ?>"></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <button type="button" id="add-site" class="button button-secondary">Add New Site</button>
</div>