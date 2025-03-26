<?php
if (!defined('ABSPATH')) {
    exit();
}


if (zendkee_get_option('disable_emoji')) {
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('embed_head', 'print_emoji_detection_script');

    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    add_filter('emoji_svg_url', '__return_false');//删除s.w.org的 DNS-Prefetch功能
}//disable emoji


if (zendkee_get_option('disable_embeds')) {
    /*
    * 禁止加载wp-embeds.mins.js
    */
    if(!function_exists('disable_embeds_init')){
        function disable_embeds_init()
        {
            /* @var WP $wp */
            global $wp;
            // Remove the embed query var.
            $wp->public_query_vars = array_diff($wp->public_query_vars, array(
                'embed',
            ));
            // Remove the REST API endpoint.
            remove_action('rest_api_init', 'wp_oembed_register_route');
            // Turn off
            add_filter('embed_oembed_discover', '__return_false');
            // Don't filter oEmbed results.
            remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
            // Remove oEmbed discovery links.
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
            // Remove oEmbed-specific JavaScript from the front-end and back-end.
            remove_action('wp_head', 'wp_oembed_add_host_js');
            add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
            // Remove all embeds rewrite rules.
            add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        }
    }


    add_action('init', 'disable_embeds_init', 9999);

    /**
     * Removes the 'wpembed' TinyMCE plugin.
     *
     * @since 1.0.0
     *
     * @param array $plugins List of TinyMCE plugins.
     * @return array The modified list.
     */
    if(!function_exists('disable_embeds_tiny_mce_plugin')){
        function disable_embeds_tiny_mce_plugin($plugins)
        {
            return array_diff($plugins, array('wpembed'));
        }
    }


    /**
     * Remove all rewrite rules related to embeds.
     *
     * @since 1.2.0
     *
     * @param array $rules WordPress rewrite rules.
     * @return array Rewrite rules without embeds rules.
     */
    if(!function_exists('disable_embeds_rewrites')){
        function disable_embeds_rewrites($rules)
        {
            foreach ($rules as $rule => $rewrite) {
                if (false !== strpos($rewrite, 'embed=true')) {
                    unset($rules[$rule]);
                }
            }
            return $rules;
        }
    }


    /**
     * Remove embeds rewrite rules on plugin activation.
     *
     * @since 1.2.0
     */
    if(!function_exists('disable_embeds_remove_rewrite_rules')){
        function disable_embeds_remove_rewrite_rules()
        {
            add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
            flush_rewrite_rules();
        }
    }


    register_activation_hook(__FILE__, 'disable_embeds_remove_rewrite_rules');

    /**
     * Flush rewrite rules on plugin deactivation.
     *
     * @since 1.2.0
     */
    if(!function_exists('disable_embeds_flush_rewrite_rules')){
        function disable_embeds_flush_rewrite_rules()
        {
            remove_filter('rewrite_rules_array', 'disable_embeds_rewrites');
            flush_rewrite_rules();
        }
    }


    register_deactivation_hook(__FILE__, 'disable_embeds_flush_rewrite_rules');
}//disable embeds


if (zendkee_get_option('disable_feed')) {
    remove_action('wp_head', 'feed_links', 2); //移除feed
    remove_action('wp_head', 'feed_links_extra', 3); //移除feed
}//disable feed


if (zendkee_get_option('disable_offline_editor')) {
    remove_action('wp_head', 'rsd_link'); //移除离线编辑器开放接口
    remove_action('wp_head', 'wlwmanifest_link');  //移除离线编辑器开放接口
}//disable offline editor


if (zendkee_get_option('disable_index_rel_link')) {
    remove_action('wp_head', 'index_rel_link');//去除本页唯一链接信息
}//disable index_rel_link


if (zendkee_get_option('disable_prev_next')) {
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);//清除前后文信息
    remove_action('wp_head', 'start_post_rel_link', 10, 0);//清除前后文信息
}//disable prev next link


if (zendkee_get_option('disable_related_post')) {
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);//移除相关联的文章链接
}//disable related post


if (zendkee_get_option('disable_feature_publish')) {
    remove_action('publish_future_post', 'check_and_publish_future_post', 10, 1);//检查未来的文章发布
}//disable feature publish


if (zendkee_get_option('disable_generator')) {
    remove_action('wp_head', 'wp_generator'); //移除WordPress版本
    add_filter('the_generator', '__return_empty_string');
}//disable generator


