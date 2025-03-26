<?php

/**
 * Plugin Name: Disable Bloat for WordPress & WooCommerce
 * Plugin URI: https://disablebloat.com/
 * Description: All-in-One solution to speed up your WordPress & WooCommerce. Remove unnecessary features and make your site faster and cleaner.
 * Version: 3.2.4
 * Author: Disable Bloat
 * Developer: Disable Bloat
 * Author URI: https://disablebloat.com/
 * Text Domain: disable-dashboard-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 4.5
 * Tested up to: 6.3
 * Requires PHP: 5.6
 * WC requires at least: 4.0
 * WC tested up to: 7.9
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wcbloat_fs' ) ) {
    wcbloat_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'wcbloat_fs' ) ) {
        // Actual Freemius integration snippet
        
        if ( !function_exists( 'wcbloat_fs' ) ) {
            // Create a helper function for easy SDK access.
            function wcbloat_fs()
            {
                global  $wcbloat_fs ;
                
                if ( !isset( $wcbloat_fs ) ) {
                    // Activate multisite network integration.
                    if ( !defined( 'WP_FS__PRODUCT_10157_MULTISITE' ) ) {
                        define( 'WP_FS__PRODUCT_10157_MULTISITE', true );
                    }
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
                    $wcbloat_fs = fs_dynamic_init( array(
                        'id'             => '10157',
                        'slug'           => 'disable-dashboard-for-woocommerce',
                        'premium_slug'   => 'disable-dashboard-for-woocommerce-pro',
                        'type'           => 'plugin',
                        'public_key'     => 'pk_16f665643a809fd13e01f8a3d1381',
                        'is_premium'     => false,
                        'premium_suffix' => 'PRO',
                        'has_addons'     => false,
                        'has_paid_plans' => true,
                        'menu'           => array(
                        'contact' => false,
                        'support' => false,
                        'account' => false,
                        'pricing' => false,
                    ),
                        'pricing'        => false,
                        'anonymous_mode' => !function_exists( 'is_anonymous_mode_disabled__premium_only' ) || !is_anonymous_mode_disabled__premium_only(),
                        'is_live'        => true,
                    ) );
                }
                
                return $wcbloat_fs;
            }
            
            // Init Freemius.
            wcbloat_fs();
            // Signal that SDK was initiated.
            do_action( 'wcbloat_fs_loaded' );
        }
        
        if ( function_exists( 'fs_override_i18n' ) ) {
            fs_override_i18n( array(
                'opt-in' => __( '', 'disable-dashboard-for-woocommerce' ),
            ), 'disable-dashboard-for-woocommerce' );
        }
        wcbloat_fs()->add_filter( 'default_to_anonymous_feedback', '__return_true' );
        wcbloat_fs()->add_filter( 'hide_freemius_powered_by', '__return_true' );
        wcbloat_fs()->add_filter( 'hide_billing_and_payments_info', '__return_true' );
        // Opt-In Icon Customization
        function wcbloat_custom_plugin_icon()
        {
            return dirname( __FILE__ ) . '/assets/img/disable-dashboard-for-woocommerce.png';
        }
        
        wcbloat_fs()->add_filter( 'plugin_icon', 'wcbloat_custom_plugin_icon' );
        // Freemius END
        // load plugin text domain
        function wcbloat_init()
        {
            load_plugin_textdomain( 'disable-dashboard-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }
        
        add_action( 'plugins_loaded', 'wcbloat_init' );
        // Links on Plugins screen
        function wcbloat_action_links( $links )
        {
            $custom_links = [ '<a href="' . admin_url( 'options-general.php?page=disable-bloat' ) . '">' . __( 'Settings' ) . '</a>' ];
            if ( !wcbloat_fs()->is_premium() ) {
                $custom_links[] = '<a href="https://disablebloat.com/?utm_source=plugins_list&utm_medium=referral&utm_campaign=Plugin+links" target="_blank"><b>' . __( 'Upgrade', 'disable-dashboard-for-woocommerce' ) . '</b></a>';
            }
            if ( wcbloat_fs()->is_plan( 'pro' ) ) {
                $custom_links[] = sprintf( '<a href="%1$s">%2$s</a>', esc_url( wcbloat_fs()->get_account_url() ), __( 'My Account', 'disable-dashboard-for-woocommerce' ) );
            }
            return array_merge( $custom_links, $links );
        }
        
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wcbloat_action_links' );
    }
    
    // Add CSS and JS to the plugin settings screens
    function wcbloat_custom_wp_admin_assets()
    {
        $page = ( isset( $_GET['page'] ) ? $_GET['page'] : '' );
        
        if ( $page === 'disable-bloat' ) {
            wp_enqueue_style( 'wcbloat_wp_admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/disable-bloat-admin-style.css' );
            wp_enqueue_script( 'wcbloat_wp_admin_js', plugin_dir_url( __FILE__ ) . 'assets/js/disable-bloat-admin.js' );
        }
    
    }
    
    add_action( 'admin_enqueue_scripts', 'wcbloat_custom_wp_admin_assets' );
    // Include WooCommerce integration
    require_once 'includes/functions/disable-bloat-woocommerce.php';
    // Include functions files
    require_once 'includes/functions/disable-bloat-functions_free.php';
    // Include Options pages
    require_once 'includes/settings/class-disable-bloat-settings.php';
    // Include Uninstall Cleanup code
    require_once 'includes/functions/disable-bloat-uninstall-cleanup.php';
    // Compatibility with WooCommerce HPOS (Custom order tables) - only if WooCommmerce is active
    if ( wcbloat_is_woo_active() ) {
        add_action( 'before_woocommerce_init', function () {
            if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            }
        } );
    }
    // MAIN PLUGIN FILE END
    // DO NOT REMOVE THE BRACKET BELOW, AS IT IS NEEDED FOR THE MECHANISM OF AUTO DEACTIVATING THE FREE VERSION DURING PRO ACTIVATION:
}
