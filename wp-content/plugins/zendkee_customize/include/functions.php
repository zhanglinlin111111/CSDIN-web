<?php

/**
 * Created by PhpStorm.
 * User: Zendkee
 * Date: 2020/10/23 0023
 * Time: 10:03
 */


//调试输出
if (!function_exists('dp')) {
    function dp($s)
    {
        echo '<pre>';
        var_dump($s);
        echo '</pre><br>';
    }
}

//配置类
if (!class_exists('ZENDKEE_OPTION')) {

    class ZENDKEE_OPTION
    {
        //私有属性，用于保存实例
        private static $instance;
        //存储值的标记
        private static $flag = 'zendkee_customize';
        //用于本实例存储数据值
        private $options;


        //构造方法私有化，防止外部创建实例
        private function __construct()
        {
            $this->options = (array)get_option(self::$flag);
        }

        //公有方法，用于获取实例
        public static function getInstance()
        {
            //判断实例有无创建，没有的话创建实例并返回，有的话直接返回
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        //克隆方法私有化，防止复制实例
        private function __clone()
        {
        }



        public function get($name)
        {
            if (!empty($this->options)) {
                $this->options = (array)$this->options;
                return isset($this->options[$name]) ? $this->options[$name] : false;
            }

            return false;
        }

        public function update($name, $value)
        {
            if ($this->options == false) {
                $this->options = array();
            }

            $this->options = array_merge($this->options, array($name => $value));

            return true;
        }

        public function reset()
        {
            $this->options = array();
            return true;
        }

        public function save()
        {
            return update_option(self::$flag, $this->options);
        }


        public function remove($name)
        {
            if (!empty($this->options)) {
                $this->options = (array)$this->options;
                if (isset($this->options[$name])) {
                    $kv = array($name => $this->options[$name]);
                    unset($this->options[$name]);
                    return $kv;
                }
            }

            return false;
        }
    }
}


//获得配置
if (!function_exists('zendkee_get_option')) {
    function zendkee_get_option($name)
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->get($name);
    }
}


//更新配置
if (!function_exists('zendkee_update_option')) {
    function zendkee_update_option($name, $value)
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->update($name, $value);
    }
}


//重置配置
if (!function_exists('zendkee_reset_option')) {
    function zendkee_reset_option()
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->reset();
    }
}


//保存配置
if (!function_exists('zendkee_save_option')) {
    function zendkee_save_option()
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->save();
    }
}


//删除配置
if (!function_exists('zendkee_remove_option')) {
    function zendkee_remove_option($name)
    {
        $zendkee_option = ZENDKEE_OPTION::getInstance();
        return $zendkee_option->remove($name);
    }
}


//检查键值是否存在
if (!function_exists('zendkee_is_set')) {
    function zendkee_is_set($haystack, $needle)
    {
        if (is_string($haystack)) {
            if ($haystack == $needle) {
                return true;
            } else {
                return false;
            }
        } elseif (is_array($haystack)) {
            return in_array($needle, $haystack);
        }
    }
}


//获得所有wordpress菜单
if (!function_exists('zendkee_get_all_wordpress_menus')) {
    function zendkee_get_all_wordpress_menus($hide_empty = true)
    {
        $hide_empty = $hide_empty === true ? true : false;
        return get_terms('nav_menu', array('hide_empty' => $hide_empty));
    }
}


