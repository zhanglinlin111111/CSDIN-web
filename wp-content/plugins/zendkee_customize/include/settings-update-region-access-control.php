<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:20
 */



//ajax actions
//add_action('wp_ajax_nopriv_zendkee_region_view_control', 'zendkee_region_view_control'); // for not logged in users
add_action('wp_ajax_zendkee_region_view_control', 'zendkee_region_view_control');
if(!function_exists('zendkee_region_view_control')){
    function zendkee_region_view_control($par)
    {
        if ($_REQUEST['action'] == 'zendkee_region_view_control') {

            $enable_region_rule = $_REQUEST['enable_region_rule'];
            $svc_rule = $_REQUEST['svc_rule'];
            $enable_svc_debug_mode = $_REQUEST['enable_svc_debug_mode'];


            $country_status_code = array();
            foreach ($_REQUEST['country_status_code'] as $country_code => $status_code) {
                if ($status_code) {
                    $country_status_code[$country_code] = $status_code;
                }
            }

            zendkee_update_option('enable_region_rule', $enable_region_rule);
            zendkee_update_option('svc_rule', $svc_rule);
            zendkee_update_option('country_status_code', $country_status_code);
            zendkee_update_option('enable_svc_debug_mode', $enable_svc_debug_mode);


            if (zendkee_save_option()) {
                echo json_encode(array('status' => 'ok', 'info' => 'Update Success'));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Update Fail'));
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect Input'));
        }

        wp_die();
    }
}



//ajax actions - region view control - delete rule
//add_action('wp_ajax_nopriv_zendkee_svc_delete_rule', 'zendkee_svc_delete_rule'); // for not logged in users
add_action('wp_ajax_zendkee_svc_delete_rule', 'zendkee_svc_delete_rule');
if(!function_exists('zendkee_svc_delete_rule')){
    function zendkee_svc_delete_rule($par)
    {
        if ($_REQUEST['action'] == 'zendkee_svc_delete_rule') {
            $country_status_code = zendkee_get_option('country_status_code');
            $delete_country_code = $_REQUEST['country_code'];

            foreach ($country_status_code as $country_code => $status_code) {
                if ($delete_country_code == $country_code) {
                    unset($country_status_code[$country_code]);
                }
            }

            zendkee_update_option('country_status_code', $country_status_code);

            if (zendkee_save_option()) {
                echo json_encode(array('status' => 'ok', 'info' => 'Update Success'));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Update Fail'));
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();
    }
}
