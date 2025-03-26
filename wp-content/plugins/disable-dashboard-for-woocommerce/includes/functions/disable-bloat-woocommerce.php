<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Checking if WooCommerce plugin is active, including multisite activations
function wcbloat_is_woo_active()
{
    // Makes sure the plugin is defined before trying to use it
    $need = false;

    if (!function_exists('is_plugin_active_for_network')) {
        require_once(ABSPATH . '/wp-admin/includes/plugin.php');
    }

    // multisite && this plugin is locally activated - Woo can be network or locally activated 
    if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
        // this plugin is network activated - Woo must be network activated 
        $need = is_plugin_active_for_network('woocommerce/woocommerce.php') ? false : true;
        // this plugin runs on a single site || is locally activated 
    } else {
        $need =  is_plugin_active('woocommerce/woocommerce.php') ? false : true;
    }

    if ($need === true) {
        return false;
    } else {
        return true;
    }
}
