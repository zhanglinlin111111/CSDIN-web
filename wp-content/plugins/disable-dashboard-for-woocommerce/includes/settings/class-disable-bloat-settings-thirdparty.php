<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add sections and fields on Main settings screen
function wcbloat_thirdparty_settings_init()
{

	// Section's icon
	add_settings_section(
		'wcbloat-thirdparty-desc-icon-section',
		'',
		'wcbloat_thirdparty_desc_icon_callback',
		'wcbloat-thirdparty'
	);

	// Section's title and description
	add_settings_section(
		'wcbloat-thirdparty-desc-section',
		esc_attr__('Third-party plugins bloat', 'disable-dashboard-for-woocommerce'),
		'wcbloat_thirdparty_desc_callback',
		'wcbloat-thirdparty'
	);

	// Jetpack section
	add_settings_section(
		'wcbloat_jetpack_section',
		esc_attr__('Jetpack', 'disable-dashboard-for-woocommerce'),
		'wcbloat_jetpack_section_callback',
		'wcbloat-thirdparty'
	);

	// Jetpack installation notice
	add_settings_field(
		'wcbloat_jetpack_installation_disable',
		'Jetpack installation notice',
		'wcbloat_jetpack_installation_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_jetpack_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_jetpack_installation_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable Jetpack ads
	add_settings_field(
		'wcbloat_jetpack_disable',
		'Jetpack promotions',
		'wcbloat_jetpack_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_jetpack_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_jetpack_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Elementor section
	add_settings_section(
		'wcbloat_elementor_section',
		esc_attr__('Elementor', 'disable-dashboard-for-woocommerce'),
		'wcbloat_elementor_section_callback',
		'wcbloat-thirdparty'
	);

	// Disable Elementor overview Dashboard widget
	add_settings_field(
		'wcbloat_elementor_widget_disable',
		'Elementor Dashboard widget',
		'wcbloat_elementor_widget_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_elementor_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_elementor_widget_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// SkyVerge section
	add_settings_section(
		'wcbloat_skyverge_section',
		esc_attr__('SkyVerge', 'disable-dashboard-for-woocommerce'),
		'wcbloat_skyverge_section_callback',
		'wcbloat-thirdparty'
	);

	// Disable SkyVerge Dashboard
	add_settings_field(
		'wcbloat_wc_skyverge_disable',
		'SkyVerge Dashboard',
		'wcbloat_wc_skyverge_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_skyverge_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_wc_skyverge_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Yoast SEO section
	add_settings_section(
		'wcbloat_yoast_section',
		esc_attr__('Yoast SEO', 'disable-dashboard-for-woocommerce'),
		'wcbloat_yoast_section_callback',
		'wcbloat-thirdparty'
	);

	// Yoast SEO Ads, Premium nags, Premium menu PRO
	add_settings_field(
		'wcbloat_yoast_premium_disable',
		'Ads, Premium nags, Premium menu',
		'wcbloat_yoast_premium_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_yoast_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_yoast_premium_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Yoast SEO Admin bar item PRO
	add_settings_field(
		'wcbloat_yoast_admin_bar_disable',
		'Ads, Premium nags, Premium menu',
		'wcbloat_yoast_admin_bar_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_yoast_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_yoast_admin_bar_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Yoast SEO Remove HTML Comments from the Front-end PRO
	add_settings_field(
		'wcbloat_yoast_html_comments_disable',
		'Remove HTML Comments from the Front-end',
		'wcbloat_yoast_html_comments_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_yoast_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_yoast_html_comments_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Yoast SEO Remove Yoast SEO Dashboard widget PRO
	add_settings_field(
		'wcbloat_yoast_widget_disable',
		'Remove Yoast SEO Dashboard widget',
		'wcbloat_yoast_widget_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_yoast_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_yoast_widget_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Contact Form 7 section
	add_settings_section(
		'wcbloat_cf7_section',
		esc_attr__('Contact Form 7', 'disable-dashboard-for-woocommerce'),
		'wcbloat_cf7_section_callback',
		'wcbloat-thirdparty'
	);

	// Disable Contact Form 7 JavaScript and CSS stylesheet on every page PRO
	add_settings_field(
		'wcbloat_cf7_disable',
		'Contact Form 7 JavaScript and CSS',
		'wcbloat_cf7_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_cf7_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_cf7_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// UpDraftPlus section
	add_settings_section(
		'wcbloat_updraft_section',
		esc_attr__('UpDraftPlus', 'disable-dashboard-for-woocommerce'),
		'wcbloat_updraft_section_callback',
		'wcbloat-thirdparty'
	);

	// Hide "UpdraftPlus" on admin toolbar PRO
	add_settings_field(
		'wcbloat_updraftplus_menubar_disable',
		'Hide "UpdraftPlus" on admin toolbar',
		'wcbloat_updraftplus_menubar_disable_callback',
		'wcbloat-thirdparty',
		'wcbloat_updraft_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_updraftplus_menubar_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Advanced Custom Fields section
	add_settings_section(
		'wcbloat_acf_section',
		esc_attr__('Advanced Custom Fields', 'disable-dashboard-for-woocommerce'),
		'wcbloat_acf_section_callback',
		'wcbloat-thirdparty'
	);

	// Advanced Custom Fields: Remove admin interface PRO
	add_settings_field(
		'wcbloat_acf_hide_menu',
		'Hide Advanced Custom Fields admin menu',
		'wcbloat_acf_hide_menu_callback',
		'wcbloat-thirdparty',
		'wcbloat_acf_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_acf_hide_menu',
		array('sanitize_callback' => 'sanitize_key')
	);

	// WPML section
	add_settings_section(
		'wcbloat_wpml_section',
		esc_attr__('WPML', 'disable-dashboard-for-woocommerce'),
		'wcbloat_wpml_section_callback',
		'wcbloat-thirdparty'
	);

	// Remove WPML Meta Generator Tag PRO
	add_settings_field(
		'wcbloat_wpml_remove_meta',
		'Remove WPML Meta Generator Tag',
		'wcbloat_wpml_remove_meta_callback',
		'wcbloat-thirdparty',
		'wcbloat_wpml_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_wpml_remove_meta',
		array('sanitize_callback' => 'sanitize_key')
	);

	// WP Desk section
	add_settings_section(
		'wcbloat_wpdesk_section',
		esc_attr__('WP Desk', 'disable-dashboard-for-woocommerce'),
		'wcbloat_wpdesk_section_callback',
		'wcbloat-thirdparty'
	);

	// Remove the "Grow your business with WP Desk" widget
	add_settings_field(
		'wcbloat_wpdesk_disable_dashboard_widget',
		'Disable WP Desk dashboard widget',
		'wcbloat_wpdesk_disable_dashboard_widget_callback',
		'wcbloat-thirdparty',
		'wcbloat_wpdesk_section'
	);
	register_setting(
		'wcbloat-thirdparty-options',
		'wcbloat_wpdesk_disable_dashboard_widget',
		array('sanitize_callback' => 'sanitize_key')
	);

}

// Display the fields added before
add_action('admin_init', 'wcbloat_thirdparty_settings_init');

// Fields callbacks

// Section's icon
function wcbloat_thirdparty_desc_icon_callback()
{
	_e('<span class="dashicons dashicons-admin-plugins"></span>');
}

// Description
function wcbloat_thirdparty_desc_callback()
{
	_e('The plugin integrates with third-party plugins. Using the options from this section, you can hide or turn off unwanted User Interface elements that these plugins add to your admin panel by default. Options for the supported plugin will be always presented on this screen - the plugin does not detect if you have a specific plugin installed.<hr>', 'disable-dashboard-for-woocommerce');
}

// Jetpack section
function wcbloat_jetpack_section_callback()
{
	_e('WordPress often encourages you to install Jetpack and connect your site to WordPress.com. If you do not want Jetpack, you can remove the installation notice. If you are using Jetpack, you can disable Jetpack promotions.', 'disable-dashboard-for-woocommerce');
}

// Jetpack installation notice
function wcbloat_jetpack_installation_disable_callback()
{
	$value = get_option('wcbloat_jetpack_installation_disable');
?>
	<input type='hidden' name='wcbloat_jetpack_installation_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_jetpack_installation_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Remove Jetpack installation notice', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will remove Jetpack installation notice.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable Jetpack ads
function wcbloat_jetpack_disable_callback()
{
	$value = get_option('wcbloat_jetpack_disable');
?>
	<input type='hidden' name='wcbloat_jetpack_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_jetpack_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable Jetpack promotions', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will disable Jetpack-related notices that promote services like the backup services VaultPress or WordPress Apps. Works only if you have Jetpack installed.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Elementor section
function wcbloat_elementor_section_callback()
{
	_e('Elementor is a great tool, but it may also lead to cluttering your WordPress Dashboard. The options below will only be effective if you have Elementor installed and activated.', 'disable-dashboard-for-woocommerce');
}

// Disable Elementor overview Dashboard widget
function wcbloat_elementor_widget_disable_callback()
{
	$value = get_option('wcbloat_elementor_widget_disable');
?>
	<input type='hidden' name='wcbloat_elementor_widget_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_elementor_widget_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable Elementor Dashboard widget', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will disable Elementor overview widget shown in WordPress Dashboard.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// SkyVerge section
function wcbloat_skyverge_section_callback()
{
	_e('SkyVerge plugins are adding their own top-level admin menu item with their Dashboard. You may not want to use it.', 'disable-dashboard-for-woocommerce');
}

// Disable SkyVerge Dashboard
function wcbloat_wc_skyverge_disable_callback()
{
	$value = get_option('wcbloat_wc_skyverge_disable');
?>
	<input type='hidden' name='wcbloat_wc_skyverge_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_skyverge_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable SkyVerge Dashboard', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will disable SkyVerge Dashboard. Works only if you are using SkyVerge plugins.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Yoast SEO Section
function wcbloat_yoast_section_callback()
{
	_e('Yoast is a very handy plugin, but it may add to your admin panel some elements that would not be your first choice.', 'disable-dashboard-for-woocommerce');
	_e ( wcbloat_buy_pro_bar() );
}

// Yoast SEO Ads, Premium nags, Premium menu
function wcbloat_yoast_premium_disable_callback()
{
	$value = get_option('wcbloat_yoast_premium_disable');
?>
	<input type='hidden' name='wcbloat_yoast_premium_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_yoast_premium_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable Ads, Premium nags, Premium menu', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('If you do not want Yoast to scream about the premium version at all times, use this option. This option will disable Ads, Premium nags, Premium menu.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Yoast SEO Admin bar item
function wcbloat_yoast_admin_bar_disable_callback()
{
	$value = get_option('wcbloat_yoast_admin_bar_disable');
?>
	<input type='hidden' name='wcbloat_yoast_admin_bar_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_yoast_admin_bar_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable Yoast SEO Admin bar item', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will hide Yoast SEO item from WordPress Admin bar.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Yoast SEO Remove HTML Comments from the Front-end
function wcbloat_yoast_html_comments_disable_callback()
{
	$value = get_option('wcbloat_yoast_html_comments_disable');
?>
	<input type='hidden' name='wcbloat_yoast_html_comments_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_yoast_html_comments_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Remove Yoast HTML Comments from the Front-end', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Yoast SEO does include some HTML commented-out code on your site\'s front. It may lead to weaker page performance.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Yoast SEO Remove Yoast SEO Dashboard widget
function wcbloat_yoast_widget_disable_callback()
{
	$value = get_option('wcbloat_yoast_widget_disable');
?>
	<input type='hidden' name='wcbloat_yoast_widget_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_yoast_widget_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Remove Yoast SEO Dashboard widget', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will Remove the Yoast SEO widget from your WordPress Dashboard.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Contact Form 7 section
function wcbloat_cf7_section_callback()
{
	_e('We all love Contact Form 7 for flexibility, but loading tons of CSS and javascript on every subpage of your WordPress frontend may be frustrating for you and for your visitors. Why don\'t disable it globally and load it manually only where it is needed?', 'disable-dashboard-for-woocommerce');
}

// Disable Contact Form 7 JavaScript and CSS stylesheet on every page PRO
function wcbloat_cf7_disable_callback()
{
	$value = get_option('wcbloat_cf7_disable');
?>
	<input type='hidden' name='wcbloat_cf7_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_cf7_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable Contact Form 7 JavaScript and CSS', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will disable Contact Form 7 JavaScript and CSS files from loading on every page. Works only if you are using Contact Form 7. Please remember to add the scripts manually to your contact page.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// UpDraftPlus section
function wcbloat_updraft_section_callback()
{
	// TODO Section description
	// _e('');
}

// Disable Contact Form 7 JavaScript and CSS stylesheet on every page PRO
function wcbloat_updraftplus_menubar_disable_callback()
{
	$value = get_option('wcbloat_updraftplus_menubar_disable');
?>
	<input type='hidden' name='wcbloat_updraftplus_menubar_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_updraftplus_menubar_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Hide "UpdraftPlus" on admin toolbar', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will hide "UpdraftPlus" on admin toolbar. Works only if you are using “UpdraftPlus”. Please remember to add the scripts manually to your contact page.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// UpDraftPlus section
function wcbloat_acf_section_callback()
{
	// TODO Section description
	// _e('');
}

// Advanced Custom Fields: Remove admin interface PRO
function wcbloat_acf_hide_menu_callback()
{
	$value = get_option('wcbloat_acf_hide_menu');
?>
	<input type='hidden' name='wcbloat_acf_hide_menu' value='no'>
	<label><input type='checkbox' name='wcbloat_acf_hide_menu' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Hide Advanced Custom Fields admin menu', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will hide Advanced Custom Fields admin interface. Custom Fields admin menu item won\'t be available.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WPML section
function wcbloat_wpml_section_callback()
{
	_e('Some users of the WPML plugin have reported that it can cause bloat on their website, meaning that it can slow down the website\'s performance due to the increased amount of data and code that the plugin adds to the site. This can result in a slower page load time and decreased user experience. To reduce the effects of WPML bloat, you may want to disable certain features of the plugin:', 'disable-dashboard-for-woocommerce');
}

// Remove WPML Meta Generator Tag PRO
function wcbloat_wpml_remove_meta_callback()
{
	$value = get_option('wcbloat_wpml_remove_meta');
?>
	<input type='hidden' name='wcbloat_wpml_remove_meta' value='no'>
	<label><input type='checkbox' name='wcbloat_wpml_remove_meta' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Remove WPML Meta Generator Tag', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will remove WPML Meta Generator Tag from WordPress Header.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WP Desk section
function wcbloat_wpdesk_section_callback()
{
	_e('WP Desk plugins, such as Flexible Checkout Fields, Flexible Invoices, Shopmagic, and Flexible Product Fields add a "Grow your business with WP Desk" widget to the WordPress dashboard. Disabling an unwanted dashboard widget can help to improve the user experience by decluttering the dashboard and making it easier to navigate:', 'disable-dashboard-for-woocommerce');
}

// Disable WP Desk dashboard widget	
function wcbloat_wpdesk_disable_dashboard_widget_callback()
{
	$value = get_option('wcbloat_wpdesk_disable_dashboard_widget');
?>
	<input type='hidden' name='wcbloat_wpdesk_disable_dashboard_widget' value='no'>
	<label><input type='checkbox' name='wcbloat_wpdesk_disable_dashboard_widget' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable "Grow your business with WP Desk" dashboard widget', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Check this option to disable "Grow your business with WP Desk" dashboard widget which comes with WP Desk plugins', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}
