<?php

/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 11:25
 */




$image_hide_title = zendkee_get_option('image_hide_title');
$disable_builtin_size = zendkee_get_option('disable_builtin_size');
$disable_image_size = zendkee_get_option('disable_image_size');

$product_detail_contact_form = zendkee_get_option('product_detail_contact_form');
$jumpto = zendkee_get_option('jumpto');
$float_contact_form = zendkee_get_option('float_contact_form');
//$float_button_base_color = zendkee_get_option('float_button_base_color') ? zendkee_get_option('float_button_base_color') : '#555555';
$float_button_bg_color = zendkee_get_option('float_button_bg_color') ? zendkee_get_option('float_button_bg_color') : '#ffffff';

$scroll_top = zendkee_get_option('scroll_top');
$enable_float_form = zendkee_get_option('enable_float_form');

$enable_frontend_js = zendkee_get_option('enable_frontend_js');
$frontend_js = zendkee_get_option('frontend_js');
$enable_backend_js = zendkee_get_option('enable_backend_js');
$backend_js = zendkee_get_option('backend_js');

$frontend_css_position = zendkee_get_option('frontend_css_position');



/* add google multi language dropdown select start  */
$menu_to_add_language_bar = zendkee_get_option('menu_to_add_language_bar');
$menu_to_add_search_bar = zendkee_get_option('menu_to_add_search_bar');



add_filter('wp_nav_menu_items', 'zendkee_add_google_language_selecter', 10, 2);
if (!function_exists('zendkee_add_google_language_selecter')) {
    function zendkee_add_google_language_selecter($items, $args)
    {
        global $menu_to_add_language_bar;
        global $menu_to_add_search_bar;

        static $google_dropdown_display = false;

        if ($menu_to_add_language_bar) {
            if ($google_dropdown_display === false) {
                foreach ($menu_to_add_language_bar as $menu_slug) {
                    if (is_object($args) && property_exists($args, 'menu') 
						&& is_object($args->menu) && property_exists($args->menu, 'slug') 
						&& $menu_slug == $args->menu->slug) {
                        $items .= '<li class="google_translate_element_wrapper"><div id="google_translate_element_' . $menu_slug . '"></div></li>';
                        $google_dropdown_display = true;
                    } elseif (property_exists($args, 'menu') && $args->menu == $menu_slug) {
                        $items .= '<li class="google_translate_element_wrapper"><div id="google_translate_element_' . $menu_slug . '"></div></li>';
                        $google_dropdown_display = true;
                    }
                }
            }
        }

        if ($menu_to_add_search_bar) {
            foreach ($menu_to_add_search_bar as $menu_slug) {
                if (is_object($args) && property_exists($args, 'menu') 
						&& is_object($args->menu) && property_exists($args->menu, 'slug') 
						&& $menu_slug == $args->menu->slug) {
                    $items .= '<li class="fusion-custom-menu-item fusion-main-menu-search">
                        <a class="fusion-main-menu-icon fusion-bar-highlight" href="#" aria-label="Search" data-title="Search" title="Search"></a>
                        <div class="fusion-custom-menu-item-contents">
                            <form role="search" class="searchform fusion-search-form  fusion-search-form-classic" method="get" action="/">
                                <div class="fusion-search-form-content">
                                    <div class="fusion-search-field search-field">
                                        <label>
                                            <span class="screen-reader-text">搜索：</span>
                                            <input type="search" value="" name="s" class="s" placeholder="搜索..." required="" aria-required="true" aria-label="">
                                        </label>
                                    </div>
                                    <div class="fusion-search-button search-button">
                                        <input type="submit" class="fusion-search-submit searchsubmit" value="">
                                    </div>
                                </div>
                            </form>
                        </div>
                      </li>';
                }
            }
        }

        return $items;
    }
}


add_action('wp_footer', function () {
    global $menu_to_add_language_bar;
    global $menu_to_add_search_bar;

    if ($menu_to_add_language_bar) {
        $translate_these = zendkee_get_option('translate_these');
        $html = '';
        if (!empty($menu_to_add_language_bar)) {
            $html .= '<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" ></script>
              <script type="text/javascript">';
            $html .= 'function googleTranslateElementInit() {';
            foreach ($menu_to_add_language_bar as $menu_slug) {
                $html .= sprintf('new google.translate.TranslateElement(
              {
                includedLanguages: "%s",
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
              },
              "google_translate_element_%s"
            );', ($translate_these ? implode(',', $translate_these) : ''), $menu_slug);
            }
            $html .= '}';
            $html .= '</script>';
        }
        echo $html;
    }

    if ($menu_to_add_search_bar) {

        //........................

    }
});


