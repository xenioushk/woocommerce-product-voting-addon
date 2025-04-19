<?php
namespace WPVAADDON\Controllers\Filters;

use Xenioushk\BwlPluginApi\Api\Filters\FiltersApi;

use WPVAADDON\Callbacks\Filters\ProductCatalogOptionsCb;
use WPVAADDON\Callbacks\Filters\ProductCatalogArgsCb;

/**
 * Class for registering the frontend filters.
 *
 * @since: 1.1.0
 * @package WPVAADDON
 */
class WPVAFilters {

    /**
	 * Register filters.
	 */
    public function register() {

        // Initialize API.
        $filters_api = new FiltersApi();

        // Initialize callbacks.
        $product_catalog_options_cb = new ProductCatalogOptionsCb();
        $product_catalog_args_cb    = new ProductCatalogArgsCb();

        // All filters.
        $filters = [
            [
                'tag'      => 'woocommerce_default_catalog_orderby_options',
                'callback' => [ $product_catalog_options_cb, 'get_options' ],
            ],
            [
                'tag'      => 'woocommerce_catalog_orderby',
                'callback' => [ $product_catalog_options_cb, 'get_options' ],
            ],
            [
                'tag'      => 'woocommerce_get_catalog_ordering_args',
                'callback' => [ $product_catalog_args_cb, 'get_args' ],
            ],
        ];

        $filters_api->add_filters( $filters )->register();
    }
}
