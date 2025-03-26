<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 11:25
 */




if(zendkee_get_option('remove_meta_og')){

//    //去掉 <meta property="og:title" content="xxxxxxxxxxx" /> 标签
//    add_filter( 'wpseo_opengraph_title', function($title, $presentation){
//
//        return '';
//
//    } , 20 , 2 );
//
//    //去掉 <meta property="og:description" content="xxxxxxxxxxx" /> 标签
//    add_filter( 'wpseo_opengraph_desc', function($title, $presentation){
//
//        return '';
//
//    } , 20 , 2 );

    //去掉 <meta property="og:wpseo_og_locale" content="xxxxxxxxxxx" /> 标签
    add_filter( 'wpseo_og_locale', function($title, $presentation){

        return '';

    } , 20 , 2 );
}










if(zendkee_get_option('auto_complete_alt')){

    add_filter( 'wp_get_attachment_image_attributes', function($attr, $attachment, $size){

        if(isset($attr['alt']) && trim($attr['alt']) == ''){
            $attr['alt'] = get_image_alt_by_url($attr['src']);

            if(zendkee_get_option('add_site_title_to_alt')){
                //ALT属性前面加上Site Title
                $attr['alt'] = get_option('blogname') . ' ' . $attr['alt'];
            }

        }
        return $attr;

    } , 1000 , 3 );






    add_filter( 'intermediate_image_sizes_advanced', function($new_sizes, $image_meta, $attachment_id ){

        $alt = get_post_meta($attachment_id , '_wp_attachment_image_alt' , true);
        if(trim($alt)==''){
            $url = isset($image_meta['original_image']) ? $image_meta['original_image'] : $image_meta['file'];

            $alt = get_image_alt_by_url($url);

            if(zendkee_get_option('add_site_title_to_alt')) {
                //ALT属性前面加上Site Title
                $alt = get_option('blogname') . ' ' . $alt;
            }

            update_post_meta($attachment_id , '_wp_attachment_image_alt' ,$alt);
        }

        return $new_sizes;

    } , 100 , 3);
}



//add_filter('pre_get_document_title' , function($title){
////    var_dump($title);
//    $v = WPSEO_Meta::get_value('title' , 728);
//    var_dump($v);
////    exit;
//} , 1 , 1);

/** 注册使用%%hot_product%% **/
function zendkee_hot_product($var , $args){
    return zendkee_get_option('hot_product');
}

add_action('wpseo_register_extra_replacements',function(){
    WPSEO_Replace_Vars::register_replacement( 'hot_product', 'zendkee_hot_product', 'advanced', 'zendkee hot product' );
});

/** 注册使用%%industry_name%% **/
function zendkee_industry_name($var , $args){
    return zendkee_get_option('industry_name');
}

add_action('wpseo_register_extra_replacements',function(){
    WPSEO_Replace_Vars::register_replacement( 'industry_name', 'zendkee_industry_name', 'advanced', 'zendkee industry name' );
});
