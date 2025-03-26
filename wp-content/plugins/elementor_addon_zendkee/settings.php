<?php
if (!defined('ABSPATH')) {
    exit();
}


//add menu
add_action('admin_menu', function () {
    add_menu_page('Zendkee Elementor', 'Zendkee Elementor', 'manage_options', ZENDKEE_ELEMENT_FLAG, 'zendkee_elementor_settings_display', 'dashicons-palmtree');

    
});


//load scripts and styles
add_action('admin_enqueue_scripts', 'zendkee_elementor_load_script_and_style');
if (!function_exists('zendkee_elementor_load_script_and_style')) {
    function zendkee_elementor_load_script_and_style()
    {
        if (strpos($_SERVER['REQUEST_URI'], ZENDKEE_ELEMENT_FLAG) !== false) {
            wp_register_style('layui_css', 'https://www.layuicdn.com/layui-v2.5.6/css/layui.css');
            wp_enqueue_style('layui_css');

            wp_enqueue_style(ZENDKEE_ELEMENT_FLAG . '-css', plugin_dir_url(__FILE__) . 'libs/css/backend_settings.css');

            wp_register_script('layui_js', 'https://www.layuicdn.com/layui-v2.5.6/layui.js', '');
            wp_enqueue_script('layui_js' );



            wp_enqueue_script(ZENDKEE_ELEMENT_FLAG . '-js', plugin_dir_url(__FILE__) . 'libs/js/backend_settings.js', array('jquery'), '3.6.0');
            $data = array(
                'ajax_url' => admin_url('admin-ajax.php'),
            );
        
            // Localize the script with the data
            wp_localize_script(ZENDKEE_ELEMENT_FLAG . '-js', 'zendkee_elementor_js_object', $data);
        }
    }
}


//显示设置的主页
if (!function_exists('zendkee_elementor_settings_display')) {
    function zendkee_elementor_settings_display()
    {
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


        <div class="layui-form">
            <div class="layui-tab layui-tab-card" lay-filter="zendkee_elementor_tabs">
                <ul class="layui-tab-title">
                    <li lay-id="base_settings" class="layui-this">Base Settings</li>

                </ul>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show"><?php require_once(__DIR__ . '/include/view_base_settings.php'); ?></div>

                </div>
            </div>
<!--            <a href="#" id="reset" class="layui-btn layui-btn-danger" style="float:right;clear-after: right;">还原默认值</a>-->
        </div>

        <?php
    }
}



/*****************       Base Settings Start             ******************/
require_once(__DIR__ . '/include/update_base_settings.php');
/*****************       Base Settings End             ******************/
