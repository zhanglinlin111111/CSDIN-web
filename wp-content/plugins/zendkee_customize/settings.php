<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2019/3/18
 * Time: 18:09
 */

if (!defined('ABSPATH')) {
    exit();
}


//插件自动更新：https://www.wpdaxue.com/automatic-updates-for-any-plugin.html


// require_once (__DIR__.'/config.php');



//default settings
// $default_settings = array(
//     'rule' => 'Disallow',
//     'country_status' => array(
//         //country code => status code
//         //....
//     ),
// );


// require_once (__DIR__.'/include/functions.php');


//add menu
add_action('admin_menu', 'zendkee_add_menu');
if(!function_exists('zendkee_add_menu')){
    function zendkee_add_menu()
    {
        add_menu_page('Zendkee Customize', 'Zendkee Customize', 'manage_options', ZENDKEE_ADMIN_FLAG, 'zendkee_settings_display', 'dashicons-hammer');

    }
}





//load scripts and styles
add_action('admin_enqueue_scripts', 'zendkee_load_script_and_style');
if(!function_exists('zendkee_load_script_and_style')){
    function zendkee_load_script_and_style()
    {
        if (strpos($_SERVER['REQUEST_URI'], ZENDKEE_ADMIN_FLAG) !== false) {
            // wp_register_style('layui_css', 'https://cdnjs.cloudflare.com/ajax/libs/layui/2.5.6/css/layui.min.css');
            wp_register_style('layui_css', plugin_dir_url(__FILE__) . 'js_libs/layui/css/layui.css');
            wp_enqueue_style('layui_css');

            wp_enqueue_style(ZENDKEE_ADMIN_FLAG . '-css', plugin_dir_url(__FILE__) . 'css/admin.css', [] , ZENDKEE_CUSTOM_VERSION);


            // wp_register_script('layui_js', 'https://cdnjs.cloudflare.com/ajax/libs/layui/2.5.6/layui.min.js', '');
            wp_register_script('layui_js', plugin_dir_url(__FILE__) . 'js_libs/layui/layui.js', '');
            wp_enqueue_script('layui_js');

            //load jquery ui sortable js
            wp_enqueue_script('jquery-ui-sortable');

            wp_enqueue_script(ZENDKEE_ADMIN_FLAG . '-js', plugin_dir_url(__FILE__) . 'js/admin.js' , array('jquery') , ZENDKEE_CUSTOM_VERSION);

            $data = array(
                'ajax_url' => admin_url('admin-ajax.php'),
            );
        
            // Localize the script with the data
            wp_localize_script(ZENDKEE_ADMIN_FLAG . '-js', 'zendkee_customize_js_object', $data);
        }
    }
}





//显示设置的主页
if(!function_exists('zendkee_settings_display')){
    function zendkee_settings_display()
    {
        $enable_pro = zendkee_get_option('enable_pro');
        $is_old_version_exists = zendkee_is_old_version_exists();
        ?>
        <style>
            body {
                background-color: #fff;
            }

            .layui-form-label {
                width: 180px;
            }

            .layui-input-block {
                margin-left: 210px;
            }
        </style>

        <div class="notice notice-info"> <p>Zendkee Customize插件版本:<a target="_blank" href="<?php echo plugin_dir_url(__FILE__);?>readme.html"><?php echo ZENDKEE_CUSTOM_VERSION;?></a></p> </div>

        <div class="layui-form">
            <div class="layui-tab layui-tab-card" lay-filter="zendkee_custom_tabs">
                <ul class="layui-tab-title">
                    <li lay-id="ip_view_control" class="layui-this">IP View Control</li>
                    <li lay-id="region_view_control">Region View Control</li>
                    <li lay-id="small_feature">Small Feature</li>
                    <li lay-id="optimize">Optimize</li>
                    <li lay-id="misc">Misc</li>
                    <li lay-id="database">Database</li>
                    <li lay-id="seo">SEO</li>
                    <?php if($enable_pro){?><li lay-id="init">Init</li><?php } ?>
                    <?php if($enable_pro){?><li lay-id="online">Online</li><?php } ?>
                    <?php
                    if ($is_old_version_exists) {
                        echo '<li lay-id="compatible_update">Compatible Update</li>';
                    }
                    ?>
                    <li lay-id="pro">Pro Version</li>

                </ul>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show"><?php require_once(__DIR__ . '/include/settings-view-ip-access-control.php'); ?></div>
                    <div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-region-access-control.php'); ?></div>
                    <div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-small-feature.php'); ?></div>
                    <div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-optimize.php'); ?></div>
                    <div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-misc.php'); ?></div>
                    <div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-database.php'); ?></div>
                    <div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-seo.php'); ?></div>
                    <?php if($enable_pro){?><div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-init.php'); ?></div><?php } ?>
                    <?php if($enable_pro){?><div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-online.php'); ?></div><?php } ?>
                    <?php
                    if ($is_old_version_exists) {
                        echo '<div class="layui-tab-item">';
                        require_once(__DIR__ . '/include/settings-view-compatible.php');
                        echo '</div>';
                    }
                    ?>
                    <div class="layui-tab-item"><?php require_once(__DIR__ . '/include/settings-view-pro.php'); ?></div>

                </div>
            </div>
            <a href="#" id="reset" class="layui-btn layui-btn-danger" style="float:right;clear-after: right;">还原默认值</a>
        </div>

        <?php


    }
}



//开启debug模式后，后台全局提示功能
if (zendkee_get_option('enable_debug')) {
    add_action('admin_notices', function () {
        printf('<div class="notice notice-error is-dismissible"> <p>系统调试模式已经开启。<a href="%s">点击这里</a>去设置</p> </div>', admin_url('admin.php?page=' . ZENDKEE_ADMIN_FLAG));
    });
}


/*****************       IP Access Control Start             ******************/
require_once (__DIR__.'/include/settings-update-ip-access-control.php');
/*****************       IP Access Control End             ******************/


/*****************       Region Access Control Start             ******************/
require_once (__DIR__.'/include/settings-update-region-access-control.php');
/*****************       Region Access Control End             ******************/


/*****************       Small Feature Start             ******************/
require_once (__DIR__.'/include/settings-update-small-feature.php');
/*****************       Small Feature End             ******************/


/*****************       Optimize Start             ******************/
require_once (__DIR__.'/include/settings-update-optimize.php');
/*****************       Optimize End             ******************/


/*****************       Misc Start             ******************/
require_once (__DIR__.'/include/settings-update-misc.php');
/*****************       Misc End             ******************/


/*****************       SEO Start             ******************/
require_once (__DIR__.'/include/settings-update-seo.php');
/*****************       SEO End             ******************/

/*****************       Database Start             ******************/
require_once (__DIR__.'/include/settings-update-database.php');
/*****************       Database End             ******************/


/*****************       Init Start             ******************/
require_once (__DIR__.'/include/settings-update-init.php');
/*****************       Init End             ******************/


/*****************       Online Start             ******************/
require_once (__DIR__.'/include/settings-update-online.php');
/*****************       Online End             ******************/



/*****************       Compatible Start             ******************/
require_once (__DIR__.'/include/settings-update-compatible.php');
/*****************       Compatible End             ******************/


/*****************       Online Start             ******************/
require_once (__DIR__.'/include/settings-update-pro.php');
/*****************       Online End             ******************/