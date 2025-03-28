<?php

namespace WP_STATISTICS;

class Admin_Dashboard
{
    /**
     * User Meta Set Dashboard Option name
     *
     * @var string
     */
    public static $dashboard_set = 'dashboard_set';

    /**
     * Admin_Dashboard constructor.
     */
    public function __construct()
    {

        //Register Dashboard Widget
        add_action('wp_dashboard_setup', array($this, 'load_dashboard_widget'));

        // Add plugin's global class name
        add_action('admin_body_class', array($this, 'add_plugin_body_class'));
    }

    /**
     * Register Wp-statistics Dashboard
     */
    public function register_dashboard_widget()
    {

        foreach (apply_filters('wp_statistics_dashboard_widget_list', Meta_Box::getList()) as $widget_key => $dashboard) {
            if (Option::check_option_require($dashboard) === true and isset($dashboard['show_on_dashboard']) and $dashboard['show_on_dashboard'] === true) {
                wp_add_dashboard_widget(Meta_Box::getMetaBoxKey($widget_key), $dashboard['name'], Meta_Box::LoadMetaBox($widget_key), $control_callback = null, array('widget' => $widget_key));
            }
        }
    }

    /**
     * Load Dashboard Widget
     * This Function add_action to `wp_dashboard_setup`
     */
    public function load_dashboard_widget()
    {

        // If the user does not have at least read access to the status plugin, just return without adding the widgets.
        if (!User::Access('read')) {
            return;
        }

        // Check Hidden User Dashboard Option
        $user_dashboard = Option::getUserOption(self::$dashboard_set);


        /**
         * @note This code is commented due to reset widget issue on plugin update, and not sure why it's been added in the first place! Anyway, let's comment it!
         */
        // if ($user_dashboard != WP_STATISTICS_VERSION) {
        //     self::set_user_hidden_dashboard_option();
        // }

        if ($user_dashboard === false) {
            self::set_user_hidden_dashboard_option();
        }

        // If the admin has disabled the widgets, don't display them.
        if (!Option::get('disable_dashboard')) {
            $this->register_dashboard_widget();
        }
    }

    /**
     * Set Default Hidden Dashboard User Option
     */
    public static function set_user_hidden_dashboard_option()
    {

        //Get List Of Wp-statistics Dashboard Widget
        $dashboard_list = Meta_Box::getList();
        $hidden_opt     = 'metaboxhidden_dashboard';

        //Create Empty Option and save in User meta
        Option::update_user_option(self::$dashboard_set, WP_STATISTICS_VERSION);

        //Get Dashboard Option User Meta
        $hidden_widgets = get_user_meta(User::get_user_id(), $hidden_opt, true);
        if (!is_array($hidden_widgets)) {
            $hidden_widgets = array();
        }

        //Set Default Hidden Dashboard in Admin Wordpress
        foreach ($dashboard_list as $widget => $dashboard) {
            if (isset($dashboard['hidden']) and $dashboard['hidden'] === true) {
                $hidden_widgets[] = Meta_Box::getMetaBoxKey($widget);
            }
        }

        update_user_meta(User::get_user_id(), $hidden_opt, $hidden_widgets);
    }

	public function add_plugin_body_class($classes)
	{
		// Add class for the admin body only for plugin's pages
		if (isset($_GET['page']) && strpos($_GET['page'], 'wps_') === 0) {
			$classes .= ' wps_page';
		}

		return $classes;
	}
}

new Admin_Dashboard;