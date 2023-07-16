<?php

/**
 * Plugin Name:    WooCommerce Product Voting Addon
 * Plugin URI:       https://bluewindlab.net/portfolio/woocommerce-product-voting-addon/
 * Description:     The Addon automatically added a voting interface below the WooCommerce products. User can quickly filter and easily sort the products based on votes. As a result, users can get a clear overview about the top voted and popular products.
 * Version:          1.0.7
 * Author:           Md Mahbub Alam Khan
 * Author URI:     https://1.envato.market/bpvm-wp
 * Text Domain: bpvm_wpva
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_pvm_plugin_version'));
define('BPVMWPVA_ADDON_PARENT_PLUGIN_TITLE', '<b>BWL Pro Voting Manager</b> ');
define('BPVMWPVA_ADDON_TITLE', '<b>WooCommerce Product Voting Addon</b>');
define('BPVMWPVA_PARENT_PLUGIN_REQUIRED_VERSION', '1.1.1'); // change plugin required version in here.
define('BPVMWPVA_ADDON_CURRENT_VERSION', '1.0.7'); // change plugin current version in here.

define('BPVMWPVA_PATH', plugin_dir_path(__FILE__));
define("BPVMWPVA_DIR", plugins_url() . '/woocommerce-product-voting-addon/');
define("BPVMWPVA_UPDATER_SLUG", plugin_basename(__FILE__)); // change plugin current version in here.

require_once(plugin_dir_path(__FILE__) . 'public/class-wpva-addon.php');

register_activation_hook(__FILE__, array('BPVM_Wpva', 'activate'));
register_deactivation_hook(__FILE__, array('BPVM_Wpva', 'deactivate'));

add_action('plugins_loaded', array('BPVM_Wpva', 'get_instance'));

if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . 'admin/class-wpva-addon-admin.php');
    add_action('plugins_loaded', array('BPVM_Wpva_Admin', 'get_instance'));
}
