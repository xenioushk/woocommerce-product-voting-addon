<?php
namespace WPVAADDON\Helpers;

/**
 * Class for plugin constants.
 *
 * @package WPVAADDON
 */
class PluginConstants {

		/**
         * Static property to hold plugin options.
         *
         * @var array
         */
	public static $plugin_options = [];

	/**
	 * Initialize the plugin options.
	 */
	public static function init() {

		self::$plugin_options = get_option( 'bwl_pvm_options' );
	}

		/**
         * Get the relative path to the plugin root.
         *
         * @return string
         * @example wp-content/plugins/<plugin-name>/
         */
	public static function get_plugin_path(): string {
		return dirname( dirname( __DIR__ ) ) . '/';
	}


    /**
     * Get the plugin URL.
     *
     * @return string
     * @example http://appealwp.local/wp-content/plugins/<plugin-name>/
     */
	public static function get_plugin_url(): string {
		return plugin_dir_url( self::get_plugin_path() . WPVAADDON_PLUGIN_ROOT_FILE );
	}

	/**
	 * Register the plugin constants.
	 */
	public static function register() {
		self::init();
		self::set_paths_constants();
		self::set_base_constants();
		self::set_assets_constants();
		self::set_recaptcha_constants();
		self::set_updater_constants();
		self::set_product_info_constants();
	}

	/**
	 * Set the plugin base constants.
     *
	 * @example: $plugin_data = get_plugin_data( WPVAADDON_PLUGIN_DIR . '/' . WPVAADDON_PLUGIN_ROOT_FILE );
	 * echo '<pre>';
	 * print_r( $plugin_data );
	 * echo '</pre>';
	 * @example_param: Name,PluginURI,Description,Author,Version,AuthorURI,RequiresAtLeast,TestedUpTo,TextDomain,DomainPath
	 */
	private static function set_base_constants() {
		// This is super important to check if the get_plugin_data function is already loaded or not.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_data = get_plugin_data( WPVAADDON_PLUGIN_DIR . WPVAADDON_PLUGIN_ROOT_FILE );

		define( 'WPVAADDON_PLUGIN_VERSION', $plugin_data['Version'] ?? '1.0.0' );
		define( 'WPVAADDON_PLUGIN_TITLE', $plugin_data['Name'] ?? 'Recaptcha Addon For BWL Pro Voting Manager' );
		define( 'WPVAADDON_TRANSLATION_DIR', $plugin_data['DomainPath'] ?? '/languages/' );
		define( 'WPVAADDON_TEXT_DOMAIN', $plugin_data['TextDomain'] ?? '' );

		define( 'WPVAADDON_PLUGIN_FOLDER', 'bpvm-recaptcha-addon' );
		define( 'WPVAADDON_PLUGIN_CURRENT_VERSION', WPVAADDON_PLUGIN_VERSION );
		define( 'WPVAADDON_PLUGIN_POST_TYPE', 'bwl_kb' );
		define( 'WPVAADDON_PLUGIN_TAXONOMY_CAT', 'bkb_category' );
		define( 'WPVAADDON_PLUGIN_TAXONOMY_TAGS', 'bkb_tags' );

	}

	/**
	 * Set the plugin paths constants.
	 */
	private static function set_paths_constants() {
		define( 'WPVAADDON_PLUGIN_ROOT_FILE', 'bpvm-recaptcha-addon.php' );
		define( 'WPVAADDON_PLUGIN_DIR', self::get_plugin_path() );
		define( 'WPVAADDON_PLUGIN_FILE_PATH', WPVAADDON_PLUGIN_DIR );
		define( 'WPVAADDON_PLUGIN_URL', self::get_plugin_url() );
	}

	/**
	 * Set the plugin assets constants.
	 */
	private static function set_assets_constants() {
		define( 'WPVAADDON_PLUGIN_STYLES_ASSETS_DIR', WPVAADDON_PLUGIN_URL . 'assets/styles/' );
		define( 'WPVAADDON_PLUGIN_SCRIPTS_ASSETS_DIR', WPVAADDON_PLUGIN_URL . 'assets/scripts/' );
		define( 'WPVAADDON_PLUGIN_LIBS_DIR', WPVAADDON_PLUGIN_URL . 'libs/' );
	}

	/**
	 * Set the recaptcha constants.
	 */
	private static function set_recaptcha_constants() {
		define( 'WPVAADDON_SITE_KEY', self::$plugin_options['bpvm_recaptcha_site_key'] ?? '' );
		define( 'WPVAADDON_ENABLE_STATUS', self::$plugin_options['bpvm_recaptcha_conditinal_fields']['enabled'] ?? [] );
		define( 'WPVAADDON_TIME_INTERVAL_STATUS', self::$plugin_options['bpvm_recaptcha_conditinal_fields']['bpvm_recaptcha_interval'] ?? 3600 );
	}

	/**
	 * Set the updater constants.
	 */
	private static function set_updater_constants() {

		// Only change the slug.
		$slug        = 'bpvm/notifier_wpva.php';
		$updater_url = "https://projects.bluewindlab.net/wpplugin/zipped/plugins/{$slug}";

		define( 'WPVAADDON_PLUGIN_UPDATER_URL', $updater_url ); // phpcs:ignore
		define( 'WPVAADDON_PLUGIN_UPDATER_SLUG', WPVAADDON_PLUGIN_FOLDER . '/' . WPVAADDON_PLUGIN_ROOT_FILE ); // phpcs:ignore
		define( 'WPVAADDON_PLUGIN_PATH', WPVAADDON_PLUGIN_DIR );
	}

	/**
	 * Set the product info constants.
	 */
	private static function set_product_info_constants() {
		define( 'WPVAADDON_PRODUCT_ID', '14564197' ); // Plugin codecanyon/themeforest Id.
		define( 'WPVAADDON_PRODUCT_INSTALLATION_TAG', 'wpva_bpvm_installation_' . str_replace( '.', '_', WPVAADDON_PLUGIN_VERSION ) );
	}
}
