<?php

/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:29
 */



//ajax actions:online
add_action('wp_ajax_zendkee_online', 'zendkee_online');
if (!function_exists('zendkee_online')) {
    function zendkee_online($par)
    {
        if ($_REQUEST['action'] == 'zendkee_online') {

            $replace_url = $_REQUEST['replace_url']; //这个值不需要保存
            $old_url = $_REQUEST['old_url']; //这个值不需要保存
            $new_url = $_REQUEST['new_url']; //这个值不需要保存

            $status = array('replace' => 0);
            if ($replace_url and $old_url and $new_url) {

                $config = array(
                    'change_from' => array(
                        $old_url,
                    ),
                    'change_to' => array(
                        $new_url,
                    ),
                    'host' => DB_HOST,
                    'user' => DB_USER,
                    'pw' => DB_PASSWORD,
                    'db' => DB_NAME,
                    'charset' => DB_CHARSET,
                    'debug' => false,
                );

                // $memory_start_usage = memory_get_usage();
                $domain_name_changer = new DomainNameChanger($config);
                $status = $domain_name_changer->do_it();
                // $memory_end_usage = memory_get_usage();

                // $memory_usage = sprintf("%.3f MB", ($memory_end_usage - $memory_start_usage) / 1024 / 1024);
                $memory_max_usage = sprintf("%.3f MB", memory_get_peak_usage() / 1024 / 1024);
            }


            if ($status['replace'] > 0) {
                echo json_encode(array(
                    'status' => 'ok', 
                    'info' => sprintf(
                        "URL替换完毕！<br>
                        总数：%s<br>
                        替换数：%s<br>
                        SQL查询时间: %.2f<br>
                        数据替换时间: %.2f<br>
                        SQL更新时间: %.2f<br>
                        总用时：%.2f<br>
                        内存峰值使用量：%s<br>", 
                        $status['total'], 
                        $status['replace'], 
                        $status['query_time'], 
                        $status['replace_time'], 
                        $status['update_time'], 
                        $status['time_used'], 
                        $memory_max_usage
                )));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => sprintf("Nothing To Do.用时：%.2f<br>内存峰值使用量：%s<br>", $status['time_used'], $memory_max_usage)));
                // echo json_encode(array('status' => 'fail', 'info' => 'Nothing To Do'));
            }
        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Incorrect parameter'));
        }
        wp_die();
    }
}
