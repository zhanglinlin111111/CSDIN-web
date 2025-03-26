<?php
/*
Plugin Name: Zendkee Customize
Plugin URI: #
Description: 网站自定义功能：屏蔽、优化、辅助功能
Author: Zendkee
Version: 3.38
Author URI: #

*/
//error_reporting(E_ALL);
//ini_set('display_errors', 'on');
//die(plugin_dir_path(__FILE__ ));

if (!defined('ABSPATH')) {
    exit();
}

//init variable
define('ZENDKEE_ADMIN_FLAG', 'zendkee_customize');
define('ZENDKEE_CUSTOM_PATH', plugin_dir_path(__FILE__));
define('ZENDKEE_CUSTOM_VERSION', '3.38');

//全局变量，是否ip白名单客户
$is_ip_in_white_list = false;
//全局变量，是否ip黑名单客户
$is_ip_in_black_list = false;

$start_time = microtime(true);
// 测试内存使用情况
$start_memory = memory_get_usage();

// $zk_ts=microtime(1);



require_once(ZENDKEE_CUSTOM_PATH . 'config.php');
require_once(ZENDKEE_CUSTOM_PATH . 'include/functions.php');

$is_admin_page = is_admin();


if ($is_admin_page) {
    //插件列表加入设置链接的入口
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'zendkee_add_settings_link');
    if (!function_exists('zendkee_add_settings_link')) {
        function zendkee_add_settings_link($links)
        {
            $plugin_links = array(
                '<a href="' . admin_url('admin.php?page=' . ZENDKEE_ADMIN_FLAG) . '">' . __('Settings', 'zendkee') . '</a>',
            );
            return array_merge($plugin_links, $links);
        }
    }
}


if ($is_admin_page) { //后台加载设置项
    require_once(ZENDKEE_CUSTOM_PATH . 'settings.php');
}



if ($is_admin_page) { //后台检查更新
    require_once(ZENDKEE_CUSTOM_PATH . 'plugin-update-checker/plugin-update-checker.php');

    $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://download.ehaitech.com/update/zendkee_customize/info.json',
        __FILE__,
        'zendkee_customize'
    );
}


//执行操作
require_once(ZENDKEE_CUSTOM_PATH . 'run.php');


/**使用量显示 */
$end_time = microtime(true);
$cpu_time = $end_time - $start_time;
$end_memory = memory_get_usage();


$zendkee_customize['cpu'] = $cpu_time;
$zendkee_customize['memory'] = ($end_memory - $start_memory);
$zendkee_customize['max-memory'] = memory_get_peak_usage();


if (!function_exists('zendkee_customize_source_usage')) {
    function zendkee_customize_source_usage()
    {
        global $zendkee_customize;
        if (zendkee_get_option('enable_debug')) {
            printf('<p style="margin:0 auto;text-align: right; width: 400px;">CPU消耗时间：%.6f秒<br>内存消耗：%s字节<br>内存峰值：%s字节<br></p>', $zendkee_customize['cpu'], $zendkee_customize['memory'], $zendkee_customize['max-memory']);
        }
    }
}
add_action('wp_footer', 'zendkee_customize_source_usage');
add_action('admin_footer', 'zendkee_customize_source_usage');
