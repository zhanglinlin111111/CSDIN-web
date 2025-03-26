<?php
/*
Plugin Name: Zendkee Extends For Woocommerce
Plugin URI: #
Description: 给Woocommerce做扩展
Author: Zendkee
Version: 3.5
Author URI: #
*/


include __DIR__.'/meta_box.php';
include __DIR__.'/shortcode.php';



//引入js
add_action('admin_enqueue_scripts', function () {
    // //引入css
    // wp_register_style('stylesheet_css', get_stylesheet_directory_uri() . '/style.css');
    // wp_enqueue_style('stylesheet_css');

    //引入js
    wp_register_script('zk_extends_js', plugin_dir_url(__FILE__) . '/backend.js', '', '1.0', true);
    wp_enqueue_script('zk_extends_js');
});