// 禁用自动生成的图片尺寸
if ($disable_builtin_size) {
    if (!function_exists('shapeSpace_disable_image_sizes')) {
        function shapeSpace_disable_image_sizes($sizes)
        {
            global $disable_image_size;

            foreach (array_keys($sizes) as $key) {
                if (in_array($key, $disable_image_size)) {
                    unset($sizes[$key]);
                }
            }

            return $sizes;
        }
    }


    add_filter('intermediate_image_sizes_advanced', 'shapeSpace_disable_image_sizes', 20, 1);
    //    add_action('intermediate_image_sizes_advanced', 'shapeSpace_disable_image_sizes');
} //disable builtin size


if ($image_hide_title) {
    add_action('wp_footer', function () {
        echo PHP_EOL . '<script>
jQuery(document).ready(function(){
    jQuery("img").removeAttr("title");
});
</script>' . PHP_EOL;
    });
}



//开启支持webp图片
if (zendkee_get_option('enable_webp')) {
    if (!function_exists('zendkee_filter_mime_types')) {
        function zendkee_filter_mime_types($array)
        {
            $array['webp'] = 'image/webp';
            return $array;
        }
    }

    add_filter('mime_types', 'zendkee_filter_mime_types', 10, 1);

    if (!function_exists('zendkee_file_is_displayable_image')) {
        function zendkee_file_is_displayable_image($result, $path)
        {
            $info = @getimagesize($path);
            if ($info['mime'] == 'image/webp') {
                $result = true;
            }
            return $result;
        }
    }

    add_filter('file_is_displayable_image', 'zendkee_file_is_displayable_image', 10, 2);
}



/* add google multi language dropdown select end  */


//移除菜单的多余CSS选择器
if (zendkee_get_option('remove_menu_id_class')) {
    add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
    add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
    add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
    if (!function_exists('my_css_attributes_filter')) {
        function my_css_attributes_filter($var)
        {
            return is_array($var) ? array_intersect($var, array('current-menu-item', 'current-post-ancestor', 'current-menu-ancestor', 'current-menu-parent')) : '';
        }
    }
}


//自定义编辑器字体
if (zendkee_get_option('enable_custom_font')) {



    //添加新的字体
    if (!function_exists('custum_fontfamily')) {
        function custum_fontfamily($initArray)
        {

            $zk_font_name = zendkee_get_option('zk_font_name');
            $zk_font_value = zendkee_get_option('zk_font_value');

            $font_add = [];
            foreach ($zk_font_name as $key => $font_name) {
                $font_add[] = sprintf("%s=%s;", $font_name, $zk_font_value[$key]);
            }

            $font_custom1 = "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆';";
            $font_custom2 = implode("", $font_add);
            $font_origin = "Andale Mono=andale mono,monospace;Arial=arial,helvetica,sans-serif;Arial Black=arial black,sans-serif;Book Antiqua=book antiqua,palatino,serif;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,palatino,serif;Helvetica=helvetica,arial,sans-serif;Impact=impact,sans-serif;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco,monospace;Times New Roman=times new roman,times,serif;Trebuchet MS=trebuchet ms,geneva,sans-serif;Verdana=verdana,geneva,sans-serif;Webdings=webdings;Wingdings=wingdings;";

            $initArray['font_formats'] = $font_custom1 . $font_custom2 . $font_origin;

            return $initArray;
        }
    }

    add_filter('tiny_mce_before_init', 'custum_fontfamily', 20);




    //自定义字体大小
    if (!function_exists('wpex_mce_text_sizes')) {
        function wpex_mce_text_sizes($initArray)
        {
            $initArray['fontsize_formats'] = "8px 9px 10px 12px 13px 14px 16px 18px 20px 21px 24px 28px 32px 36px 48px 60px 72px 96px";
            return $initArray;
        }
    }
    add_filter('tiny_mce_before_init', 'wpex_mce_text_sizes');
}








//Google Code


/* Google Code Start */
//run GA shortcode in <head>
if (!function_exists('zendkee_ga_code')) {
    function zendkee_ga_code()
    {
        $misc_ga = zendkee_get_option('misc_ga');
        if (zendkee_get_option("enable_page_code") && is_page()) {
            $page_id = get_queried_object_id();
            if ($page_id == zendkee_get_option("misc_page_code_pageid")) {
                $misc_ga = zendkee_get_option("misc_ga_page[thanks]") ? zendkee_get_option("misc_ga_page[thanks]") : $misc_ga;
            }
        }

        if ($misc_ga) {
            echo do_shortcode($misc_ga);
        }
    }
}

add_action('wp_head', 'zendkee_ga_code');


if (!function_exists('zendkee_gsc_code')) {
    function zendkee_gsc_code()
    {
        $misc_gsc = zendkee_get_option('misc_gsc');
        if (zendkee_get_option("enable_page_code") && is_page()) {
            $page_id = get_queried_object_id();
            if ($page_id == zendkee_get_option("misc_page_code_pageid")) {
                $misc_gsc = zendkee_get_option("misc_gsc_page[thanks]") ? zendkee_get_option("misc_gsc_page[thanks]") : $misc_gsc;
            }
        }
        if ($misc_gsc) {
            echo $misc_gsc;
        }
    }
}

