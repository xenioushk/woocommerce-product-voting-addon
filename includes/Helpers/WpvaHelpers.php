<?php
namespace WPVAADDON\Helpers;

use WPVAADDON\Helpers\PluginConstants;

/**
 * Class for plugin helpers.
 *
 * @package WPVAADDON
 */
class WpvaHelpers {

    /**
     * Get the voting icons.
     *
     * @return array
     */
	public static function get_pvm_woo_icon_data() {

        $pvm_data = PluginConstants::$plugin_options;

        // Like Button Icon
        $pvm_like_thumb_icon = $pvm_data['pvm_like_thumb_icon'] ?? 'fa-thumbs-o-up';
        $pvm_like_thumb_html = '<i class="fa ' . $pvm_like_thumb_icon . ' icon_like_color"></i>';

        // Dislike Button Icon
        $pvm_dislike_thumb_icon = $pvm_data['pvm_dislike_thumb_icon'] ?? 'fa-thumbs-o-down';
        $pvm_dislike_thumb_html = '<i class="fa ' . $pvm_dislike_thumb_icon . ' icon_dislike_color"></i>';

        return [
            'pvm_like_thumb_html'    => $pvm_like_thumb_html,
            'pvm_dislike_thumb_html' => $pvm_dislike_thumb_html,
        ];
	}

    /**
     * Get the WooCommerce filter data.
     *
     * @param string $interval The interval.
     * @param string $vote_type The vote type.
     * @param string $order The order.
     * @param int    $limit The limit.
     *
     * @return array
     */
	public static function pvm_woo_filter_data( $interval, $vote_type, $order, $limit ) {

        global $wpdb;

        $post_type = 'product';

		if ( $interval == 'custom' ) {

            // Custom Date Range.
            // bpvm_change_date_format($custom_date);
            $query = 'SELECT postid, SUM(`total_votes`) AS tv FROM `' . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                                `vote_date`>='{$vis}' AND `vote_date`<='{$vie}' GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
		} elseif ( $interval == 'count_all' ) {

            $vis = '2012-01-01'; // Start Date.
            $vie = date( 'Y-m-d' ); // Current Date.
            // Custom Date Range.
            // bpvm_change_date_format($custom_date);
            $query = 'SELECT postid, SUM(`total_votes`) AS tv FROM `' . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                                `vote_date`>='{$vis}' AND `vote_date`<='{$vie}' GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
		} elseif ( $interval == '6_month' ) {

            // 6 Month interval from current date.

            $query = 'SELECT postid, SUM(`total_votes`) AS tv FROM `' . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 6 month AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
		} elseif ( $interval == '1_month' ) {

            // 1 Month interval from current date.

            $query = 'SELECT postid, SUM(`total_votes`) AS tv FROM `' . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 1 month AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
		} elseif ( $interval == '1_day' ) {

            // 1 day interval from current date.

            $query = 'SELECT postid, SUM(`total_votes`) AS tv FROM `' . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 1 day AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
		} else {

            // 1 Week  interval from current date. ( Default )

            $query = 'SELECT postid, SUM(`total_votes`) AS tv FROM `' . $wpdb->prefix . "bpvm_summary` WHERE 1 AND `post_type` = '{$post_type}' AND `vote_type`={$vote_type} AND 
                                " . $wpdb->prefix . "bpvm_summary.vote_date BETWEEN now() - interval 1 week AND now() GROUP BY `postid` ORDER BY tv {$order} limit 0, {$limit}";
		}

        $query_results = $wpdb->get_results( $query );

        return $query_results;
	}
}
