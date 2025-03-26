<?php
namespace ElementorPro\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



class Zendkee_Widget_Product_CATEGORY_DESC2 extends Base_Widget {

    public function get_name() {
        return 'woocommerce-archive-description-2';
    }

    public function get_title() {
        return __( 'Archive Description 2', 'elementor-pro' );
    }

    public function get_icon() {
        return 'eicon-product-description';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'text', 'description', 'category', 'product', 'archive' ];
    }

    public function get_categories() {
        return [
            'woocommerce-elements-archive',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_product_description_style_2',
            [
                'label' => __( 'Style', 'elementor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wc_style_warning_2',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'elementor-pro' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_responsive_control(
            'text_align_2',
            [
                'label' => __( 'Alignment', 'elementor-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementor-pro' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementor-pro' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementor-pro' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'elementor-pro' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_color_2',
            [
                'label' => __( 'Text Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .term-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __( 'Typography', 'elementor-pro' ),
                'selector' => '.woocommerce {{WRAPPER}} .term-description',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
            $term = get_queried_object();
            $zendkee_product_cat_desc2 = get_term_meta($term->term_id, 'zendkee_product_cat_desc2', true);
            if ( $term && ! empty( $zendkee_product_cat_desc2 ) ) {
                echo '<div class="term-description">' . wc_format_content( wp_kses_post( $zendkee_product_cat_desc2 ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }
    }

    public function render_plain_content() {}



}


\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zendkee_Widget_Product_CATEGORY_DESC2() );


