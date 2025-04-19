<?php
namespace WPVAADDON\Callbacks\Filters;

use WPVAADDON\Helpers\PluginConstants;

/**
 * Class for registering the product tab.
 *
 * @package WPVAADDON
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class ProductCatalogOptionsCb {


	/**
	 * Get the product catalog options.
	 *
	 * @param array $sortby The sortby array.
	 *
	 * @return array
	 */
	public function get_options( $sortby ) {

		// Adjust the text as desired

		$sortby['pvm_like_votes_count']    = esc_html__( 'Sort by top liked', 'bpvm_wpva' );
		$sortby['pvm_dislike_votes_count'] = esc_html__( 'Sort by top disliked', 'bpvm_wpva' );

		$sortby['pvm_asc_total_vote']  = esc_html__( 'Sort by votes: low to high', 'bpvm_wpva' );
		$sortby['pvm_desc_total_vote'] = esc_html__( 'Sort by votes: high to low', 'bpvm_wpva' );

		return $sortby;
	}
}
