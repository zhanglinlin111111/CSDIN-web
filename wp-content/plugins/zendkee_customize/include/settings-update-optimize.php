<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:25
 */


//ajax actions:optimize
//add_action('wp_ajax_nopriv_zendkee_form_optimize', 'zendkee_form_optimize'); // for not logged in users
add_action('wp_ajax_zendkee_form_optimize', 'zendkee_form_optimize');
if(!function_exists('zendkee_form_optimize')){
    function zendkee_form_optimize($par)
    {
        if ($_REQUEST['action'] == 'zendkee_form_optimize') {
            $disable_emoji = $_REQUEST['disable_emoji'];
            $disable_embeds = $_REQUEST['disable_embeds'];
            $disable_feed = $_REQUEST['disable_feed'];
            $disable_offline_editor = $_REQUEST['disable_offline_editor'];
            $disable_index_rel_link = $_REQUEST['disable_index_rel_link'];
            $disable_prev_next = $_REQUEST['disable_prev_next'];
            $disable_related_post = $_REQUEST['disable_related_post'];
            $disable_feature_publish = $_REQUEST['disable_feature_publish'];
            $disable_generator = $_REQUEST['disable_generator'];
            $disable_shortlink = $_REQUEST['disable_shortlink'];
            $disable_comment_style = $_REQUEST['disable_comment_style'];

            $disable_language_package = $_REQUEST['disable_language_package'];
            $disable_language_package_backend = $_REQUEST['disable_language_package_backend'];
            $cdn_js_libs = $_REQUEST['cdn_js_libs'];
            $cdn_js_libs_type = $_REQUEST['cdn_js_libs_type'];
            $cdn_css_libs = $_REQUEST['cdn_css_libs'];
            $remove_js_css_version = $_REQUEST['remove_js_css_version'];
            $disable_rss = $_REQUEST['disable_rss'];
            $disable_pingbacks = $_REQUEST['disable_pingbacks'];
            $disable_xml_rpc = $_REQUEST['disable_xml_rpc'];
            $disable_google_font = $_REQUEST['disable_google_font'];
            $disable_about_wordpress_icon = $_REQUEST['disable_about_wordpress_icon'];
            $disable_dashboard_widget = $_REQUEST['disable_dashboard_widget'];

            $disable_woocommerce_admin = $_REQUEST['disable_woocommerce_admin'];
            $disable_avada_faq = $_REQUEST['disable_avada_faq'];
            $disable_avada_portfolio = $_REQUEST['disable_avada_portfolio'];
            $reduce_shop_page_dynamic_url = $_REQUEST['reduce_shop_page_dynamic_url'];
            $disable_wp_json_and_rest_api = $_REQUEST['disable_wp_json_and_rest_api'];
            $disable_notice = $_REQUEST['disable_notice'];
            $disable_update_notice = $_REQUEST['disable_update_notice'];
            $optimize_b2b = $_REQUEST['optimize_b2b'];


            $page_preload = $_REQUEST['page_preload'];
            $local_avatar = $_REQUEST['local_avatar'];
            $remove_donate = $_REQUEST['remove_donate'];


            zendkee_update_option('disable_emoji', $disable_emoji);
            zendkee_update_option('disable_embeds', $disable_embeds);
            zendkee_update_option('disable_feed', $disable_feed);
            zendkee_update_option('disable_offline_editor', $disable_offline_editor);
            zendkee_update_option('disable_index_rel_link', $disable_index_rel_link);
            zendkee_update_option('disable_prev_next', $disable_prev_next);
            zendkee_update_option('disable_related_post', $disable_related_post);
            zendkee_update_option('disable_feature_publish', $disable_feature_publish);
            zendkee_update_option('disable_generator', $disable_generator);
            zendkee_update_option('disable_shortlink', $disable_shortlink);
            zendkee_update_option('disable_comment_style', $disable_comment_style);

            zendkee_update_option('disable_language_package', $disable_language_package);
            zendkee_update_option('disable_language_package_backend', $disable_language_package_backend);
            zendkee_update_option('cdn_js_libs', $cdn_js_libs);
            zendkee_update_option('cdn_js_libs_type', $cdn_js_libs_type);
            zendkee_update_option('cdn_css_libs', $cdn_css_libs);
            zendkee_update_option('remove_js_css_version', $remove_js_css_version);
            zendkee_update_option('disable_rss', $disable_rss);
            zendkee_update_option('disable_pingbacks', $disable_pingbacks);
            zendkee_update_option('disable_xml_rpc', $disable_xml_rpc);
            zendkee_update_option('disable_google_font', $disable_google_font);
            zendkee_update_option('disable_about_wordpress_icon', $disable_about_wordpress_icon);
            zendkee_update_option('disable_dashboard_widget', $disable_dashboard_widget);

            zendkee_update_option('disable_woocommerce_admin', $disable_woocommerce_admin);
            zendkee_update_option('disable_avada_faq', $disable_avada_faq);
            zendkee_update_option('disable_avada_portfolio', $disable_avada_portfolio);
            zendkee_update_option('reduce_shop_page_dynamic_url', $reduce_shop_page_dynamic_url);
            zendkee_update_option('disable_wp_json_and_rest_api', $disable_wp_json_and_rest_api);
            zendkee_update_option('disable_notice', $disable_notice);
            zendkee_update_option('disable_update_notice', $disable_update_notice);
            zendkee_update_option('optimize_b2b', $optimize_b2b);


            zendkee_update_option('page_preload', $page_preload);
            zendkee_update_option('local_avatar', $local_avatar);
            zendkee_update_option('remove_donate', $remove_donate);





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
