<?php

/**
 * Plugin Name:    WooCommerce Product Voting Addon
 * Plugin URI:       https://bluewindlab.net/portfolio/woocommerce-product-voting-addon/
 * Description:     This addon seamlessly integrates a voting interface directly beneath your WooCommerce product listings, enhancing the user experience. Users gain the ability to efficiently filter and effortlessly sort products based on their vote counts. Consequently, this feature grants users a concise and insightful overview of the most highly-rated and popular products in your store, facilitating informed purchasing decisions.
 * Version:          2.0.0
 * Author:           Md Mahbub Alam Khan
 * Author URI:     https://codecanyon.net/user/xenioushk
 * WP Requires at least: 6.0+
 * Text Domain: bpvm_wpva
 * Domain Path: /languages/
 *
 * @package   WPVAADDON
 * @author    Mahbub Alam Khan
 * @license   GPL-2.0+
 * @link      https://codecanyon.net/user/xenioushk
 * @copyright 2025 BlueWindLab
 */

namespace WPVAADDON;

// security check.
defined( 'ABSPATH' ) || die( 'Unauthorized access' );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Load the plugin constants
if ( file_exists( __DIR__ . '/includes/Helpers/DependencyManager.php' ) ) {
	require_once __DIR__ . '/includes/Helpers/DependencyManager.php';
	Helpers\DependencyManager::register();
}

use RECAPADDON\Base\Activate;
use RECAPADDON\Base\Deactivate;

/**
 * Function to handle the activation of the plugin.
 *
 * @return void
 */
 function activate_plugin() { // phpcs:ignore
	$activate = new Activate();
	$activate->activate();
}

/**
 * Function to handle the deactivation of the plugin.
 *
 * @return void
 */
 function deactivate_plugin() { // phpcs:ignore
	Deactivate::deactivate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_plugin' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate_plugin' );

/**
 * Function to handle the initialization of the plugin.
 *
 * @return void
 */
function init_wpva_addon() {

	// Check if the parent plugin installed.
	if ( ! class_exists( 'BPVMWP\\Init' ) ) {
		add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_main_plugin' ] );
		return;
	}

	// Check parent plugin activation status.
	if ( ! ( Helpers\DependencyManager::get_product_activation_status() ) ) {
		add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_purchase_verification' ] );
		return;
	}

	if ( class_exists( 'RECAPADDON\\Init' ) ) {

		// Check the required minimum version of the parent plugin.
		if ( ! ( Helpers\DependencyManager::check_minimum_version_requirement_status() ) ) {
			add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_min_version_main_plugin' ] );
			return;
		}

		// Initialize the plugin.
		Init::register_services();
	}
}

add_action( 'init', __NAMESPACE__ . '\\init_wpva_addon' );

return;

define( 'BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION', get_option( 'bwl_pvm_plugin_version' ) );
define( 'BPVMWPVA_ADDON_PARENT_PLUGIN_TITLE', 'BWL Pro Voting Manager' );
define( 'BPVMWPVA_ADDON_TITLE', 'WooCommerce Product Voting Addon' );
define( 'BPVMWPVA_PARENT_PLUGIN_REQUIRED_VERSION', '1.3.0' ); // change plugin required version in here.
define( 'BPVMWPVA_ADDON_CURRENT_VERSION', '1.1.4' ); // change plugin current version in here.

define( 'BPVMWPVA_ADDON_ROOT_FILE', 'woocommerce-product-voting-addon.php' ); // use for the meta info.

define( 'BPVMWPVA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BPVMWPVA_DIR', plugins_url() . '/woocommerce-product-voting-addon/' );
define( 'BPVMWPVA_UPDATER_SLUG', plugin_basename( __FILE__ ) );
define( 'BPVMWPVA_CC_ID', '14564197' );
define( 'BPVMWPVA_INSTALLATION_TAG', 'wpva_bpvm_installation_' . str_replace( '.', '_', BPVMWPVA_ADDON_CURRENT_VERSION ) );

define( 'BPVMWPVA_PARENT_PLUGIN_PURCHASE_STATUS', get_option( 'bpvm_purchase_verified' ) == 1 ? 1 : 0 );

require_once BPVMWPVA_PATH . 'includes/public/class-wpva-addon.php';

register_activation_hook( __FILE__, [ 'BPVM_Wpva', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'BPVM_Wpva', 'deactivate' ] );

add_action( 'plugins_loaded', [ 'BPVM_Wpva', 'get_instance' ] );

if ( is_admin() ) {

    include_once BPVMWPVA_PATH . 'includes/admin/class-wpva-addon-admin.php';
    add_action( 'plugins_loaded', [ 'BPVM_Wpva_Admin', 'get_instance' ] );
}
