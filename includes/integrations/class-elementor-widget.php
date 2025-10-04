<?php
/**
 * SearchAdvanx Elementor Widget
 *
 * @package SearchAdvanx
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * SearchAdvanx Elementor Widget Class
 */
class SearchAdvanx_Elementor_Widget extends \Elementor\Widget_Base {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'searchadvanx';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return __('SearchAdvanx', 'searchadvanx');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-search';
    }
    
    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['general'];
    }
    
    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'searchadvanx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $this->get_post_types(),
            ]
        );
        
        $this->add_control(
            'placeholder',
            [
                'label' => __('Placeholder Text', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Search...', 'searchadvanx'),
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Search', 'searchadvanx'),
            ]
        );
        
        $this->add_control(
            'show_filters',
            [
                'label' => __('Show Filters', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'searchadvanx'),
                'label_off' => __('Hide', 'searchadvanx'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'results_per_page',
            [
                'label' => __('Results Per Page', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50,
                'step' => 1,
                'default' => 10,
            ]
        );
        
        $this->add_control(
            'include_external',
            [
                'label' => __('Include External Sites', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'searchadvanx'),
                'label_off' => __('No', 'searchadvanx'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Search connected external sites along with local content', 'searchadvanx'),
            ]
        );
        
        $this->add_control(
            'display_style',
            [
                'label' => __('Display Style', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'list',
                'options' => [
                    'list' => __('List View', 'searchadvanx'),
                    'grid-cards' => __('Grid Cards', 'searchadvanx'),
                    'grid-modern' => __('Modern Grid', 'searchadvanx'),
                ],
                'description' => __('Choose how search results are displayed', 'searchadvanx'),
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'searchadvanx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'input_color',
            [
                'label' => __('Input Text Color', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .searchadvanx-input' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .searchadvanx-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'results_bg_color',
            [
                'label' => __('Results Background', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .searchadvanx-result-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'results_title_color',
            [
                'label' => __('Results Title Color', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .searchadvanx-result-item h3 a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'results_text_color',
            [
                'label' => __('Results Text Color', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .searchadvanx-result-item p' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'grid_gap',
            [
                'label' => __('Grid Gap', 'searchadvanx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .searchadvanx-results-list' => 'gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'display_style!' => 'list',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Pro Features Section
        $this->start_controls_section(
            'pro_features_section',
            [
                'label' => __('🚀 Pro Features', 'searchadvanx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'pro_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">
                        <h3 style="color: white; margin: 0 0 10px 0;">✨ SearchAdvanx Pro Coming Soon!</h3>
                        <p style="margin: 0 0 15px 0; opacity: 0.9;">Advanced search filters, premium themes, analytics dashboard, and more!</p>
                        <a href="https://github.com/prangishviliAbe/SearchAdvanx" target="_blank" style="display: inline-block; background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 20px; text-decoration: none; border: 1px solid rgba(255,255,255,0.3);">
                            ⭐ Star on GitHub for Updates
                        </a>
                    </div>
                ',
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $shortcode_atts = array(
            'post_type' => $settings['post_type'],
            'placeholder' => $settings['placeholder'],
            'button_text' => $settings['button_text'],
            'show_filters' => $settings['show_filters'] === 'yes' ? 'true' : 'false',
            'results_per_page' => $settings['results_per_page'],
            'include_external' => $settings['include_external'] === 'yes' ? 'true' : 'false',
            'display_style' => $settings['display_style'],
        );
        
        $searchadvanx = SearchAdvanx::get_instance();
        echo $searchadvanx->search_shortcode($shortcode_atts);
    }
    
    /**
     * Get available post types
     */
    private function get_post_types() {
        $post_types = get_post_types(array('public' => true), 'objects');
        $options = array();
        
        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }
        
        return $options;
    }
}