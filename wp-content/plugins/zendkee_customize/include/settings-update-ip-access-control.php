<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:23
 */

//ajax actions:ip_view_control
//add_action('wp_ajax_nopriv_zendkee_ip_view_control', 'zendkee_ip_view_control'); // for not logged in users
add_action('wp_ajax_zendkee_ip_view_control', 'zendkee_ip_view_control');
if(!function_exists('zendkee_ip_view_control')){
    function zendkee_ip_view_control($par)
    {
        if ($_REQUEST['action'] == 'zendkee_ip_view_control') {
            $enable_ip_rule = $_REQUEST['enable_ip_rule'];
            $ip_rule = $_REQUEST['ip_rule'];
            $ip_white_list = textarea_list_to_ip_list($_REQUEST['ip_white_list']);
            $ip_black_list = textarea_list_to_ip_list($_REQUEST['ip_black_list']);
            $enable_ivc_debug_mode = $_REQUEST['enable_ivc_debug_mode'];


            zendkee_update_option('enable_ip_rule', $enable_ip_rule);
            zendkee_update_option('ip_rule', $ip_rule);
            zendkee_update_option('ip_white_list', $ip_white_list);
            zendkee_update_option('ip_black_list', $ip_black_list);
            zendkee_update_option('enable_ivc_debug_mode', $enable_ivc_debug_mode);


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
