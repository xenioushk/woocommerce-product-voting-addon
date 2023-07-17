<?php

class BPVM_Wpva
{

    const VERSION = BPVMWPVA_ADDON_CURRENT_VERSION;

    protected $plugin_slug = 'bpvm-wpva';
    protected static $instance = null;

    private function __construct()
    {

        if (class_exists('BWL_Pro_Voting_Manager') && class_exists('WooCommerce') && BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION >= BPVMWPVA_PARENT_PLUGIN_REQUIRED_VERSION) {

            // Load plugin text domain
            add_action('init', [$this, 'load_plugin_textdomain']);

            // Load public-facing style sheet and JavaScript.
            add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);


            add_filter('woocommerce_get_catalog_ordering_args', [$this, 'bpvm_add_postmeta_ordering_args']);
            add_filter('woocommerce_default_catalog_orderby_options', [$this, 'bpvm_add_new_postmeta_orderby']);
            add_filter('woocommerce_catalog_orderby', [$this, 'bpvm_add_new_postmeta_orderby']);
            add_action('woocommerce_after_shop_loop_item', [$this, 'bpvm_shop_display_voting_meta'], 9);

            // This function has been added in version 1.0.2.
            // It counts and update all the products both like and dislike votes and update in to a post meta 'pvm_total_votes'.
            //                        add_action('save_post_product', [ $this, 'wpva_update_post_meta') ); // update post meta.

            $this->includeFiles();
        }
    }

    function wpva_update_total_votes()
    {

        $get_pvm_total_votes_update = get_option('pvm_total_votes_update');

        if ($get_pvm_total_votes_update != 1) {

            $args = [
                'post_type' => 'product',
                'posts_per_page' => '-1'
            ];

            $loop = new WP_Query($args);

            if ($loop->have_posts()) :

                while ($loop->have_posts()) :

                    $loop->the_post();

                    $pvm_like_votes_count = get_post_meta(get_the_ID(), "pvm_like_votes_count", true);
                    $bpvm_post_total_like_votes = empty($pvm_like_votes_count) ? 0 : $pvm_like_votes_count;

                    $pvm_dislike_votes_count = get_post_meta(get_the_ID(), "pvm_dislike_votes_count", true);
                    $bpvm_post_total_dislike_votes = empty($pvm_dislike_votes_count) ? 0 : $pvm_dislike_votes_count;

                    $bpvm_init_count = ($bpvm_post_total_like_votes + $bpvm_post_total_dislike_votes);

                    $pvm_total_votes = get_post_meta(get_the_ID(), "pvm_total_votes", true);

                    if (empty($pvm_total_votes)) {

                        update_post_meta(get_the_ID(), 'pvm_total_votes', $bpvm_init_count);
                    }

                endwhile;

            endif;

            wp_reset_query();

            //@Finally Update DB upgrade status.
            update_option('pvm_total_votes_update', 1);
        }
    }

    public function includeFiles()
    {
        require_once(BPVMWPVA_PATH . 'includes/widgets/pvm-woo-widget.php');
    }

    function bpvm_shop_display_voting_meta()
    {

        global $product;

        $post_id = $product->get_id();

        $bwl_pvm_display_status = get_post_meta($post_id, "bwl_pvm_display_status", true);

        if ($bwl_pvm_display_status == "") {
            $bwl_pvm_display_status = 1;
        }

        echo do_shortcode('[bwl_pvm id=' . $post_id . ' status=' . $bwl_pvm_display_status . ' woo=1]');
    }

    function bpvm_add_postmeta_ordering_args($sort_args)
    {

        $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

        switch ($orderby_value) {

                // Name your sortby key whatever you'd like; must correspond to the $sortby in the next function
            case 'pvm_like_votes_count':
                $sort_args['orderby'] = 'meta_value_num';
                // Sort by meta_value because we're using alphabetic sorting
                $sort_args['order'] = 'DESC';
                $sort_args['meta_key'] = 'pvm_like_votes_count';
                break;

            case 'pvm_dislike_votes_count':
                $sort_args['orderby'] = 'meta_value_num';
                // We use meta_value_num here because points are a number and we want to sort in numerical order
                $sort_args['order'] = 'DESC';
                $sort_args['meta_key'] = 'pvm_dislike_votes_count';
                break;

            case 'pvm_asc_total_vote':
                $sort_args['orderby'] = 'meta_value_num';
                // Sort by meta_value because we're using alphabetic sorting
                $sort_args['order'] = 'ASC';
                $sort_args['meta_key'] = 'pvm_total_votes';
                break;

            case 'pvm_desc_total_vote':
                $sort_args['orderby'] = 'meta_value_num';
                // Sort by meta_value because we're using alphabetic sorting
                $sort_args['order'] = 'DESC';
                $sort_args['meta_key'] = 'pvm_total_votes';
                break;
        }

        return $sort_args;
    }

    // Add these new sorting arguments to the sortby options on the frontendBPVM_UVT_Admin
    function bpvm_add_new_postmeta_orderby($sortby)
    {

        // Adjust the text as desired

        $sortby['pvm_like_votes_count'] = __('Sort by top liked', 'bpvm_wpva');
        $sortby['pvm_dislike_votes_count'] = __('Sort by top disliked', 'bpvm_wpva');

        $sortby['pvm_asc_total_vote'] = __('Sort by votes: low to high', 'bpvm_wpva');
        $sortby['pvm_desc_total_vote'] = __('Sort by votes: high to low', 'bpvm_wpva');

        return $sortby;
    }

    function wpva_update_post_meta($post_id)
    {
        add_post_meta($post_id, 'pvm_like_votes_count', 0, true);
        add_post_meta($post_id, 'pvm_dislike_votes_count', 0, true);
    }

    // Return the plugin slug.

    public function get_plugin_slug()
    {
        return $this->plugin_slug;
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    1.0.0
     *
     * @param    int    $blog_id    ID of the new blog.
     */
    public function activate_new_site($blog_id)
    {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    1.0.0
     *
     * @return   array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids()
    {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate()
    {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate()
    {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');

        $this->wpva_update_total_votes();
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-frontend', BPVMWPVA_DIR . 'assets/styles/frontend.css', [], self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_slug . '-frontend', BPVMWPVA_DIR . 'assets/scripts/frontend.js', ['jquery'], self::VERSION);
    }
}
