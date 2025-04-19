<?php
namespace WPVAADDON\Base;

use Xenioushk\BwlPluginApi\Api\AjaxHandlers\AjaxHandlersApi;

use WPVAADDON\Callbacks\FrontendAjaxHandlers\ProductsDataCb;

/**
 * Class for frontend ajax handlers.
 *
 * @package WPVAADDON
 * @since: 1.1.0
 * @author: Mahbub Alam Khan
 */
class FrontendAjaxHandlers {

	/**
	 * Register frontend ajax handlers.
	 */
	public function register() {

		$ajax_handlers_api = new AjaxHandlersApi();

		// Initialize the callback class.

		$products_data_cb = new ProductsDataCb();

		// Do not change the tag.
		// If do so, you need to change in js file too.
		$ajax_requests = [
			[
				'tag'      => 'bpvm_get_woo_product_data',
				'callback' => [ $products_data_cb, 'get_data' ],
			],
		];

		$ajax_handlers_api->add_ajax_handlers( $ajax_requests )->register();
	}
}