add_action('wp_head', 'zendkee_gsc_code', 5);


//run GTM header shortcode in <head>
if (!function_exists('zendkee_gtm_head_code')) {
    function zendkee_gtm_head_code()
    {
        $misc_gtm_header = zendkee_get_option('misc_gtm_header');
        if (zendkee_get_option("enable_page_code") && is_page()) {
            $page_id = get_queried_object_id();
            if ($page_id == zendkee_get_option("misc_page_code_pageid")) {
                $misc_gtm_header = zendkee_get_option("misc_gtm_header_page[thanks]") ? zendkee_get_option("misc_gtm_header_page[thanks]") : $misc_gtm_header;
            }
        }
        if ($misc_gtm_header) {
            echo do_shortcode($misc_gtm_header);
        }
    }
}

add_action('wp_head', 'zendkee_gtm_head_code');


//run GTM footer shortcode close to <body>
if (!function_exists('zendkee_gtm_foot_code_start')) {
    function zendkee_gtm_foot_code_start()
    {
        $misc_gtm_footer = zendkee_get_option('misc_gtm_footer');
        if (zendkee_get_option("enable_page_code") && is_page()) {
            $page_id = get_queried_object_id();
            if ($page_id == zendkee_get_option("misc_page_code_pageid")) {
                $misc_gtm_footer = zendkee_get_option("misc_gtm_footer_page[thanks]") ? zendkee_get_option("misc_gtm_footer_page[thanks]") : $misc_gtm_footer;
            }
        }
        if ($misc_gtm_footer) {
            if (!is_admin()) {
                ob_start('zendkee_filter_final_html');
            }
        }
    }
}

add_action('init', 'zendkee_gtm_foot_code_start', 1);
if (!function_exists('zendkee_gtm_foot_code_end')) {
    function zendkee_gtm_foot_code_end()
    {
        $misc_gtm_footer = zendkee_get_option('misc_gtm_footer');
        if (zendkee_get_option("enable_page_code") && is_page()) {
            $page_id = get_queried_object_id();
            if ($page_id == zendkee_get_option("misc_page_code_pageid")) {
                $misc_gtm_footer = zendkee_get_option("misc_gtm_footer_page[thanks]") ? zendkee_get_option("misc_gtm_footer_page[thanks]") : $misc_gtm_footer;
            }
        }
        if ($misc_gtm_footer) {
            if (!is_admin()) {
                if (ob_get_length()) {
                    ob_end_flush();
                }
            }
        }
    }
}

add_action('shutdown', 'zendkee_gtm_foot_code_end', 100000);
if (!function_exists('zendkee_filter_final_html')) {
    function zendkee_filter_final_html($buffer)
    {
        if (!is_admin()) {
            $misc_gtm_footer = zendkee_get_option('misc_gtm_footer');
            if (zendkee_get_option("enable_page_code") && is_page()) {
                $page_id = get_queried_object_id();
                if ($page_id == zendkee_get_option("misc_page_code_pageid")) {
                    $misc_gtm_footer = zendkee_get_option("misc_gtm_footer_page[thanks]") ? zendkee_get_option("misc_gtm_footer_page[thanks]") : $misc_gtm_footer;
                }
            }
            if ($misc_gtm_footer) {
                $buffer = str_replace($misc_gtm_footer, "", $buffer);

                /*replace all script tag to special tag start*/
                $i = 0;
                //script tag replace
                $script_array = array();
                $buffer = preg_replace_callback("/(<script[^>]*>[\s\S]*?<\/script>)/i", function ($matches) use (&$i, &$script_array) {
                    $script_array[$i] = $matches[1];

                    return '{{{script' . $i++ . '}}}';
                }, $buffer);

                //html comment replace
                $html_comment = array();
                $buffer = preg_replace_callback("/(<!--[\s\S]*?-->)/i", function ($matches) use (&$i, &$html_comment) {
                    $html_comment[$i] = $matches[1];

                    return '{{{comment' . $i++ . '}}}';
                }, $buffer);
                /*replace all script tag to special tag end*/


                /*replace body script to insert GTM code start*/
                //做判断去替换，避免<script>标签中带有<body>标签的注释影响
                $script_flag = false;
                $buffer = preg_replace_callback("/.*/", function ($matches) use (&$script_flag, $misc_gtm_footer) {
                    if (preg_match("/<script[^>]*>/", $matches[0])) {
                        $script_flag = true;
                    }
                    if (preg_match("/<\/script>/", $matches[0])) {
                        $script_flag = false;
                    }
                    if ($script_flag === false) {
                        return preg_replace("/(<body\b[^>]*?>)/", "$1" . "\n" . $misc_gtm_footer . "\n", $matches[0]);
                    } else {
                        return $matches[0];
                    }
                }, $buffer);
                /*replace body script to insert GTM code end*/


                /*restore all script tag to special tag start*/
                //html comment restore
                $buffer = preg_replace_callback("/{{{comment(\d+)}}}/i", function ($matches) use (&$html_comment) {
                    return $html_comment[$matches[1]];
                }, $buffer);


                //script tag restore
                $buffer = preg_replace_callback("/{{{script(\d+)}}}/i", function ($matches) use (&$script_array) {
                    return $script_array[$matches[1]];
                }, $buffer);
                /*restore all script tag to special tag end*/


                return apply_filters('zendkee_frontend_html',$buffer);

            }
            // return $buffer;
        }
    }
}