if (zendkee_get_option('disable_shortlink')) {
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('template_redirect', 'wp_shortlink_header', 11, 0);
}//disable short link


if (zendkee_get_option('disable_comment_style')) {
    add_action('widgets_init', function () {
        global $wp_widget_factory;
        remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
    });//评论样式
}//disable comment css

//前台不加载语言包
if (zendkee_get_option('disable_language_package')) {
    add_filter('locale', function ($locale) {
        return is_admin() ? $locale : 'en_US';
    });
}//$disable language package in front



//后台不加载语音包
if (zendkee_get_option('disable_language_package_backend')) {
    add_filter('locale', function ($locale) {
        return is_admin() && strpos($_SERVER['REQUEST_URI'],'/wp-admin/options-general.php')===false ? 'en_US' : $locale  ;
    });
}//$disable language package in back


function remove_version_subfix($ver , $handle=''){
    $cdn_version = preg_replace(array('/-wc\.[\d\.]+$/','/-wp$/') , '' , $ver);

    if($handle == 'jquery-blockui'){//jquery-blockui bug
        if($cdn_version=='2.7.0'){//有个bug
            $cdn_version = '2.70';
        }
    }elseif ($handle == 'json2'){
        $cdn_version = preg_replace('/^(\d{4})-(\d{2})-(\d{2})$/' , '$1$2$3' , $cdn_version);
    }


    return $cdn_version;
}


