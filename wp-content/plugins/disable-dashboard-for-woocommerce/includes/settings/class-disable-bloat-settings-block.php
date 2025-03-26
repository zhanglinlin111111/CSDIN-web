<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add sections and fields on Main settings screen
function wcbloat_block_settings_init()
{

	// Section's icon
	add_settings_section(
		'wcbloat-block-desc-icon-section',
		'',
		'wcbloat_block_desc_icon_callback',
		'wcbloat-block'
	);

	// Section's title and description
	add_settings_section(
		'wcbloat-block-desc-section',
		esc_attr__('Block Editor', 'disable-dashboard-for-woocommerce'),
		'wcbloat_block_desc_callback',
		'wcbloat-block'
	);

	// Disable Gutenberg section
	add_settings_section(
		'wcbloat_disable_gutenberg_section',
		esc_attr__('Disable Gutenberg', 'disable-dashboard-for-woocommerce'),
		'wcbloat_disable_gutenberg_section_callback',
		'wcbloat-block'
	);

	// Gutenberg
	add_settings_field(
		'wcbloat_disable_gutenberg',
		'Hide update notice for non-admin users',
		'wcbloat_disable_gutenberg_callback',
		'wcbloat-block',
		'wcbloat_disable_gutenberg_section'
	);
	register_setting(
		'wcbloat-block-options',
		'wcbloat_disable_gutenberg',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable Gutenberg features
	add_settings_section(
		'wcbloat-gutenberg-features-section',
		esc_attr__('Disable Gutenberg features', 'disable-dashboard-for-woocommerce'),
		'wcbloat_gutenberg_features_callback',
		'wcbloat-block'
	);

	// Auto-close Welcome Guide PRO
	add_settings_field(
		'wcbloat_autoclose_welcome_guide',
		'Welcome Guide',
		'wcbloat_autoclose_welcome_guide_callback',
		'wcbloat-block',
		'wcbloat-gutenberg-features-section'
	);
	register_setting(
		'wcbloat-block-options',
		'wcbloat_autoclose_welcome_guide',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable the WordPress Block Directory PRO
	add_settings_field(
		'wcbloat_disable_block_directory',
		'WordPress Block Directory',
		'wcbloat_disable_block_directory_callback',
		'wcbloat-block',
		'wcbloat-gutenberg-features-section'
	);
	register_setting(
		'wcbloat-block-options',
		'wcbloat_disable_block_directory',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Remove the default block patterns from WordPress PRO
	add_settings_field(
		'wcbloat_disable_default_block_patterns',
		'Remove the default block patterns',
		'wcbloat_disable_default_block_patterns_callback',
		'wcbloat-block',
		'wcbloat-gutenberg-features-section'
	);
	register_setting(
		'wcbloat-block-options',
		'wcbloat_disable_default_block_patterns',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Auto-exit the Fullscreen Mode on editor load PRO
	add_settings_field(
		'wcbloat_disable_fullscreen_editor_mode',
		'Auto-exit the Fullscreen Mode on editor load',
		'wcbloat_disable_fullscreen_editor_mode_callback',
		'wcbloat-block',
		'wcbloat-gutenberg-features-section'
	);
	register_setting(
		'wcbloat-block-options',
		'wcbloat_disable_fullscreen_editor_mode',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Deactivate the Template Editor PRO
	add_settings_field(
		'wcbloat_disable_template_editor',
		'Deactivate the Template Editor',
		'wcbloat_disable_template_editor_callback',
		'wcbloat-block',
		'wcbloat-gutenberg-features-section'
	);
	register_setting(
		'wcbloat-block-options',
		'wcbloat_disable_template_editor',
		array('sanitize_callback' => 'sanitize_key')
	);

}

// Display the fields added before
add_action('admin_init', 'wcbloat_block_settings_init');

// Fields callbacks

// Section's icon
function wcbloat_block_desc_icon_callback()
{
	_e('<span class="dashicons dashicons-block-default"></span>');
}

// Description
function wcbloat_block_desc_callback()
{
	_e('Using Block Editor can significantly slow down your page during editing posts and while browsing through your site. You can decide to either turn Gutenberg (WordPress built-in Block Editor) off completely or keep it on and only disable unnecessary features.<hr>', 'disable-dashboard-for-woocommerce');
}

// Disable Gutenberg
function wcbloat_disable_gutenberg_section_callback()
{
	_e('By enabling this option, you will turn off Gutenberg Block Editor for all supported post types (including pages). All Gutenberg action and filter hooks will be deactivated, and Gutenberg frontend scripts won’t load on your site too.', 'disable-dashboard-for-woocommerce');
}

// Gutenberg
function wcbloat_disable_gutenberg_callback()
{
	$value = get_option('wcbloat_disable_gutenberg');
?>
	<input type='hidden' name='wcbloat_disable_gutenberg' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_gutenberg' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable Gutenberg', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('By enabling this option, you will turn off Gutenberg Block Editor for all supported post types (including pages). All Gutenberg action and filter hooks will be deactivated, and Gutenberg frontend scripts won\'t load on your site too.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable Gutenberg features section
function wcbloat_gutenberg_features_callback()
{
	_e('If you decide to keep Gutenberg on, you can at least maximize your experience by tweaking its features to match your needs.', 'disable-dashboard-for-woocommerce');
}

// Auto-close Welcome Guide PRO
function wcbloat_autoclose_welcome_guide_callback()
{
	$value = get_option('wcbloat_autoclose_welcome_guide');
?>
	<input type='hidden' name='wcbloat_autoclose_welcome_guide' value='no'>
	<label><input type='checkbox' name='wcbloat_autoclose_welcome_guide' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Auto-close Welcome Guide', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Turn off the annoying pop-up Welcome Guide Modal. Users will still be able to open it manually via the context menu.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable the WordPress Block Directory PRO
function wcbloat_disable_block_directory_callback()
{
	$value = get_option('wcbloat_disable_block_directory');
?>
	<input type='hidden' name='wcbloat_disable_block_directory' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_block_directory' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable WordPress Block Directory', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('As Blocks are in fact plugins, it\'s a potential security threat to use Block Directory. New Blocks are added to the Directory automatically, without human review.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Remove the default block patterns from WordPress PRO
function wcbloat_disable_default_block_patterns_callback()
{
	$value = get_option('wcbloat_disable_default_block_patterns');
?>
	<input type='hidden' name='wcbloat_disable_default_block_patterns' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_default_block_patterns' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Remove the default block patterns', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Patterns is a feature in the Block editor that lets you save and reuse patterns of many blocks. It is similar to reusable blocks, but they are not global by default.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Auto-exit the Fullscreen Mode on editor load PRO
function wcbloat_disable_fullscreen_editor_mode_callback()
{
	$value = get_option('wcbloat_disable_fullscreen_editor_mode');
?>
	<input type='hidden' name='wcbloat_disable_fullscreen_editor_mode' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_fullscreen_editor_mode' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Auto-exit the Fullscreen Mode on editor load', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Users will still be able to enter it manually via the context menu.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Deactivate the Template Editor PRO
function wcbloat_disable_template_editor_callback()
{
	$value = get_option('wcbloat_disable_template_editor');
?>
	<input type='hidden' name='wcbloat_disable_template_editor' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_template_editor' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Deactivate the Template Editor', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('The Template Editor allows users to edit their theme\'s templates from within the Block Editor. Block themes might overwrite this setting.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}