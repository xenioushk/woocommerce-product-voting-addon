<?php

class BPVM_Wpva_Admin {

    protected static $instance = null;
    protected $plugin_screen_hook_suffix = null;

    private function __construct() {

        //@Description: First we need to check if KB Plugin & WooCommerce is activated or not. If not then we display a message and return false.
        //@Since: Version 1.0.5

        if (!class_exists('BWL_Pro_Voting_Manager') || !class_exists('WooCommerce') || BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION < '1.1.0') {
            add_action('admin_notices', array($this, 'wpva_version_update_admin_notice'));
            return false;
        }

        // Start Plugin Admin Panel Code.

        $plugin = BPVM_Wpva::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
        $post_types = 'product';
    }

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    //Version Manager:  Update Checking

    public function wpva_version_update_admin_notice() {

        echo '<div class="updated"><p>You need to download & install both '
        . '<b><a href="https://1.envato.market/bpvm-wp" target="_blank"> BWL Pro Voting Manager (Minimum Version 1.1.1)</a></b> && '
        . '<b><a href="http://downloads.wordpress.org/plugin/woocommerce.zip" target="_blank">WooCommerce Plugin</a></b>  '
        . 'to use ' . BPVMWPVA_ADDON_TITLE . '.</p></div>';
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function bkb_wpva_admin_enqueue_scripts($hook) {

        // We only load this JS script in product edit page.

        if ('edit.php' == $hook && isset($_GET['post_type']) && $_GET['post_type'] == "product") {

//            wp_enqueue_script($this->plugin_slug . '-admin-custom-script', plugins_url('assets/js/kbtfw-admin-scripts.js', __FILE__), array('jquery'), BPVM_Wpva::VERSION, TRUE);
        } else {

            return;
        }
    }

    public function include_files() {
        
    }

}
