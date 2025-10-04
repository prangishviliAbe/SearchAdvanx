<?php
/**
 * Search Form Template
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="searchadvanx-container" data-post-type="<?php echo esc_attr($atts['post_type']); ?>" data-results-per-page="<?php echo esc_attr($atts['results_per_page']); ?>">
    <form class="searchadvanx-form">
        <div class="searchadvanx-input-group">
            <input type="text" name="searchadvanx_query" placeholder="<?php echo esc_attr($atts['placeholder']); ?>" class="searchadvanx-input">
            <button type="submit" class="searchadvanx-button"><?php echo esc_html($atts['button_text']); ?></button>
        </div>
        
        <?php if ($atts['show_filters'] === 'true'): ?>
        <div class="searchadvanx-filters">
            <select name="searchadvanx_post_type" class="searchadvanx-filter">
                <option value="">All Post Types</option>
                <?php
                $post_types = get_post_types(array('public' => true), 'objects');
                foreach ($post_types as $post_type) {
                    echo '<option value="' . esc_attr($post_type->name) . '">' . esc_html($post_type->label) . '</option>';
                }
                ?>
            </select>
            
            <select name="searchadvanx_orderby" class="searchadvanx-filter">
                <option value="relevance">Relevance</option>
                <option value="date">Date</option>
                <option value="title">Title</option>
            </select>
        </div>
        <?php endif; ?>
    </form>
    
    <div class="searchadvanx-results"></div>
    <div class="searchadvanx-loading" style="display: none;">Searching...</div>
</div>