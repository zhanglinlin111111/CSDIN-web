<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add sections and fields on Main settings screen
function wcbloat_uninstall_settings_init()
{

	// Section's icon
	add_settings_section(
		'wcbloat-uninstall-icon-section',
		'',
		'wcbloat_uninstall_icon_callback',
		'wcbloat-uninstall'
	);

	// Section's title and description
	add_settings_section(
		'wcbloat-site-uninstall-desc-section',
		esc_attr__('Plugin data cleanup', 'disable-dashboard-for-woocommerce'),
		'wcbloat_uninstall_desc_callback',
		'wcbloat-uninstall'
	);

	// Uninstall setting
	add_settings_field(
		'wcbloat_uninstall_cleanup',
		'Plugin data cleanup during uninstallation',
		'wcbloat_uninstall_cleanup_callback',
		'wcbloat-uninstall',
		'wcbloat-site-uninstall-desc-section'
	);
	register_setting(
		'wcbloat-uninstall-options',
		'wcbloat_uninstall_cleanup',
		array('sanitize_callback' => 'sanitize_key')
	);

}

// Display the fields added before
add_action('admin_init', 'wcbloat_uninstall_settings_init');

// Fields callbacks

// Section's icon
function wcbloat_uninstall_icon_callback()
{
	_e('<span class="dashicons dashicons-trash"></span>');
}

// First section with title description
function wcbloat_uninstall_desc_callback()
{
	_e('If you decide to uninstall the plugin using the standard WordPress <i>Delete plugin</i> option, you can automatically erase the plugin settings from the database.<hr />', 'disable-dashboard-for-woocommerce');
}

// Plugin data cleanup during uninstallation
function wcbloat_uninstall_cleanup_callback()
{
	$value = get_option('wcbloat_uninstall_cleanup');
?>
	<input type='hidden' name='wcbloat_uninstall_cleanup' value='no'>
	<label><input type='checkbox' name='wcbloat_uninstall_cleanup' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Clean plugin settings while deleting the plugin', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Clear plugin data from the database during the uninstallation.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}
