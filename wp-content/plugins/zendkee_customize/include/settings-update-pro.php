<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:29
 */



//ajax actions:online
add_action('wp_ajax_zendkee_pro', 'zendkee_pro');
if(!function_exists('zendkee_pro')){
    function zendkee_pro($par)
    {
        if ($_REQUEST['action'] == 'zendkee_pro') {

            $pro_key = $_REQUEST['pro_key'];
            $status = $_REQUEST['status'];

            $msg = '';
            if($status=='enable'){
                if($pro_key == 'Zendkee'){
                    zendkee_update_option('enable_pro', 1);
                    $msg = 'Pro Version Enabled.';
                }else{
                    $msg = 'Key Incorrect';
                    echo json_encode(array('status' => 'fail', 'info' => $msg));
                    wp_die();
                }
            }elseif($status=='disable'){
                zendkee_update_option('enable_pro', 0);
                $msg = 'Pro Version Disabled.';
            }



            if (zendkee_save_option()) {
                echo json_encode(array('status' => 'ok', 'info' => $msg));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Nothing To Do'));
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();

    }
}
