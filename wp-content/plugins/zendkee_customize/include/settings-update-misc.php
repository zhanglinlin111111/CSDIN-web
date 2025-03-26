<?php

/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:28
 */


//ajax actions:misc
//add_action('wp_ajax_nopriv_zendkee_misc', 'zendkee_misc'); // for not logged in users
add_action('wp_ajax_zendkee_misc', 'zendkee_misc');
if (!function_exists('zendkee_misc')) {
    function zendkee_misc($par)
    {
        if ($_REQUEST['action'] == 'zendkee_misc') {
            $menu_to_add_language_bar = $_REQUEST['menu_to_add_language_bar'];
            $menu_to_add_search_bar = $_REQUEST['menu_to_add_search_bar'];
            $translate_these = $_REQUEST['translate_these'];
            $disable_builtin_size = $_REQUEST['disable_builtin_size'];
            $disable_image_size = $_REQUEST['disable_image_size'];

            $image_hide_title = $_REQUEST['image_hide_title'];
            $enable_webp = $_REQUEST['enable_webp'];

            $remove_menu_id_class = $_REQUEST['remove_menu_id_class'];
            $enable_custom_font = $_REQUEST['enable_custom_font'];
            $zk_font_name = $_REQUEST['zk_font_name'];
            $zk_font_value = $_REQUEST['zk_font_value'];

            $misc_ga = stripcslashes($_REQUEST['misc_ga']);
            $misc_gsc = stripcslashes($_REQUEST['misc_gsc']);
            $misc_gtm_header = stripcslashes($_REQUEST['misc_gtm_header']);
            $misc_gtm_footer = stripcslashes($_REQUEST['misc_gtm_footer']);

            $enable_page_code = stripcslashes($_REQUEST["enable_page_code"]);
            $misc_page_code_pageid = stripcslashes($_REQUEST["misc_page_code_pageid"]);
            $misc_gsc_page['thanks'] = stripcslashes($_REQUEST['misc_gsc_page']['thanks']);
            $misc_ga_page['thanks'] = stripcslashes($_REQUEST['misc_ga_page']['thanks']);
            $misc_gtm_header_page['thanks'] = stripcslashes($_REQUEST['misc_gtm_header_page']['thanks']);
            $misc_gtm_footer_page['thanks'] = stripcslashes($_REQUEST['misc_gtm_footer_page']['thanks']);



            $product_detail_contact_form = stripcslashes($_REQUEST['product_detail_contact_form']);
            $product_detail_form_priority = $_REQUEST['product_detail_form_priority'];
            $product_list_contact_form = stripcslashes($_REQUEST['product_list_contact_form']);
            $enable_product_list_contact_form = stripcslashes($_REQUEST['enable_product_list_contact_form']);
            $footer_contact_form = stripcslashes($_REQUEST['footer_contact_form']);
            $enable_footer_contact_form = stripcslashes($_REQUEST['enable_footer_contact_form']);
            $jumpto = $_REQUEST['jumpto'];
            $enhance_form = $_REQUEST['enhance_form'];
            // $enhance_select = $_REQUEST['enhance_select'];
            $form_select_button_bg_color = $_REQUEST['form_select_button_bg_color'];
            $form_submit_button_bg_color = $_REQUEST['form_submit_button_bg_color'];
            $form_button_radius_size = $_REQUEST['form_button_radius_size'];
            $jumpto_position = $_REQUEST['jumpto_position'];
            $jumpto_text = stripcslashes($_REQUEST['jumpto_text']);
            $float_contact_form = stripcslashes($_REQUEST['float_contact_form']);
            $quote_text = stripcslashes($_REQUEST['quote_text']);
            //        $float_button_base_color = stripcslashes($_REQUEST['float_button_base_color']) ? stripcslashes($_REQUEST['float_button_base_color']) : '#555555';
            $float_button_bg_color = stripcslashes($_REQUEST['float_button_bg_color']) ? stripcslashes($_REQUEST['float_button_bg_color']) : '#ffffff';
            $scroll_top = $_REQUEST['scroll_top'];
            $enable_float_form = $_REQUEST['enable_float_form'];

            $enable_frontend_js = $_REQUEST['enable_frontend_js'];
            $frontend_js = preg_replace(array("/<script[^>]*?>/", "/<\/script>/"), array('', ''), stripcslashes(trim($_REQUEST['frontend_js'])));
            $enable_backend_js = $_REQUEST['enable_backend_js'];
            $backend_js = preg_replace(array("/<script[^>]*?>/", "/<\/script>/"), array('', ''), stripcslashes(trim($_REQUEST['backend_js'])));
            
            $enable_frontend_css = $_REQUEST['enable_frontend_css'];
            $frontend_css_position = $_REQUEST['frontend_css_position'];
            $frontend_css = preg_replace(array("/<style[^>]*?>/", "/<\/style>/"), array('', ''), stripcslashes(trim($_REQUEST['frontend_css'])));
            $enable_backend_css = $_REQUEST['enable_backend_css'];
            $backend_css = preg_replace(array("/<style[^>]*?>/", "/<\/style>/"), array('', ''), stripcslashes(trim($_REQUEST['backend_css'])));



            /*保存通用css*/
            $upload_dir = wp_upload_dir();

            //init
            $save_status_js_x0 = file_put_contents($upload_dir['basedir'] . '/zk_frontend.js', '');


            //本插件的通用css
            ob_start();
            echo PHP_EOL . '/* Zendkee Customize Frontend CSS Start */' . PHP_EOL;
            include(ZENDKEE_CUSTOM_PATH . 'css/frontend.css.php');
            echo PHP_EOL . '/* Zendkee Customize Frontend CSS End */' . PHP_EOL;
            echo str_repeat(PHP_EOL, 4);
            $frontend_css_x1 = ob_get_clean();
            $save_status_css_x0 = file_put_contents($upload_dir['basedir'] . '/zk_frontend.css', $frontend_css_x1);


            if ($enable_float_form && $float_contact_form || $scroll_top) {
                ob_start();
                echo PHP_EOL . '/* Zendkee Customize Frontend Form JS Start */' . PHP_EOL;
                include(ZENDKEE_CUSTOM_PATH . 'js/frontend.js');
                echo PHP_EOL . '/* Zendkee Customize Frontend Form JS End */' . PHP_EOL;
                echo str_repeat(PHP_EOL, 4);
                $frontend_js_x1 = ob_get_clean();
                $save_status_js_x1 = file_put_contents($upload_dir['basedir'] . '/zk_frontend.js', $frontend_js_x1, FILE_APPEND);
            }


            if ($enhance_form) {
                //增强型表单css
                ob_start();
                echo PHP_EOL . '/* Zendkee Customize Enhance Form CSS Start */' . PHP_EOL;
                include(ZENDKEE_CUSTOM_PATH . 'css/enhance_form.css.php');
                echo PHP_EOL . '/* Zendkee Customize Enhance Form CSS End */' . PHP_EOL;
                echo str_repeat(PHP_EOL, 4);
                $frontend_css_x2 = ob_get_clean();
                $save_status_css_x2 = file_put_contents($upload_dir['basedir'] . '/zk_frontend.css', $frontend_css_x2, FILE_APPEND);

                //增强型表单js
                ob_start();
                echo PHP_EOL . '/* Zendkee Customize Enhance Form JS Start */' . PHP_EOL;
                include ZENDKEE_CUSTOM_PATH . 'js/enhance_form.js';
                echo PHP_EOL . '/* Zendkee Customize Enhance Form JS End */' . PHP_EOL;
                echo str_repeat(PHP_EOL, 4);
                $frontend_js_x2 = ob_get_clean();
                $save_status_js_x2 = file_put_contents($upload_dir['basedir'] . '/zk_frontend.js', $frontend_js_x2, FILE_APPEND);
            }


            //将Global的frontend/backend内容写入文件
            if ($enable_frontend_js) {
                $frontend_js_x = PHP_EOL . '/* Zendkee Customize Global Frontend JS Start */' . PHP_EOL
                    . $frontend_js
                    . PHP_EOL . '/* Zendkee Customize Global Frontend JS End */' . str_repeat(PHP_EOL, 4);
                $save_status_js_x3 = file_put_contents($upload_dir['basedir'] . '/zk_frontend.js', $frontend_js_x, FILE_APPEND);
            }
            if ($enable_frontend_css) {
                $frontend_css_x = PHP_EOL . '/* Zendkee Customize Global Frontend CSS Start */' . PHP_EOL
                . $frontend_css
                    . PHP_EOL . '/* Zendkee Customize Global Frontend CSS End */' . str_repeat(PHP_EOL, 4);
                $save_status_css_x3 = file_put_contents($upload_dir['basedir'] . '/zk_frontend.css', $frontend_css_x, FILE_APPEND);
            }
            if ($enable_backend_js) {
                $backend_js_x = PHP_EOL . '/* Zendkee Customize Global Backend Js Start */' . PHP_EOL
                . $backend_js
                    . PHP_EOL . '/* Zendkee Customize Global Backend Js End */' . str_repeat(PHP_EOL, 4);
                $save_status_js_x4 = file_put_contents($upload_dir['basedir'] . '/zk_backend.js', $backend_js_x);
            }
            if ($enable_backend_css) {
                $backend_css_x = PHP_EOL . '/* Zendkee Customize Global Backend CSS Start */' . PHP_EOL
                    . $backend_css
                    . PHP_EOL . '/* Zendkee Customize Global Backend CSS End */' . str_repeat(PHP_EOL, 4);
                $save_status_css_x4 = file_put_contents($upload_dir['basedir'] . '/zk_backend.css', $backend_css_x);
            }



            $enable_outer_js = $_REQUEST['enable_outer_js'];
            $outer_js_position = $_REQUEST['outer_js_position'];
            $outer_js_dependence = $_REQUEST['outer_js_dependence'];
            $outer_js_dependence_other = $_REQUEST['outer_js_dependence_other'];
            $outer_js = $_REQUEST['outer_js'];
            $enable_outer_css = $_REQUEST['enable_outer_css'];
            $outer_css = $_REQUEST['outer_css'];


            $enable_debug = $_REQUEST['enable_debug'];

            zendkee_update_option('menu_to_add_language_bar', $menu_to_add_language_bar);
            zendkee_update_option('menu_to_add_search_bar', $menu_to_add_search_bar);
            zendkee_update_option('translate_these', $translate_these);
            zendkee_update_option('disable_builtin_size', $disable_builtin_size);
            zendkee_update_option('disable_image_size', $disable_image_size);
            zendkee_update_option('image_hide_title', $image_hide_title);
            zendkee_update_option('enable_webp', $enable_webp);
            zendkee_update_option('enable_debug', $enable_debug);
            zendkee_update_option('remove_menu_id_class', $remove_menu_id_class);

            zendkee_update_option('enable_custom_font', $enable_custom_font);
            zendkee_update_option('zk_font_name', $zk_font_name);
            zendkee_update_option('zk_font_value', $zk_font_value);
            zendkee_update_option('misc_ga', $misc_ga);
            zendkee_update_option('misc_gsc', $misc_gsc);
            zendkee_update_option('misc_gtm_header', $misc_gtm_header);
            zendkee_update_option('misc_gtm_footer', $misc_gtm_footer);

            zendkee_update_option('enable_page_code', $enable_page_code);
            zendkee_update_option('misc_page_code_pageid', $misc_page_code_pageid);
            zendkee_update_option('misc_gsc_page[thanks]', $misc_gsc_page['thanks']);
            zendkee_update_option('misc_ga_page[thanks]', $misc_ga_page['thanks']);
            zendkee_update_option('misc_gtm_header_page[thanks]', $misc_gtm_header_page['thanks']);
            zendkee_update_option('misc_gtm_footer_page[thanks]', $misc_gtm_footer_page['thanks']);



            zendkee_update_option('product_detail_contact_form', $product_detail_contact_form);
            zendkee_update_option('product_detail_form_priority', $product_detail_form_priority);
            zendkee_update_option('product_list_contact_form', $product_list_contact_form);
            zendkee_update_option('enable_product_list_contact_form', $enable_product_list_contact_form);
            zendkee_update_option('footer_contact_form', $footer_contact_form);
            zendkee_update_option('enable_footer_contact_form', $enable_footer_contact_form);
            zendkee_update_option('jumpto', $jumpto);
            zendkee_update_option('enhance_form', $enhance_form);
            // zendkee_update_option('enhance_select', $enhance_select);
            zendkee_update_option('form_select_button_bg_color', $form_select_button_bg_color);
            zendkee_update_option('form_submit_button_bg_color', $form_submit_button_bg_color);
            zendkee_update_option('form_button_radius_size', $form_button_radius_size);
            zendkee_update_option('jumpto_position', $jumpto_position);
            zendkee_update_option('jumpto_text', $jumpto_text);
            zendkee_update_option('float_contact_form', $float_contact_form);
            zendkee_update_option('quote_text', $quote_text);
            //        zendkee_update_option('float_button_base_color', $float_button_base_color);
            zendkee_update_option('float_button_bg_color', $float_button_bg_color);
            zendkee_update_option('scroll_top', $scroll_top);
            zendkee_update_option('enable_float_form', $enable_float_form);

            zendkee_update_option('enable_frontend_js', $enable_frontend_js);
            zendkee_update_option('frontend_js', $frontend_js);
            zendkee_update_option('enable_backend_js', $enable_backend_js);
            zendkee_update_option('backend_js', $backend_js);
            zendkee_update_option('enable_frontend_css', $enable_frontend_css);
            zendkee_update_option('frontend_css_position', $frontend_css_position);
            zendkee_update_option('frontend_css', $frontend_css);
            zendkee_update_option('enable_backend_css', $enable_backend_css);
            zendkee_update_option('backend_css', $backend_css);


            zendkee_update_option('enable_outer_js', $enable_outer_js);
            zendkee_update_option('outer_js_position', $outer_js_position);
            zendkee_update_option('outer_js_dependence', $outer_js_dependence);
            zendkee_update_option('outer_js_dependence_other', $outer_js_dependence_other);
            zendkee_update_option('outer_js', $outer_js);
            zendkee_update_option('enable_outer_css', $enable_outer_css);
            zendkee_update_option('outer_css', $outer_css);

            if($save_status_js_x1 === false || $save_status_css_x1 ===false || $save_status_js_x2 ===false || $save_status_css_x2 ===false || $save_status_js_x3 ===false || $save_status_css_x3 ===false || $save_status_js_x4 ===false || $save_status_css_x4 === false){
                echo json_encode(array('status' => 'fail', 'info' => 'File Save Failed'));
                wp_die();
            }

            if (zendkee_save_option()) {
                echo json_encode(array('status' => 'ok', 'info' => 'Update Success'));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Nothing To Do'));
            }
        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();
    }
}




//ajax actions:guess_old_url
add_action('wp_ajax_zendkee_guess_old_url', 'zendkee_guess_old_url');
if (!function_exists('zendkee_guess_old_url')) {
    function zendkee_guess_old_url($par)
    {
        $urls = array();

        global $wpdb;
        $row = $wpdb->get_row(sprintf("SELECT `option_value` FROM `{$wpdb->prefix}options` WHERE `option_name`='siteurl'"), ARRAY_A);

        $urls[] = trim($row['option_value']);

        $server_ip = file_get_contents('https://ip.gs/ip');
        if(rest_is_ip_address($server_ip)){
            $urls[] = $server_ip ? 'http://' . trim($server_ip) : '';
        }


        $urls = array_unique($urls);
        sort($urls);

        if ($urls) {
            echo json_encode(array('status' => 'ok', 'urls' => $urls));
        } else {
            echo json_encode(array('status' => 'fail', 'urls' => ''));
        }

        wp_die();
    }
}