/* Google Code End */




/* Product Detail Form Start */

/* Contact Form Short Start */
//add a button to product detail page,use to jump to contact form
if (!function_exists('zendkee_add_a_jumpto_button')) {
    function zendkee_add_a_jumpto_button()
    {
        static $is_jumpto_button_run = 0;

        if ($is_jumpto_button_run === 0) {
            $jumpto = zendkee_get_option('jumpto');
            $jumpto_text = zendkee_get_option('jumpto_text');
            if ($jumpto) {
                echo '<button id="goto_contact_form">' . $jumpto_text . '</button>';
                $is_jumpto_button_run++;
            }
        }
    }
}


$jumpto_position = zendkee_get_option('jumpto_position');
if ($jumpto_position == 'b2b' || $jumpto_position == '') {
    add_action('woocommerce_single_product_summary', 'zendkee_add_a_jumpto_button', 35);
    //    add_action('woocommerce_after_single_product_summary', 'zendkee_add_a_jumpto_button', 30);
} else {
    add_action('woocommerce_after_add_to_cart_button', 'zendkee_add_a_jumpto_button', 1);
}




add_action('wp_footer', function () {
    $jumpto = zendkee_get_option('jumpto');
    if ($jumpto) {
        echo '<script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(document).ready(function($) {
                    $("#goto_contact_form").click(function(event) {
                        if($("#product_detail_contact_form").length){
                            $("html,body").animate({"scrollTop": $("#product_detail_contact_form").offset().top-150 } , 500);
                        }else{
                            console.log("Please Add Contact Form First!");
                        }
                    });
                });
            });
        </script>';
    }
}, 2000);


//product detail contact form
if (!function_exists('zendkee_product_detail_form_shorcode')) {
    function zendkee_product_detail_form_shorcode()
    {
        $product_detail_contact_form = zendkee_get_option('product_detail_contact_form');
        if ($product_detail_contact_form) {
            echo '<div id="product_detail_contact_form">';
            echo do_shortcode($product_detail_contact_form);
            echo '</div>';
        }
    }
}


//footer form
$enable_footer_contact_form = zendkee_get_option('enable_footer_contact_form');
if ($enable_footer_contact_form) {

    //Avada主题
    add_action('avada_after_main_content', function () {
        $footer_contact_form = zendkee_get_option('footer_contact_form');
        if ($footer_contact_form) {
            echo '<div id="footer_contact_form">';
            echo do_shortcode($footer_contact_form);
            echo '</div>';
        }
    });

    //其他主题（暂时用不到。因为wp_footer的调用都在footer很靠后的位置。表单位置不对。）
    //    add_action('wp_footer',function(){
    //        $avada_after_main_content_runtime = did_action('avada_after_main_content');
    //        if($avada_after_main_content_runtime <= 0){
    //            $footer_contact_form = zendkee_get_option('footer_contact_form');
    //            if($footer_contact_form){
    //                echo '<div id="footer_contact_form">';
    //                echo do_shortcode($footer_contact_form);
    //                echo '</div>';
    //            }
    //        }
    //
    //    },1);

}



//find the function < woocommerce_output_related_products > priority when it hook to action < woocommerce_after_single_product_summary >
if (!function_exists('zendkee_get_woocommerce_output_related_products_priority')) {
    function zendkee_get_woocommerce_output_related_products_priority()
    {
        global $wp_filter;
        $related_filters = array();

        foreach ($wp_filter as $key => $val) {
            if (FALSE !== strpos($key, 'woocommerce_after_single_product_summary')) {
                $related_filters = $val->callbacks;
            }
        }
        if ($related_filters) {
            $get_related = false;
            foreach ($related_filters as $priority => $items) {
                array_walk_recursive($items, function ($item, $key) use (&$get_related) {
                    if (is_string($item) && strpos($item, 'woocommerce_output_related_products') !== false) {
                        $get_related = true;
                    }
                });
                if ($get_related === true) {
                    return $priority;
                }

                array_walk_recursive($items, function ($item, $key) use (&$get_related) {
                    if (is_string($item) && strpos($item, 'output_related_products') !== false) {
                        $get_related = true;
                    }
                });
                if ($get_related === true) {
                    return $priority;
                }

                array_walk_recursive($items, function ($item, $key) use (&$get_related) {
                    if (is_string($item) && strpos($item, 'related') !== false) {
                        $get_related = true;
                    }
                });
                if ($get_related === true) {
                    return $priority;
                }
            }
        }
    }
}