//检测是否中文浏览器
if (!function_exists('zendkee_is_chinese_browser')) {
    function zendkee_is_chinese_browser()
    {
        return preg_match("~zh-CN|zh~i", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }
}


//判断是否前台
if (!function_exists('zendkee_frontend')) {
    function zendkee_frontend()
    {
        //不是后台、用户没登录、不是登录页
        if (!is_admin() && !is_user_logged_in() && !preg_match("~^/wp-login\.php~", $_SERVER['REQUEST_URI'])) {
            return true;
        } else {
            return false;
        }
    }
}


//返回值转义字符（将特殊字符转换为 HTML 实体）
if (!function_exists('zk_r')) {
    function zk_r($value)
    {
        return htmlspecialchars($value);
    }
}

if (!function_exists('zk_e')) {
    //输出值转义字符（将特殊字符转换为 HTML 实体）
    function zk_e($value)
    {
        echo htmlspecialchars($value);
    }
}



//fix wp < v5.3 function miss
if (!function_exists('wp_get_registered_image_subsizes')) {
    function wp_get_registered_image_subsizes()
    {
        $additional_sizes = wp_get_additional_image_sizes();
        $all_sizes = array();

        foreach (get_intermediate_image_sizes() as $size_name) {
            $size_data = array(
                'width' => 0,
                'height' => 0,
                'crop' => false,
            );

            if (isset($additional_sizes[$size_name]['width'])) {
                // For sizes added by plugins and themes.
                $size_data['width'] = intval($additional_sizes[$size_name]['width']);
            } else {
                // For default sizes set in options.
                $size_data['width'] = intval(get_option("{$size_name}_size_w"));
            }

            if (isset($additional_sizes[$size_name]['height'])) {
                $size_data['height'] = intval($additional_sizes[$size_name]['height']);
            } else {
                $size_data['height'] = intval(get_option("{$size_name}_size_h"));
            }

            if (empty($size_data['width']) && empty($size_data['height'])) {
                // This size isn't set.
                continue;
            }

            if (isset($additional_sizes[$size_name]['crop'])) {
                $size_data['crop'] = $additional_sizes[$size_name]['crop'];
            } else {
                $size_data['crop'] = get_option("{$size_name}_crop");
            }

            if (!is_array($size_data['crop']) || empty($size_data['crop'])) {
                $size_data['crop'] = (bool)$size_data['crop'];
            }

            $all_sizes[$size_name] = $size_data;
        }

        return $all_sizes;
    }
}



//检查旧版插件数据是否存在
if (!function_exists('zendkee_is_old_version_exists')) {
    function zendkee_is_old_version_exists()
    {
        $old_version_mod_keys = array(
            'product_detail_form_jumpto',
            'product_detail_form_priority',
            'use_inner_js_css',
            'scroll_to_top',
            'floading_navigation',
            'floading_navigation_depth',
            'floading_navigation_html_before',
            'floading_navigation_css',
            'floading_navigation_js',
        );

        $old_version_option_keys = array(
            'product_detail_form',
            'right_side_form',
            'google_ga_code',
            'google_header_gtm_code',
            'google_footer_gtm_code',
            'enable_frontend_global_js',
            'enable_frontend_global_css',
            'frontend_global_css',
            'frontend_global_js',
            'enable_backend_global_js',
            'enable_backend_global_css',
            'backend_global_css',
            'backend_global_js',
        );
        //    global $old_version_mod_keys, $old_version_option_keys;
        $all_options = wp_load_alloptions();

        if (array_intersect(array_values($old_version_mod_keys), array_keys(get_theme_mods()))) {
            return true;
        }

        if (array_intersect(array_values($old_version_option_keys), array_keys($all_options))) {
            return true;
        }

        return false;
    }
}




//导入旧版插件数据
if (!function_exists('zendkee_import_old_version_data')) {
    function zendkee_import_old_version_data()
    {

        zendkee_update_option('misc_ga', get_option('google_ga_code'));
        zendkee_update_option('misc_gtm_header', get_option('google_header_gtm_code'));
        zendkee_update_option('misc_gtm_footer', get_option('google_footer_gtm_code'));

        zendkee_update_option('product_detail_contact_form', get_option('product_detail_form'));
        zendkee_update_option('product_detail_form_priority', get_theme_mod('product_detail_form_priority'));
        zendkee_update_option('jumpto', get_theme_mod('product_detail_form_jumpto'));
        zendkee_update_option('jumpto_text', 'Get A Quote');
        zendkee_update_option('float_contact_form', get_option('right_side_form'));
        zendkee_update_option('scroll_top', get_theme_mod('scroll_to_top'));

        zendkee_update_option('enable_frontend_js', get_option('enable_frontend_global_js'));
        zendkee_update_option('frontend_js', get_option('frontend_global_js'));
        zendkee_update_option('enable_backend_js', get_option('enable_backend_global_js'));
        zendkee_update_option('backend_js', get_option('backend_global_js'));
        zendkee_update_option('enable_frontend_css', get_option('enable_frontend_global_css'));
        zendkee_update_option('frontend_css', get_option('frontend_global_css'));
        zendkee_update_option('enable_backend_css', get_option('enable_backend_global_css'));
        zendkee_update_option('backend_css', get_option('backend_global_css'));

        return zendkee_save_option();
    }
}


//删除旧版插件数据
if (!function_exists('zendkee_remove_old_version_data')) {
    function zendkee_remove_old_version_data()
    {
        delete_option('google_ga_code');
        delete_option('google_header_gtm_code');
        delete_option('google_footer_gtm_code');

        delete_option('product_detail_form');
        remove_theme_mod('product_detail_form_priority');
        remove_theme_mod('product_detail_form_jumpto');

        delete_option('right_side_form');
        remove_theme_mod('use_inner_js_css');
        remove_theme_mod('scroll_to_top');

        remove_theme_mod('floading_navigation');
        remove_theme_mod('floading_navigation_depth');
        remove_theme_mod('floading_navigation_html_before');
        remove_theme_mod('floading_navigation_css');
        remove_theme_mod('floading_navigation_js');

        delete_option('enable_frontend_global_js');
        delete_option('frontend_global_js');
        delete_option('enable_backend_global_js');
        delete_option('backend_global_js');
        delete_option('enable_frontend_global_css');
        delete_option('frontend_global_css');
        delete_option('enable_backend_global_css');
        delete_option('backend_global_css');

        return true;
    }
}





//获取激活的插件
if (!function_exists('zendkee_get_active_plugins')) {
    function zendkee_get_active_plugins()
    {
        $the_plugs = get_option('active_plugins');
        $plugin_slugs = array();
        foreach ($the_plugs as $key => $value) {
            $string = explode('/', $value); // Folder name will be displayed
            $plugin_slugs[] = $string[0];
        }
        return $plugin_slugs;
    }
}


//检查缓存插件是否存在
if (!function_exists('maybe_cache_plugin_install')) {
    function maybe_cache_plugin_install()
    {
        $active_plugin_slugs = zendkee_get_active_plugins();
        foreach ($active_plugin_slugs as $plugin_slug) {
            if (strpos($plugin_slug, 'cache') !== false && !(strpos($plugin_slug, 'redis') !== false || strpos($plugin_slug, 'memcache') !== false)) {
                return true;
            }
        }
        return false;
    }
}



//文本区域内容列表转成ip列表
if (!function_exists('textarea_list_to_ip_list')) {
    function textarea_list_to_ip_list($haystack_ips)
    {
        $ips = array();
        $explodes = explode("\n", $haystack_ips);

        foreach ($explodes as $ip) {
            $ip = trim($ip);
            if (rest_is_ip_address($ip)) {
                $ips[] = $ip;
            }
        }
        return $ips;
    }
}





/************************** URL Replace Functions Start ***************************/

if (!function_exists('encode_chinese')) {
    function encode_chinese($string)
    {
        return strtolower(urlencode($string));
    }
}




//判断字符串是否JSON格式{
if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}



