<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Add a position in Options menu in admin panel
function wcbloat_menu_page()
{
	add_options_page(esc_attr__('Disable Bloat', 'disable-dashboard-for-woocommerce'), esc_attr__('Disable Bloat', 'disable-dashboard-for-woocommerce'), 'manage_options', 'disable-bloat', 'wcbloat_options_page');
}
add_action('admin_menu', 'wcbloat_menu_page');

// Display admin options page
function wcbloat_options_page()
{
?>
	<div class="wrap">
		<h1><?php esc_attr_e('Disable Bloat for WordPress & WooCommerce', 'disable-dashboard-for-woocommerce'); ?></h1>
		<?php $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'main'; ?>
		<h2 class="nav-tab-wrapper">
			<a href="?page=disable-bloat&tab=main" class="nav-tab <?php echo esc_attr($active_tab) == 'main' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('WooCommerce', 'disable-dashboard-for-woocommerce'); ?></a>
			<a href="?page=disable-bloat&tab=admin" class="nav-tab <?php echo esc_attr($active_tab) == 'admin' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Admin panel optimization', 'disable-dashboard-for-woocommerce'); ?></a>
			<a href="?page=disable-bloat&tab=performance" class="nav-tab <?php echo esc_attr($active_tab) == 'performance' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Site performance', 'disable-dashboard-for-woocommerce'); ?></a>
			<a href="?page=disable-bloat&tab=wpcore" class="nav-tab <?php echo esc_attr($active_tab) == 'wpcore' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('WordPress Core', 'disable-dashboard-for-woocommerce'); ?></a>
			<a href="?page=disable-bloat&tab=block" class="nav-tab <?php echo esc_attr($active_tab) == 'block' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Block Editor', 'disable-dashboard-for-woocommerce'); ?></a>
			<a href="?page=disable-bloat&tab=thirdparty" class="nav-tab <?php echo esc_attr($active_tab) == 'thirdparty' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Third-party plugins bloat', 'disable-dashboard-for-woocommerce'); ?></a>
			<a href="?page=disable-bloat&tab=uninstall" class="nav-tab <?php echo esc_attr($active_tab) == 'uninstall' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Plugin data cleanup', 'disable-dashboard-for-woocommerce'); ?></a>


		</h2>
		<div class="disable-bloat-container">
			<form action="options.php" method="POST">
				<?php if ($active_tab == 'main') {
					settings_fields('wcbloat-main-options');
					do_settings_sections('wcbloat-main');
				} elseif ($active_tab == 'admin') {
					settings_fields('wcbloat-admin-options');
					do_settings_sections('wcbloat-admin');
				} elseif ($active_tab == 'performance') {
					settings_fields('wcbloat-performance-options');
					do_settings_sections('wcbloat-performance');
				} elseif ($active_tab == 'wpcore') {
					settings_fields('wcbloat-wpcore-options');
					do_settings_sections('wcbloat-wpcore');
				} elseif ($active_tab == 'block') {
					settings_fields('wcbloat-block-options');
					do_settings_sections('wcbloat-block');
				} elseif ($active_tab == 'thirdparty') {
					settings_fields('wcbloat-thirdparty-options');
					do_settings_sections('wcbloat-thirdparty');
				} elseif ($active_tab == 'uninstall') {
					settings_fields('wcbloat-uninstall-options');
					do_settings_sections('wcbloat-uninstall');
				}
				submit_button(); ?>
			</form>
		</div>
	</div>
<?php
}


// Show a bar on WooCommerce settings screen if WooCommerce is not installed

function wcbloat_woo_not_installed_info_bar()
{
	// Check if WooCommmerce is active
	if (!wcbloat_is_woo_active()) {
		$woo_not_installed_bar =  __('<p class="wcbloat-bar">You need to <strong>activate WooCommerce</strong> to make the options below effective.<hr />', 'disable-dashboard-for-woocommerce');
		return $woo_not_installed_bar;
	}
}

// "Buy PRO" bar
function wcbloat_buy_pro_bar()
{
	if (wcbloat_fs()->is_free_plan()) {
		$buy_pro_bar =  __('<p class="wcbloat-bar">Get <a href="https://disablebloat.com/?utm_source=settings&utm_medium=referral&utm_campaign=Buy+PRO+Bar" target="_blank">Disable Bloat for WordPress & WooCommerce PRO</a> to unlock the<span class="wcbloat-pro-label">PRO</span>options.', 'disable-dashboard-for-woocommerce');
	} else {
		$buy_pro_bar = '';
	}
	return $buy_pro_bar;
}

// Is the current field PRO only
function wcbloat_is_pro_readonly()
{
	if (wcbloat_fs()->is_free_plan()) {
		$is_pro_readonly =  __(' disabled="disabled"');
	} else {
		$is_pro_readonly = '';
	}
	return $is_pro_readonly;
}

// If the field is PRO-only, display a PRO badge
function wcbloat_is_pro_badge()
{
	if (wcbloat_fs()->is_free_plan()) {
		$is_pro_badge =  __('<span class="wcbloat-pro-label">PRO</span>');
	} else {
		$is_pro_badge = '';
	}
	return $is_pro_badge;
}

// Include options pages
require_once 'class-disable-bloat-settings-main.php';
require_once 'class-disable-bloat-settings-admin.php';
require_once 'class-disable-bloat-settings-performance.php';
require_once 'class-disable-bloat-settings-wpcore.php';
require_once 'class-disable-bloat-settings-block.php';
require_once 'class-disable-bloat-settings-thirdparty.php';
require_once 'class-disable-bloat-settings-uninstall.php';


// Remove top-level plugin menu. Fix of a Freemius-related bug, which caused adding unnecessary admin menu (multisite only)

add_action('admin_head', 'wcbloat_remove_multisite_plugin_menu');
function wcbloat_remove_multisite_plugin_menu()
{
	remove_menu_page('disable-dashboard-for-woocommerce');
}
