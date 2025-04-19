<?php
namespace WPVAADDON\Controllers\Actions;

use Xenioushk\BwlPluginApi\Api\Actions\ActionsApi;
use WPVAADDON\Callbacks\Actions\ProductShopItemCb;

/**
 * Class for registering the frontend actions.
 *
 * @since: 1.1.0
 * @package WPVAADDON
 */
class WPVAActions {

    /**
	 * Register Actions.
	 */
    public function register() {

        // Initialize API.
        $actions_api = new ActionsApi();

        // Initialize callbacks.
        $product_shop_item_cb = new ProductShopItemCb();

        $actions = [
            [
                'tag'      => 'woocommerce_after_shop_loop_item',
                'callback' => [ $product_shop_item_cb, 'set_the_voting_box' ],
            ],

        ];

        $actions_api->add_actions( $actions )->register();
    }
}
