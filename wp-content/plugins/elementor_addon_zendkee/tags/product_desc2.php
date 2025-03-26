<?php

namespace ElementorPro\Modules\Woocommerce\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

const Zendkee_Product_DESC2 = 'zendkee_product_desc2';

class Zendkee_Product_DESC2 extends Base_Tag {
    public function get_name() {
        return 'woocommerce-product-desc-tag-2';
    }

    public function get_title() {
        return __( 'Product DESC 2', 'elementor-pro' );
    }

    public function render() {

        echo get_post_meta(get_the_ID(), Zendkee_Product_DESC2, true);

    }
}






