<?php

/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 11:27
 */


//根据ip判断是否能访问网站
add_action('init', 'show_site_of_ip', 1);
if (!function_exists('show_site_of_ip')) {
    function show_site_of_ip()
    {
        if (!is_admin() && !is_user_logged_in() && !preg_match("~^/wp-login\.php~", $_SERVER['REQUEST_URI'])) { //not wp-admin, not login user , not login page
            global $status_code_list;
            global $is_ip_in_white_list;
            global $is_ip_in_black_list;

            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            $enable_ip_rule = zendkee_get_option('enable_ip_rule');
            $ip_rule = zendkee_get_option('ip_rule');
            $ip_white_list = zendkee_get_option('ip_white_list');
            $ip_black_list = zendkee_get_option('ip_black_list');
            $enable_ivc_debug_mode = zendkee_get_option('enable_ivc_debug_mode');


            if ($enable_ivc_debug_mode) {
                printf('<!-- IP:%s -->', $ip);
            }


            if ($enable_ip_rule) {
                if ($ip_rule == 'only_white_list') {
                    if (!in_array($ip, $ip_white_list)) {
                        status_header(array_search('Disallow', $status_code_list), '');
                        exit;
                    }
                } elseif ($ip_rule == 'only_black_list') {
                    if (in_array($ip, $ip_black_list)) {
                        status_header(array_search('Disallow', $status_code_list), '');
                        exit;
                    }
                } elseif ($ip_rule == 'white_list') {
                    if (in_array($ip, $ip_white_list)) {
                        $is_ip_in_white_list = true;
                    }
                } elseif ($ip_rule == 'black_list') {
                    if (in_array($ip, $ip_black_list)) {
                        $is_ip_in_black_list = true;
                    }
                }
            }
        }
    }
}
