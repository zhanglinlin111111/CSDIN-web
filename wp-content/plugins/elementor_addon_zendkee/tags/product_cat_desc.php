<?php
namespace ElementorPro\Modules\Woocommerce\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



class Zendkee_Product_Category_DESC2 extends Base_Tag {
    public function get_name() {
        return 'woocommerce-product-cat-desc-tag-1';
    }

    public function get_title() {
        return __( 'Product Category DESC 2', 'elementor-pro' );
    }

    public function render() {
        if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
            $term = get_queried_object();
            $zendkee_product_cat_desc2 = get_term_meta($term->term_id, 'zendkee_product_cat_desc2', true);
            if ( $term && ! empty( $zendkee_product_cat_desc2 ) ) {
                echo wp_kses_post( $zendkee_product_cat_desc2 );
            }
        }
    }
}


