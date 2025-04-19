<?php
namespace WPVAADDON\Callbacks\FrontendAjaxHandlers;

use WPVAADDON\Helpers\WpvaHelpers;

/**
 * Class for WooCommerce products data callback.
 *
 * @package WPVAADDON
 */
class ProductsDataCb {

	/**
	 * Save the installation data.
	 */
	public function get_data() {

		$interval  = $_POST['interval'];
		$vote_type = $_POST['vote_type']; // $vote_type == $bwl_pvm_order_type
		$order     = $_POST['order'] ?? 'desc';
		$limit     = $_POST['limit'] ?? 10;

		$query_results = WpvaHelpers::pvm_woo_filter_data( $interval, $vote_type, $order, $limit );

		if ( count( $query_results ) > 0 ) :

			$pvm_woo_icon_data = WpvaHelpers::get_pvm_woo_icon_data();

			$pvm_like_thumb_html    = $pvm_woo_icon_data['pvm_like_thumb_html'];
			$pvm_dislike_thumb_html = $pvm_woo_icon_data['pvm_dislike_thumb_html'];

			$pvm_voted_post_string = '';

			foreach ( $query_results as $bpvm_filter_post_info ) {

				$bpvm_post_id = $bpvm_filter_post_info->postid;

				$bpvm_post_total_votes = $bpvm_filter_post_info->tv;

				$bpvm_product_title = get_the_title( $bpvm_post_id );

				$bpvm_product_link = get_permalink( $bpvm_post_id );

				$post_thumb = '';

				if ( \has_post_thumbnail( $bpvm_post_id ) && $thumb == 1 ) {

					// $post_thumb = get_the_post_thumbnail($bpvm_post_id, 'pvm-post-thumb');

				}

				$bwl_pvm_like_vote_string = '';

				$bwl_pvm_dislike_vote_string = '';

				if ( $vote_type == '2' ) {

					$bwl_pvm_dislike_vote_string = $pvm_dislike_thumb_html . ' ' . $bpvm_post_total_votes . ' &nbsp; ';

					$pvm_voted_post_string .= "<li><a href='" . $bpvm_product_link . "'>" . $post_thumb . $bpvm_product_title . '</a><br />' . $bwl_pvm_dislike_vote_string . $bwl_pvm_like_vote_string . '</li>';
				} else {

					$bwl_pvm_like_vote_string = $pvm_like_thumb_html . ' ' . $bpvm_post_total_votes . ' &nbsp; ';

					$pvm_voted_post_string .= "<li><a href='" . $bpvm_product_link . "'>" . $post_thumb . $bpvm_product_title . '</a><br />' . $bwl_pvm_like_vote_string . $bwl_pvm_dislike_vote_string . '</li>';
				}
			}

			$pvm_voted_post_string .= '';

    else :

        $pvm_voted_post_string = '<p>' . esc_html__( 'Sorry, No Product Found!', 'bpvm_wpva' ) . '</p>';

    endif;

    wp_reset_postdata();

    echo $pvm_voted_post_string;

    wp_die();
	}
}
