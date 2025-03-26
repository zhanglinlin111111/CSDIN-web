<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add sections and fields on Main settings screen
function wcbloat_wpcore_settings_init()
{

	// Section's icon
	add_settings_section(
		'wcbloat-wpcore-icon-section',
		'',
		'wcbloat_wpcore_icon_callback',
		'wcbloat-wpcore'
	);

	// Section's title and description
	add_settings_section(
		'wcbloat-site-wpcore-desc-section',
		esc_attr__('WordPress Core', 'disable-dashboard-for-woocommerce'),
		'wcbloat_wpcore_desc_callback',
		'wcbloat-wpcore'
	);

	// Updates section
	add_settings_section(
		'wcbloat-wpcore-updates-section',
		esc_attr__('Updates', 'disable-dashboard-for-woocommerce'),
		'wcbloat_updates_section_callback',
		'wcbloat-wpcore'
	);

	// Themes auto-updates
	add_settings_field(
		'wcbloat_themes_auto_update_disable',
		'Themes auto-updates',
		'wcbloat_themes_auto_update_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-updates-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_themes_auto_update_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable plugins auto-updates
	add_settings_field(
		'wcbloat_plugins_auto_update_disable',
		'Plugins auto-updates',
		'wcbloat_plugins_auto_update_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-updates-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_plugins_auto_update_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable all WordPress core updates PRO
	add_settings_field(
		'wcbloat_wp_core_update_disable',
		'WordPress core updates',
		'wcbloat_wp_core_update_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-updates-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_wp_core_update_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Speed and security section
	add_settings_section(
		'wcbloat-wpcore-speed-section',
		esc_attr__('Speed and security', 'disable-dashboard-for-woocommerce'),
		'wcbloat_updates_speed_callback',
		'wcbloat-wpcore'
	);

	// Disable File Editor PRO
	add_settings_field(
		'wcbloat_file_editor_disable',
		'File Editor',
		'wcbloat_file_editor_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-speed-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_file_editor_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable post revisions PRO
	add_settings_field(
		'wcbloat_post_revisions_disable',
		'Post revisions',
		'wcbloat_post_revisions_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-speed-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_post_revisions_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable Application Passwords PRO
	add_settings_field(
		'wcbloat_app_passwords_disable',
		'Application Passwords',
		'wcbloat_app_passwords_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-speed-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_app_passwords_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Remove script/style version parameter PRO
	add_settings_field(
		'wcbloat_remove_script_style_ver',
		'Remove script/style version parameter',
		'wcbloat_remove_script_style_ver_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-speed-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_remove_script_style_ver',
		array('sanitize_callback' => 'sanitize_key')
	);

	// API section
	add_settings_section(
		'wcbloat-wpcore-api-section',
		esc_attr__('WordPress API', 'disable-dashboard-for-woocommerce'),
		'wcbloat_wpcore_api_callback',
		'wcbloat-wpcore'
	);

	// Disable XML-RPC API PRO
	add_settings_field(
		'wcbloat_xml_rpc_disable',
		'XML-RPC API',
		'wcbloat_xml_rpc_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-api-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_xml_rpc_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WordPress Heartbeat API PRO
	add_settings_field(
		'wcbloat_wp_heartbeat_disable',
		'WordPress Heartbeat API',
		'wcbloat_wp_heartbeat_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-api-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_wp_heartbeat_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WordPress REST API PRO
	add_settings_field(
		'wcbloat_wp_rest_api_disable',
		'REST API',
		'wcbloat_wp_rest_api_disable_callback',
		'wcbloat-wpcore',
		'wcbloat-wpcore-api-section'
	);
	register_setting(
		'wcbloat-wpcore-options',
		'wcbloat_wp_rest_api_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

}

// Display the fields added before
add_action('admin_init', 'wcbloat_wpcore_settings_init');

// Fields callbacks

// Section's icon
function wcbloat_wpcore_icon_callback()
{
	_e('<span class="dashicons dashicons-code-standards"></span>');
}

// First section with title description
function wcbloat_wpcore_desc_callback()
{
	_e('WordPress by default comes with a lot of powerful features. In fact, you will probably never use some of them. Disabling them will not only improve performance but will also give your site a higher security level. Disabling them can prevent attacks and make your WordPress site and admin panel faster.<hr />', 'disable-dashboard-for-woocommerce');
}

// Updates section
function wcbloat_updates_section_callback()
{
	_e('Keeping your website updated is important. But some people prefer to do it manually. In these cases, using a built-in update system is not recommended, as it is highly resource-consuming.	', 'disable-dashboard-for-woocommerce');
	_e ( wcbloat_buy_pro_bar() );
}

// Themes auto-updates
function wcbloat_themes_auto_update_disable_callback()
{
	$value = get_option('wcbloat_themes_auto_update_disable');
?>
	<input type='hidden' name='wcbloat_themes_auto_update_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_themes_auto_update_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable themes auto-updates', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will disable the automatic updates feature for themes. You will still be able to update the themes by manually clicking the <code>Update</code> button on the Plugins list, but plugins will never be updated automatically.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Plugins auto-updates
function wcbloat_plugins_auto_update_disable_callback()
{
	$value = get_option('wcbloat_plugins_auto_update_disable');
?>
	<input type='hidden' name='wcbloat_plugins_auto_update_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_plugins_auto_update_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable plugins auto-updates', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will disable the automatic updates feature for plugins. You will still be able to update the plugins by manually clicking the <code>Update</code> button on the Plugins list, but plugins will never be updated automatically.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable all WordPress core updates PRO
function wcbloat_wp_core_update_disable_callback()
{
	$value = get_option('wcbloat_wp_core_update_disable');
?>
	<input type='hidden' name='wcbloat_wp_core_update_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_core_update_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable all WordPress core updates', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This allows you to disable all WordPress core updates, including automatic updates. WordPress will not check for core updates and will not notify the users about the update availability.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// First section with title description
function wcbloat_updates_speed_callback()
{
	_e('If you leave some of the core WordPress features active, they may result in a bloated database, lower security level, and lack of website speed optimization. Use the options below to disable some of the core features that are enabled in the default WordPress installation.', 'disable-dashboard-for-woocommerce');
}

// Disable File Editor PRO
function wcbloat_file_editor_disable_callback()
{
	$value = get_option('wcbloat_file_editor_disable');
?>
	<input type='hidden' name='wcbloat_file_editor_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_file_editor_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable File Editor', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will disable the file editing tool in your WordPress admin panel. This disables the plugin and theme editors. Since editing plugin and theme files can be safely done manually over FTP and are not recommended to be done in the admin panel, you can safely disable this feature.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable post revisions PRO
function wcbloat_post_revisions_disable_callback()
{
	$value = get_option('wcbloat_post_revisions_disable');
?>
	<input type='hidden' name='wcbloat_post_revisions_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_post_revisions_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable post revisions', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('By default, WordPress uses post revisions to save each post revision in your database. It cannot be turned off using standard WordPress settings. You may want to disable revisions to reduce the WordPress database size.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable Application Passwords PRO
function wcbloat_app_passwords_disable_callback()
{
	$value = get_option('wcbloat_app_passwords_disable');
?>
	<input type='hidden' name='wcbloat_app_passwords_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_app_passwords_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable Application Passwords', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Application Passwords feature allows external applications to request some permissions on your website. Not every website needs APIs, and granting permission for an external application can lead to security issues. Activating this option will turn the Application Passwords feature off.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Remove script/style version parameter PRO
function wcbloat_remove_script_style_ver_callback()
{
	$value = get_option('wcbloat_remove_script_style_ver');
?>
	<input type='hidden' name='wcbloat_remove_script_style_ver' value='no'>
	<label><input type='checkbox' name='wcbloat_remove_script_style_ver' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Remove script/style version parameter', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Exposing your scripts\' versions to the public may not be a good idea and may be a potential security threat. This option will remove the script/style version parameter for your site front-end.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// API section
function wcbloat_wpcore_api_callback()
{
	_e('WordPress API refers to a set of functions and tools that allow developers to interact with the WordPress platform and its data. You may not need them at all and prefer to turn them off. The WordPress API includes a variety of different APIs:', 'disable-dashboard-for-woocommerce');
}

// Disable Disable XML-RPC API PRO
function wcbloat_xml_rpc_disable_callback()
{
	$value = get_option('wcbloat_xml_rpc_disable');
?>
	<input type='hidden' name='wcbloat_xml_rpc_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_xml_rpc_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable XML-RPC API', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('This option will disable the XML-RPC API functionality. XML-RPC is a specification that enables the communication between WordPress and other systems. If you disable XML-RPC, you will not be able to receive pingbacks and trackbacks.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable WordPress Heartbeat API PRO
function wcbloat_wp_heartbeat_disable_callback()
{
	$value = get_option('wcbloat_wp_heartbeat_disable');
?>
	<input type='hidden' name='wcbloat_wp_heartbeat_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_heartbeat_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable WordPress Heartbeat API', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('The WordPress Heartbeat API is a feature that provides real-time communication between the server and the browser when you are logged into your admin panel. Unfortunately, the AJAX requests from the API can pile up and generate high CPU usage, leading to server performance issues.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable WordPress REST API PRO
function wcbloat_wp_rest_api_disable_callback()
{
	$value = get_option('wcbloat_wp_rest_api_disable');
?>
	<input type='hidden' name='wcbloat_wp_rest_api_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_rest_api_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable WordPress REST API', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Prevent access to the API endpoints that allow external applications to interact with a website. This can be done for security reasons, to prevent unauthorized access or protect sensitive data, or for performance reasons: to reduce server load. <br /><strong>Note:</strong> Disabling the REST API can impact functionality of certain themes and plugins that rely on it.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}