//CDN JS
if (zendkee_get_option('cdn_js_libs')) {
    $cdn_staticfile_cloudflare_map = array(
        //handle=>array('slug'=>'xxx', 'file'=>'yyyyy', 'ver'=>'zzzz')
        'jquery-core'=>['slug'=>'jquery', 'file'=>'jquery.min.js'],
        'jquery-migrate'=>['slug'=>'jquery-migrate', 'file'=>'jquery-migrate.min.js'],
        'jquery-form'=>['slug'=>'jquery.form', 'file'=>'jquery.form.min.js'],
        'jquery-ui-draggable'=>['slug'=>'jqueryui', 'file'=>'jquery.ui.draggable.min.js','ver'=>'1.10.4'],
        'jquery-blockui'=>['slug'=>'jquery.blockUI', 'file'=>'jquery.blockUI.min.js'],//bug
        'jquery-ui-widget'=>['slug'=>'jqueryui', 'file'=>'jquery.ui.widget.min.js','ver'=>'1.10.4'],
        'prettyPhoto'=>['slug'=>'prettyPhoto', 'file'=>'js/jquery.prettyPhoto.js'],
        'jquery-color'=>['slug'=>'jquery-color', 'file'=>'jquery.color.plus-names.min.js'],
        'imagesloaded'=>['slug'=>'jquery.imagesloaded', 'file'=>'imagesloaded.pkgd.min.js'],
        'masonry'=>['slug'=>'masonry', 'file'=>'masonry.pkgd.min.js'],
        'jcrop'=>['slug'=>'jquery-jcrop', 'file'=>'js/jquery.Jcrop.min.js'],
        'swfobject'=>['slug'=>'swfobject', 'file'=>'swfobject.min.js'],
        'plupload'=>['slug'=>'plupload', 'file'=>'plupload.min.js'],//如有问题，用下面的URL: https://cdn.staticfile.org/plupload/2.1.9/plupload.dev.js
//                    'moxiejs'=>['slug'=>'moxiejs', 'file'=>'moxie.min.js'],//??
        'json2'=>['slug'=>'json2', 'file'=>'json2.min.js'],//version diff
        'underscore'=>['slug'=>'underscore.js', 'file'=>'underscore-min.js'],//version diff
        'backbone'=>['slug'=>'backbone.js', 'file'=>'backbone-min.js'],
        'hoverIntent'=>['slug'=>'jquery.hoverintent', 'file'=>'jquery.hoverIntent.min.js'],
        'react'=>['slug'=>'react', 'file'=>'umd/react.production.min.js'],
        'react-dom'=>['slug'=>'react-dom', 'file'=>'umd/react-dom.production.min.js'],
        'moment'=>['slug'=>'moment.js', 'file'=>'moment.min.js'],
        'lodash'=>['slug'=>'lodash.js', 'file'=>'lodash.min.js'],
        'wp-tinymce-root'=>['slug'=>'tinymce', 'file'=>'tinymce.min.js', 'ver'=>'4.9.4'],
        'flexslider'=>['slug'=>'flexslider', 'file'=>'jquery.flexslider.min.js'],
        'jquery-cookie'=>['slug'=>'jquery-cookie', 'file'=>'jquery.cookie.js'],
        'photoswipe'=>['slug'=>'photoswipe', 'file'=>'photoswipe.min.js'],
        'photoswipe-ui-default'=>['slug'=>'photoswipe', 'file'=>'photoswipe-ui-default.min.js'],
        'zoom'=>['slug'=>'jquery-zoom', 'file'=>'jquery.zoom.min.js'],
        'js-cookie'=>['slug'=>'js-cookie', 'file'=>'js.cookie.min.js'],
        'jquery-validate'=>['slug'=>'jquery-validate', 'file'=>'jquery.validate.min.js', 'ver'=>'1.17.0'],
        'wpdm-front-bootstrap3'=>['slug'=>'twitter-bootstrap', 'file'=>'js/bootstrap.min.js' , 'ver'=>'3.3.4'],
        'wpdm-admin-bootstrap'=>['slug'=>'twitter-bootstrap', 'file'=>'js/bootstrap.min.js' , 'ver'=>'3.3.4'],
        'wpdm-front-bootstrap'=>['slug'=>'twitter-bootstrap', 'file'=>'js/bootstrap.bundle.min.js' , 'ver'=>'4.4.1'],
        'jquery-choosen'=>['slug'=>'chosen', 'file'=>'chosen.jquery.min.js' , 'ver'=>'1.4.2'],
        'datepicker-min-js'=>['slug'=>'jquery-datetimepicker', 'file'=>'jquery.datetimepicker.js', 'ver'=>'2.4.1'],
//        'wp-polyfill'=>['slug'=>'babel-polyfill', 'file'=>'polyfill.min.js'],//如果网站有问题，看看是不是这个js引起的。这个js同版本，cdn的代码不一致。源链接：/wp-includes/js/dist/vendor/wp-polyfill.min.js
    );


//    if(is_admin()){
//        add_action('admin_enqueue_scripts', function () {
//            global $wp_scripts;
//            global $cdn_staticfile_cloudflare_map;
////            var_dump($wp_scripts);
//            foreach ($cdn_staticfile_cloudflare_map as $handle => $item){//后台js直接使用国内的cdn
//                if (wp_script_is($handle)) {
//                    if(isset($item['ver']) && $item['ver']!=''){
//                        $cdn_version = $item['ver'];
//                    }else{
//                        $cdn_version = remove_version_subfix($wp_scripts->registered[$handle]->ver , $handle);
//                    }
//                    $wp_scripts->registered[$handle]->src = "https://cdn.staticfile.org/{$item['slug']}/{$cdn_version}/{$item['file']}";
//                }
//            }
//        } , 10000);
//    }


    if (!is_admin()) {
        add_action('wp_enqueue_scripts', function () {
            global $wp_scripts;
            global $cdn_staticfile_cloudflare_map;

            $cdn_js_libs_type = zendkee_get_option('cdn_js_libs_type');
            if(empty($cdn_js_libs_type)){
                $cdn_js_libs_type = 'cdnjs.cloudflare.com';
            }



            //
            if($cdn_js_libs_type == 'cdn.staticfile.org'){//国内强悍一点
                foreach ($cdn_staticfile_cloudflare_map as $handle => $item){
                    if (wp_script_is($handle)) {
                        if(isset($item['ver']) && $item['ver']!=''){
                            $cdn_version = $item['ver'];
                        }else{
                            $cdn_version = remove_version_subfix($wp_scripts->registered[$handle]->ver , $handle);
                        }
                        $wp_scripts->registered[$handle]->src = "https://cdn.staticfile.org/{$item['slug']}/{$cdn_version}/{$item['file']}";
                    }
                }
            }elseif($cdn_js_libs_type == 'cdnjs.cloudflare.com'){//国外强悍一点
                foreach ($cdn_staticfile_cloudflare_map as $handle => $item){
                    if (wp_script_is($handle)) {
                        if(isset($item['ver']) && $item['ver']!=''){
                            $cdn_version = $item['ver'];
                        }else{
                            $cdn_version = remove_version_subfix($wp_scripts->registered[$handle]->ver , $handle);
                        }
                        $wp_scripts->registered[$handle]->src = "https://cdnjs.cloudflare.com/ajax/libs/{$item['slug']}/{$cdn_version}/{$item['file']}";
                    }
                }
            }elseif($cdn_js_libs_type == 'auto'){//根据浏览器语言判断。全静态化的时候失效
                if (zendkee_is_chinese_browser()) {//中文浏览器,七牛云cdn
                    foreach ($cdn_staticfile_cloudflare_map as $handle => $item){
                        if (wp_script_is($handle)) {
                            if(isset($item['ver']) && $item['ver']!=''){
                                $cdn_version = $item['ver'];
                            }else{
                                $cdn_version = remove_version_subfix($wp_scripts->registered[$handle]->ver , $handle);
                            }
                            $wp_scripts->registered[$handle]->src = "https://cdn.staticfile.org/{$item['slug']}/{$cdn_version}/{$item['file']}";
                        }
                    }
                }else {
                    foreach ($cdn_staticfile_cloudflare_map as $handle => $item){
                        if (wp_script_is($handle)) {
                            if(isset($item['ver']) && $item['ver']!=''){
                                $cdn_version = $item['ver'];
                            }else{
                                $cdn_version = remove_version_subfix($wp_scripts->registered[$handle]->ver , $handle);
                            }
                            $wp_scripts->registered[$handle]->src = "https://cdnjs.cloudflare.com/ajax/libs/{$item['slug']}/{$cdn_version}/{$item['file']}";
                        }
                    }
                }
            }

        }, 1000000);
    }
}





