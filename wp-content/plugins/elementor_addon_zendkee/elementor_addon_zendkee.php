<?php
/*
Plugin Name: Elementor Add On - Zendkee

Description: AN Elementor Add On
Version: 1.20.7
Author: Zendkee


*/


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/** require base function **/
require_once(__DIR__ . '/functions.php');


/** config with TWIG **/
define('TWIG_AUTOLOAD_FILE', __DIR__ . '/vendor/autoload.php');
define('ELEMENT_ADDON_ZENDKEE_TEMPLATE', __DIR__ . '/templates/');
define('ELEMENT_ADDON_ZENDKEE_URL', plugin_dir_url(__FILE__));
define('ELEMENT_ADDON_ZENDKEE_PATH', plugin_dir_path(__FILE__));
define('ZENDKEE_ELEMENT_FLAG', 'zendkee_elementor');

$upload_dir = wp_upload_dir();
define('TWIG_CACHE_DIR', $upload_dir['basedir'] . '/cache/');


/** Update Check **/
require_once(ELEMENT_ADDON_ZENDKEE_PATH . 'plugin-update-checker/plugin-update-checker.php');

$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://download.ehaitech.com/update/elementor_addon_zendkee/info.json',
    __FILE__,
    'elementor_addon_zendkee'
);







Elementor_Zendkee_Extension::instance();
/** 扩展功能 end **/



/** Init Start **/

//插件激活初始化
register_activation_hook(__FILE__, function () {
    //默认隐藏产品详情页主编辑器
    zendkee_update_option('ze_hide_product_main_description', '1');
    //默认打开Editor登录跳转到首页编辑页
    zendkee_update_option('ze_redirect_to_elementor_editor', '1');
    //产品列表页侧边栏菜单下拉样式
    // zendkee_update_option('ze_product_list_menu_dropdown', '1');
    //产品详情页图库轮播
    // zendkee_update_option('ze_product_detail_gallery_carousel', '1');
    //产品分类页显示产品短描述
    // zendkee_update_option('display_product_archive_short_desc', '1');
    //使用自定义avatar头像
    zendkee_update_option('customize_avatar', '1');
    //适配主题Everest Admin Theme
    // zendkee_update_option('compatible_everest_admin_theme', '1');

    zendkee_save_option();
});
/** Init End **/




/** Settings start **/
//for admin settins page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=' . ZENDKEE_ELEMENT_FLAG) . '">' . __('Settings', 'zendkee') . '</a>',
    );

    return array_merge($plugin_links, $links);
});

require_once(ELEMENT_ADDON_ZENDKEE_PATH . 'settings.php');
/** Settings end **/





/** Run start */
require_once(ELEMENT_ADDON_ZENDKEE_PATH . 'run.php');
/** Run end */





/*
 * 加载后台全局css
 * */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('element_addon_zendkee_backend_all_css', ELEMENT_ADDON_ZENDKEE_URL . 'libs/css/backend_all.css');
});



/*
 * 加载后台全局js
 * */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('element_addon_zendkee_backend_all_js', ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/backend_all.js', array('jquery'), '1.0', true);
}, 10000);

//add_action('admin_footer',function(){
//    printf('<script type="text/javascript" src="%s"></script>' , ELEMENT_ADDON_ZENDKEE_URL . 'libs/js/backend_all.js');
//
//} , 10000000000);








//隐藏了很多不常用/容易造成页面错乱、不美观的内容。
//需要激活Element Pro插件
add_action('init', function () {
    require_once(__DIR__ . '/vendor/disable_edit_functions/disable_edit_functions.php');
});




//add capacity
require_once(__DIR__ . '/vendor/capacity/add_capacity.php');
//drop capacity
require_once(__DIR__ . '/vendor/capacity/drop_capacity.php');




/* 增加自定义功能 */
//改写菜单
require_once(__DIR__.'/vendor/feature/change_menu.php');
