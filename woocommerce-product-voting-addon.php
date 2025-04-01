<?php

/**
 * Plugin Name:    WooCommerce Product Voting Addon
 * Plugin URI:       https://bluewindlab.net/portfolio/woocommerce-product-voting-addon/
 * Description:     This addon seamlessly integrates a voting interface directly beneath your WooCommerce product listings, enhancing the user experience. Users gain the ability to efficiently filter and effortlessly sort products based on their vote counts. Consequently, this feature grants users a concise and insightful overview of the most highly-rated and popular products in your store, facilitating informed purchasing decisions.
 * Version:          1.1.4
 * Author:           Md Mahbub Alam Khan
 * Author URI:     https://codecanyon.net/user/xenioushk
 * Requires at least: 6.0+
 * Text Domain: bpvm_wpva
 * Domain Path: /languages/
 *
 * @package   WooCommerce Product Voting Addon
 * @author    Mahbub Alam Khan
 * @license   GPL-2.0+
 * @link      https://codecanyon.net/user/xenioushk
 * @copyright 2025 BlueWindLab
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_pvm_plugin_version'));
define('BPVMWPVA_ADDON_PARENT_PLUGIN_TITLE', 'BWL Pro Voting Manager');
define('BPVMWPVA_ADDON_TITLE', 'WooCommerce Product Voting Addon');
define('BPVMWPVA_PARENT_PLUGIN_REQUIRED_VERSION', '1.3.0'); // change plugin required version in here.
define('BPVMWPVA_ADDON_CURRENT_VERSION', '1.1.4'); // change plugin current version in here.    

define("BPVMWPVA_ADDON_ROOT_FILE", "woocommerce-product-voting-addon.php"); // use for the meta info.

define('BPVMWPVA_PATH', plugin_dir_path(__FILE__));
define("BPVMWPVA_DIR", plugins_url() . '/woocommerce-product-voting-addon/');
define("BPVMWPVA_UPDATER_SLUG", plugin_basename(__FILE__));
define("BPVMWPVA_CC_ID", "14564197");
define("BPVMWPVA_INSTALLATION_TAG", "wpva_bpvm_installation_" . str_replace('.', '_', BPVMWPVA_ADDON_CURRENT_VERSION));

define("BPVMWPVA_PARENT_PLUGIN_PURCHASE_STATUS", get_option('bpvm_purchase_verified') == 1 ? 1 : 0);

require_once BPVMWPVA_PATH . 'includes/public/class-wpva-addon.php';

register_activation_hook(__FILE__, ['BPVM_Wpva', 'activate']);
register_deactivation_hook(__FILE__, ['BPVM_Wpva', 'deactivate']);

add_action('plugins_loaded', ['BPVM_Wpva', 'get_instance']);

if (is_admin()) {

    include_once BPVMWPVA_PATH . 'includes/admin/class-wpva-addon-admin.php';
    add_action('plugins_loaded', ['BPVM_Wpva_Admin', 'get_instance']);
}
