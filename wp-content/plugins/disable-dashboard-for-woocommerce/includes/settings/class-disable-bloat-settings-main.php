<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add sections and fields on Main settings screen
function wcbloat_main_settings_init()
{
	// Section's icon
	add_settings_section(
		'wcbloat-main-desc-icon-section',
		'',
		'wcbloat_main_desc_icon_callback',
		'wcbloat-main'
	);

	// Section's title and description
	add_settings_section(
		'wcbloat-main-desc-section',
		esc_attr__('Disable WooCommerce Bloat', 'disable-dashboard-for-woocommerce'),
		'wcbloat_main_desc_callback',
		'wcbloat-main'
	);

	// WooCommerce Admin section
	add_settings_section(
		'wcbloat-main-wooadmin-section',
		esc_attr__('WooCommerce Admin', 'disable-dashboard-for-woocommerce'),
		'wcbloat_main_wooadmin_section_title_callback',
		'wcbloat-main'
	);

	// WooCommerce Admin, Analytics tab, Notification bar
	add_settings_field(
		'wcbloat_admin_disable',
		'WooCommerce Admin',
		'wcbloat_main_admin_disable_callback',
		'wcbloat-main',
		'wcbloat-main-wooadmin-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_admin_disable',
		'validate_setting'
	);

	// WooCommerce Admin particular features
	add_settings_field(
		'wcbloat_admin_disable_features',
		'Disable WooCommerce Admin features',
		'wcbloat_admin_disable_features_callback',
		'wcbloat-main',
		'wcbloat-main-wooadmin-section',
		array(
			'class' => 'wcbloat_admin_disable_features'
		) 
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_admin_disable_features',
		'validate_setting'
	);

	// Marketing Hub
	add_settings_field(
		'wcbloat_marketing_disable',
		'Marketing Hub',
		'wcbloat_main_marketing_disable_callback',
		'wcbloat-main',
		'wcbloat-main-wooadmin-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_marketing_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// WooCommerce promotions section
	add_settings_section(
		'wcbloat-woo-promotions-section',
		esc_attr__('WooCommerce promotions', 'disable-dashboard-for-woocommerce'),
		'wcbloat_woo_promotions_callback',
		'wcbloat-main'
	);
	
	// WooCommerce.com notice
	add_settings_field(
		'wcbloat_wc_helper_disable',
		'WooCommerce.com notice',
		'wcbloat_wc_helper_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-promotions-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_helper_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// WooCommerce.com notice
	add_settings_field(
		'wcbloat_wc_helper_disable',
		'WooCommerce.com notice',
		'wcbloat_wc_helper_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-promotions-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_helper_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable Marketplace Suggestions
	add_settings_field(
		'wcbloat_wc_marketplace',
		'Marketplace Suggestions',
		'wcbloat_wc_marketplace_callback',
		'wcbloat-main',
		'wcbloat-woo-promotions-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_marketplace',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable Extensions & My Subscriptions submenu
	add_settings_field(
		'wcbloat_remove_addon_submenu',
		'Extensions & My Subscriptions submenus',
		'wcbloat_remove_addon_submenu_callback',
		'wcbloat-main',
		'wcbloat-woo-promotions-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_remove_addon_submenu',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable Recommended shipping solutions metabox
	add_settings_field(
		'woocommerce_settings_shipping_recommendations_hidden',
		'Recommended shipping solutions metabox',
		'woocommerce_settings_shipping_recommendations_hidden_callback',
		'wcbloat-main',
		'wcbloat-woo-promotions-section'
	);
	register_setting(
		'wcbloat-main-options',
		'woocommerce_settings_shipping_recommendations_hidden',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable Recommended payments solutions metabox
	add_settings_field(
		'woocommerce_setting_payments_recommendations_hidden',
		'Recommended payments solutions metabox',
		'woocommerce_setting_payments_recommendations_hidden_callback',
		'wcbloat-main',
		'wcbloat-woo-promotions-section'
	);
	register_setting(
		'wcbloat-main-options',
		'woocommerce_setting_payments_recommendations_hidden',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Hide Discover other payment providers link on the WooCommerce Settings Payments screen
	add_settings_field(
		'wcbloat_hide_payment_providers',
		'Hide marketplace link on the Payments Settings screen',
		'wcbloat_hide_payment_providers_callback',
		'wcbloat-main',
		'wcbloat-woo-promotions-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_hide_payment_providers',
		array('sanitize_callback' => 'sanitize_key')
	);

	// WooCommerce emails section
	add_settings_section(
		'wcbloat-woo-emails-section',
		esc_attr__('WooCommerce emails', 'disable-dashboard-for-woocommerce'),
		'wcbloat_woo_emails_callback',
		'wcbloat-main'
	);

	// Disable WooCommerce guide email notifications
	add_settings_field(
		'wcbloat_woo_merchant_email_notifications',
		'Disable WooCommerce guide emails',
		'wcbloat_woo_merchant_email_notifications_callback',
		'wcbloat-main',
		'wcbloat-woo-emails-section'
	);

	// Disable WooCommerce Footer email mobile text PRO
	add_settings_field(
		'wcbloat_hide_woo_mobile_footer_text',
		'Remove the <i>Get the app</i> footer from emails',
		'wcbloat_hide_woo_mobile_footer_text_callback',
		'wcbloat-main',
		'wcbloat-woo-emails-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_hide_woo_mobile_footer_text',
		array('sanitize_callback' => 'sanitize_key')
	);

	// WooCommerce back-end scripts section
	add_settings_section(
		'wcbloat-woo-backend-scripts-section',
		esc_attr__('WooCommerce back-end scripts', 'disable-dashboard-for-woocommerce'),
		'wcbloat_woo_backend_scripts_section_callback',
		'wcbloat-main'
	);

	// Disable WooCommerce Status Meta Box
	add_settings_field(
		'wcbloat_wc_status_meta_box_disable',
		'WooCommerce Status Meta Box',
		'wcbloat_wc_status_meta_box_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-backend-scripts-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_status_meta_box_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WooCommerce Dashboard Setup Widget
	add_settings_field(
		'disable_admin_dashboard_setup_widget',
		'WooCommerce Dashboard Setup Widget',
		'wcbloat_disable_admin_dashboard_setup_widget_callback',
		'wcbloat-main',
		'wcbloat-woo-backend-scripts-section'
	);
	register_setting(
		'wcbloat-main-options',
		'disable_admin_dashboard_setup_widget',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WooCommerce Blocks (back-end) PRO
	add_settings_field(
		'wcbloat_wc_blocks_backend_disable',
		'WooCommerce Blocks (back-end)',
		'wcbloat_wc_blocks_backend_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-backend-scripts-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_blocks_backend_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WooCommerce Blocks (back-end) PRO
	add_settings_field(
		'wcbloat_wc_blocks_backend_disable',
		'WooCommerce Blocks (back-end)',
		'wcbloat_wc_blocks_backend_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-backend-scripts-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_blocks_backend_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Your Store's Front-end
	add_settings_section(
		'wcbloat-woo-frontend-section',
		esc_attr__('Your Store\'s Front-end', 'disable-dashboard-for-woocommerce'),
		'wcbloat_woo_frontend_section_callback',
		'wcbloat-main'
	);

	// Disable WooCommerce Widgets
	add_settings_field(
		'wcbloat_wc_widgets_disable',
		'WooCommerce Widgets',
		'wcbloat_wc_widgets_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-frontend-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_widgets_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WooCommerce Scripts and Styles
	add_settings_field(
		'wcbloat_wc_scripts_disable',
		'WooCommerce scripts and styles',
		'wcbloat_wc_scripts_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-frontend-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_scripts_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WooCommerce Cart Fragments
	add_settings_field(
		'wcbloat_wc_fragmentation_disable',
		'WooCommerce Cart Fragments',
		'wcbloat_wc_fragmentation_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-frontend-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_fragmentation_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable WooCommerce Blocks (front-end) PRO
	add_settings_field(
		'wcbloat_wc_blocks_frontend_disable',
		'WooCommerce Blocks (front-end)',
		'wcbloat_wc_blocks_frontend_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-frontend-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_blocks_frontend_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

	// Disable unnecessary Stripe scripts PRO
	add_settings_field(
		'wcbloat_wc_stripe_scripts_disable',
		'Stripe scripts',
		'wcbloat_wc_stripe_scripts_disable_callback',
		'wcbloat-main',
		'wcbloat-woo-frontend-section'
	);
	register_setting(
		'wcbloat-main-options',
		'wcbloat_wc_stripe_scripts_disable',
		array('sanitize_callback' => 'sanitize_key')
	);

}

// Display the fields added before
add_action('admin_init', 'wcbloat_main_settings_init');

// Fields callbacks

// Section's icon
function wcbloat_main_desc_icon_callback()
{
	_e('<span class="dashicons dashicons-performance"></span>');
}

// First section with title description
function wcbloat_main_desc_callback()
{
	_e('<p>Using options from this tab, you will be able to turn off general WooCommerce functionalities which are not required to run your store. These features cannot be disabled using standard WooCommerce configuration options.</p>', 'disable-dashboard-for-woocommerce');
	echo wcbloat_woo_not_installed_info_bar();
}

// WooCommerce Admin section description
function wcbloat_main_wooadmin_section_title_callback()
{
	_e('<p>WooCommerce Admin is a built-in feature in WooCommerce. It is a javascript-driven dashboard. WooCommerce Admin\'s features continue to become increasingly blended into the merchant experience in WooCommerce. It can cause performance issues and slow down your website. Disabling the feature can help speed up your website and improve the user experience for your customers. Using the options below, you can effectively disable WooCommerce Admin, Analytics, Home screen and other features that are making your admin panel slower.</p>', 'disable-dashboard-for-woocommerce');
}


// Disable WooCommerce Admin completely or specific features - radio 
function wcbloat_main_admin_disable_callback()
{
	$value = get_option('wcbloat_admin_disable', 'yes');
?>	
<div class="wcbloat-wcadmin-radio">
	<label><input type='radio' name='wcbloat_admin_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Admin completely', 'disable-dashboard-for-woocommerce'); ?></label><br />
	<label><input type='radio' name='wcbloat_admin_disable' <?php checked(esc_attr($value), 'disable-wc-admin-features'); ?> value='disable-wc-admin-features'> <?php esc_attr_e('Choose which WooCommerce Admin features to disable', 'disable-dashboard-for-woocommerce'); ?></label><br />
	<label><input type='radio' name='wcbloat_admin_disable' <?php checked(esc_attr($value), 'no'); ?> value='no'> <?php esc_attr_e('Keep WooCommerce Admin enabled (do nothing)', 'disable-dashboard-for-woocommerce'); ?></label>
</div>

	<p><?php _e('You can either completely disable the whole WooCommerce Admin (Analytics menu, Notification bar, Home screen and more) or choose to keep it enabled, but disable its particular features. If you choose the third option, no changes will be applied.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Choose which specific WooCommerce Admin features to disable
function wcbloat_admin_disable_features_callback()
{
	$options = get_option('wcbloat_admin_disable_features');	
	?>
	<div class="wcbloat-select-unselect-buttons">
	<a id="select-all" class="button button-secondary"><?php esc_attr_e('Select / Deselect All', 'disable-dashboard-for-woocommerce'); ?></a>
	</div>
<table id="wcbloat-multi-checkbox-table">
<tbody>
  <tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('activity-panels', $options)) { esc_attr_e('checked'); }} ?> value='activity-panels'><?php esc_attr_e('Activity panels', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('analytics', $options)) { esc_attr_e('checked'); }} ?> value='analytics'><?php esc_attr_e('Analytics', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('coupons', $options)) { esc_attr_e('checked'); }} ?> value='coupons'><?php esc_attr_e('Coupons', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('customer-effort-score-tracks', $options)) { esc_attr_e('checked'); }} ?> value='customer-effort-score-tracks'><?php esc_attr_e('Customer Effort Score', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('import-products-task', $options)) { esc_attr_e('checked'); }} ?> value='import-products-task'><?php esc_attr_e('Import sample products', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('experimental-fashion-sample-products', $options)) { esc_attr_e('checked'); }} ?> value='experimental-fashion-sample-products'><?php esc_attr_e('Use new sample products CSV file', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('shipping-smart-defaults', $options)) { esc_attr_e('checked'); }} ?> value='shipping-smart-defaults'><?php esc_attr_e('Shipping smart defaults', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('shipping-setting-tour', $options)) { esc_attr_e('checked'); }} ?> value='shipping-setting-tour'><?php esc_attr_e('Shipping setting tour', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('homescreen', $options)) { esc_attr_e('checked'); }} ?> value='homescreen'><?php esc_attr_e('Homescreen', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('marketing', $options)) { esc_attr_e('checked'); }} ?> value='marketing'><?php esc_attr_e('Marketing', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('multichannel-marketing', $options)) { esc_attr_e('checked'); }} ?> value='multichannel-marketing'><?php esc_attr_e('Multichannel Marketing', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('minified-js', $options)) { esc_attr_e('checked'); }} ?> value='minified-js'><?php esc_attr_e('Minified JS', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('mobile-app-banner', $options)) { esc_attr_e('checked'); }} ?> value='mobile-app-banner'><?php esc_attr_e('Mobile App Banner', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('navigation', $options)) { esc_attr_e('checked'); }} ?> value='navigation'><?php esc_attr_e('Navigation', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('new-product-management-experience', $options)) { esc_attr_e('checked'); }} ?> value='new-product-management-experience'><?php esc_attr_e('New product editor', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('onboarding', $options)) { esc_attr_e('checked'); }} ?> value='onboarding'><?php esc_attr_e('Onboarding', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('onboarding-tasks', $options)) { esc_attr_e('checked'); }} ?> value='onboarding-tasks'><?php esc_attr_e('Onboarding tasks', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('product-variation-management', $options)) { esc_attr_e('checked'); }} ?> value='product-variation-management'><?php esc_attr_e('Product variation management', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('remote-inbox-notifications', $options)) { esc_attr_e('checked'); }} ?> value='remote-inbox-notifications'><?php esc_attr_e('Remote inbox notifications', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('remote-free-extensions', $options)) { esc_attr_e('checked'); }} ?> value='remote-free-extensions'><?php esc_attr_e('Remote free extensions', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('payment-gateway-suggestions', $options)) { esc_attr_e('checked'); }} ?> value='payment-gateway-suggestions'><?php esc_attr_e('Payment gateway suggestions', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
	<td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('settings', $options)) { esc_attr_e('checked'); }} ?> value='settings'><?php esc_attr_e('Settings', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('shipping-label-banner', $options)) { esc_attr_e('checked'); }} ?> value='shipping-label-banner'><?php esc_attr_e('Shipping label banner', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('subscriptions', $options)) { esc_attr_e('checked'); }} ?> value='subscriptions'><?php esc_attr_e('Subscriptions', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
	<td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('store-alerts', $options)) { esc_attr_e('checked'); }} ?> value='store-alerts'><?php esc_attr_e('Store alerts', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('transient-notices', $options)) { esc_attr_e('checked'); }} ?> value='transient-notices'><?php esc_attr_e('Transient notices', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('woo-mobile-welcome', $options)) { esc_attr_e('checked'); }} ?> value='woo-mobile-welcome'><?php esc_attr_e('Woo mobile welcome', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
<tr>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('wc-pay-promotion', $options)) { esc_attr_e('checked'); }} ?> value='wc-pay-promotion'><?php esc_attr_e('WooCommerce Payments promotion', 'disable-dashboard-for-woocommerce'); ?></label></td>
    <td class="wcbloat-admin-disable-features-multi-checkbox"><label><input type='checkbox' name='wcbloat_admin_disable_features[]' <?php if (!empty($options)) { if (in_array('wc-pay-welcome-page', $options)) { esc_attr_e('checked'); }} ?> value='wc-pay-welcome-page'><?php esc_attr_e('WooCommerce Payments welcome page', 'disable-dashboard-for-woocommerce'); ?></label></td>