// 从脚本和样式表删除版本
if (zendkee_get_option('remove_js_css_version')) {
    if(!function_exists('zendkee_remove_version_scripts_styles')){
        function zendkee_remove_version_scripts_styles($src)
        {
            if (strpos($src, 'ver=')) {
                $src = remove_query_arg('ver', $src);
            }
            return $src;
        }
    }


    add_filter('style_loader_src', 'zendkee_remove_version_scripts_styles', 9999);
    add_filter('script_loader_src', 'zendkee_remove_version_scripts_styles', 9999);
}


//禁用RSS
if (zendkee_get_option('disable_rss')) {
    //    RSS源主要也是用来订阅网站文章的，但是现在基本没人用，反而给采集文章的人带来了便利。所以说不想使用的可以直接禁止掉。
    if(!function_exists('zendkee_disable_feed')){
        function zendkee_disable_feed()
        {
            wp_die(__('No feed available, please visit the <a href="' . esc_url(home_url('/')) . '">homepage</a>!'));
        }
    }


    add_action('do_feed', 'zendkee_disable_feed', 1);
    add_action('do_feed_rdf', 'zendkee_disable_feed', 1);
    add_action('do_feed_rss', 'zendkee_disable_feed', 1);
    add_action('do_feed_rss2', 'zendkee_disable_feed', 1);
    add_action('do_feed_atom', 'zendkee_disable_feed', 1);
    add_action('do_feed_rss2_comments', 'zendkee_disable_feed', 1);
    add_action('do_feed_atom_comments', 'zendkee_disable_feed', 1);


    //禁用后我们的每个页面还是有RSS地址的，所以我们把这个地址也删除
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);

}

//关闭自我pingbacks
if (zendkee_get_option('disable_pingbacks')) {
    if(!function_exists('zendkee_no_self_ping')){
        function zendkee_no_self_ping(&$links)
        {
            $home = get_option('home');
            foreach ($links as $l => $link) {
                if (0 === strpos($link, $home)) {
                    unset($links[$l]);
                }
            }
        }
    }


    add_action('pre_ping', 'zendkee_no_self_ping');
}

//关闭XML-RPC
if (zendkee_get_option('disable_xml_rpc')) {
    add_filter('xmlrpc_methods', 'zendkee_remove_xmlrpc_pingback_ping');
    if(!function_exists('zendkee_remove_xmlrpc_pingback_ping')){
        function zendkee_remove_xmlrpc_pingback_ping($methods)
        {
            unset($methods['pingback.ping']);
            return $methods;
        }
    }


    add_filter('xmlrpc_enabled', '__return_false');
}


