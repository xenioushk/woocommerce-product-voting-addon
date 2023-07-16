<?php

class BPVM_Wpva_Admin
{

    protected static $instance = null;
    protected $plugin_screen_hook_suffix = null;
    public $plugin_slug;

    private function __construct()
    {

        //@Description: First we need to check if KB Plugin & WooCommerce is activated or not. If not then we display a message and return false.
        //@Since: Version 1.0.5

        if (!class_exists('BWL_Pro_Voting_Manager') || !class_exists('WooCommerce') || BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION < '1.1.0') {
            add_action('admin_notices', array($this, 'wpva_version_update_admin_notice'));
            return false;
        }

        // Start Plugin Admin Panel Code.

        $plugin = BPVM_Wpva::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        $this->includeFiles();

        $post_types = 'product';
    }

    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    //Version Manager:  Update Checking

    public function wpva_version_update_admin_notice()
    {

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
    public function bkb_wpva_admin_enqueue_scripts($hook)
    {

        // We only load this JS script in product edit page.

        if ('edit.php' == $hook && isset($_GET['post_type']) && $_GET['post_type'] == "product") {
        } else {

            return;
        }
    }

    public function enqueue_scripts()
    {
        wp_register_script($this->plugin_slug . '-admin', BPVM_UVT_ADDON_DIR . 'assets/scripts/admin.js', ['jquery'], BPVM_Wpva::VERSION, TRUE);
        wp_localize_script(
            $this->plugin_slug . '-admin',
            'wpvaBpvmAdminData',
            [
                'product_id' => 14564197,
                'installation' => get_option('wpva_bpvm_installation')
            ]
        );
    }

    public function includeFiles()
    {
        if (is_admin()) {

            include_once BPVMWPVA_PATH . 'includes/autoupdater/WpAutoUpdater.php';
            include_once BPVMWPVA_PATH . 'includes/autoupdater/installer.php';
            include_once BPVMWPVA_PATH . 'includes/autoupdater/updater.php';
        }
    }
}
