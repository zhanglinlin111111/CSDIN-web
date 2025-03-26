<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/11/5 0005
 * Time: 11:46
 */


//ajax actions,update old version data
//add_action('wp_ajax_nopriv_zendkee_compatible', 'zendkee_compatible'); // for not logged in users
add_action('wp_ajax_zendkee_compatible', 'zendkee_compatible');
if(!function_exists('zendkee_compatible')){
    function zendkee_compatible($par)
    {
        if ($_REQUEST['action'] == 'zendkee_compatible') {

            if ($_REQUEST['compatible_action'] == 'import') {
                if (zendkee_import_old_version_data()) {
                    echo json_encode(array('status' => 'ok', 'info' => 'Data Update Success'));
                } else {
                    echo json_encode(array('status' => 'fail', 'info' => 'Data Update Fail'));
                }

            } elseif ($_REQUEST['compatible_action'] == 'remove') {
                if (zendkee_remove_old_version_data()) {
                    echo json_encode(array('status' => 'ok', 'info' => 'Old Data Remove Success'));
                } else {
                    echo json_encode(array('status' => 'fail', 'info' => 'Old Data Remove Fail'));
                }
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();
    }
}



//ajax actions,reset options
//add_action('wp_ajax_nopriv_zendkee_reset', 'zendkee_reset'); // for not logged in users
add_action('wp_ajax_zendkee_reset', 'zendkee_reset');
if(!function_exists('zendkee_reset')){
    function zendkee_reset($par)
    {
        if ($_REQUEST['action'] == 'zendkee_reset') {

            if (zendkee_reset_option()) {
                echo json_encode(array('status' => 'ok', 'info' => 'Reset Success'));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Reset Fail'));
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();
    }
}