//禁用Google字体
if (zendkee_get_option('disable_google_font')) {

    /**
     * Dequeue Google Fonts based on URL.
     */
    if(!function_exists('drgf_dequeueu_fonts')){
        function drgf_dequeueu_fonts()
        {
            global $wp_styles;

            if (!($wp_styles instanceof WP_Styles)) {
                return;
            }

            $allowed = apply_filters(
                'drgf_exceptions',
                ['olympus-google-fonts']
            );

            foreach ($wp_styles->registered as $style) {
                $handle = $style->handle;
                $src = $style->src;
                $gfonts = strpos($src, 'fonts.googleapis');

                if (false !== $gfonts) {
                    if (!array_key_exists($handle, array_flip($allowed))) {
                        wp_dequeue_style($handle);
                    }
                }
            }
            // Dequeue Google Fonts loaded by Revolution Slider.
            remove_action('wp_footer', array('RevSliderFront', 'load_google_fonts'));

            // Dequeue the Jupiter theme font loader.
            wp_dequeue_script('mk-webfontloader');

        }
    }


    add_action('wp_enqueue_scripts', 'drgf_dequeueu_fonts', 9999);
    add_action('wp_print_styles', 'drgf_dequeueu_fonts', 9999);

    /**
     * Dequeue Google Fonts loaded by Elementor.
     */
    add_filter('elementor/frontend/print_google_fonts', '__return_false');

    /**
     * Dequeue Google Fonts loaded by Beaver Builder.
     */
    add_filter(
        'fl_builder_google_fonts_pre_enqueue',
        function ($fonts) {
            return array();
        }
    );

    /**
     * Dequeue Google Fonts loaded by JupiterX theme.
     */
    add_filter(
        'jupiterx_register_fonts',
        function ($fonts) {
            return array();
        },
        99999
    );


}

if (zendkee_get_option('disable_about_wordpress_icon')) {
    //去除后台左上角的关于WordPress图标及链接
    add_action('admin_bar_menu', 'zendkee_remove_wp_logo_from_admin_bar_new', 25);
    if(!function_exists('zendkee_remove_wp_logo_from_admin_bar_new')){
        function zendkee_remove_wp_logo_from_admin_bar_new($wp_admin_bar)
        {
            $wp_admin_bar->remove_node('wp-logo');
        }
    }


    //去除wordpress仪表板
    remove_action( 'welcome_panel', 'wp_welcome_panel' );
    
    //后台底部信息
    add_filter('admin_footer_text' , '__return_false');

}


if (zendkee_get_option('disable_dashboard_widget')) {
    //移除仪表盘(dashboard)页面加载的小工具
    if(!function_exists('cwp_remove_dashboard_widgets')){
        function cwp_remove_dashboard_widgets()
        {
            global $wp_meta_boxes;

            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
            //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_status']);//Woocommerce
            unset($wp_meta_boxes['dashboard']['normal']['core']['e-dashboard-overview']);//Elementor
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
            unset($wp_meta_boxes['dashboard']['side']['core']['wpdm_dashboard_widget']);//Download Manager
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);


        }
    }



    add_action('wp_dashboard_setup', 'cwp_remove_dashboard_widgets', 11);
    add_action('do_meta_boxes' , function(){
        global $wp_meta_boxes;

        unset($wp_meta_boxes['dashboard']['normal']['core']['wpmet-stories']);//Wpmet Stories
        unset($wp_meta_boxes['wpdmpro']);//WordPress Download Manager
        unset($wp_meta_boxes['dashboard']['normal']['core']['themefusion_news']);//ThemeFusion News
        unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_recent_reviews']);//WooCommerce Recent Reviews

    });
}



if(zendkee_get_option('disable_woocommerce_admin')){
    //禁用Woocommerce仪表盘和分析页面
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_filter( 'woocommerce_admin_disabled', '__return_true' );
    }

}


if(zendkee_get_option('disable_avada_faq')){
    //禁用Avada的FAQ
    add_action( 'plugins_loaded', function(){
        if ( class_exists( 'FusionCore_Plugin' ) ) {
            add_action( 'init', function(){
                //unregister post type
                unregister_post_type('avada_faq');

                //unregister taxonomy
                unregister_taxonomy('faq_category');

            }, 100 );

        }
    } );

    add_action('wp_enqueue_scripts', function () {
        wp_deregister_script('avada-faqs');
    } , 11);
}



if(zendkee_get_option('disable_avada_portfolio')){
    //禁用Avada的Portfolio
    add_action( 'plugins_loaded', function(){
        if ( class_exists( 'FusionCore_Plugin' ) ) {
            add_action( 'init', function(){
                //unregister post type
                unregister_post_type('avada_portfolio');

                //unregister taxonomy
                unregister_taxonomy('portfolio_category');
                unregister_taxonomy('portfolio_skills');
                unregister_taxonomy('portfolio_tags');

            }, 100 );
        }
    } );
    add_action('wp_enqueue_scripts', function () {
        wp_deregister_script('avada-portfolio');
    } , 11);
}



