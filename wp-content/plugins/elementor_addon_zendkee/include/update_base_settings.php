<?php
if (!defined('ABSPATH')) {
    exit();
}

//ajax actions:base settings
add_action('wp_ajax_zendkee_elementor_base', 'zendkee_elementor_base');
if (!function_exists('zendkee_elementor_base')) {
    function zendkee_elementor_base($par)
    {
        $ze_hide_product_main_description = $_REQUEST['ze_hide_product_main_description'];
        $ze_redirect_to_elementor_editor = $_REQUEST['ze_redirect_to_elementor_editor'];
        $ze_progress_bar_color = $_REQUEST['ze_progress_bar_color'];
        $ze_product_list_menu_dropdown = $_REQUEST['ze_product_list_menu_dropdown'];
        $ze_product_detail_gallery_carousel = $_REQUEST['ze_product_detail_gallery_carousel'];
        $display_product_archive_short_desc = $_REQUEST['display_product_archive_short_desc'];
        $customize_avatar = $_REQUEST['customize_avatar'];
        $compatible_everest_admin_theme = $_REQUEST['compatible_everest_admin_theme'];
        $ze_product_detail_inquiry_shortcode_enable = $_REQUEST['ze_product_detail_inquiry_shortcode_enable'];
        $ze_product_detail_inquiry_shortcode = stripcslashes($_REQUEST['ze_product_detail_inquiry_shortcode']);

        zendkee_update_option('ze_hide_product_main_description', $ze_hide_product_main_description);
        zendkee_update_option('ze_redirect_to_elementor_editor', $ze_redirect_to_elementor_editor);
        zendkee_update_option('ze_progress_bar_color', $ze_progress_bar_color);
        zendkee_update_option('ze_product_list_menu_dropdown', $ze_product_list_menu_dropdown);
        zendkee_update_option('ze_product_detail_gallery_carousel', $ze_product_detail_gallery_carousel);
        zendkee_update_option('display_product_archive_short_desc', $display_product_archive_short_desc);
        zendkee_update_option('customize_avatar', $customize_avatar);
        zendkee_update_option('compatible_everest_admin_theme', $compatible_everest_admin_theme);
        zendkee_update_option('ze_product_detail_inquiry_shortcode_enable', $ze_product_detail_inquiry_shortcode_enable);
        zendkee_update_option('ze_product_detail_inquiry_shortcode', $ze_product_detail_inquiry_shortcode);


        if (zendkee_save_option()) {
            echo json_encode(array('status' => 'ok', 'info' => 'Update Success'));
        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Nothing To Do'));
        }


        wp_die();
    }
}
