<?php
/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/11/11 0011
 * Time: 17:21
 */


//ajax actions:misc
add_action('wp_ajax_zendkee_database', 'zendkee_database');
if(!function_exists('zendkee_database')){
    function zendkee_database($par)
    {
        if ($_REQUEST['action'] == 'zendkee_database') {


            $success = 0;
            $clean_total = 0;
            if($_REQUEST['clean_revision']){
                global $wpdb;

                $reserve_revisions = (int)$_REQUEST['reserve_revisions'] >= 0 ? (int)$_REQUEST['reserve_revisions'] : 20;


                $get_all_publish_posts = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type != 'revision' ORDER BY ID;", ARRAY_A);
                foreach ($get_all_publish_posts as $post_arr) {
                    $old_revisions = $wpdb->get_results($wpdb->prepare("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type='revision' AND post_status = 'inherit' AND post_parent = %d ORDER BY post_modified DESC limit %d ,9999999999;", $post_arr['ID'], $reserve_revisions), ARRAY_A);


                    $old_revision_ids = array();
                    foreach ($old_revisions as $old_revisions_arr) {
                        $old_revision_ids[] = $old_revisions_arr['ID'];
                    }

                    if(!empty($old_revision_ids)){
                        $status = $wpdb->query( sprintf("DELETE FROM {$wpdb->prefix}posts WHERE ID IN (%s);" , implode(',',$old_revision_ids)) );

                        if($status){
                            $success++;
                        }

                    }

                    $clean_total += sizeof($old_revision_ids);
                }
            }



            //做完后重置状态
//        zendkee_update_option('clean_revision', 0);
//        zendkee_save_option();


            zendkee_update_option('reserve_revisions', $reserve_revisions);

            if (zendkee_save_option() || $success) {
                echo json_encode(array('status' => 'ok', 'info' => '数据库优化完毕，清理条数：'.$clean_total));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Nothing To Do'));
            }

        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();

    }
}
