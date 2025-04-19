<?php
namespace WPVAADDON\Callbacks\Actions;

/**
 * Class for registering the product shop item callback.
 *
 * @package WPVAADDON
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class ProductShopItemCb {

	/**
	 * Set the voting box.
	 *
	 * @return void
	 */
	public function set_the_voting_box() {
		global $product;

		$post_id = $product->get_id();

		if ( ! $post_id ) {
			return;
		}

		$voting_box_status = get_post_meta( $post_id, 'bwl_pvm_display_status', true ) ?? 1;

		echo do_shortcode( sprintf(
            '[bwl_pvm id="%d" status="%d" woo="1"]',
            intval( $post_id ),
            intval( $voting_box_status )
		) );
	}
}
