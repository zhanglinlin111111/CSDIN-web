<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
// Add sections and fields on Main settings screen
function wcbloat_performance_settings_init()
{
    // Section's icon
    add_settings_section(
        'wcbloat-performance-icon-section',
        '',
        'wcbloat_performance_icon_callback',
        'wcbloat-performance'
    );
    // Section's title and description
    add_settings_section(
        'wcbloat-site-performance-desc-section',
        esc_attr__( 'Site performance', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_site_performance_desc_callback',
        'wcbloat-performance'
    );
    // Speed up your site section
    add_settings_section(
        'wcbloat-performance-speed-section',
        esc_attr__( 'Speed up your site', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_performance_speed_section_callback',
        'wcbloat-performance'
    );
    // Disable Password Strength Meter
    add_settings_field(
        'wcbloat_password_meter_disable',
        'Password Strength Meter',
        'wcbloat_password_meter_disable_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_password_meter_disable', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Load comment script only when needed
    add_settings_field(
        'wcbloat_load_comment_scripts_when_needed',
        'Comments scripts',
        'wcbloat_load_comment_scripts_when_needed_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_load_comment_scripts_when_needed', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Prevent auto-linking URLs in comments
    add_settings_field(
        'wcbloat_prevent_linking_url_comments',
        'Auto-linking URLs in comments',
        'wcbloat_prevent_linking_url_comments_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_prevent_linking_url_comments', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Disable Dashicons
    add_settings_field(
        'wcbloat_disable_dashicons',
        'Disable Dashicons',
        'wcbloat_disable_dashicons_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_disable_dashicons', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove DNS prefetch to s.w.org PRO
    add_settings_field(
        'wcbloat_remove_dns_prefetch',
        'DNS prefetch to s.w.org',
        'wcbloat_remove_dns_prefetch_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_remove_dns_prefetch', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Disable jQuery Migrate PRO
    add_settings_field(
        'wcbloat_disable_jquery_migrate',
        'Disable jQuery Migrate',
        'wcbloat_disable_jquery_migrate_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_disable_jquery_migrate', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Disable Sidebar WordPress Widgets PRO
    add_settings_field(
        'wcbloat_wp_sidebar_widgets_disable',
        esc_attr__( 'Disable Sidebar WordPress Widgets', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_wp_sidebar_widgets_disable_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_wp_sidebar_widgets_disable', 'validate_setting' );
    // Remove WordPress Meta Generator Tag PRO
    add_settings_field(
        'wcbloat_wp_meta_generator_disable',
        'WordPress & WooCommerce Meta Generator Tag',
        'wcbloat_wp_meta_generator_disable_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_wp_meta_generator_disable', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove emoji styles and scripts PRO
    add_settings_field(
        'wcbloat_remove_emoji_scripts',
        'Emoji styles and scripts',
        'wcbloat_remove_emoji_scripts_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_remove_emoji_scripts', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Disable wp-embed PRO
    add_settings_field(
        'wcbloat_disable_wp_embed',
        'Disable wp-embed',
        'wcbloat_disable_wp_embed_callback',
        'wcbloat-performance',
        'wcbloat-performance-speed-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_disable_wp_embed', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove unwanted scripts from header section
    add_settings_section(
        'wcbloat-site-performance-header-section',
        esc_attr__( 'Remove scripts from Header', 'disable-dashboard-for-woocommerce' ),
        'wcbloat_site_performance_header_section_callback',
        'wcbloat-performance'
    );
    // Remove RSS Feed Links PRO
    add_settings_field(
        'wcbloat_remove_rss_links',
        'RSS Feed Links',
        'wcbloat_remove_rss_links_callback',
        'wcbloat-performance',
        'wcbloat-site-performance-header-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_remove_rss_links', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Disable the RSS feeds PRO
    add_settings_field(
        'wcbloat_disable_all_feeds',
        'RSS feeds',
        'wcbloat_disable_all_feeds_callback',
        'wcbloat-performance',
        'wcbloat-site-performance-header-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_disable_all_feeds', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove Feed Generator Tag PRO
    add_settings_field(
        'wcbloat_remove_feed_generator_tag',
        'Feed Generator Tag',
        'wcbloat_remove_feed_generator_tag_callback',
        'wcbloat-performance',
        'wcbloat-site-performance-header-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_remove_feed_generator_tag', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove Link to the WLW Manifest File PRO
    add_settings_field(
        'wcbloat_disable_wlw_link',
        'Windows Live Writer',
        'wcbloat_disable_wlw_link_callback',
        'wcbloat-performance',
        'wcbloat-site-performance-header-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_disable_wlw_link', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove RSD link PRO
    add_settings_field(
        'wcbloat_disable_rsd_link',
        'RSD Link',
        'wcbloat_disable_rsd_link_callback',
        'wcbloat-performance',
        'wcbloat-site-performance-header-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_disable_rsd_link', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove RSD link PRO
    add_settings_field(
        'wcbloat_disable_rsd_link',
        'RSD Link',
        'wcbloat_disable_rsd_link_callback',
        'wcbloat-performance',
        'wcbloat-site-performance-header-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_disable_rsd_link', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
    // Remove Shortlink From HTTP Header PRO
    add_settings_field(
        'wcbloat_remove_shortlink',
        'Shortlink',
        'wcbloat_remove_shortlink_callback',
        'wcbloat-performance',
        'wcbloat-site-performance-header-section'
    );
    register_setting( 'wcbloat-performance-options', 'wcbloat_remove_shortlink', array(
        'sanitize_callback' => 'sanitize_key',
    ) );
}

// Display the fields added before
add_action( 'admin_init', 'wcbloat_performance_settings_init' );
// Fields callbacks
// Section's icon
function wcbloat_performance_icon_callback()
{
    _e( '<span class="dashicons dashicons-admin-site-alt"></span>' );
}

// First section with title description
function wcbloat_site_performance_desc_callback()
{
    _e( 'Page load time is very important for your visitors. To improve page load time, try to disable scripts, features, and unnecessary queries:<hr />', 'disable-dashboard-for-woocommerce' );
}

// Speed up your site section
function wcbloat_performance_speed_section_callback()
{
    _e( 'Use the settings from this section to reduce page load time on the front-end of your WordPress site. Before disabling them, please make sure that you will not use any of these features - so they can be safely turned off.', 'disable-dashboard-for-woocommerce' );
    _e( wcbloat_buy_pro_bar() );
}

// Disable Password Strength Meter
function wcbloat_password_meter_disable_callback()
{
    $value = get_option( 'wcbloat_password_meter_disable' );
    ?>
	<input type='hidden' name='wcbloat_password_meter_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_password_meter_disable' <?php 
    checked( esc_attr( $value ), 'yes' );
    ?> value='yes'> <?php 
    esc_attr_e( 'Disable Password Strength Meter', 'disable-dashboard-for-woocommerce' );
    ?></label>
	<p><?php 
    _e( 'Removes the WordPress and WooCommerce password strength meter scripts (over 400 KB) from non-essential pages.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Load comment script only when needed
function wcbloat_load_comment_scripts_when_needed_callback()
{
    $value = get_option( 'wcbloat_load_comment_scripts_when_needed' );
    ?>
	<input type='hidden' name='wcbloat_load_comment_scripts_when_needed' value='no'>
	<label><input type='checkbox' name='wcbloat_load_comment_scripts_when_needed' <?php 
    checked( esc_attr( $value ), 'yes' );
    ?> value='yes'> <?php 
    esc_attr_e( 'Load Comments script only when needed', 'disable-dashboard-for-woocommerce' );
    ?></label>
	<p><?php 
    _e( 'By default, WordPress Comments javascript files are loaded everywhere on your site. This option will load the Comments script only when needed (on single posts and pages with existing comments).', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Load comment script only when needed
function wcbloat_prevent_linking_url_comments_callback()
{
    $value = get_option( 'wcbloat_prevent_linking_url_comments' );
    ?>
	<input type='hidden' name='wcbloat_prevent_linking_url_comments' value='no'>
	<label><input type='checkbox' name='wcbloat_prevent_linking_url_comments' <?php 
    checked( esc_attr( $value ), 'yes' );
    ?> value='yes'> <?php 
    esc_attr_e( 'Prevent auto-linking URLs in comments', 'disable-dashboard-for-woocommerce' );
    ?></label>
	<p><?php 
    _e( 'In a standard WordPress installation, adding clickable links to the comments is made on the fly while generating the page which may be time- and resource-consuming. This option will prevent auto-linking URLs in comments.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Disable Dashicons
function wcbloat_disable_dashicons_callback()
{
    $value = get_option( 'wcbloat_disable_dashicons' );
    ?>
	<input type='hidden' name='wcbloat_disable_dashicons' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_dashicons' <?php 
    checked( esc_attr( $value ), 'yes' );
    ?> value='yes'> <?php 
    esc_attr_e( 'Disable WordPress Dashicons on the front-end', 'disable-dashboard-for-woocommerce' );
    ?></label>
	<p><?php 
    _e( 'Dashicons add additional overhead to your site, slowing it down, especially on slower computers or mobile devices. Disabling dashicons can help reduce the total size of your page and improve your site\'s performance.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Disable jQuery Migrate PRO
function wcbloat_disable_jquery_migrate_callback()
{
    $value = get_option( 'wcbloat_disable_jquery_migrate' );
    ?>
	<input type='hidden' name='wcbloat_disable_jquery_migrate' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_jquery_migrate' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Disable jQuery Migrate', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'The jQuery Migrate was introduced in WordPress to provide backward compatibility for older jQuery code. However, in recent versions of WordPress, many of the compatibility issues have been resolved, so it is no longer necessary in most cases. Disabling jQuery Migrate can improve the performance of your WordPress website by reducing the amount of JavaScript code that needs to be loaded, and by reducing the number of warnings and error messages that are output to the browser console. Additionally, by disabling the plugin, you can avoid potential security vulnerabilities that may arise from using outdated and unsupported code.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Remove DNS prefetch to s.w.org PRO
function wcbloat_remove_dns_prefetch_callback()
{
    $value = get_option( 'wcbloat_remove_dns_prefetch' );
    ?>
	<input type='hidden' name='wcbloat_remove_dns_prefetch' value='no'>
	<label><input type='checkbox' name='wcbloat_remove_dns_prefetch' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove DNS prefetch to s.w.org', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'DNS prefetching is an attempt to resolve domain names before a user tries to follow a link. Activating this option will remove DNS prefetch to s.w.org and may result in page load optimization.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Disable Sidebar WordPress Widgets PRO
function wcbloat_wp_sidebar_widgets_disable_callback()
{
    $options = get_option( 'wcbloat_wp_sidebar_widgets_disable' );
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
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'archives', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='archives'><?php 
    esc_attr_e( 'Archives', 'disable-dashboard-for-woocommerce' );
    ?></td>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'audio', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='audio'><?php 
    esc_attr_e( 'Audio', 'disable-dashboard-for-woocommerce' );
    ?></td>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'block', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='block'><?php 
    esc_attr_e( 'Block', 'disable-dashboard-for-woocommerce' );
    ?></td>
</tr>
<tr>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'calendar', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='calendar'><?php 
    esc_attr_e( 'Calendar', 'disable-dashboard-for-woocommerce' );
    ?></td>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'categories', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='categories'><?php 
    esc_attr_e( 'Categories', 'disable-dashboard-for-woocommerce' );
    ?></td>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'html', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='html'><?php 
    esc_attr_e( 'Custom HTML', 'disable-dashboard-for-woocommerce' );
    ?></td>
</tr>
<tr>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'gallery', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='gallery'><?php 
    esc_attr_e( 'Gallery', 'disable-dashboard-for-woocommerce' );
    ?></td>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'image', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='image'><?php 
    esc_attr_e( 'Image', 'disable-dashboard-for-woocommerce' );
    ?></td>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'meta', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='meta'><?php 
    esc_attr_e( 'Meta', 'disable-dashboard-for-woocommerce' );
    ?></td>
</tr>
<tr>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'navigation', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='navigation'><?php 
    esc_attr_e( 'Navigation Menu', 'disable-dashboard-for-woocommerce' );
    ?></td>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'pages', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='pages'><?php 
    esc_attr_e( 'Pages', 'disable-dashboard-for-woocommerce' );
    ?></td>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'rss', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='rss'><?php 
    esc_attr_e( 'RSS', 'disable-dashboard-for-woocommerce' );
    ?></td>
</tr>
<tr>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'comments', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='comments'><?php 
    esc_attr_e( 'Recent Comments', 'disable-dashboard-for-woocommerce' );
    ?></td>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'posts', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='posts'><?php 
    esc_attr_e( 'Recent Posts', 'disable-dashboard-for-woocommerce' );
    ?></td>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'search', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='search'><?php 
    esc_attr_e( 'Search', 'disable-dashboard-for-woocommerce' );
    ?></td>
</tr>
<tr>
    <td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'tag', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='tag'><?php 
    esc_attr_e( 'Tag Cloud', 'disable-dashboard-for-woocommerce' );
    ?></td>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'text', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='text'><?php 
    esc_attr_e( 'Text', 'disable-dashboard-for-woocommerce' );
    ?></td>
	<td class="wcbloat-disable-sidebar-widgets-multi-checkbox"><label><input type='checkbox' name='wcbloat_wp_sidebar_widgets_disable[]' <?php 
    if ( !empty($options) ) {
        if ( in_array( 'video', $options ) ) {
            esc_attr_e( 'checked' );
        }
    }
    echo  wcbloat_is_pro_readonly() ;
    ?> value='video'><?php 
    esc_attr_e( 'Video', 'disable-dashboard-for-woocommerce' );
    ?></td>
</tr>
</tbody>
</table>
	<p><?php 
    echo  wcbloat_is_pro_badge() ;
    _e( 'WordPress by default comes with a lot of Dashboard widgets installed. They often are not used at all, but can add backend load and front-end load. Choose Dashboard Widgets you would like to disable for this site. The <strong>ones you choose will be disabled</strong>, and the <strong>ones that had not been selected will stay active</strong>.

', 'disable-dashboard-for-woocommerce' );
    ?></p>

	<?php 
}

// Remove WordPress Meta Generator Tag PRO
function wcbloat_wp_meta_generator_disable_callback()
{
    $value = get_option( 'wcbloat_wp_meta_generator_disable' );
    ?>
	<input type='hidden' name='wcbloat_wp_meta_generator_disable' value='no'>
	<label><input type='checkbox' name='wcbloat_wp_meta_generator_disable' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove WordPress & WooCommerce Meta Generator Tag', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'Meta Generator Tag displays the WordPress & WooCommerce version number. Removing the meta generator tag protects you against attacks and may reduce your web page\'s size. Use this option to disable the Meta Tag generated by WordPress, WooCommerce, and many other plugins that hook up with the wp_generator action.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Remove emoji styles and scripts PRO
function wcbloat_remove_emoji_scripts_callback()
{
    $value = get_option( 'wcbloat_remove_emoji_scripts' );
    ?>
	<input type='hidden' name='wcbloat_remove_emoji_scripts' value='no'>
	<label><input type='checkbox' name='wcbloat_remove_emoji_scripts' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove emoji styles and scripts', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'Remove the code bloat used to add support for emojis in older browsers. Emoticons and emojis will still work in browsers that have built-in support for them.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Disable wp-embed PRO
function wcbloat_disable_wp_embed_callback()
{
    $value = get_option( 'wcbloat_disable_wp_embed' );
    ?>
	<input type='hidden' name='wcbloat_disable_wp_embed' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_wp_embed' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Disable wp-embed', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'Prevents the WordPress core feature known as wp-embed from loading on your website. The wp-embed feature allows you to embed videos, images, tweets, and other types of content from various platforms by pasting a URL into the WordPress editor. While this feature can be useful, it can also slow down your site\'s loading times, increase your server resources usage, and pose potential security risks.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// First section with title description
function wcbloat_site_performance_header_section_callback()
{
    _e( 'Remove unwanted scripts from the Header section of your site. The header section is used on all of your subpages and in most cases you do not need to load all the default scripts. Use the option below to turn them off.', 'disable-dashboard-for-woocommerce' );
}

// Remove RSS Feed Links PRO
function wcbloat_remove_rss_links_callback()
{
    $value = get_option( 'wcbloat_remove_rss_links' );
    ?>
	<input type='hidden' name='wcbloat_remove_rss_links' value='no'>
	<label><input type='checkbox' name='wcbloat_remove_rss_links' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove RSS Feed Links', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'If you do not use RSS feeds, you can safely turn them off without losing any functionality. Links to RSS Feeds will be removed from the plugin header, but the process of generating RSS feeds will still be active.', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Disable the RSS feeds PRO
function wcbloat_disable_all_feeds_callback()
{
    $value = get_option( 'wcbloat_disable_all_feeds' );
    ?>
	<input type='hidden' name='wcbloat_disable_all_feeds' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_all_feeds' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Disable all RSS feeds', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'You can also completely disable the RSS generation process by activating this option. If it is a feature that you do not plan to use, you can disable RSS Feeds.', 'disable-dashboard-for-woocommerce', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Feed Generator Tag
function wcbloat_remove_feed_generator_tag_callback()
{
    $value = get_option( 'wcbloat_remove_feed_generator_tag' );
    ?>
	<input type='hidden' name='wcbloat_remove_feed_generator_tag' value='no'>
	<label><input type='checkbox' name='wcbloat_remove_feed_generator_tag' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove the Generator Tag From RSS Feeds', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'This option will Remove the Generator Tag From RSS Feeds', 'disable-dashboard-for-woocommerce', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Remove Link to the WLW Manifest File PRO
function wcbloat_disable_wlw_link_callback()
{
    $value = get_option( 'wcbloat_disable_wlw_link' );
    ?>
	<input type='hidden' name='wcbloat_disable_wlw_link' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_wlw_link' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove Link to the Windows Live Writer Manifest File', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'If are not using software called Windows Live Writer, you can safely turn off this feature that is added to your header by default.', 'disable-dashboard-for-woocommerce', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Remove Shortlink From HTTP Header PRO
function wcbloat_disable_rsd_link_callback()
{
    $value = get_option( 'wcbloat_disable_rsd_link' );
    ?>
	<input type='hidden' name='wcbloat_disable_rsd_link' value='no'>
	<label><input type='checkbox' name='wcbloat_disable_rsd_link' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove RSD link', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'RSD link is a tag that is added to your Header by default. The RSD link is used by software blog clients. If you access your site admin panel from the browser then it is safe for you to remove the RSD Link.', 'disable-dashboard-for-woocommerce', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}

// Remove Shortlink From HTTP Header PRO
function wcbloat_remove_shortlink_callback()
{
    $value = get_option( 'wcbloat_remove_shortlink' );
    ?>
	<input type='hidden' name='wcbloat_remove_shortlink' value='no'>
	<label><input type='checkbox' name='wcbloat_remove_shortlink' <?php 
    checked( esc_attr( $value ), 'yes' );
    echo  wcbloat_is_pro_readonly() ;
    ?> value='yes'> <?php 
    esc_attr_e( 'Remove Shortlink From HTTP Header', 'disable-dashboard-for-woocommerce' );
    echo  wcbloat_is_pro_badge() ;
    ?></label>
	<p><?php 
    _e( 'Shortlink is the shorter version of the post or page URL. Shortlink (enabled in WordPress by default) creates a separate request on every page.', 'disable-dashboard-for-woocommerce', 'disable-dashboard-for-woocommerce' );
    ?></p>
<?php 
}
