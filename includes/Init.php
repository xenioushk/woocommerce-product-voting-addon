<?php

namespace WPVAADDON;

/**
 * Class for Initialize plugin required searvices.
 *
 * @since: 1.1.0
 * @package WPVAADDON
 */
class Init {

	/**
	 * Get all the services.
	 */
	public static function get_services() {

		/**
		 * Add plugin required classes.
        *
		 * @since 1.1.0
		 */

		$services = [];

		$service_classes = [
			'helpers' => self::get_helper_classes(),
			'base'    => self::get_base_classes(),
			'meta'    => self::get_meta_classes(),
			'actions' => self::get_action_classes(),
			'filters' => self::get_filter_classes(),
		];

		foreach ( $service_classes as $service_class ) {
			$services = array_merge( $services, $service_class );
		}
		return $services;

	}

	/**
	 * Registered all the classes.
     *
	 * @since 1.0.0
	 */
	public static function register_services() {

		if ( empty( self::get_services() ) ) {
			return;
		}

		foreach ( self::get_services() as $service ) {

			$service = self::instantiate( $service );

			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Instantiate all the registered service classes.
     *
     * @param   class $service The class to instantiate.
     * @author   Md Mahbub Alam Khan
     * @return  object
     * @since   1.1.0
	 */
	private static function instantiate( $service ) {
		return new $service();
	}

	/**
	 * Get Base classes.
	 *
	 * @return array
	 */
	private static function get_base_classes() {
		$classes = [
			Base\Enqueue::class,
			Base\AdminEnqueue::class,
			Base\PluginUpdate::class,
			Base\Language::class,
			Base\FrontendAjaxHandlers::class,
			Base\AdminAjaxHandlers::class,
		];
		return $classes;
	}

	/**
	 * Get Helper classes.
	 *
	 * @return array
	 */
	private static function get_helper_classes() {
		$classes = [
			Helpers\PluginConstants::class,
			Helpers\WpvaHelpers::class,
		];
		return $classes;
	}

	/**
	 * Get Meta classes.
	 *
	 * @return array
	 */
	private static function get_meta_classes() {
		$classes = [
			Controllers\PluginMeta\MetaInfo::class,
		];
		return $classes;
	}


	/**
	 * Get Action classes.
	 *
	 * @return array
	 */
	private static function get_action_classes() {

		$classes = [
			Controllers\Actions\WPVAActions::class,
		];
		return $classes;
	}

	/**
	 * Get Filter classes.
	 *
	 * @return array
	 */
	private static function get_filter_classes() {

		$classes = [
			Controllers\Filters\WPVAFilters::class,
		];
		return $classes;
	}
}
