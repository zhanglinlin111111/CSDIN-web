<?php

namespace ElementorPro\Modules\Woocommerce\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

const Zendkee_Product_DESC5 = 'zendkee_product_desc5';

class Zendkee_Product_DESC5 extends Base_Tag {
    public function get_name() {
        return 'woocommerce-product-desc-tag-5';
    }

    public function get_title() {
        return __( 'Product DESC 5', 'elementor-pro' );
    }

    public function render() {

        echo get_post_meta(get_the_ID(), Zendkee_Product_DESC5, true);

    }
}