if(zendkee_get_option('disable_wp_json_and_rest_api')){
    //屏蔽 REST API
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');

    // 移除头部 wp-json 标签和 HTTP header 中的 link
    remove_action('wp_head', 'rest_output_link_wp_head', 10 );
    remove_action('template_redirect', 'rest_output_link_header', 11 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

//contact form 7 要用到这个。不能禁用
//    add_filter('rest_authentication_errors', function () {
//        if (!is_user_logged_in()) {
//            return new \WP_Error(403, 'not allowed');
//        }
//    });

    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');

    //测试例子：  http://www.test.com/wp-json/wp/v2/pages/18
}



/*下面两个功能暂时关闭*/

if (zendkee_get_option('page_preload')) {
    add_action('wp_footer', function () {
        echo '
    <script src="http://instantclick.io/v3.1.0/instantclick.min.js" data-no-instant></script>
<script data-no-instant>InstantClick.init();</script>
    ';
    }, 2000000);
}


/*本地化头像*/
if (zendkee_get_option('local_avatar')) {
    add_filter('get_avatar', 'zendkee_avatar', 10, 6);
    if(!function_exists('zendkee_avatar')){
        function zendkee_avatar($avatar, $id_or_email, $size, $default, $alt, $args)
        {
            $image_data = 'data:image/jpeg;base64,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';
            return sprintf('<img alt="%s" src="%s" class="avatar photo" height="%d" width="%d" />', $alt, $image_data, $size, $size);
        }
    }

}




/** 去除Avada不启用的element附带的JS start **/
add_action( 'wp_enqueue_scripts', function(){
    if(class_exists('Fusion_Dynamic_JS')){
        global $avada_exclude_elements;
        global $avada_exclude_elements_scripts_map;

        foreach ($avada_exclude_elements as $element){
            $script_list = isset($avada_exclude_elements_scripts_map[$element]) ? $avada_exclude_elements_scripts_map[$element] : '';
            if(!empty($script_list) && function_exists('fusion_is_element_enabled') && !fusion_is_element_enabled($element)){

                foreach ((array)$script_list as $script){

                    Fusion_Dynamic_JS::deregister_script($script);
                }
            }
        }
    }




} , 0 );
/** 去除Avada不启用的element附带的JS end **/



/** Elementor相关 **/

//去除所有通知
if(zendkee_get_option('disable_notice')){
    add_action('admin_head' ,function(){

        //删除所有通知
        remove_all_actions( 'admin_notices' );

    });
}


//禁止更新通知
if(zendkee_get_option('disable_update_notice')){
    add_filter( 'auto_core_update_send_email', '__return_false' );
}


//B2B端优化
if(zendkee_get_option('optimize_b2b')){
//    wc-add-to-cart
    add_action('wp_enqueue_scripts', function () {
//        $wp_scripts = wp_scripts();
//        var_dump($wp_scripts);
        wp_deregister_script('wc-add-to-cart');
        wp_deregister_script('wc-add-to-cart-variation');
        wp_deregister_script('wc-cart-fragments');
        wp_deregister_script('wc-cart');
        wp_deregister_script('wc-add-payment-method');
        wp_deregister_script('wc-checkout');
        wp_deregister_script('wc-credit-card-form');
        wp_deregister_script('jquery-payment');


        wp_deregister_script('avada-quantity');

    },11);

    //去除产品列表页?add_to_cart的动态链接
    add_filter(
        'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
        function ($html, $product, $args) {
            return '';
        }, 20, 3
    );


    //run once
    //unable woocommerce reviews
    //update_option('woocommerce_enable_reviews' , 'no');
}


//去除捐赠信息
if(zendkee_get_option('remove_donate')) {
    remove_action( 'add_meta_boxes', 'devvn_ihotspot_donate_meta_box' );
}


//减少Shop Page的动态链接（Avada主题适用）
if(zendkee_get_option('reduce_shop_page_dynamic_url')) {

    function zendkee_catalog_ordering()
    {
        include(dirname(__DIR__) . '/templates/wc-catalog-ordering.php');
    }

//replace Avada - Woocommerce Archive Page - ordering , view url
    add_action('woocommerce_before_shop_loop', function () {
        global $avada_woocommerce;

        //remove action:woocommerce_before_shop_loop : $avada_woocommerce -> 'catalog_ordering'
        remove_action('woocommerce_before_shop_loop', [$avada_woocommerce, 'catalog_ordering'], 30);

        add_action('woocommerce_before_shop_loop', 'zendkee_catalog_ordering', 40);
    });
}