add_action('wp_loaded', function () {
    global $avada_woocommerce;
    $product_detail_form_priority = zendkee_get_option('product_detail_form_priority');

    $woocommerce_output_related_products_priority = zendkee_get_woocommerce_output_related_products_priority();
    if ($product_detail_form_priority == 'before_related_product') {
        add_action('woocommerce_after_single_product_summary', 'zendkee_product_detail_form_shorcode', $woocommerce_output_related_products_priority - 1);
    } elseif ($product_detail_form_priority == 'after_related_product') {
        add_action('woocommerce_after_single_product_summary', 'zendkee_product_detail_form_shorcode', $woocommerce_output_related_products_priority + 1);
    }
});
/* Product Detail Form End */




/* Product List Form Start */
$enable_product_list_contact_form = zendkee_get_option('enable_product_list_contact_form');
$product_list_contact_form = zendkee_get_option('product_list_contact_form');
if ($enable_product_list_contact_form && $product_list_contact_form) {
    add_action('woocommerce_after_main_content', function () use ($product_list_contact_form) {
        if (function_exists('is_product_category') && is_product_category() || function_exists('is_shop') && is_shop()) {
            printf('<div class="product_list_contact_form">%s</div>', do_shortcode($product_list_contact_form));
        }
    });
    add_action('wp_head', function () {
        echo '<style>
.product_list_contact_form{
    clear:both;
    width: 100%;
}


</style>';
    });
}

/* Product List Form End */







/* Float Form Start */

