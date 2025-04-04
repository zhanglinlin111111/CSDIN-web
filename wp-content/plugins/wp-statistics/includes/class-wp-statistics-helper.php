<?php

namespace WP_STATISTICS;

use Exception;
use WP_STATISTICS;
use WP_Statistics_Mail;

class Helper
{
    protected static $admin_notices = [];

    /**
     * WP Statistics WordPress Log
     *
     * @param $function
     * @param $message
     * @param $version
     */
    public static function doing_it_wrong($function, $message, $version = '')
    {
        if (empty($version)) {
            $version = WP_STATISTICS_VERSION;
        }
        $message .= ' Backtrace: ' . wp_debug_backtrace_summary();
        if (is_ajax()) {
            do_action('doing_it_wrong_run', $function, $message, $version);
            error_log("{$function} was called incorrectly. {$message}. This message was added in version {$version}.");
        } else {
            _doing_it_wrong($function, $message, $version); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    /**
     * Returns an array of site id's
     *
     * @return array
     */
    public static function get_wp_sites_list()
    {
        $site_list = array();
        $sites     = get_sites();
        foreach ($sites as $site) {
            $site_list[] = $site->blog_id;
        }
        return $site_list;
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or frontend.
     * @return bool
     */
    public static function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'cron':
                return defined('DOING_CRON');
            case 'wp-cli':
                return defined('WP_CLI') && WP_CLI;
            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON') && !self::is_rest_request();
        }
    }

    /**
     * Returns true if the request is a non-legacy REST API request.
     *
     * @return bool
     */
    public static function is_rest_request()
    {
        if (empty($_SERVER['REQUEST_URI'])) {
            return false;
        }

        $rest_prefix = trailingslashit(rest_get_url_prefix());
        return (false !== strpos($_SERVER['REQUEST_URI'], $rest_prefix)) or isset($_REQUEST['rest_route']);
    }

    /**
     * Check is Login Page
     *
     * @return bool
     */
    public static function is_login_page()
    {
        // Check From global WordPress
        if (isset($GLOBALS['pagenow']) and in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
            return true;
        }

        if (defined('WP_CLI') && WP_CLI) {
            return false;
        }

        // Backward compatibility
        if (empty($_SERVER['SERVER_PROTOCOL']) or empty($_SERVER['HTTP_HOST'])) {
            return false;
        }

        // Check Native php
        $protocol   = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false ? 'http' : 'https';
        $host       = sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']));
        $script     = sanitize_text_field(wp_unslash($_SERVER['SCRIPT_NAME']));
        $currentURL = $protocol . '://' . $host . $script;
        $loginURL   = wp_login_url();

        if ($currentURL == $loginURL) {
            return true;
        }

        return false;
    }

    /**
     * Show Admin WordPress UI Notice
     *
     * @param $text
     * @param string $model
     * @param bool $close_button
     * @param bool $id
     * @param bool $echo
     * @param string $style_extra
     * @return string
     */
    public static function wp_admin_notice($text, $model = "info", $close_button = true, $id = false, $echo = true, $style_extra = 'padding:6px 0')
    {
        $text = '
        <div class="notice notice-' . $model . '' . ($close_button === true ? " is-dismissible" : "") . '"' . ($id != false ? ' id="' . $id . '"' : '') . '>
           <div style="' . $style_extra . '">' . $text . '</div>
        </div>
        ';

        if ($echo) {
            echo wp_kses_post($text);
        } else {
            return $text;
        }
    }

    /**
     * Get Screen ID
     *
     * @return string
     */
    public static function get_screen_id()
    {
        $screen    = get_current_screen();
        $screen_id = $screen ? $screen->id : '';
        return $screen_id;
    }

    /**
     * Get File Path Of Plugins File
     *
     * @param $path
     * @return string
     */
    public static function get_file_path($path)
    {
        return wp_normalize_path(path_join(WP_STATISTICS_DIR, $path));
    }

    /**
     * Check User is Used Cache Plugin
     *
     * @return array
     */
    public static function is_active_cache_plugin()
    {
        $use = array('status' => false, 'plugin' => '');

        /* WordPress core */
        if (defined('WP_CACHE') && WP_CACHE) {
            $use = array('status' => true, 'plugin' => 'core');
        }

        /* WP Rocket */
        if (function_exists('get_rocket_cdn_url')) {
            $use = array('status' => true, 'plugin' => 'WP Rocket');
        }

        /* WP Super Cache */
        if (function_exists('wpsc_init')) {
            $use = array('status' => true, 'plugin' => 'WP Super Cache');
        }

        /* Comet Cache */
        if (function_exists('___wp_php_rv_initialize')) {
            $use = array('status' => true, 'plugin' => 'Comet Cache');
        }

        /* WP Fastest Cache */
        if (class_exists('WpFastestCache')) {
            $use = array('status' => true, 'plugin' => 'WP Fastest Cache');
        }

        /* Cache Enabler */
        if (defined('CE_MIN_WP')) {
            $use = array('status' => true, 'plugin' => 'Cache Enabler');
        }

        /* W3 Total Cache */
        if (defined('W3TC')) {
            $use = array('status' => true, 'plugin' => 'W3 Total Cache');
        }

        return apply_filters('wp_statistics_cache_status', $use);
    }

    /**
     * Get WordPress Uploads DIR
     *
     * @param string $path
     * @return mixed
     * @default For WP Statistics Plugin is 'wp-statistics' dir
     */
    public static function get_uploads_dir($path = '')
    {
        $upload_dir = wp_upload_dir();
        return path_join($upload_dir['basedir'], $path);
    }

    /**
     * Get Robots List
     *
     * @param string $type
     * @return array|bool|string
     */
    public static function get_robots_list($type = 'list')
    {
        # Set Default
        $list = array();

        # Load From file
        include WP_STATISTICS_DIR . "includes/defines/robots-list.php";
        if (isset($wps_robots_list_array)) {
            $list = $wps_robots_list_array;
        }

        return ($type == "array" ? $list : implode("\n", $list));
    }

    /**
     * Get URL Query Parameters List
     *
     * @param string $type
     * @return array|bool|string
     */
    public static function get_query_params_allow_list($type = 'array')
    {
        # Set Default
        $list = [];

        if (Option::get('query_params_allow_list') !== false) {
            # Load from options
            $list = array_map('trim', explode("\n", Option::get('query_params_allow_list')));
        } else {
            # Load the default options
            $list = self::get_default_query_params_allow_list();
        }

        return ($type == "array" ? $list : implode("\n", $list));
    }


    /**
     * Get the default URL Query Parameters List
     * @param string $type
     * @return array|string
     */
    public static function get_default_query_params_allow_list($type = 'array')
    {
        include WP_STATISTICS_DIR . "includes/defines/query-params-allow-list.php";
        $list = isset($wps_query_params_allow_list_array) ? $wps_query_params_allow_list_array : [];
        return ($type == "array" ? $list : implode("\n", $list));
    }

    /**
     * Get Number Days From install this plugin
     * this method used for `ALL` Option in Time Range Pages
     */
    public static function get_date_install_plugin()
    {
        global $wpdb;

        //Create Empty default Option
        $first_day = '';

        //First Check Visitor Table , if not exist Web check Pages Table
        $list_tbl = array(
            'visitor' => array('order_by' => 'ID', 'column' => 'last_counter'),
            'pages'   => array('order_by' => 'page_id', 'column' => 'date'),
        );
        foreach ($list_tbl as $tbl => $val) {
            $first_day = $wpdb->get_var(
                $wpdb->prepare("SELECT %s FROM `". WP_STATISTICS\DB::table($tbl) ."` ORDER BY %s ASC LIMIT 1", $val['column'], $val['order_by'])
            );
            if (!empty($first_day)) {
                break;
            }
        }

        //Calculate hit day if range is exist
        if (empty($first_day)) {
            return false;
        } else {
            return $first_day;
        }
    }

    /**
     * Check User Is Using Gutenberg Editor
     */
    public static function is_gutenberg()
    {
        $current_screen = get_current_screen();
        return ((method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()) || (function_exists('is_gutenberg_page')) && is_gutenberg_page());
    }

    /**
     * Get List WordPress Post Type
     *
     * @return array
     */
    public static function get_list_post_type()
    {
        // Get default post types which are public (exclude media post type)
        $post_types = get_post_types(array('public' => true, '_builtin' => true), 'names', 'and');
        $post_types = array_diff($post_types, ['attachment']);

        // Get custom post types which are public
        $custom_post_types = get_post_types(array('public' => true, '_builtin' => false), 'names', 'and');

        foreach ($custom_post_types as $name) {
            $post_types[] = $name;
        }

        return $post_types;
    }

    public static function get_updated_list_post_type()
    {
        return array_map(function ($postType) {
            return in_array($postType, ['post', 'page', 'product', 'attachment']) ? $postType : 'post_type_' . $postType;
        }, self::get_list_post_type());
    }

    /**
     * Check Url Scheme
     *
     * @param $url
     * @param array $accept
     * @return bool
     */
    public static function check_url_scheme($url, $accept = array('http', 'https'))
    {
        $scheme = @wp_parse_url($url, PHP_URL_SCHEME);
        return in_array($scheme, $accept);
    }

    /**
     * Get WordPress Version
     *
     * @return mixed|string
     */
    public static function get_wordpress_version()
    {
        return get_bloginfo('version');
    }

    /**
     * Convert Json To Array
     *
     * @param $json
     * @return bool|mixed
     */
    public static function json_to_array($json)
    {

        // Sanitize Slash Data
        $data = wp_unslash($json);

        // Check Validate Json Data
        if (!empty($data) && is_string($data) && is_array(json_decode($data, true)) && json_last_error() == 0) {
            return json_decode($data, true);
        }

        return false;
    }

    /**
     * Standard Json Encode
     *
     * @param $array
     * @return false|string
     */
    public static function standard_json_encode($array)
    {

        //Fixed entity decode Html
        foreach ((array)$array as $key => $value) {
            if (!is_scalar($value)) {
                continue;
            }
            $array[$key] = html_entity_decode((string)$value, ENT_QUOTES, 'UTF-8');
        }

        return wp_json_encode($array, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Show Site Icon by Url
     *
     * @param $url
     * @param int $size
     * @param string $style
     * @return bool|string
     */
    public static function show_site_icon($url, $size = 16, $style = '')
    {
        $url = preg_replace('/^https?:\/\//', '', $url);
        if ($url != "") {
            $img_url = "https://www.google.com/s2/favicons?domain=" . $url;
            return '<img src="' . $img_url . '" width="' . $size . '" height="' . $size . '" style="' . ($style == "" ? 'vertical-align: -3px;' : '') . '" />';
        }

        return false;
    }

    /**
     * Get Domain name from url
     * e.g : https://wp-statistics.com/add-ons/ -> wp-statistics.com
     *
     * @param $url
     * @return mixed
     */
    public static function get_domain_name($url)
    {
        //Remove protocol
        $url = preg_replace("(^https?://)", "", trim($url));
        //remove w(3)
        $url = preg_replace('#^(http(s)?://)?w{3}\.#', '$1', $url);
        //remove all Query
        $url = explode("/", $url);

        return $url[0];
    }

    /**
     * Get Site title By Url
     *
     * @param $url string e.g : wp-statistics.com
     * @return bool|string
     */
    public static function get_site_title_by_url($url)
    {

        //Get Body Page
        $html = Helper::get_html_page($url);
        if ($html === false) {
            return false;
        }

        //Get Page Title
        if (class_exists('DOMDocument')) {
            $dom = new \DOMDocument;
            @$dom->loadHTML($html);
            $title = '';
            if (isset($dom) and $dom->getElementsByTagName('title')->length > 0) {
                $title = $dom->getElementsByTagName('title')->item('0')->nodeValue;
            }
            return (wp_strip_all_tags($title) == "" ? false : wp_strip_all_tags($title));
        }

        return false;
    }

    /**
     * Get Html Body Page By Url
     *
     * @param $url string e.g : wp-statistics.com
     * @return bool
     */
    public static function get_html_page($url)
    {

        //sanitize Url
        $parse_url = wp_parse_url($url);
        $urls[]    = esc_url_raw($url);

        //Check Protocol Url
        if (!array_key_exists('scheme', $parse_url)) {
            $urls      = array();
            $url_parse = wp_parse_url($url);
            foreach (array('http://', 'https://') as $scheme) {
                $urls[] = preg_replace('/([^:])(\/{2,})/', '$1/', $scheme . path_join((isset($url_parse['host']) ? $url_parse['host'] : ''), (isset($url_parse['path']) ? $url_parse['path'] : '')));
            }
        }

        //Send Request for Get Page Html
        foreach ($urls as $page) {
            $response = wp_remote_get($page, array(
                'timeout'    => 30,
                'user-agent' => "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36"
            ));
            if (is_wp_error($response)) {
                continue;
            }
            $data = wp_remote_retrieve_body($response);
            if (is_wp_error($data)) {
                continue;
            }
            return (wp_strip_all_tags($data) == "" ? false : $data);
        }

        return false;
    }

    /**
     * Generate Random String
     *
     * @param $num
     * @return string
     */
    public static function random_string($num = 50)
    {
        $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $num; $i++) {
            $randomString .= $characters[wp_rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    /**
     * Get Post List From custom Post Type
     *
     * @param array $args
     * @area utility
     * @return mixed
     */
    public static function get_post_list($args = array())
    {

        //Prepare Arg
        $defaults = array(
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => '-1',
            'order'          => 'ASC',
            'fields'         => 'ids'
        );
        $args     = wp_parse_args($args, $defaults);

        //Get Post List
        $query = new \WP_Query($args);
        $list  = array();
        foreach ($query->posts as $ID) {
            $list[$ID] = esc_html(get_the_title($ID));
        }

        return $list;
    }

    /**
     * Check WordPress Post is Published
     *
     * @param $ID
     * @return bool
     */
    public static function IsPostPublished($ID)
    {
        return get_post_status($ID) == 'public';
    }

    /**
     * Generate RGBA colors
     *
     * @param        $num
     * @param string $opacity
     * @param bool $quote
     * @return string
     */
    public static function GenerateRgbaColor($num, $opacity = '1', $quote = true)
    {
        $hash   = md5('color' . $num);
        $rgba   = "rgba(%s, %s, %s, %s)";
        $format = ($quote === true ? "'$rgba'" : $rgba);

        return sprintf($format,
            hexdec(substr($hash, 0, 2)),
            hexdec(substr($hash, 2, 2)),
            hexdec(substr($hash, 4, 2)),
            $opacity
        );
    }

    /**
     * Remove Query String From Url
     *
     * @param $url
     * @return bool|string
     */
    public static function RemoveQueryStringUrl($url)
    {
        return substr($url, 0, strrpos($url, "?"));
    }

    /**
     *
     * Filter certain query string in the URL based on Query Params Allowed List
     * @param string $url
     * @param array $allowedParams
     * @return string
     */
    public static function FilterQueryStringUrl($url, $allowedParams)
    {
        // Get query from the URL
        $urlQuery = strpos($url, '?');

        // Check if the URL has query strings
        if ($urlQuery !== false) {

            // Parse query strings passed via the URL
            parse_str(substr($url, $urlQuery + 1), $parsedQuery);

            // Loop through query params and unset ones not allowed  
            foreach ($parsedQuery as $key => $value) {
                if (!in_array($key, $allowedParams)) {
                    unset($parsedQuery[$key]);
                }
            }

            // Rebuild URL with allowed params
            $urlPath = substr($url, 0, $urlQuery);
            if (!empty($parsedQuery)) {
                $filteredQuery = http_build_query($parsedQuery);
                $url           = $urlPath . '?' . $filteredQuery;
            } else {
                $url = $urlPath;
            }
        }

        return $url;
    }

    /**
     * Sort associative array
     *
     * @param $array
     * @param $subfield
     * @param int $type
     * @return void
     * @see https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
     */
    public static function SortByKeyValue(&$array, $subfield, $type = SORT_DESC)
    {
        $sort_array = array();
        foreach ($array as $key => $row) {
            $sort_array[$key] = $row[$subfield];
        }
        array_multisort($sort_array, $type, $array);
    }

    /**
     * Format array for the datepicker
     *
     * @param $array_to_strip
     * @return array
     */
    public static function strip_array_indices($array_to_strip)
    {
        $NewArray = array();
        foreach ($array_to_strip as $objArrayItem) {
            $NewArray[] = $objArrayItem;
        }

        return ($NewArray);
    }

    /**
     * Set All Option For DatePicker
     *
     * @example add_filter( 'wp_statistics_days_ago_request', array( '', 'set_all_option_datepicker' ) );
     */
    public static function set_all_option_datepicker()
    {
        $first_day = Helper::get_date_install_plugin();
        return ($first_day === false ? 30 : (int)TimeZone::getNumberDayBetween($first_day));
    }

    /**
     * Url Decode
     *
     * @param $value
     * @return string
     */
    public static function getUrlDecode($value)
    {
        return utf8_decode(urldecode($value));
    }

    /**
     * Check is Assoc Array
     *
     * @param array $arr
     * @return bool
     */
    public static function isAssoc(array $arr)
    {
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Create Condition SQL
     *
     * @param array $args
     * @return string
     */
    public static function getConditionSQL($args = array())
    {

        // Create Empty SQL
        $sql = '';

        // Check Number Params
        if (self::isAssoc($args)) {
            $condition[] = $args;
        } else {
            $condition = $args;
        }

        // Add WHERE
        if (count($condition) > 0) {
            $sql .= ' WHERE ';
        }

        // Push To SQL
        $i = 0;
        foreach ($condition as $params) {
            if ($i > 0) {
                $sql .= ' AND ';
            }
            if ($params['compare'] == "BETWEEN") {
                $sql .= $params['key'] . " " . $params['compare'] . " " . (is_numeric($params['from']) ? $params['from'] : "'" . $params['from'] . "'") . " AND " . (is_numeric($params['to']) ? $params['to'] : "'" . $params['to'] . "'");
            } else {
                $sql .= $params['key'] . " " . $params['compare'] . " " . (is_numeric($params['value']) ? $params['value'] : "'" . $params['value'] . "'");
            }
            $i++;
        }

        return $sql;
    }

    /**
     * Send Email
     *
     * @param $to
     * @param $subject
     * @param $content
     * @param bool $email_template
     * @param array $args
     * @return bool
     */
    public static function send_mail($to, $subject, $content, $email_template = true, $args = array())
    {
        // Email Template
        if ($email_template) {
            $email_template = wp_normalize_path(WP_STATISTICS_DIR . 'includes/admin/templates/emails/layout.php');
        }

        // Email from
        $from_name  = get_bloginfo('name');
        $from_email = get_bloginfo('admin_email');
        $from       = sprintf('%s <%s>', $from_name, $from_email);

        //Template Arg
        $template_arg = array(
            'title'        => $subject,
            'logo'         => '',
            'content'      => $content,
            'site_url'     => home_url(),
            'site_title'   => get_bloginfo('name'),
            'footer_text'  => '',
            'email_title'  => apply_filters('wp_statistics_email_title', __('Email from', 'wp-statistics') . ' ' . wp_parse_url(get_site_url())['host']),
            'logo_image'   => apply_filters('wp_statistics_email_logo', WP_STATISTICS_URL . 'assets/images/logo-statistics-header-blue.png'),
            'logo_url'     => apply_filters('wp_statistics_email_logo_url', get_bloginfo('url')),
            'copyright'    => apply_filters('wp_statistics_email_footer_copyright', Admin_Template::get_template('emails/copyright', array(), true)),
            'email_header' => apply_filters('wp_statistics_email_header', ""),
            'email_footer' => apply_filters('wp_statistics_email_footer', ""),
            'is_rtl'       => (is_rtl() ? true : false)
        );
        $arg          = wp_parse_args($args, $template_arg);

        /**
         * Send Email
         */
        try {

            WP_Statistics_Mail::init()
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($subject)
                ->setBody($content)
                ->setTemplate($email_template, $arg)
                ->send();

            return true;

        } catch (Exception $e) {
            \WP_Statistics::log($e->getMessage());

            return false;
        }
    }

    /**
     * Send SMS With WP SMS Plugin
     *
     * @param $to
     * @param $text
     * @return bool
     */
    public static function send_sms($to, $text)
    {
        if (function_exists('wp_sms_send')) {
            $run = wp_sms_send($to, $text);
            return (is_wp_error($run) ? false : true);
        }

        return false;
    }

    /**
     * Get List Taxonomy
     *
     * @param bool $hide_empty
     * @return array
     */
    public static function get_list_taxonomy($hide_empty = false)
    {
        $taxonomies = array('category' => __("Category", "wp-statistics"), "post_tag" => __("Tags", "wp-statistics"));
        $get_tax    = get_taxonomies(array('public' => true, '_builtin' => false), 'objects', 'and');
        foreach ($get_tax as $object) {
            $object = get_object_vars($object);
            if ($hide_empty === true) {
                $count_term_in_tax = wp_count_terms($object['name']);
                if ($count_term_in_tax > 0 and isset($object['rewrite']['slug'])) {
                    $taxonomies[$object['name']] = $object['labels']->name;
                }
            } else {
                if (isset($object['rewrite']['slug'])) {
                    $taxonomies[$object['name']] = $object['labels']->name;
                }
            }
        }

        return $taxonomies;
    }

    /**
     * Create Condition Where Time in MySql
     *
     * @param string $field : date column name in database table
     * @param string $time : Time return
     * @param array $range : an array contain two Date e.g : array('start' => 'xx-xx-xx', 'end' => 'xx-xx-xx', 'is_day' => true, 'current_date' => true)
     *
     * ---- Time Range -----
     * today
     * yesterday
     * week
     * month
     * year
     * total
     * “-x” (i.e., “-10” for the past 10 days)
     * ----------------------
     *
     * @return string|bool
     */
    public static function mysql_time_conditions($field = 'date', $time = 'total', $range = array())
    {
        //Get Current Date From WP
        $current_date = TimeZone::getCurrentDate('Y-m-d');

        //Create Field Sql
        $field_sql = function ($time) use ($current_date, $field, $range) {
            $is_current     = array_key_exists('current_date', $range);
            $getCurrentDate = TimeZone::getCurrentDate('Y-m-d', (int)$time);
            return "`$field` " . ($is_current === true ? '=' : 'BETWEEN') . " '{$getCurrentDate}'" . ($is_current === false ? " AND '{$current_date}'" : "");
        };

        //Check Time
        switch ($time) {
            case 'today':
                $where = "`$field` = '{$current_date}'";
                break;
            case 'yesterday':
                $getCurrentDate = TimeZone::getTimeAgo(1, 'Y-m-d');
                $where          = "`$field` = '{$getCurrentDate}'";
                break;
            case 'last-week':
                $fromDate = TimeZone::getTimeAgo(14, 'Y-m-d');
                $toDate   = TimeZone::getTimeAgo(7, 'Y-m-d');
                $where    = "`$field` BETWEEN '{$fromDate}' AND '{$toDate}'";
                break;
            case 'week':
                $where = $field_sql(-7);
                break;
            case 'month':
                $where = $field_sql(-30);
                break;
            case '60days':
                $where = $field_sql(-60);
                break;
            case '90days':
                $where = $field_sql(-90);
                break;
            case 'year':
                $where = $field_sql(-365);
                break;
            case 'this-year':
                $fromDate = TimeZone::getLocalDate('Y-m-d', strtotime(gmdate('Y-01-01')));
                $toDate   = TimeZone::getCurrentDate('Y-m-d');
                $where    = "`$field` BETWEEN '{$fromDate}' AND '{$toDate}'";
                break;
            case 'last-year':
                $fromDate = TimeZone::getTimeAgo(365, 'Y-01-01');
                $toDate   = TimeZone::getTimeAgo(365, 'Y-12-31');
                $where    = "`$field` BETWEEN '{$fromDate}' AND '{$toDate}'";
                break;
            case 'total':
                $where = "";
                break;
            default:
                if (array_key_exists('is_day', $range)) {
                    //Check a day
                    if (TimeZone::isValidDate($time)) {
                        $where = "`$field` = '{$time}'";
                    } else {
                        $getCurrentDate = TimeZone::getCurrentDate('Y-m-d', $time);
                        $where          = "`$field` = '{$getCurrentDate}'";
                    }
                } elseif (array_key_exists('start', $range) and array_key_exists('end', $range)) {
                    //Check Between Two Time
                    $getCurrentDate    = TimeZone::getCurrentDate('Y-m-d', '-0', strtotime($range['start']));
                    $getCurrentEndDate = TimeZone::getCurrentDate('Y-m-d', '-0', strtotime($range['end']));
                    $where             = "`$field` BETWEEN '{$getCurrentDate}' AND '{$getCurrentEndDate}'";
                } else {
                    //Check From a Date To Now
                    $where = $field_sql($time);
                }
        }

        return $where;
    }

    /**
     * Easy U-sort Array
     *
     * @param $a
     * @param $b
     * @return bool
     */
    public static function compare_uri_hits($a, $b)
    {
        return $a[1] < $b[1];
    }

    /**
     * Easy U-sort Array
     *
     * @param $a
     * @param $b
     * @return int
     */
    public static function compare_uri_hits_int($a, $b)
    {
        if ($a[1] == $b[1]) return 0;
        if ($a[1] > $b[1]) return 1;
        if ($a[1] < $b[1]) return -1;

    }

    /**
     * Return Number Posts in WordPress
     *
     * @return int
     */
    public static function getCountPosts()
    {
        $count_posts = wp_count_posts('post');

        $ret = 0;
        if (is_object($count_posts)) {
            $ret = $count_posts->publish;
        }
        return $ret;
    }

    /**
     * Get Count Pages WordPress
     *
     * @return int
     */
    public static function getCountPages()
    {
        $count_pages = wp_count_posts('page');

        $ret = 0;
        if (is_object($count_pages)) {
            $ret = $count_pages->publish;
        }
        return $ret;
    }

    /**
     * Get All WordPress Count
     *
     * @return mixed
     */
    public static function getCountComment()
    {
        global $wpdb;

        $countcomms = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = '1'");
        return $countcomms;
    }

    /**
     * Get Count Comment Spam
     *
     * @return mixed
     */
    public static function getCountSpam()
    {
        return number_format_i18n(get_option('akismet_spam_count'));
    }

    /**
     * Get Count All WordPress Users
     *
     * @return mixed
     */
    public static function getCountUsers()
    {
        $result = count_users();
        return $result['total_users'];
    }

    /**
     * Return the last date a post was published on your site.
     *
     * @return string
     */
    public static function getLastPostDate()
    {
        global $wpdb;

        $db_date     = $wpdb->get_var("SELECT post_date FROM {$wpdb->posts} WHERE post_type='post' AND post_status='publish' ORDER BY post_date DESC LIMIT 1");
        $date_format = get_option('date_format');
        return TimeZone::getCurrentDate_i18n($date_format, $db_date, false);
    }

    /**
     * Returns the average number of days between posts on your site.
     *
     * @param bool $days
     * @return float
     */
    public static function getAveragePost($days = false)
    {
        global $wpdb;

        $get_first_post = $wpdb->get_var("SELECT post_date FROM {$wpdb->posts} WHERE post_status = 'publish' ORDER BY post_date LIMIT 1");
        $get_total_post = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type = 'post'");

        $days_spend = intval(
            (time() - strtotime($get_first_post)) / 86400
        ); // 86400 = 60 * 60 * 24 = number of seconds in a day

        if ($days == true) {
            if ($get_total_post == 0) {
                $get_total_post = 1;
            } // Avoid divide by zero errors.

            return round($days_spend / $get_total_post, 0);
        } else {
            if ($days_spend == 0) {
                $days_spend = 1;
            } // Avoid divide by zero errors.

            return round($get_total_post / $days_spend, 2);
        }
    }

    /**
     * Returns the average number of days between comments on your site.
     *
     * @param bool $days
     * @return float
     */
    public static function getAverageComment($days = false)
    {

        global $wpdb;

        $get_first_comment = $wpdb->get_var("SELECT comment_date FROM {$wpdb->comments} ORDER BY comment_date LIMIT 1");
        $get_total_comment = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = '1'");

        $days_spend = intval(
            (time() - strtotime($get_first_comment)) / 86400
        ); // 86400 = 60 * 60 * 24 = number of seconds in a day

        if ($days == true) {
            if ($get_total_comment == 0) {
                $get_total_comment = 1;
            } // Avoid divide by zero errors.

            return round($days_spend / $get_total_comment, 0);
        } else {
            if ($days_spend == 0) {
                $days_spend = 1;
            } // Avoid divide by zero errors.

            return round($get_total_comment / $days_spend, 2);
        }
    }

    /**
     * Returns the average number of days between user registrations on your site.
     *
     * @param bool $days
     * @return float
     */
    public static function getAverageRegisterUser($days = false)
    {

        global $wpdb;

        $get_first_user = $wpdb->get_var("SELECT `user_registered` FROM {$wpdb->users} ORDER BY user_registered LIMIT 1");
        $get_total_user = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->users}");

        $days_spend = intval(
            (time() - strtotime($get_first_user)) / 86400
        );

        if ($days == true) {
            if ($get_total_user == 0) {
                $get_total_user = 1;
            }

            return round($days_spend / $get_total_user, 0);
        } else {
            if ($days_spend == 0) {
                $days_spend = 1;
            }

            return round($get_total_user / $days_spend, 2);
        }
    }

    /**
     * Add notice to display in the admin area
     *
     * @param $message
     * @param string $class
     * @param bool $is_dismissible
     * @since 13.2.5
     */
    public static function addAdminNotice($message, $class = 'info', $is_dismissible = true)
    {
        self::$admin_notices[] = array(
            'message'        => $message,
            'class'          => $class,
            'is_dismissible' => (bool)$is_dismissible,
        );
    }

    /**
     * Display all notices in the admin area
     *
     * @return void
     * @since 13.2.5
     */
    public static function displayAdminNotices()
    {
        foreach ((array)self::$admin_notices as $notice) :
            $dismissible = $notice['is_dismissible'] ? 'is-dismissible' : '';
            ?>

            <div class="notice notice-<?php echo esc_attr($notice['class']); ?> <?php echo esc_attr($dismissible); ?>">
                <p>
                    <?php echo wp_kses_post($notice['message']); ?>
                </p>
            </div>

        <?php
        endforeach;
    }

    /**
     * Returns default parameters for hits request
     *
     * @return array
     */
    public static function getHitsDefaultParams()
    {
        // Create Empty Params Object
        $params = array();

        //track all page
        $params['track_all'] = (Pages::is_track_all_page() === true ? 1 : 0);

        //Set Page Type
        $get_page_type               = Pages::get_page_type();
        $params['current_page_type'] = $get_page_type['type'];
        $params['current_page_id']   = $get_page_type['id'];
        $params['search_query']      = (isset($get_page_type['search_query']) ? esc_html($get_page_type['search_query']) : '');

        //page url
        $params['page_uri'] = base64_encode(Pages::get_page_uri());

        //return Json Data
        return $params;
    }

    /**
     * The version number will be anonymous using this function
     *
     * @param $version
     * @return string
     * @example 106.2.124.0 -> 106.0.0.0
     *
     */
    public static function makeAnonymousVersion($version)
    {
        $mainVersion         = substr($version, 0, strpos($version, '.'));
        $subVersion          = substr($version, strpos($version, '.') + 1);
        $anonymousSubVersion = preg_replace('/[0-9]+/', '0', $subVersion);

        return "{$mainVersion}.{$anonymousSubVersion}";
    }

    /**
     * Do not track browser detection
     *
     * @return bool
     */
    public static function dntEnabled()
    {
        if (Option::get('do_not_track')) {
            return (isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1) or (function_exists('getallheaders') && isset(getallheaders()['DNT']) && getallheaders()['DNT'] == 1);
        }

        return false;
    }

    public static function getRequestUri()
    {
        if (self::is_rest_request() and isset($_REQUEST['page_uri'])) {
            return base64_decode($_REQUEST['page_uri']);
        }

        return sanitize_url(wp_unslash($_SERVER['REQUEST_URI']));
    }

    /**
     * Check whether an add-on is active or not
     *
     * @param string $slug
     * @return bool
     */
    public static function isAddOnActive($slug)
    {
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $pluginName = sprintf('wp-statistics-%1$s/wp-statistics-%1$s.php', $slug);

        return is_plugin_active($pluginName);
    }

    public static function convertBytes($input)
    {
        $unit  = strtoupper(substr($input, -1));
        $value = (int)$input;
        switch ($unit) {
            case 'G':
                $value *= 1024;
            case 'M':
                $value *= 1024;
            case 'K':
                $value *= 1024;
        }
        return $value;
    }

    public static function checkMemoryLimit()
    {
        if (!function_exists('memory_get_peak_usage') or !function_exists('ini_get')) {
            return false;
        }

        $memoryLimit = ini_get('memory_limit');

        if (memory_get_peak_usage(true) > self::convertBytes($memoryLimit)) {
            return true;
        }

        return false;
    }

    public static function yieldARow($rows)
    {
        $i = 0;
        while ($row = current($rows)) {
            yield $row;
            unset($rows[$i]);
            $i++;
        }
    }

    public static function prepareArrayToStringForQuery($fields = array())
    {
        global $wpdb;
    
        foreach ($fields as &$value) {
            $value = $wpdb->prepare('%s', $value);
        }

        return implode(', ', $fields);
    }

}
