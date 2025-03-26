<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:29
 */


//ajax actions:misc
add_action('wp_ajax_zendkee_init', 'zendkee_init');
if (!function_exists('zendkee_init')) {
    function zendkee_init($par)
    {
        if ($_REQUEST['action'] == 'zendkee_init') {
            $clear_google_code = $_REQUEST['clear_google_code'];
            $clear_smtp_settings = $_REQUEST['clear_smtp_settings'];
            $clear_mail_settings = $_REQUEST['clear_mail_settings'];
            $clear_inquiry_log = $_REQUEST['clear_inquiry_log'];
            $clear_seo_log = $_REQUEST['clear_seo_log'];
            $clear_avada_elements = $_REQUEST['clear_avada_elements'];
            $optimize_avada = $_REQUEST['optimize_avada'];
            $disable_all_image_dimensions = $_REQUEST['disable_all_image_dimensions'];//这个值不需要保存
            $clear_settings = $_REQUEST['clear_settings'];

            zendkee_update_option('clear_google_code', $clear_google_code);
            zendkee_update_option('clear_smtp_settings', $clear_smtp_settings);
            zendkee_update_option('clear_mail_settings', $clear_mail_settings);
            zendkee_update_option('clear_inquiry_log', $clear_inquiry_log);
            zendkee_update_option('clear_seo_log', $clear_seo_log);
            zendkee_update_option('clear_avada_elements', $clear_avada_elements);
//            zendkee_update_option('optimize_avada', $optimize_avada);
            zendkee_update_option('clear_settings', $clear_settings);


            //禁用所有图像尺寸
            if ($disable_all_image_dimensions) {
                $new_sizes = wp_get_registered_image_subsizes();

                $disable_image_size = array();
                foreach ($new_sizes as $name => $size) {
                    $disable_image_size[] = $name;
                }

                zendkee_update_option('disable_builtin_size', '1');
                zendkee_update_option('disable_image_size', $disable_image_size);
            }


            //去除Avada不常用功能
            if ($optimize_avada) {
                $avada_options = get_option('fusion_options');

                $avada_options['status_totop'] = 'off';//ToTop Script
                $avada_options['status_yt'] = '0';//Youtube API Scripts
                $avada_options['status_vimeo'] = '0';//Vimeo API Scripts
                $avada_options['status_eslider'] = '0';//Elastic Slider

                $avada_options['woocommerce_acc_link_main_nav'] = '0';//WooCommerce My Account Link in Main Menu
                $avada_options['woocommerce_cart_link_main_nav'] = '0';//WooCommerce Cart Icon in Main Menu
                $avada_options['woocommerce_cart_counter'] = '0';//WooCommerce Menu Cart Icon Counter
                $avada_options['woocommerce_one_page_checkout'] = '1';//WooCommerce One Page Checkout

                $avada_options['comments_pages'] = '0';//Comments on Pages
                $avada_options['nofollow_social_links'] = '1';//Add "nofollow" to social links


                update_option('fusion_options', $avada_options);
            }


            if (zendkee_save_option()) {
                echo json_encode(array('status' => 'ok', 'info' => '网站初始化完毕！'));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Nothing To Do'));
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();

    }
}
/*
 *             if($optimize_avada){

            }
 * */
