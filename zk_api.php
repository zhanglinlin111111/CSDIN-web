<?php
define('API_VERSION', '1.3');

$salt = 'ZENDKEEZENDKEE'; //这个salt，用于实际执行权限更改的shell脚本中，校验数据用

$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : "";
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

/**
 * Only Check Version
 */
if ($action == 'check_version') { //检查版本
    echo json_encode(array('status' => 'ok', 'version' => API_VERSION));
    exit;
}






function generate_verify_code($salt)
{
    return md5($salt . date("Y-m-d"));
}


//加解密
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    $ckey_length = 4;
    $key = md5($key);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = [];
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}




$CA_URL = "https://download.ehaitech.com/auth/token.php?action=verify&token=" . $token;
$auth_token = file_get_contents($CA_URL);


if ($token != '' && $auth_token == md5($token)) {

    $base_path = defined('ABSPATH') ? ABSPATH . 'wp-content/uploads/' : __DIR__ . '/wp-content/uploads/';
    $file_name = 'plugin_update_controller.txt';


    if ($action == 'allow_update') { //允许更新
        if (file_put_contents($base_path . $file_name, generate_verify_code($salt))) {
            echo json_encode(array('status' => 'ok', 'info' => 'Allow Update', 'version' => API_VERSION));
        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'File Not Writable', 'version' => API_VERSION));
        }
    } elseif ($action == 'disallow_update') { //禁止更新
        if (file_put_contents($base_path . $file_name, "Disallow Update")) {
            echo json_encode(array('status' => 'ok', 'info' => 'Disallow Update', 'version' => API_VERSION));
        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'File Not Writable', 'version' => API_VERSION));
        }
    } elseif ($action == 'check_permission') { //检查权限
        $theme_path_permission = false;
        $plugin_path_permission = false;
        $language_path_permission = false;
        $upgrade_path_permission = false;

        if (is_writable(dirname($base_path) . "/themes/")) {
            $theme_path_permission = true;
        }
        if (is_writable(dirname($base_path) . "/plugins/")) {
            $plugin_path_permission = true;
        }
        if (is_writable(dirname($base_path) . "/languages/")) {
            $language_path_permission = true;
        }
        if (is_writable(dirname($base_path) . "/upgrade/")) {
            $upgrade_path_permission = true;
        }


        if ($theme_path_permission && $plugin_path_permission && $language_path_permission && $upgrade_path_permission) {
            echo json_encode(array('status' => 'ok', 'info' => 'Allow Update', 'version' => API_VERSION));
        } elseif (!$plugin_path_permission && !$language_path_permission && !$upgrade_path_permission) {
            echo json_encode(array('status' => 'ok', 'info' => 'Disallow Update', 'version' => API_VERSION));
        } else {
            echo json_encode(array('status' => 'ok', 'info' => 'Permission Unknown', 'version' => API_VERSION));
        }
    } elseif ($action == 'update_password') { //更新密码
        if (file_exists(__DIR__ . '/wp-load.php')) {
            require_once(__DIR__ . '/wp-load.php');
            $user = $_POST['user'];

            $user_data = get_user_by('login', $user);

            if (!empty($user_data) and property_exists($user_data, 'data')) {
                $user_id = $user_data->data->ID;
                $new_password = wp_generate_password(24);

                wp_set_password($new_password, $user_id);

                echo json_encode(array('status' => 'ok', 'info' => 'Password Updated.', 'new_password' => authcode($new_password, 'ENCODE', $token), 'version' => API_VERSION, 'pw' => $new_password));
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'User Not Exists.', 'version' => API_VERSION));
            }
        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'ERROR. Code 404', 'version' => API_VERSION));
        }
    } elseif ($action == 'login') { //使用API登录
        if (file_exists(__DIR__ . '/wp-load.php')) {
            require_once(__DIR__ . '/wp-load.php');

            $account = $_REQUEST['account'];
            $password = authcode(base64_decode($_REQUEST['password']), 'DECODE', $token);

            if ($password) {
                $user = wp_authenticate($account, $password);

                // Redirect URL //
                if (!is_wp_error($user)) {
                    wp_clear_auth_cookie();
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);
                    $redirect_to = admin_url();
                    // var_dump($redirect_to);
                    // exit;
                    wp_safe_redirect($redirect_to);
                    exit();
                } else {
                    echo json_encode(array('status' => 'fail', 'info' => 'User or Password Incorrect', 'version' => API_VERSION));
                }
            } else {
                echo json_encode(array('status' => 'fail', 'info' => 'Request Timeout', 'version' => API_VERSION));
            }
        } else {
            echo json_encode(array('status' => 'fail', 'info' => 'Error. Code 414', 'version' => API_VERSION));
        }
    } else {
        echo json_encode(array('status' => 'fail', 'info' => 'Action Incorrect', 'version' => API_VERSION));
    }
} else {
    echo json_encode(array('status' => 'fail', 'info' => 'Token Incorrect', 'version' => API_VERSION));
}