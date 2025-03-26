<?php

/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 11:28
 */


//根据IP判断地区，根据地区判断是否展示网站给用户
require_once ZENDKEE_CUSTOM_PATH . 'vendor/autoload.php';

use GeoIp2\Database\Reader;

//for front page
add_action('init', 'show_site', 2);


if (!function_exists('show_site')) {
    function show_site()
    {
        global $status_code_list;
        global $is_ip_in_white_list;
        global $is_ip_in_black_list;



        if (!is_admin() && !is_user_logged_in() && !preg_match("~^/wp-login\.php~", $_SERVER['REQUEST_URI'])) { //not wp-admin, not login user , not login page
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            //        $ip='184.160.24.7';//CA
            //        $ip='173.0.224.70';//USA
            //        $ip='125.192.9.14';//日本JP
            $settings = zendkee_get_option('zendkee_site_view_control_settings');

            $svc_rule = zendkee_get_option('svc_rule');
            $country_status_code = zendkee_get_option('country_status_code');
            $enable_svc_debug_mode = zendkee_get_option('enable_svc_debug_mode');



            $country_code = zendkee_get_country($ip);

            if ($enable_svc_debug_mode) {
                printf('<!-- IP:%s , Country Code: %s -->', $ip, $country_code);
            }

            if (isset($svc_rule) && isset($country_status_code) && !empty($country_status_code)) {
                $country_list = array_keys($country_status_code);
                if ($svc_rule == 'Disallow') {
                    //白名单用户，pass
                    if ($is_ip_in_white_list) {
                        return;
                    }

                    if (is_ip_in_country_list($ip, $country_list) === true && $country_status_code[$country_code] == array_search('Disallow', $status_code_list)) { //these country not allow to access
                        status_header(array_search('Disallow', $status_code_list), '');
                        exit;
                    }
                } elseif ($svc_rule == 'Allow') {
                    //黑名单用户，block
                    if ($is_ip_in_black_list) {
                        status_header(array_search('Disallow', $status_code_list), '');
                        exit;
                    }

                    if (!(is_ip_in_country_list($ip, $country_list) === true && $country_status_code[$country_code] == array_search('Allow', $status_code_list))) { //country not in list , not allow to access
                        status_header(array_search('Disallow', $status_code_list), '');
                        exit;
                    } 
                }
            } else { //not setup,pass
                return;
            }
            //        $country_list = array(
            //            'US',//美国
            //            'CA',//加拿大
            //        );

            //        if (is_ip_in_country_list($ip, $country_list) === true) {//in accept list ,nothing to do
            //        } else {
            //            //not in accept list, do not allow to access
            //            status_header(404, 'Not found');
            //            exit;
            //        }


            //        //just for test
            //        $ips = array(
            //            '124.168.23.6',//澳大利亚AU
            //            '125.192.9.14',//日本JP
            //            '83.112.89.6',//法国FR
            //            '155.161.98.21',//美国US
            //            '173.0.224.70',//美国US
            //            '174.88.29.3',//加拿大CA
            //            '184.160.24.7',//加拿大CA
            //            '123.202.39.1',//香港HK
            //            '80.40.24.7',//英国GB
            //        );
            //
            //        foreach ($ips as $ip){
            //            printf("Country: %s<br>\n" , zendkee_get_country($ip));
            //        }



        }
    }
}


if (!function_exists('zendkee_get_country')) {
    function zendkee_get_country($ip)
    {
        // This creates the Reader object, which should be reused across
        // lookups.

        // Replace "city" with the appropriate method for your database, e.g.,
        // print($record->country->isoCode . "\n"); // 'US'
        // print($record->country->name . "\n"); // 'United States'
        // print($record->country->names['zh-CN'] . "\n"); // '美国'

        // print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
        // print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

        // print($record->city->name . "\n"); // 'Minneapolis'

        // print($record->postal->code . "\n"); // '55455'

        // print($record->location->latitude . "\n"); // 44.9733
        // print($record->location->longitude . "\n"); // -93.2323
        //    try {
        //        $reader = @new Reader( plugin_dir_path(__FILE__ ) .'GeoIP_data/GeoLite2-City.mmdb');
        //        $record = @$reader->city($ip);
        //        return $record->country->isoCode;
        //
        //    } catch (Exception $e) {
        //        return false;
        //    }
        //        echo ZENDKEE_CUSTOM_PATH . 'GeoIP_data/GeoLite2-Country.mmdb';

        try {
            $reader = @new Reader(ZENDKEE_CUSTOM_PATH . 'GeoIP_data/GeoLite2-Country.mmdb');
            $record = @$reader->country($ip);
            return $record->country->isoCode;
        } catch (Exception $e) {
            return false;
        }
    }
}


if (!function_exists('is_ip_in_country_list')) {
    function is_ip_in_country_list($ip, $country_code_list)
    {
        if (false === zendkee_get_country($ip)) {
            return 'ERROR';
        } elseif (in_array(zendkee_get_country($ip), $country_code_list)) {
            return true;
        } else {
            return false;
        }
    }
}