if (!function_exists('digui_maybe_unserialize')) {
    function digui_maybe_unserialize($string, $tries = 0)
    {
        $unserialize_string = maybe_unserialize($string);
        ++$tries;
        if ($string == $unserialize_string) {
            return array('return' => $string, 'tries' => --$tries);
        } else {
            return digui_maybe_unserialize($unserialize_string, $tries);
        }
    }
}

if (!function_exists('digui_maybe_serialize')) {
    function digui_maybe_serialize($string, $tries)
    {
        if ($tries > 0) {
            $string = maybe_serialize($string);
            if (--$tries > 0) {
                return digui_maybe_serialize($string, $tries);
            } else {
                return $string;
            }
        }
        return $string;
    }
}


if (!function_exists('digui_replace')) {
    function digui_replace($string, $search, $replace)
    {
        if (is_array($string)) {
            foreach ($string as &$item) {
                $item = digui_replace($item, $search, $replace);
            }
            return $string;
        } elseif (is_string($string)) {

            if (is_array($replace)) { //multi to multi
                foreach ((array)$search as $key => $one_search) {
                    $string = str_replace($one_search, $replace[$key], $string);
                }
            } else {
                foreach ((array)$search as $key => $one_search) {
                    $string = str_replace($one_search, $replace, $string);
                }
            }

            return $string;
        } else {
            return $string;
        }
    }
}




/**
 * Domain Name Changer
 */
