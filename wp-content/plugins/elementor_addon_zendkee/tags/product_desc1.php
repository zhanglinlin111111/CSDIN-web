<?php

namespace ElementorPro\Modules\Woocommerce\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

const Zendkee_Product_DESC1 = 'zendkee_product_desc1';

class Zendkee_Product_DESC1 extends Base_Tag {
    public function get_name() {
        return 'woocommerce-product-desc-tag-1';
    }

    public function get_title() {
        return __( 'Product DESC 1', 'elementor-pro' );
    }

    public function render() {

        echo get_post_meta(get_the_ID(), Zendkee_Product_DESC1, true);

    }
}






