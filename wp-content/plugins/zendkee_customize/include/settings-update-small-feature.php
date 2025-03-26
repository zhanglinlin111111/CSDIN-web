<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:24
 */



//ajax actions:small feature
//add_action('wp_ajax_nopriv_zendkee_small_feature', 'zendkee_small_feature'); // for not logged in users
add_action('wp_ajax_zendkee_small_feature', 'zendkee_small_feature');
if(!function_exists('zendkee_small_feature')){
    function zendkee_small_feature($par)
    {
        if ($_REQUEST['action'] == 'zendkee_small_feature') {
            $disable_f12 = $_REQUEST['disable_f12'];
            $disable_rightclick = $_REQUEST['disable_rightclick'];
            $disable_lang_cn = $_REQUEST['disable_lang_cn'];
            $disable_search = $_REQUEST['disable_search'];
            $disable_author_page = $_REQUEST['disable_author_page'];


            zendkee_update_option('disable_f12', $disable_f12);
            zendkee_update_option('disable_rightclick', $disable_rightclick);
            zendkee_update_option('disable_lang_cn', $disable_lang_cn);
            zendkee_update_option('disable_search', $disable_search);
            zendkee_update_option('disable_author_page', $disable_author_page);

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