if (!class_exists('DomainNameChanger')) {
    class DomainNameChanger
    {
        protected $mysqli;
        protected $change_from = array();
        protected $change_to = array();
        protected $host, $user, $pw, $db, $charset = null;
        protected $tables = array();
        protected $one_row;
        protected $replace_sql;
        protected $ok = 0;
        protected $count = 0;
        protected $min_print = 1;
        protected $max_print = -1;
        protected $time_start = 0;
        protected $time_end = 0;
        protected $total_query_time = 0; //数据查询时间
        protected $total_update_time = 0; //数据更新时间
        protected $total_replace_time = 0; //数据替换时间
        protected $debug = false;

        protected $get_row_per_query = 10000;


        public function __construct($config)
        {
            $this->change_from = $config['change_from'];
            $this->change_to = $config['change_to'];
            $this->host = $config['host'];
            $this->user = $config['user'];
            $this->pw = $config['pw'];
            $this->db = $config['db'];
            $this->charset = $config['charset'];
            $this->debug = $config['debug'];

            $this->time_start = microtime(true);

            $this->connect_to_mysql();
        }

        public function connect_to_mysql()
        {
            try {
                $this->mysqli = new mysqli($this->host, $this->user, $this->pw, $this->db);
                $this->mysqli->set_charset($this->charset);
            } catch (\Exception $e) {
                echo "Can't Connect To MYSQL , Error Message:" . $e;
            }
        }

        protected function get_all_table()
        {
            //get all tables
            $sql = sprintf("Show tables;");
            $result = $this->mysqli->query($sql);

            //all tables in DB
            while ($row = $result->fetch_assoc()) {
                $this->tables[$row['Tables_in_' . $this->db]] = '';
            }

            if ($this->debug) {
                var_dump($this->tables);
            }
        }

        protected function contruct_tables()
        {
            if ($this->tables) {
                //contruct table structure
                foreach ($this->tables as $table_name => $table_cols_name_type) {
                    $desc_table_sql = sprintf("DESC `%s`;", $table_name);
                    $result = $this->mysqli->query($desc_table_sql);
                    $cols = array();
                    while ($row = $result->fetch_assoc()) {
                        //if this field type is string, mark it 1 ,eles mark it 0
                        $cols[$row['Field']] = (preg_match("/char|blob|text|enum/i", $row['Type']) ? 1 : 0);
                    }
                    $this->tables[$table_name] = $cols;
                }
            }
            if ($this->debug) {
                var_dump($this->tables);
            }
        }



        //这个函数做的判断，也许可以改进。
        //现在所有的列，无论什么形式的字段都做判断
        protected function is_match_string($table_name)
        {
            /*
            $this->one_row是数据库的二维表格，格式：
            array(6) {
              ["theme_id"]=>
              string(1) "1"
              ["title"]=>
              string(13) "Default Theme"
              ["slug"]=>
              string(13) "default-theme"
              ["theme_settings"]=>
              string(4220) "a:2:{s:7:"general";a:2:{s:11:"line_height";s:3:"1.5";}}
              ["created"]=>
              string(19) "2018-07-18 08:07:28"
              ["modified"]=>
              string(19) "2018-07-18 08:07:28"
            }
            */
            if ($this->one_row) {
                foreach ($this->change_from as $search_string) {
                    foreach ($this->one_row as $key => $value) {

                        if ($this->tables[$table_name][$key]) {
                            //经过json格式化之后，普通的字符串，前后会加入双引号，所以要使用trim去除。
                            if (isJson($value) && stripos($value, trim(json_encode($search_string), '"')) !== false) {
                                return true;
                            } elseif (stripos($value, $search_string) !== false) {
                                return true;
                            }
                        }
                    }
                }
                return false;
            }
        }


        public function serialized_string_replace($matches)
        {
            $str = $matches[2];
            if (is_array($this->change_to)) { //multi to multi
                foreach ($this->change_from as $key => $value) {
                    if (strpos($str, $value) !== false) { //替换域名长路径
                        $str = str_replace($value, $this->change_to[$key], $str);
                    } else { //其他不用替换
                    }
                }
            } else { //multi to single
                foreach ($this->change_from as $key => $value) {
                    if (strpos($str, $value) !== false) { //替换域名长路径
                        $str = str_replace($value, $this->change_to, $str);
                    } else { //其他不用替换
                    }
                }
            }
            return sprintf("s:%s:\"%s\";", strlen($str), $str);
        }

        function json_string_replace($string)
        {

            if (is_array($this->change_to)) { //multi to multi
                foreach ($this->change_from as $key => $value) {
                    //经过json格式化之后，普通的字符串，前后会加入双引号，所以要使用trim去除。

                    if (strpos($string, $value) !== false) {
                        $string = str_replace($value, $this->change_to[$key], $string);
                    } else {
                        $string = str_replace(trim(json_encode($value), '"'), trim(json_encode($this->change_to[$key]), '"'), $string);
                    }
                }
            } else { //multi to single
                foreach ($this->change_from as $key => $value) {
                    //经过json格式化之后，普通的字符串，前后会加入双引号，所以要使用trim去除。
                    if (strpos($string, $value) !== false) {
                        $string = str_replace($value, $this->change_to, $string);
                    } else {
                        $string = str_replace(trim(json_encode($value), '"'), trim(json_encode($this->change_to), '"'), $string);
                    }
                }
            }
            return $string;
        }


        function normal_string_replace($string)
        {
            if (is_array($this->change_to)) { //multi to multi
                foreach ($this->change_from as $key => $value) {
                    $string = str_replace($value, $this->change_to[$key], $string);
                }
            } else { //multi to single
                foreach ($this->change_from as $key => $value) {
                    $string = str_replace($value, $this->change_to, $string);
                }
            }

            return $string;
        }


        //替换字符串
        protected function get_replace_string_sql($table_name)
        {
            // $replace_sql = '';
            $set_sql = array();
            $where_sql = array();
            foreach ($this->one_row as $key => $value) {
                if (is_string($value) and is_serialized($value)) {

                    $unserialize_return = digui_maybe_unserialize($value);
                    $new_value = digui_replace($unserialize_return['return'], $this->change_from, $this->change_to);

                    $new_value = digui_maybe_serialize($new_value, $unserialize_return['tries']);

                    if ($new_value != $value) {
                        $set_sql[] = sprintf("`%s`='%s'", $key, $this->mysqli->real_escape_string($new_value));
                    }
                } elseif (is_string($value) and isJson($value)) {

                    $new_value = $this->json_string_replace($value);
                    if ($new_value != $value) {
                        $set_sql[] = sprintf("`%s`='%s'", $key, $this->mysqli->real_escape_string($new_value));
                    }
                } elseif (is_string($value)) {
                    $new_value = $this->normal_string_replace($value);
                    if ($new_value != $value) {
                        $set_sql[] = sprintf("`%s`='%s'", $key, $this->mysqli->real_escape_string($new_value));
                    }
                }


                if ($value === null) {
                    $where_sql[] = sprintf("`%s` is null ", $key, $key);
                } else {
                    $where_sql[] = sprintf("`%s`='%s'", $key, $this->mysqli->real_escape_string($value));
                }
            }
            if (sizeof($set_sql) >= 1) {
                $this->replace_sql = sprintf("UPDATE `%s` SET %s WHERE %s LIMIT 1;", $table_name, implode(' , ', $set_sql), implode(' and ', $where_sql));
            } else {
                $this->replace_sql = '';
            }
        }


        //do change domain name
        public function change_domain_name()
        {
            // $tb = microtime(1);

            if ($this->tables) {
                //find and replace contents in each table cols
                foreach ($this->tables as $table_name => $table_cols_name_type) {
                    if (sizeof(array_filter($table_cols_name_type)) >= 1) {

                        $each_table_query_run = 0;

                        // $foreach_b = microtime(1);

                        $query_time_start = microtime(1);
                        $select_total_sql = sprintf("SELECT COUNT(*) AS total FROM `%s`;", $table_name);
                        $result_total = $this->mysqli->query($select_total_sql);
                        $row = $result_total->fetch_assoc();
                        $total = $row['total'];

                        $query_time_end = microtime(1);

                        $each_table_query_run += ($query_time_end - $query_time_start);

                        $page = 1;
                        // $while_b = microtime(1);

                        while (1) {

                            $offset = $this->get_row_per_query * ($page - 1);

                            $query_time_start = microtime(1);
                            $select_all_col_sql = sprintf("SELECT `%s` FROM `%s` LIMIT %d,%d;", implode('`,`', array_keys($table_cols_name_type)), $table_name, $offset, $this->get_row_per_query);

                            $result = $this->mysqli->query($select_all_col_sql);
                            $current_get = $result->num_rows;

                            $query_time_end = microtime(1);

                            $each_table_query_run += ($query_time_end - $query_time_start);

                            while ($this->one_row = $result->fetch_assoc()) {
                                if ($this->is_match_string($table_name)) {

                                    $replace_time_start = microtime(1);
                                    $this->get_replace_string_sql($table_name);
                                    $replace_time_end = microtime(1);
                                    $this->total_replace_time += $replace_time_end - $replace_time_start;
                                    if ($this->replace_sql) {

                                        $update_time_start = microtime(1);
                                        $update_result = $this->mysqli->query($this->replace_sql);
                                        $update_time_end = microtime(1);
                                        $this->total_update_time += $update_time_end - $update_time_start;

                                        if ($update_result && $this->mysqli->affected_rows > 0) {
                                            $this->ok++;
                                        }
                                    }


                                    //输出一部分，用于调试
                                    $this->count++;
                                    if (($this->count >= $this->min_print && $this->count <= $this->max_print) || $this->max_print === -1) {
                                        continue;
                                    } else {
                                        break 2;
                                    }
                                }
                            }

                            if ($total <= (($page - 1) * $this->get_row_per_query + $current_get)) {
                                break;
                            }

                            $page++;

                        }
                        // $while_a = microtime(1);
                        // $foreach_a = microtime(1);



                        // printf("Each Table Query Run:%.8f\n", $each_table_query_run);

                        // printf("Each Table While Run:%.8f\n", ($while_a-$while_b));

                        // printf("Each Table Foreach Run:%.8f\n", ($foreach_a - $foreach_b));

                        // printf("Each Table Memory Usage:%.2fMB\n", (memory_get_peak_usage()/1024/1024));

                        $this->total_query_time += $each_table_query_run;
                    }
                }

                // printf("Total SQL Run:%.8f\n", $total_table_query_run);
            }
            // $ta = microtime(1);
            // printf("Total PHP Run:%.8f\n", ($ta-$tb));


        }

        public function do_it()
        {
            $this->get_all_table();
            $this->contruct_tables();
            $this->change_domain_name();
            return $this->get_status();
        }


        public function print_status()
        {
            $this->time_end = microtime(true);
            printf("Total:< %s > , Replace: < %s > , Time Use: < %s >\n", $this->count, $this->ok, ($this->time_end - $this->time_start));
        }

        public function get_status()
        {
            $this->time_end = microtime(true);
            return array(
                'total' => $this->count,
                'replace' => $this->ok,
                'query_time' => $this->total_query_time,
                'replace_time' => $this->total_replace_time,
                'update_time' => $this->total_update_time,
                'time_used' => ($this->time_end - $this->time_start),
            );
        }
    }
}

