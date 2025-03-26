<?php

namespace ElementorPro\Modules\Woocommerce\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

const Zendkee_Product_DESC1 = 'zendkee_product_desc1';


class Zendkee_Widget_Product_DESC_1 extends Base_Widget {

    public function get_name() {
        return 'woocommerce-product-short-description1';
    }

    public function get_title() {
        return __( 'Product DESC 1', 'elementor-pro' );
    }

    public function get_icon() {
        return 'eicon-product-description';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'text', 'description', 'product' , 'desc' ];
    }

    protected function register_controls() {

    }

    protected function render() {

        echo get_post_meta(get_the_ID(), Zendkee_Product_DESC1, true);
    }

    public function render_plain_content() {}
}


\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zendkee_Widget_Product_DESC_1() );