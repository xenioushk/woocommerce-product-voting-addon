<?php

class BPVM_Wpva {


    const VERSION = BPVMWPVA_ADDON_CURRENT_VERSION;

    protected $plugin_slug     = 'bpvm-wpva';
    protected static $instance = null;

    private function __construct() {

        if ( class_exists( 'BPVMWP\\Init' )
            && class_exists( 'WooCommerce' )
            && BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION >= BPVMWPVA_PARENT_PLUGIN_REQUIRED_VERSION
            && BPVMWPVA_PARENT_PLUGIN_PURCHASE_STATUS == 1
        ) {

            // Load public-facing style sheet and JavaScript.

            // add_filter( 'woocommerce_get_catalog_ordering_args', [ $this, 'bpvm_add_postmeta_ordering_args' ] );
            // add_filter( 'woocommerce_default_catalog_orderby_options', [ $this, 'bpvm_add_new_postmeta_orderby' ] );
            // add_filter( 'woocommerce_catalog_orderby', [ $this, 'bpvm_add_new_postmeta_orderby' ] );

            // add_action( 'woocommerce_after_shop_loop_item', [ $this, 'bpvm_shop_display_voting_meta' ], 9 );

            // This function has been added in version 1.0.2.
            // It counts and update all the products both like and dislike votes and update in to a post meta 'pvm_total_votes'.
            // add_action('save_post_product', [ $this, 'wpva_update_post_meta') ); // update post meta.

            $this->includeFiles();
        }
    }


    public function includeFiles() {
        include_once BPVMWPVA_PATH . 'includes/widgets/pvm-woo-widget.php';
    }

    function bpvm_shop_display_voting_meta() {

        global $product;

        $post_id = $product->get_id();

        $bwl_pvm_display_status = get_post_meta( $post_id, 'bwl_pvm_display_status', true );

        if ( $bwl_pvm_display_status == '' ) {
            $bwl_pvm_display_status = 1;
        }

        echo do_shortcode( '[bwl_pvm id=' . $post_id . ' status=' . $bwl_pvm_display_status . ' woo=1]' );
    }

    function bpvm_add_postmeta_ordering_args( $sort_args ) {

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

    // Add these new sorting arguments to the sortby options on the frontendBPVM_UVT_Admin
    function bpvm_add_new_postmeta_orderby( $sortby ) {

        // Adjust the text as desired

        $sortby['pvm_like_votes_count']    = esc_html__( 'Sort by top liked', 'bpvm_wpva' );
        $sortby['pvm_dislike_votes_count'] = esc_html__( 'Sort by top disliked', 'bpvm_wpva' );

        $sortby['pvm_asc_total_vote']  = esc_html__( 'Sort by votes: low to high', 'bpvm_wpva' );
        $sortby['pvm_desc_total_vote'] = esc_html__( 'Sort by votes: high to low', 'bpvm_wpva' );

        return $sortby;
    }

    function wpva_update_post_meta( $post_id ) {
        add_post_meta( $post_id, 'pvm_like_votes_count', 0, true );
        add_post_meta( $post_id, 'pvm_dislike_votes_count', 0, true );
    }
}
