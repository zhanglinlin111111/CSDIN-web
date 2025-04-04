<?php

namespace WP_STATISTICS;

class visitors_page
{
    public function __construct()
    {
        if (Menus::in_page('visitors')) {
            // Disable Screen Option
            add_filter('screen_options_show_screen', '__return_false');

            // Set Default All Option for DatePicker
            add_filter('wp_statistics_days_ago_request', array('\WP_STATISTICS\Helper', 'set_all_option_datepicker'));

            // Is Validate Date Request
            $DateRequest = Admin_Template::isValidDateRequest();
            if (!$DateRequest['status']) {
                wp_die(esc_html($DateRequest['message']));
            }
        }
    }

    /**
     * Display Html Page
     *
     * @throws \Exception
     */
    public static function view()
    {

        // Page title
        $args['title'] = (count($_GET) > 1 ? __('Visitors', 'wp-statistics') : __('Latest Visitor Activity', 'wp-statistics'));
        // Get Current Page Url
        $args['pageName'] = Menus::get_page_slug('visitors');
        $args['paged']    = Admin_Template::getCurrentPaged();

        // Get Type Of Order
        $order = ((isset($_GET['order']) and ($_GET['order'] == "asc" || $_GET['order'] == "desc")) ? $_GET['order'] : 'desc');

        // Get Date-Range
        $args['DateRang']    = Admin_Template::DateRange();
        $args['HasDateRang'] = True;

        // Default Parameter Link
        $data_link = array('from' => $args['DateRang']['from'], 'to' => $args['DateRang']['to']);
        if ($order == "asc") {
            $data_link['order'] = 'asc';
        }

        // Create Default SQL Params
        $sql[] = array('key' => 'last_counter', 'compare' => 'BETWEEN', 'from' => $args['DateRang']['from'], 'to' => $args['DateRang']['to']);

        // Create Sub List
        $args['sub']['all'] = array('title' => __('All', 'wp-statistics'), 'count' => Visitor::Count($sql), 'active' => (isset($_GET['platform']) || isset($_GET['agent']) || isset($_GET['referrer']) || isset($_GET['referred']) || isset($_GET['ip']) || isset($_GET['location']) || isset($_GET['user_id']) ? false : true), 'link' => Menus::admin_url('visitors'));

        // Filters Option
        $args['filter'] = self::Filter();

        /**
         * User ID Filter
         */
        if (isset($_GET['user_id'])) {
            // Add Params To SQL
            $user_id = sanitize_text_field($_GET['user_id']);
            $sql[]   = array('key' => 'user_id', 'compare' => '=', 'value' => trim($user_id));

            // Get User Data
            $user_info = User::get($user_id);

            // Set New Sub List
            if ($args['filter']['number'] == 1) {
                $args['sub'][$user_id] = array('title' => $user_info['user_login'] . ' #' . $user_id, 'count' => Visitor::Count($sql), 'active' => ((isset($_GET['user_id']) and $_GET['user_id'] == $_GET['user_id']) ? true : false), 'link' => add_query_arg(array_merge($data_link, array('user_id' => $user_id)), Menus::admin_url('visitors')));
            }
        }

        /**
         * IP Filter
         */
        if (isset($_GET['ip'])) {
            // Add Params To SQL
            $ip    = sanitize_text_field($_GET['ip']);
            $sql[] = array('key' => 'ip', 'compare' => 'LIKE', 'value' => trim($ip));

            // Set New Sub List
            if ($args['filter']['number'] == 1) {
                $args['sub'][$ip] = array('title' => $ip, 'count' => Visitor::Count($sql), 'active' => ((isset($_GET['ip']) and $_GET['ip'] == $_GET['ip']) ? true : false), 'link' => add_query_arg(array_merge($data_link, array('ip' => $ip)), Menus::admin_url('visitors')));
            }
        }

        /**
         * Location Filter
         */
        if (isset($_GET['location']) and !empty($_GET['location'])) {
            // Add Params To SQL
            $location = sanitize_text_field($_GET['location']);
            $sql[]    = array('key' => 'location', 'compare' => 'LIKE', 'value' => trim($location));

            // Set New Sub List
            if ($args['filter']['number'] == 1) {
                $args['sub'][$location] = array('title' => Country::getName($location), 'count' => Visitor::Count($sql), 'active' => ((isset($_GET['location']) and $_GET['location'] == $_GET['location']) ? true : false), 'link' => add_query_arg(array_merge($data_link, array('location' => $location)), Menus::admin_url('visitors')));
            }
        }

        /**
         * Platform Filter
         */
        if (isset($_GET['platform']) and !empty($_GET['platform'])) {
            // Add Params To SQL
            $platform = sanitize_text_field($_GET['platform']);
            $sql[]    = array('key' => 'platform', 'compare' => 'LIKE', 'value' => trim(Helper::getUrlDecode($platform)));

            // Set New Sub List
            if ($args['filter']['number'] == 1) {
                $args['sub'][$platform] = array('title' => Helper::getUrlDecode($platform), 'count' => Visitor::Count($sql), 'active' => ((isset($_GET['platform']) and $_GET['platform'] == $_GET['platform']) ? true : false), 'link' => add_query_arg(array_merge($data_link, array('platform' => $platform)), Menus::admin_url('visitors')));
            }
        }

        /**
         * Referrer Filter
         */
        if (isset($_GET['referrer']) and !empty($_GET['referrer'])) {
            // Add Params To SQL
            $referrer = sanitize_text_field($_GET['referrer']);
            $sql[]    = array('key' => 'referred', 'compare' => 'LIKE', 'value' => "%" . trim($referrer) . "%");

            // Set New Sub List
            if ($args['filter']['number'] == 1) {
                $args['sub'][$referrer] = array('title' => trim($referrer), 'count' => Visitor::Count($sql), 'active' => ((isset($_GET['referrer']) and $_GET['referrer'] == $_GET['referrer']) ? true : false), 'link' => add_query_arg(array_merge($data_link, array('referrer' => $referrer)), Menus::admin_url('visitors')));
            }
        }

        /**
         * Agent Filter
         */
        $browsers = UserAgent::BrowserList();
        if (isset($_GET['agent']) and !empty($_GET['agent'])) {
            // Add Params To SQL
            $agent = sanitize_text_field($_GET['agent']);
            $sql[] = array('key' => 'agent', 'compare' => 'LIKE', 'value' => trim($agent));

            // Set New Sub List
            if ($args['filter']['number'] == 1) {
                $args['sub'][$agent] = array('title' => $browsers[strtolower($agent)], 'count' => Visitor::Count(array_merge($sql, array('key' => 'agent', 'compare' => 'LIKE', 'value' => $agent))), 'active' => (isset($_GET['agent']) ? true : false), 'link' => add_query_arg(array_merge($data_link, array('agent' => $agent)), Menus::admin_url('visitors')));
            }
        }

        // Browser Sub List is Default
        if ($args['filter']['number'] < 1) {
            foreach ($browsers as $key => $se) {
                $args['sub'][$key] = array('title' => $se, 'count' => Visitor::Count(array_merge($sql, array('key' => 'agent', 'compare' => 'LIKE', 'value' => $key))), 'active' => ((isset($_GET['agent']) and $_GET['agent'] == $key) ? true : false), 'link' => add_query_arg(array_merge($data_link, array('agent' => $key)), Menus::admin_url('visitors')));
            }
        }

        // Set for Custom Filter
        if ($args['filter']['number'] > 1) {
            $args['sub']['custom'] = array('title' => __('Custom filter', 'wp-statistics'), 'count' => Visitor::Count($sql), 'active' => true, 'link' => remove_query_arg('custom'));
        }

        // Get Current View
        $CurrentView = array_filter($args['sub'], function ($val, $key) {
            return $val['active'] === true;
        }, ARRAY_FILTER_USE_BOTH);

        //Get Total List
        $args['total'] = $CurrentView[key($CurrentView)]['count'];
        $args['list']  = array();
        if ($args['total'] > 0) {

            $condition         = Helper::getConditionSQL($sql);
            $visitorTable      = DB::table('visitor');
            $relationshipTable = DB::table('visitor_relationships');

            if (Option::get('visitors_log')) {
                $sql = "SELECT vsr.*, vs.* FROM ( SELECT visitor_id, page_id, MAX(date) AS latest_visit_date FROM `{$relationshipTable}` GROUP BY visitor_id ) AS latest_visits JOIN `{$visitorTable}` vs ON latest_visits.visitor_id = vs.ID JOIN `{$relationshipTable}` vsr ON vsr.visitor_id = latest_visits.visitor_id AND vsr.date = latest_visits.latest_visit_date {$condition} ORDER BY vsr.date DESC";
            } else {
                $sql = "SELECT * FROM `{$visitorTable}` {$condition} ORDER BY `last_counter` {$order}, `hits` {$order}";
            }

            $args['list'] = Visitor::get(array(
                'sql'      => $sql,
                'per_page' => Admin_Template::$item_per_page,
                'paged'    => $args['paged'],
            ));
        }

        // Create WordPress Pagination
        $args['pagination'] = '';
        if ($args['total'] > 0) {
            $args['pagination'] = Admin_Template::paginate_links(array(
                'total' => $args['total'],
                'echo'  => false
            ));
        }

        Admin_Template::get_template(array('layout/header', 'layout/title', 'pages/visitors', 'layout/visitors.filter', 'layout/footer'), $args);
    }

    /**
     * Create Filter System
     *
     * @return mixed
     */
    public static function Filter()
    {
        $params = 0;
        foreach ($_GET as $params_key => $params_item) {
            if (!in_array($params_key, array('page', 'from', 'to', 'order', 'orderby'))) {
                $params++;
            }
        }
        $filter['number'] = $params;

        // Code Button
        $filter['code'] = '<div class="wps-pull-' . (is_rtl() ? 'left' : 'right') . '" id="visitors-filter"><span class="dashicons dashicons-filter"></span><span class="filter-text">' . __("Filters", "wp-statistics") . '</span> ' . ($filter['number'] > 0 ? '<span class="wps-badge">' . number_format_i18n($filter['number']) . '</span>' : '') . '</div>';

        // Return Data
        return $filter;
    }
}

new visitors_page;