</tr>
</tbody>
</table>
	<p><?php _e('Choose which of the WooCommerce Admin features should be disabled. The ones you choose will be <strong>disabled</strong>, and the ones that had not been selected will stay <strong>active</strong>.', 'disable-dashboard-for-woocommerce'); ?></p>

	<?php
}

// Marketing Hub
function wcbloat_main_marketing_disable_callback()
{
	$value = get_option('wcbloat_marketing_disable', 'yes');
?>
	<input type='hidden' name='wcbloat_marketing_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_marketing_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable Marketing', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will completely disable WooCommerce Marketing Hub. Coupon menu entry will stay accessible the old way (WooCommerce -> Coupons).', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WooCommerce promotions section description
function wcbloat_woo_promotions_callback()
{
	_e('WooCommerce is constantly trying to promote and advertise add-ons by adding nags to your admin panel. Now you can turn off or hide them.', 'disable-dashboard-for-woocommerce');
}

// Connect your store to WooCommerce.com to receive extensions updates and support. message for WooCommerce.com plugins
function wcbloat_wc_helper_disable_callback()
{
	$value = get_option('wcbloat_wc_helper_disable', 'yes');
?>
	<input type='hidden' name='wcbloat_wc_helper_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_helper_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce.com notice', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Disables notice from WooCommerce.com plugins: <code>Connect your store to WooCommerce.com to receive extensions updates and support</code>.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable Marketplace Suggestions
function wcbloat_wc_marketplace_callback()
{
	$value = get_option('wcbloat_wc_marketplace');
?>
	<input type='hidden' name='wcbloat_wc_marketplace' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_marketplace' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Marketplace Suggestions', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('This option will disable Marketplace Suggestions. Suggestions are visible on the product edit page and on the Orders pages.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable Extensions & My Subscriptions submenus
function wcbloat_remove_addon_submenu_callback()
{
	$value = get_option('wcbloat_remove_addon_submenu');
?>
	<input type='hidden' name='wcbloat_remove_addon_submenu' value='no'>
	<label><input type='checkbox' name='wcbloat_remove_addon_submenu' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable Extensions & My Subscriptions submenus', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Hide Extensions & My Subscriptions submenus in the WooCommerce menu in your admin panel menu. <strong>Reload the page after saving changes to see the results.</strong>', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Recommended shipping solutions metabox
function woocommerce_settings_shipping_recommendations_hidden_callback()
{
	$value = get_option('woocommerce_settings_shipping_recommendations_hidden');
?>
	<input type='hidden' name='woocommerce_settings_shipping_recommendations_hidden' value='no'>
	<label><input type='checkbox' name='woocommerce_settings_shipping_recommendations_hidden' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable Recommended shipping solutions metabox', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Recommended shipping solutions metabox is appearing on Shipping configurations pages in WooCommerce and is primarily used for advertisement.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Recommended payments solutions metabox
function woocommerce_setting_payments_recommendations_hidden_callback()
{
	$value = get_option('woocommerce_setting_payments_recommendations_hidden');
?>
	<input type='hidden' name='woocommerce_setting_payments_recommendations_hidden' value='no'>
	<label><input type='checkbox' name='woocommerce_setting_payments_recommendations_hidden' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable Recommended payments solutions metabox', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Recommended payments solutions metabox is appearing on Payments configurations pages in WooCommerce and is primarily used for advertisement.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Hide Discover other payment providers link on the WooCommerce Settings Payments screen
function wcbloat_hide_payment_providers_callback()
{
	$value = get_option('wcbloat_hide_payment_providers');
?>
	<input type='hidden' name='wcbloat_hide_payment_providers' value='no'>
	<label><input type='checkbox' name='wcbloat_hide_payment_providers' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Hide Discover other payment providers link on the Payments Settings screen
', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('<i>Discover other payment providers</i> link is displayed below your payment gateways and directs the user to the external marketplace with paid extensions.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WooCommerce emails section description
function wcbloat_woo_emails_callback()
{
	_e('The company behind WooCommerce actively promotes its add-ons, plugins, and extensions. They also affect the emails you receive from your webshop. This behavior can be seen as annoying or nagging by some users. Use the option below to stay balanced.', 'disable-dashboard-for-woocommerce');
	_e ( wcbloat_buy_pro_bar() );
}

// Disable WooCommerce footer email mobile app nag PRO
function wcbloat_hide_woo_mobile_footer_text_callback()

{
	$value = get_option('wcbloat_hide_woo_mobile_footer_text');
?>
	<input type='hidden' name='wcbloat_hide_woo_mobile_footer_text' value='no'>
	<label><input type='checkbox' name='wcbloat_hide_woo_mobile_footer_text' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Remove the "Get the app" from WooCommerce emails footer', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('WooCommerce is adding its own prompts to the footer of emails sent to the shop\'s admin. Enable this option to get rid of the annoying text in your emails.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable WooCommerce guide email notifications - works opposite - default option is CHECKED! There is no function to handle this, as it is built into WooCommerce core
function wcbloat_woo_merchant_email_notifications_callback()

{
	$value = get_option('woocommerce_merchant_email_notifications', 'no');
?>
	<input type='hidden' name='woocommerce_merchant_email_notifications' value='yes'>
	<label><input type='checkbox' name='woocommerce_merchant_email_notifications' <?php checked(esc_attr($value), 'no'); ?> value='no'> <?php esc_attr_e('Disable WooCommerce guide email notifications', 'disable-dashboard-for-woocommerce');  ?></label>
	<p><?php _e('WooComerce sends email notifications with additional guidance to complete the Store Setup. Enable this option to block this behavior and stop sending these emails.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WooCommerce back-end scripts section description
function wcbloat_woo_backend_scripts_section_callback()
{
	_e( 'Speed up your site by turning off unwanted scripts that are being loaded in the background in admin panel.', 'disable-dashboard-for-woocommerce');
}

// Disable WooCommerce Status Meta Box
function wcbloat_wc_status_meta_box_disable_callback()
{
	$value = get_option('wcbloat_wc_status_meta_box_disable');
?>
	<input type='hidden' name='wcbloat_wc_status_meta_box_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_status_meta_box_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Status Meta Box', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Enabling this option will remove WooCommerce Status Meta Box from WordPress Dashboard.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable Dashboard Setup Widget
function wcbloat_disable_admin_dashboard_setup_widget_callback()
{
	$value = get_option('disable_admin_dashboard_setup_widget');
?>
	<input type='hidden' name='disable_admin_dashboard_setup_widget' value='no'>
	<label><input type='checkbox' name='disable_admin_dashboard_setup_widget' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Dashboard Setup Widget', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Enabling this option will remove WooCommerce Dashboard Setup Widget from WordPress Dashboard.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable WooCommerce Blocks (back-end) PRO
function wcbloat_wc_blocks_backend_disable_callback()
{
	$value = get_option('wcbloat_wc_blocks_backend_disable');
?>
	<input type='hidden' name='wcbloat_wc_blocks_backend_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_blocks_backend_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Blocks (back-end)', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Activating this option will disable WooCommerce Blocks scripts from loading in the admin panel. The WordPress Block Editor will load and run faster, but WooCommerce Blocks won\'t be available to add from the Block Editor level.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Your Store\'s Front-end section description
function wcbloat_woo_frontend_section_callback()
{
	_e('By default, quite a lot of scripts and styles are automatically loading while browsing the front-end of your shop. Use the options below to disable them.', 'disable-dashboard-for-woocommerce');
}

// Disable WooCommerce Widgets
function wcbloat_wc_widgets_disable_callback()
{
	$value = get_option('wcbloat_wc_widgets_disable');
?>
	<input type='hidden' name='wcbloat_wc_widgets_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_widgets_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Widgets', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('WooCommerce by default comes with a lot of widgets installed. They often are not used at all, but can add backend load and front-end load. Use this option to disable the WooCommerce widgets. <strong>Warning: </strong>Please make sure that you are not using any of WooCommerce Widgets anywhere in your site.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WooCommerce scripts and styles
function wcbloat_wc_scripts_disable_callback()
{
	$value = get_option('wcbloat_wc_scripts_disable');
?>
	<input type='hidden' name='wcbloat_wc_scripts_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_scripts_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce scripts and styles', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('Use this option to disable WooCommerce scripts (javascript) and styles (CSS) everywhere except on product, cart and checkout pages.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WooCommerce Cart Fragments
function wcbloat_wc_fragmentation_disable_callback()
{
	$value = get_option('wcbloat_wc_fragmentation_disable');
?>
	<input type='hidden' name='wcbloat_wc_fragmentation_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_fragmentation_disable' <?php checked(esc_attr($value), 'yes'); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Cart Fragments', 'disable-dashboard-for-woocommerce'); ?></label>
	<p><?php _e('The cart fragments feature is used to update the cart total without refreshing the page. <strong>Warning:</strong> Disabling it will speed up your store, but may result in wrong calculations in mini cart. Use with caution.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// WooCommerce Blocks (front-end) PRO
function wcbloat_wc_blocks_frontend_disable_callback()
{
	$value = get_option('wcbloat_wc_blocks_frontend_disable');
?>
	<input type='hidden' name='wcbloat_wc_blocks_frontend_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_blocks_frontend_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable WooCommerce Blocks (front-end)', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('WooCommerce Blocks module loads tons of CSS files (200 KB) in your site front-end. Activating this option will turn off loading the WooCommerce Blocks in your store and should improve page loading speed. If you are using any of the Blocks, they won\'t display after enabling this option.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}

// Disable unnecessary Stripe scripts PRO
function wcbloat_wc_stripe_scripts_disable_callback()
{
	$value = get_option('wcbloat_wc_stripe_scripts_disable');
?>
	<input type='hidden' name='wcbloat_wc_stripe_scripts_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wc_stripe_scripts_disable' <?php checked(esc_attr($value), 'yes'); echo wcbloat_is_pro_readonly(); ?> value='yes'> <?php esc_attr_e('Disable unnecessary Stripe scripts', 'disable-dashboard-for-woocommerce'); echo wcbloat_is_pro_badge(); ?></label>
	<p><?php _e('Enabling this option will disable Stripe Payment Request Button on all product pages. The Stripe scripts won\'t be loaded on the product pages so the pages should be loading faster.', 'disable-dashboard-for-woocommerce'); ?></p>
<?php
}
