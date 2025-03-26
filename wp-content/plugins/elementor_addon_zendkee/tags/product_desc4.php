<?php

namespace ElementorPro\Modules\Woocommerce\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

const Zendkee_Product_DESC4 = 'zendkee_product_desc4';

class Zendkee_Product_DESC4 extends Base_Tag {
    public function get_name() {
        return 'woocommerce-product-desc-tag-4';
    }

    public function get_title() {
        return __( 'Product DESC 4', 'elementor-pro' );
    }

    public function render() {

        echo get_post_meta(get_the_ID(), Zendkee_Product_DESC4, true);

    }
}






