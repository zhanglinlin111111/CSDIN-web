<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
// Add sections and fields on Main settings screen
function wcbloat_admin_settings_init()
{
    // Section's icon
    add_settings_section(
        'wcbloat-admin-desc-icon-section',
        '',
        'wcbloat_admin_desc_icon_callback',
        'wcbloat-admin'
    );
    // Section's title and description
    add_settings_section(
        'wcbloat-admin-desc-section',
        esc_attr__( 'Admin panel optimization', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_admin_desc_callback',
        'wcbloat-admin'
    );
    // Clean admin interface section
    add_settings_section(
        'wcbloat_clean_admin_section',
        esc_attr__( 'Clean admin interface', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_clean_admin_desc_section_callback',
        'wcbloat-admin'
    );
    // Hide update notice for non-admin users
    add_settings_field(
        'wcbloat_wp_update_nag_disable',
        'Hide update notice for non-admin users',
        'wcbloat_wp_update_nag_disable_callback',
        'wcbloat-admin',
        'wcbloat_clean_admin_section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_wp_update_nag_disable', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Disable Dashboard widgets PRO
    add_settings_field(
        'wcbloat_wp_dashboard_widgets_disable',
        esc_attr__( 'Disable WordPress Dashboard widgets', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_wp_dashboard_widgets_disable_callback',
        'wcbloat-admin',
        'wcbloat_clean_admin_section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_wp_dashboard_widgets_disable', 'validate_setting' );
    // Remove the WordPress logo ("W") from the admin bar PRO
    add_settings_field(
        'wcbloat_w_logo_disable',
        'WordPress logo in the admin bar',
        'wcbloat_w_logo_disable_callback',
        'wcbloat-admin',
        'wcbloat_clean_admin_section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_w_logo_disable', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove admin footer text PRO
    add_settings_field(
        'wcbloat_wp_footer_disable',
        'Admin footer text',
        'wcbloat_wp_footer_disable_callback',
        'wcbloat-admin',
        'wcbloat_clean_admin_section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_wp_footer_disable', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // WordPress login page section
    add_settings_section(
        'wcbloat-admin-wp-login-page-section',
        esc_attr__( 'WordPress login page', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_admin_wp_login_page_section_callback',
        'wcbloat-admin'
    );
    // Hide WordPress logo on the WordPress Login Page PRO
    add_settings_field(
        'wcbloat_hide_wp_logo_on_login_page',
        'Hide WordPress logo on the Login Page',
        'wcbloat_hide_wp_logo_on_login_page_callback',
        'wcbloat-admin',
        'wcbloat-admin-wp-login-page-section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_hide_wp_logo_on_login_page', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Change the Logo URL on the WordPress Login Page PRO
    add_settings_field(
        'wcbloat_wp_logo_url_disable',
        'Change the Logo Link',
        'wcbloat_wp_logo_url_disable_callback',
        'wcbloat-admin',
        'wcbloat-admin-wp-login-page-section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_wp_logo_url_disable', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Change the Logo title the WordPress Login Page PRO
    add_settings_field(
        'wcbloat_wp_logo_title',
        'Change the Logo title parameter',
        'wcbloat_wp_logo_title_callback',
        'wcbloat-admin',
        'wcbloat-admin-wp-login-page-section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_wp_logo_title', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Disable WordPress Login Language Switcher PRO
    add_settings_field(
        'wcbloat_wp_language_select_disable',
        'Disable WordPress Login Language Switcher',
        'wcbloat_wp_language_select_disable_callback',
        'wcbloat-admin',
        'wcbloat-admin-wp-login-page-section'
    );
    register_setting( 'wcbloat-admin-options', 'wcbloat_wp_language_select_disable', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
}

// Display the fields added before
add_action( 'admin_init', 'wcbloat_admin_settings_init' );
// Fields callbacks
// Section's icon
function wcbloat_admin_desc_icon_callback()
{
    _e( '<span class="dashicons dashicons-hammer"></span>' );
}

// First section with title description
function wcbloat_admin_desc_callback()
{
    _e( 'By default, the WordPress admin panel is cluttered with preinstalled elements that may distract you from your work. You can simplify your WordPress admin panel by hiding or turning them off.<hr />', 'disable-dashboard-for-woocommerce' );
    _e( wcbloat_buy_pro_bar() );
}

// Clean admin interface section
function wcbloat_clean_admin_desc_section_callback()
{
    _e( 'If you love a simple and flexible interface, use the options below to maximize your performance while browsing through the WordPress admin panel. By removing unnecessary elements, you will be 100% focused on your tasks.', 'disable-dashboard-for-woocommerce' );
}

// Hide update notice for non-admin users
function wcbloat_wp_update_nag_disable_callback()
{
    $value = get_option( 'wcbloat_wp_update_nag_disable' );
    ?>
	<input type='hidden' name='wcbloat_wp_update_nag_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_update_nag_disable' <?php 
    checked( esc_attr( $value ), 'yes' );
    ?> value='yes'> <?php 
    esc_attr_e( 'Hide update notice for non-admin users', 'disable-dashboard-for-woocommerce' );
    ?></label>
	<p><?php 
    _e( 'The next time any user with the Role set as Subscriber, Contributor, Author, or Editor access the WordPress back-end, they will not be prompted to update the WordPress core. The notification will continue to display for admin users.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Disable Dashboard widgets PRO
function wcbloat_wp_dashboard_widgets_disable_callback()
{
    $options = get_option( 'wcbloat_wp_dashboard_widgets_disable' );
    ?>
<div class="wcbloat-select-unselect-buttons">
	<a <?php 
    ?> class="button button-secondary"<?php 
    echo  wcbloat_is_pro_readonly() ;
    ?>><?php 
    esc_attr_e( 'Select / Deselect All', 'disable-dashboard-for-woocommerce' );
    ?></a>
</div>
<table id="wcbloat-multi-checkbox-table">
<tbody>
  <tr>
    <td class="wcbloat-wp-widgets-disable-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_dashboard_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'site_health', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='site_health'><?php 
    esc_attr_e( 'Site Health Status', 'disable-dashboard-for-woocommerce' );
    ?></td>
    <td class="wcbloat-wp-widgets-disable-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_dashboard_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'at_a_glance', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='at_a_glance'><?php 
    esc_attr_e( 'At a Glance', 'disable-dashboard-for-woocommerce' );
    ?></td>
</tr>
  <tr>
  <td class="wcbloat-wp-widgets-disable-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_dashboard_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'activity', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='activity'><?php 
    esc_attr_e( 'Activity', 'disable-dashboard-for-woocommerce' );
    ?></td>
  <td class="wcbloat-wp-widgets-disable-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_dashboard_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'draft', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='draft'><?php 
    esc_attr_e( 'Quick Draft', 'disable-dashboard-for-woocommerce' );
    ?></td>
  </tr>
  <tr>
  <td class="wcbloat-wp-widgets-disable-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_dashboard_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'news', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='news'><?php 
    esc_attr_e( 'WordPress Events and News', 'disable-dashboard-for-woocommerce' );
    ?></td>
  <td class="wcbloat-wp-widgets-disable-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_dashboard_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'welcome', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='welcome'><?php 
    esc_attr_e( 'Welcome panel', 'disable-dashboard-for-woocommerce' );
    ?></td>
  </tr>
</tbody>
</table>
	<p><?php 
    echo  wcbloat_is_pro_badge() ;
    _e( 'WordPress by default comes with a lot of Dashboard widgets installed. They often are not used at all, but can add backend load and front-end load. Choose which of the WordPress Dashboard Widgets should be disabled. The <strong>ones you choose will be disabled</strong>, and the <strong>ones that had not been selected</strong> will stay <strong>active</strong>.', 'disable-dashboard-for-woocommerce' );
    ?></p>

	<?php 
}

// Remove the WordPress logo ("W") from the admin bar PRO
function wcbloat_w_logo_disable_callback()
{
    $value = get_option( 'wcbloat_w_logo_disable' );
    ?>
	<input type='hidden' name='wcbloat_w_logo_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_w_logo_disable' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove the WordPress logo (“W”) from the admin bar', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'This option will hide the WordPress logo in the upper left corner. The logo won\'t be visible in your admin panel and in your site (on the admin bar visible on the front-end after logging in.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Remove admin footer text PRO
function wcbloat_wp_footer_disable_callback()
{
    $value = get_option( 'wcbloat_wp_footer_disable' );
    ?>
	<input type='hidden' name='wcbloat_wp_footer_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_footer_disable' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove admin footer text', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'This option hides the text at the bottom of the WordPress admin: <code>Thank you for creating with WordPress</code> on the bottom left, and the WordPress version on the bottom right.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// WordPress login page section
function wcbloat_admin_wp_login_page_section_callback()
{
    _e( 'There are situations when you may prefer to hide or change the standard WordPress branding on the Login Page.', 'disable-dashboard-for-woocommerce' );
}

// Remove admin footer text PRO
function wcbloat_hide_wp_logo_on_login_page_callback()
{
    $value = get_option( 'wcbloat_hide_wp_logo_on_login_page' );
    ?>
	<input type='hidden' name='wcbloat_hide_wp_logo_on_login_page' value='no'>
	<label><input type='checkbox' name='wcbloat_hide_wp_logo_on_login_page' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Hide WordPress logo on the WordPress Login Page', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'Hide standard WordPress Logo from Login Page', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Remove admin footer text PRO
function wcbloat_wp_logo_url_disable_callback()
{
    $value = get_option( 'wcbloat_wp_logo_url_disable' );
    ?>
	<input type='hidden' name='wcbloat_wp_logo_url_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_logo_url_disable' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Change the Logo Link on the WordPress Login Page', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'By default, the WordPress login page displays the WordPress logo which is linking to WordPress.org site. After activating this option, the logo on the login page will be linking to your site\'s homepage.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Change the Logo title the WordPress Login Page PRO
function wcbloat_wp_logo_title_callback()
{
    $value = get_option( 'wcbloat_wp_logo_title' );
    ?>
	<input type='hidden' name='wcbloat_wp_logo_title' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_logo_title' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Change the Logo title parameter on the WordPress Login Page', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'By default, the WordPress Logo displayed on the WordPress Login Page has a title parameter that says <code>Powered by WordPress</code>. After activating this option, it will match your Site Name.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Disable WordPress Login Language Switcher PRO
function wcbloat_wp_language_select_disable_callback()
{
    $value = get_option( 'wcbloat_wp_language_select_disable' );
    ?>
	<input type='hidden' name='wcbloat_wp_language_select_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_language_select_disable' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Disable WordPress Login Language Switcher', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'This option will disable the language selector which allows users to switch languages from a dropdown on the login screen if more than one language is enabled on your WordPress installation.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}
