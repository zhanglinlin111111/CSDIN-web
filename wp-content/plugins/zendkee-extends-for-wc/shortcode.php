<?php

/**
 * 增加shortcode，调用“Description x2”的数据
 */

add_shortcode('explain_product_desc2', 'explain_product_desc2');
if (!function_exists('explain_product_desc2')) {
    function explain_product_desc2($attrs)
    {
        if (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            return get_term_meta($term->term_id, 'zendkee_meta_term_x2', true);
        }
    }
}





/**
 * 增加shortcode，调用“Top Position和Top Image”的数据
 */

add_shortcode(
    'explain_top_desc',
    function ($attrs) {
        if (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            return get_term_meta($term->term_id, 'zendkee_meta_term_top_desc', true);
        }
    }
);

add_shortcode(
    'explain_top_image',
    function ($attrs) {
        if (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            $src = get_term_meta($term->term_id, 'zendkee_meta_term_top_image', true);
            if($src){
                return sprintf('<img class="zk_top_image" src="%s" />',$src);
            }
            return '';
        }
    }
);


add_shortcode(
    'explain_bottom_desc',
    function ($attrs) {
        if (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            return get_term_meta($term->term_id, 'zendkee_meta_term_bottom_desc', true);
        }
    }
);

add_shortcode(
    'explain_bottom_image',
    function ($attrs) {
        if (function_exists('is_product_category') && is_product_category()) {
            $term = get_queried_object();
            $src = get_term_meta($term->term_id, 'zendkee_meta_term_bottom_image', true);
            if($src){
                return sprintf('<img class="zk_bottom_image" src="%s" />',$src);
            }
            return '';
        }
    }
);
