<?php
if (!defined('ABSPATH')) {
    exit();
}

//Editor用户登录后默认跳转到首页编辑后台
if (zendkee_get_option('ze_redirect_to_elementor_editor')) {
    add_filter('login_redirect', function ($redirect_to, $requested_redirect_to, $user) {

        $is_content_editor = false;
        $user_roles = $user->roles;

        //function in :wp-content/plugins/elementor-pro/modules/role-manager/module.php : get_role_manager_options()
        $restrictions = get_option('elementor_role-manager', []);

        foreach ((array)$user_roles as $role) {
            if (key_exists($role, $restrictions) && in_array('design', $restrictions[$role])) {
                $is_content_editor = true;
            }
        }

        if ($is_content_editor) {

            $url_parsed = parse_url($redirect_to);
            $admin_path = $url_parsed['path'];
            if (strpos($admin_path,'/wp-admin/')!==false) {

                //get front page id
                if ('page' === get_option('show_on_front') && $front_page_id = get_option('page_on_front')) {
                    if ('page' == get_post_type($front_page_id)) {
                        $new_redirect_to = sprintf('%spost.php?post=%d&action=elementor', admin_url(), $front_page_id);

                        return $new_redirect_to;
                    }
                }
            }
        }


        return $redirect_to;
    }, 10, 3);
}


//是否隐藏产品详情页主描述
if (zendkee_get_option('ze_hide_product_main_description')) {
    add_action('admin_head', function () {
        if ('product' == get_post_type()) {
            echo '<style type="text/css">#postdivrich{display:none;}</style>';
        }
    });
}



//产品列表页侧边栏菜单下拉样式
if (zendkee_get_option('ze_product_list_menu_dropdown')) {

    add_action('wp_enqueue_scripts', function () {
        if (function_exists('is_product') && (!is_product() || !is_singular())) {
            //引入css
            wp_enqueue_style('product_sidebar_css', ELEMENT_ADDON_ZENDKEE_URL . 'libs/css/product_sidebar.css');

            //引入js
            //            wp_enqueue_script('jquery_mobile_js', 'https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js', array('jquery'), '1.0', true);\
            //            add_action('wp_footer',function(){
            //                echo '<script type="text/javascript" src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js" data-ajax="false"></script>';
            //            });
            wp_enqueue_script('product_sidebar_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/product_sidebar.js', array('jquery'), '1.0', true);
        }
    });
}


//产品详情页图库轮播图
if (zendkee_get_option('ze_product_detail_gallery_carousel')) {

    add_action('wp_enqueue_scripts', function () {
        if (function_exists('is_product') && is_product()) {
            //引入css
            //            wp_enqueue_style('swiper_css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/css/swiper.min.css');
            wp_enqueue_style('product_detail_gallery_carousel_css', ELEMENT_ADDON_ZENDKEE_URL . 'libs/css/product_detail_gallery_carousel.css');
            //引入js
            //            wp_enqueue_script('swiper_js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.min.js', '', '1.0', false);
            wp_enqueue_script('product_detail_gallery_carousel_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/product_detail_gallery_carousel.js', array('jquery'), '1.20.5', true);

            // if (!wp_script_is('swiper', 'registered')) {
            //     if (is_plugin_active('elementor/elementor.php')) {
            //         wp_register_script(
            //             'swiper',
            //             plugins_url() . '/elementor/assets/lib/swiper/swiper.min.js',
            //             [],
            //             '5.3.6',
            //             true
            //         );
            //     } else {
            //         wp_register_script(
            //             'swiper',
            //             plugins_url('libs/swiper/swiper.min.js', __DIR__),
            //             [],
            //             '5.3.6',
            //             true
            //         );
            //     }
            // }
            
            wp_register_script(
                            'swiper',
                            plugins_url() . '/elementor_addon_zendkee/libs/swiper/swiper-bundle-8.0.7.min.js',
                            [],
                            '8.0.7',
                            false
                        );

            if (!wp_script_is('swiper', 'enqueued')) {
                wp_enqueue_script('swiper');
            }
        }
    });
}



//产品分类页-每个产品下方增加短描述
$display_product_archive_short_desc = zendkee_get_option('display_product_archive_short_desc');
if ($display_product_archive_short_desc == 'display_product_archive_short_desc_before_product_title') {
    add_action('woocommerce_after_shop_loop_item_title', function () {
        global $product;
        printf('<div class="product_short_desc">%s</div>', $product->get_short_description());
    });
} elseif ($display_product_archive_short_desc == 'display_product_archive_short_desc_after_product_title') {
    //产品分类页-每个产品名下方展示短描述
    //add by Justin
    add_action('woocommerce_after_shop_loop_item', function () {
        global $product;
        printf('<div class="product_short_desc">%s</div>', $product->get_short_description());
    }, 20);
}





//替换Avatar头像
if (zendkee_get_option('customize_avatar')) {
    add_filter('avatar_defaults', function ($avatar_defaults) {
        return array(
            'mystery' => 'Default',
            'profile_1' => 'Profile 1',
            'profile_2' => 'Profile 2',
            'profile_3' => 'Profile 3',
            'profile_4' => 'Profile 4',
        );
    }, 20, 1);


    add_filter('get_avatar_url', function ($url, $id_or_email, $args) {

        //        dp($args);

        $url = plugin_dir_url(__DIR__) . 'libs/image/avatar/' . $args['default'] . '.png';
        return $url;
    }, 20, 3);
}



//适配主题Everest Admin Theme
if (zendkee_get_option('compatible_everest_admin_theme')) {
    /*
 * 为主题插件：Everest Admin Theme加载后台全局css
 * */
    add_action('admin_enqueue_scripts', function () {
        wp_enqueue_style('element_addon_zendkee_everest_admin_themes_css', ELEMENT_ADDON_ZENDKEE_URL . 'libs/css/everest_admin_extended_style.css');
    });



    /*
     * 为主题插件：Everest Admin Theme加载后台全局js
     * */
    add_action('admin_enqueue_scripts', function () {
        wp_enqueue_script('element_addon_zendkee_everest_admin_themes_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/everest_admin_extended_style.js', array('jquery'), '1.0', true);
    }, 10000);
}


//产品详情页-加购物车按钮-加入询盘按钮
if (zendkee_get_option('ze_product_detail_inquiry_shortcode_enable')) {
    $ze_product_detail_inquiry_shortcode = zendkee_get_option('ze_product_detail_inquiry_shortcode');
    if ($ze_product_detail_inquiry_shortcode) {
        add_action('woocommerce_single_product_summary', function () use ($ze_product_detail_inquiry_shortcode) {
            echo do_shortcode($ze_product_detail_inquiry_shortcode);
        }, 31);
    }
}