/************************** URL Replace Functions End ***************************/




//根据url生成alt文本
if (!function_exists('get_image_alt_by_url')) {
    function get_image_alt_by_url($url)
    {
        /*
         * version:1.1
         *
         * ALT的属性使用图片名称替换，方案如下：
         * 抓取图片url最后一段的文件名作为alt基础属性，然后
         * 去掉结尾所有的数字，含数字前的中横线-
         * 去掉 _副本、微信图片_、微信截图_、QQ截图_、QQ图片 的名称
         * 下划线_、中横线- 替换成空格
         * 大写转小写
         * 去除两边的空格
         * */
        //截取url最后一段，作为图片alt属性
        //\-?\d*?
        if (preg_match("~/?([\x{4e00}-\x{9fa5}A-Za-z0-9_\-\.]+?)\.(?:jpe?g|gif|png|bmp)$~iu", $url, $matches)) {
            $alt = $matches[1];
        }
        $alt = preg_replace(
            array(
                "/_副本$/",
                "/微信图片_/",
                "/微信截图_/",
                "/QQ截图_/i",
                "/QQ图片_/i",
                "/-\d+x\d+$/i",
                "/-scaled/"
            ),
            '',
            $alt
        );

        $alt = str_replace(array(
            '_', '-',
        ), array(
            ' ', ' ',
        ), $alt);

        $alt = strtolower($alt);
        $alt = trim($alt);

        return $alt;
    }
}

/**兼容：解决高版本CF7不兼容低版本WP问题 */
if (
    !function_exists('str_starts_with')
) {
    /**
     * Polyfill for `str_starts_with()` function added in PHP 8.0.
     *
     * Performs a case-sensitive check indicating if
     * the haystack begins with needle.
     *
     * @since 5.9.0
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The substring to search for in the `$haystack`.
     * @return bool True if `$haystack` starts with `$needle`, otherwise false.
     */
    function str_starts_with($haystack, $needle)
    {
        if ('' === $needle) {
            return true;
        }
        return 0 === strpos($haystack, $needle);
    }
}