//run float form shortcode at footer
add_action('wp_footer', function () {
    //右侧按钮需要参考这个站：https://www.globalodm.com/
    $enable_float_form = zendkee_get_option('enable_float_form');
    $float_contact_form = zendkee_get_option('float_contact_form');
    $quote_text = zendkee_get_option('quote_text');
    $scroll_top = zendkee_get_option('scroll_top');


    $float_quote_button_html = $quote_text ? $quote_text : '<i class="iconfont" >&#xe6f6;</i>';
    $float_contact_form_html = sprintf('<!-- 悬浮表单按钮 -->
      <div class="coly-menu-float coly-right" id="coly-menu-float">
        <a href="javascript:;" class="coly-menu">
          <!-- 此处嵌入color -->
          %s
        </a>
      </div>', $float_quote_button_html);

    $scroll_to_top_html = '<!-- 悬浮置顶按钮 -->
      <div class="coly-top-float coly-right" id="coly-top-float">
        <a href="javascript:;" class="coly-top">
          <!-- 此处嵌入color -->
          <i class="iconfont" >&#xe761;</i>
        </a>
      </div>';

    printf('<!-- 此处嵌入color -->
    <div class="coly-side-right %s">
      %s
      %s
    </div>', ($quote_text ? 'quote-text' : ''), ($enable_float_form ? $float_contact_form_html : ''), ($scroll_top ? $scroll_to_top_html : ''));

    if ($enable_float_form && $float_contact_form) {
        printf('
        <!-- leo right side form -->
    <div class="side-form-wrapper">
      <div id="leo-side-contact-form">
        <span class="closeBtn"></span>
        %s
      </div>
    </div>', do_shortcode($float_contact_form));
    }



}, 10000);
/* Float Form End */





/* 增加全局js和css Start */
if (!function_exists('zendkee_load_frontend_js_css')) {
    function zendkee_load_frontend_js_css()
    {
        add_action('wp_enqueue_scripts', function () {
            $upload_dir = wp_upload_dir();
            if (file_exists($upload_dir['basedir'] . '/zk_frontend.css')) {
                wp_register_style('zk_frontend_css', $upload_dir['baseurl'] . '/zk_frontend.css');
                wp_enqueue_style('zk_frontend_css');
            }
        }, PHP_INT_MAX);


        add_action('wp_enqueue_scripts', function () {
            if (!wp_script_is('jquery', 'queue')) {
                if (!wp_script_is('jquery', 'registered')) {
                    wp_register_script('jquery', home_url() . '/wp-includes/js/jquery/jquery.js', array(), '');
                }
                wp_enqueue_script('jquery');
            }
        }, (PHP_INT_MAX - 100));


        add_action('wp_enqueue_scripts', function () {
            $upload_dir = wp_upload_dir();
            if (file_exists($upload_dir['basedir'] . '/zk_frontend.js')) {
                wp_register_script('zk_frontend_js', $upload_dir['baseurl'] . '/zk_frontend.js', '', ZENDKEE_CUSTOM_VERSION, true);
                wp_enqueue_script('zk_frontend_js');
            }
        }, PHP_INT_MAX);
    }
}

if (!function_exists('zendkee_load_backend_js_css')) {
    function zendkee_load_backend_js_css()
    {
        add_action('admin_enqueue_scripts', function () {
            $upload_dir = wp_upload_dir();
            if(file_exists($upload_dir['basedir'] . '/zk_backend.css')){
                wp_register_style('zk_backend_css', $upload_dir['baseurl'] . '/zk_backend.css');
                wp_enqueue_style('zk_backend_css');
            }
            
        }, PHP_INT_MAX);


        add_action('admin_enqueue_scripts', function () {
            $upload_dir = wp_upload_dir();
            if (file_exists($upload_dir['basedir'] . '/zk_backend.js')) {
                wp_register_script('zk_backend_js', $upload_dir['baseurl'] . '/zk_backend.js', '', ZENDKEE_CUSTOM_VERSION, true);
                wp_enqueue_script('zk_backend_js');
            }
            
        }, PHP_INT_MAX);
    }
}



zendkee_load_frontend_js_css();
zendkee_load_backend_js_css();
/* 增加全局js和css End */




//引入外部js
if ($enable_outer_js = zendkee_get_option('enable_outer_js')) {
    add_action('wp_enqueue_scripts', function () {
        $outer_js = zendkee_get_option('outer_js');
        $outer_js_position_in_footer = zendkee_get_option('outer_js_position') == 'header' ? false : true;
        $outer_js_dependence = zendkee_get_option('outer_js_dependence');
        $outer_js_dependence_other = zendkee_get_option('outer_js_dependence_other');

        $outer_js_dependence = array_filter(array_unique(array_merge((array)$outer_js_dependence, explode(",", $outer_js_dependence_other))));

        foreach ((array)explode("\n", $outer_js) as $js) {
            if (!empty($js)) {
                $handle = 'outer_js-' . md5($js);
                wp_register_script($handle, $js, $outer_js_dependence, null, $outer_js_position_in_footer);
                wp_enqueue_script($handle);
            }
        }
    }, (PHP_INT_MAX - 1000), 0);
}

//引入外部css
if ($enable_outer_css = zendkee_get_option('enable_outer_css')) {
    add_action('wp_enqueue_scripts', function () {
        $outer_css = zendkee_get_option('outer_css');
        foreach ((array)explode("\n", $outer_css) as $css) {
            if (!empty($css)) {
                $handle = 'outer_css-' . md5($css);
                wp_register_style($handle, $css);
                wp_enqueue_style($handle);
            }
        }
    }, (PHP_INT_MAX - 1000), 0);
}






//enable debug mode
if (zendkee_get_option('enable_debug') && isset($_GET['debug']) && $_GET['debug']) {
    if (!defined('SAVEQUERIES')) {
        define('SAVEQUERIES', true);
    }
    if (!defined('WP_DEBUG')) {
        define('WP_DEBUG', true); // false
    }
    if (WP_DEBUG) {
        if (!defined('WP_DEBUG_LOG')) {
            define('WP_DEBUG_LOG', true);
        }
        if (!defined('WP_DEBUG_DISPLAY')) {
            define('WP_DEBUG_DISPLAY', false);
        }
        @ini_set('display_errors', 0);
    }

    add_action('wp_footer', 'zendkee_debug_footer');
    add_action('admin_footer', function () {
        zendkee_debug_footer();
        echo '<style type="text/css">
            #zendkee_debug{
                margin-left: 200px;
                margin-bottom: 50px;
            }
        </style>';
    });
}

if (!function_exists('zendkee_debug_footer')) {
    function zendkee_debug_footer()
    {

        global $wpdb;

        echo '<div id="zendkee_debug">';

        echo '<hr><b style="color: red; font-size: 26px;">以下是调试信息：</b><br>';

        echo '<b style="color: red; font-size: 24px;">数据库查询按耗时：</b><br>';
        echo '<pre>';
        $qs = array();
        foreach ($wpdb->queries as $q) {
            $qs['' . $q[1] . ''] = $q;
        }
        krsort($qs);
        print_r($qs);
        echo '</pre>';

        echo '<b style="color: red; font-size: 24px;">数据库查询按执行顺序：</b><br>
        ';

        echo '<pre>';
        var_dump($wpdb->queries);
        echo '</pre>';





        $sql_total_time = 0;
        foreach ($wpdb->queries as $q) {
            $sql_total_time += $q[1];
        }
        echo '<pre>数据库查询次数：', get_num_queries();
        echo '<br>数据库查询用时：', $sql_total_time, ' 秒';
        echo '<br>页面加载用时：', timer_stop(1), ' 秒';
        echo '<br>使用内存：';
        printf('%.2fMB', memory_get_peak_usage() / 1024 / 1024);
        echo '<br>';
        echo '</pre>';

        echo '</div>';
    }
}




//增强表单体验-防止重复提交，检查输入数据，增强型下拉表单-适用于Listo（https://contactform7.com/listo/）的国家和货币
if (zendkee_get_option('enhance_form')) {

    /** for contact form 7 start */
    //去除wpcf7默认行为
    add_action('init', function () {
        remove_action('wpcf7_init', 'wpcf7_add_form_tag_select', 10);
    }, 9);

    //改写wpcf7下拉的行为
    add_action('wpcf7_init', 'zendkee_wpcf7_add_form_tag_select', 15, 0);
    function zendkee_wpcf7_add_form_tag_select()
    {

        wpcf7_add_form_tag(
            array('select', 'select*'),
            'zendkee_wpcf7_select_form_tag_handler',
            array(
                'name-attr' => true,
                'selectable-values' => true,
            )
        );
    }

    //下拉处理函数
    function zendkee_wpcf7_select_form_tag_handler($tag)
    {

        if (empty($tag->name)) {
            return '';
        }

        $validation_error = wpcf7_get_validation_error($tag->name);

        $class = wpcf7_form_controls_class($tag->type);

        if ($validation_error) {
            $class .= ' wpcf7-not-valid';
        }

        $atts = array();

        $atts['class'] = $tag->get_class_option($class);
        $atts['id'] = $tag->get_id_option();
        $atts['tabindex'] = $tag->get_option('tabindex', 'signed_int', true);

        $aria_required = '';
        if ($tag->is_required()) {
            $atts['aria-required'] = 'true';
            $aria_required = sprintf('aria-required="true"');
        }

        if ($validation_error) {
            $atts['aria-invalid'] = 'true';
            $atts['aria-describedby'] = wpcf7_get_validation_error_reference(
                $tag->name
            );
        } else {
            $atts['aria-invalid'] = 'false';
        }


        if (strpos(strtolower($tag->name), 'country') !== false || strpos(strtolower($tag->name), 'currency') !== false) { //下拉-增强型表单生成方式

            $placeholder = implode($tag->labels);

            if ($data = (array) $tag->get_data_option()) {
                $values = array_keys($data);
                $labels = array_values($data);
            }

            $html = '';

            foreach ($values as $key => $value) {
                if (strpos(strtolower($tag->name), 'country') !== false || strpos(strtolower($tag->name), 'currency') !== false) {
                    $value = strtoupper($value);
                }

                $label = isset($labels[$key]) ? $labels[$key] : $value;

                $html .= sprintf(
                    '<a href="#" data-value="%1$s">%2$s - %1$s</a>',
                    esc_html($value),
                    esc_html($label)
                );
            }

            $dropdown_wrapper_class = sanitize_html_class($tag->name);
            $input_name = esc_attr($tag->name);

            $input_id = $tag->get_id_option();
            $input_class = str_replace('wpcf7-select', '', $atts['class']); //去掉原生的某个class

            $html = sprintf(
                '<span class="wpcf7-form-control-wrap %1$s" data-name="%2$s">
        <div class="dropdown">
        <input type="text" readonly name="%2$s" id="%3$s" class="wpcf7-dropdown-value %4$s" placeholder="%7$s" %8$s>
        <input type="hidden" name="%2$s_label" id="%3$s_label" class="wpcf7-dropdown-label">
        <button class="wpcf7-dropdown-btn">Select %7$s</button>
        <div class="wpcf7-dropdown-content">
            <input type="text" placeholder="Search.." class="wpcf7-dropdown-filter">
            %5$s
        </div>
    </div>
        %6$s</span>',
                $dropdown_wrapper_class,
                $input_name,
                $input_id,
                $input_class,
                $html,
                $validation_error,
                $placeholder,
                $aria_required
            );

            return $html;
        } else { //下拉-默认表单生成方式

            $multiple = $tag->has_option('multiple');
            $include_blank = $tag->has_option('include_blank');
            $first_as_label = $tag->has_option('first_as_label');

            if ($tag->has_option('size')) {
                $size = $tag->get_option('size', 'int', true);

                if ($size) {
                    $atts['size'] = $size;
                } elseif ($multiple) {
                    $atts['size'] = 4;
                } else {
                    $atts['size'] = 1;
                }
            }

            if ($data = (array) $tag->get_data_option()) {
                $tag->values = array_merge($tag->values, array_values($data));
                $tag->labels = array_merge($tag->labels, array_values($data));
            }

            $values = $tag->values;
            $labels = $tag->labels;

            $default_choice = $tag->get_default_option(null, array(
                'multiple' => $multiple,
                'shifted' => $include_blank,
            ));

            if (
                $include_blank
                or empty($values)
            ) {
                array_unshift($labels, '---');
                array_unshift($values, '');
            } elseif ($first_as_label) {
                $values[0] = '';
            }

            $html = '';
            $hangover = wpcf7_get_hangover($tag->name);

            foreach ($values as $key => $value) {
                if ($hangover) {
                    $selected = in_array($value, (array) $hangover, true);
                } else {
                    $selected = in_array($value, (array) $default_choice, true);
                }

                $item_atts = array(
                    'value' => $value,
                    'selected' => $selected ? 'selected' : '',
                );

                $item_atts = wpcf7_format_atts($item_atts);

                $label = isset($labels[$key]) ? $labels[$key] : $value;

                $html .= sprintf(
                    '<option %1$s>%2$s</option>',
                    $item_atts,
                    esc_html($label)
                );
            }

            if ($multiple) {
                $atts['multiple'] = 'multiple';
            }

            $atts['name'] = $tag->name . ($multiple ? '[]' : '');

            $atts = wpcf7_format_atts($atts);
            $html = sprintf(
                '<span class="wpcf7-form-control-wrap" data-name="%1$s"><select %2$s>%3$s</select>%4$s</span>',
                sanitize_html_class($tag->name),
                $atts,
                $html,
                $validation_error
            );

            return $html;
        }
    }

    /** for contact form 7 end */



    /** for elementor pro - form start */

    //增加表单类型
    add_filter('elementor_pro/forms/field_types', function ($field_types) {
        if (function_exists('listo')) {
            return array_merge($field_types, ['select_country' => esc_html__('Select Country', 'elementor-pro')]);
        } else {
            return $field_types;
        }
    }, 10, 1);

    //解释上面的表单类型
    add_action("elementor_pro/forms/render_field/select_country", function ($item, $item_index, $that) {
        // $that对象用到的函数，参考这里：/wp-content/plugins/elementor/includes/base/controls-stack.php

        if (!function_exists('listo')) {
            return;
        }


        $html = '';
        foreach (listo('countries') as $value => $label) {
            $value = strtoupper($value);

            $html .= sprintf(
                '<a href="#" data-value="%1$s">%2$s - %1$s</a>',
                esc_html($value),
                esc_html($label)
            );
        }


        $that->add_render_attribute(
            [
                'select-wrapper' . $item_index => [
                    'class' => [
                        'elementor-field',
                        'elementor-select-wrapper',
                        esc_attr($item['css_classes']),
                    ],
                ],
                'select' . $item_index => [
                    'name' => $that->get_attribute_name($item) . (!empty($item['allow_multiple']) ? '[]' : ''),
                    'id' => $that->get_attribute_id($item),
                    'class' => [
                        'elementor-field-textual',
                        'elementor-size-' . $item['input_size'],
                    ],
                ],
            ]
        );

        $is_require = '';
        if ($item['required']) {
            $that->add_render_attribute('select_country' . $item_index, 'required', 'required');
            $that->add_render_attribute('select_country' . $item_index, 'aria-required', 'true');
            $is_require = ' required="required" aria-required="true"';
        }

        if ($item['allow_multiple']) {
            $that->add_render_attribute('select' . $item_index, 'multiple');
            if (!empty($item['select_size'])) {
                $that->add_render_attribute('select' . $item_index, 'size', $item['select_size']);
            }
        }

        $options = preg_split("/\\r\\n|\\r|\\n/", $item['field_options']);

        if (!$options) {
            return '';
        }

        $id = $that->get_attribute_id($item);
        $name = $that->get_attribute_name($item);
        $label_name = preg_replace("/^(\w+)(\[\w+\])$/", "$1_label$2", $name);
        $wraper_class = implode(' ', $that->get_render_attributes('select-wrapper' . $item_index, 'class')); // dropdown
        $class = esc_attr($item['css_classes']);
        $label = $item['field_label'];

        $html = sprintf(
            '
            <div class="%1$s">
                    <input type="text" readonly name="%2$s" id="%3$s" class="wpcf7-dropdown-value %4$s elementor-field-textual" placeholder="%5$s" %8$s >
                    <input type="hidden" name="%7$s" id="%7$s" class="wpcf7-dropdown-label">
                    <button class="wpcf7-dropdown-btn">Select %5$s</button>
                    <div class="wpcf7-dropdown-content">
                        <input type="text" placeholder="Search.." class="wpcf7-dropdown-filter">
                        %6$s
                    </div>
            </div>
            ',
            $wraper_class,
            $name,
            $id,
            $class,
            $label,
            $html,
            $label_name,
            $is_require

        );

        echo $html;
    }, 15, 3);


    /** for elementor pro - form end */
}

// [zk_google_translate languages="fr,la,lo"]
/**
 * 增加谷歌翻译工具短代码的支持
 */
add_shortcode("zk_google_translate",function($attrs){
    $attrs = shortcode_atts(array(
        'languages' => '',
    ), $attrs);

    $html = '<div id="google_translate_element"></div>';
    $html .= '<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>';
    $html .= sprintf('<script type="text/javascript">function googleTranslateElementInit() {
        new google.translate.TranslateElement(
            {
                includedLanguages: "%s",
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            },
            "google_translate_element"
        );
    }</script>', $attrs['languages']);

    return $html;
});