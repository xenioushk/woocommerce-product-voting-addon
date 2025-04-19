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
class ProductCatalogArgsCb {

	/**
	 * Get the product catalog options.
	 *
	 * @param array $sort_args The sortby array.
	 *
	 * @return array
	 */
	public function get_args( $sort_args ) {

		$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

		switch ( $orderby_value ) {

						// Name your sortby key whatever you'd like; must correspond to the $sortby in the next function
			case 'pvm_like_votes_count':
				$sort_args['orderby'] = 'meta_value_num';
				// Sort by meta_value because we're using alphabetic sorting
				$sort_args['order']    = 'DESC';
				$sort_args['meta_key'] = 'pvm_like_votes_count';
                break;

			case 'pvm_dislike_votes_count':
				$sort_args['orderby'] = 'meta_value_num';
				// We use meta_value_num here because points are a number and we want to sort in numerical order
				$sort_args['order']    = 'DESC';
				$sort_args['meta_key'] = 'pvm_dislike_votes_count';
                break;

			case 'pvm_asc_total_vote':
				$sort_args['orderby'] = 'meta_value_num';
				// Sort by meta_value because we're using alphabetic sorting
				$sort_args['order']    = 'ASC';
				$sort_args['meta_key'] = 'pvm_total_votes';
                break;

			case 'pvm_desc_total_vote':
				$sort_args['orderby'] = 'meta_value_num';
				// Sort by meta_value because we're using alphabetic sorting
				$sort_args['order']    = 'DESC';
				$sort_args['meta_key'] = 'pvm_total_votes';
                break;
		}

		return $sort_args;
	}
}
