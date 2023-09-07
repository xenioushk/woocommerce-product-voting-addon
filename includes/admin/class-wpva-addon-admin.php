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

        if (!class_exists('BWL_Pro_Voting_Manager') || !class_exists('WooCommerce') || BPVMWPVA_PARENT_PLUGIN_INSTALLED_VERSION < BPVMWPVA_PARENT_PLUGIN_REQUIRED_VERSION) {
            add_action('admin_notices', [$this, 'wpva_version_update_admin_notice']);
            return false;
        }

        // Start Plugin Admin Panel Code.

        $plugin = BPVM_Wpva::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
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

        echo '<div class="notice notice-error">
        <p><span class="dashicons dashicons-info-outline"></span> ' . esc_html__("You need to download & install", "bpvm_wpva") .
            ' <b><a href="https://1.envato.market/bpvm-wp" target="_blank">' . BPVMWPVA_ADDON_PARENT_PLUGIN_TITLE . '</a></b> ' . esc_html__("and", "bpvm_wpva") . ' '
            . '<b><a href="http://downloads.wordpress.org/plugin/woocommerce.zip" target="_blank">WooCommerce</a></b> '
            . esc_html__("Plugins to use", "bpvm_wpva") . ' <b>' . BPVMWPVA_ADDON_TITLE . '</b>.</p></div>';
    }

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
        wp_register_script($this->plugin_slug . '-admin', BPVMWPVA_DIR . 'assets/scripts/admin.js', ['jquery'], BPVMWPVA_ADDON_CURRENT_VERSION, TRUE);
        wp_localize_script(
            $this->plugin_slug . '-admin',
            'wpvaBpvmAdminData',
            [
                'product_id' => BPVMWPVA_CC_ID,
                'installation' => get_option(BPVMWPVA_INSTALLATION_TAG)
            ]
        );
    }

    public function includeFiles()
    {
        include_once BPVMWPVA_PATH . 'includes/admin/autoupdater/WpAutoUpdater.php';
        include_once BPVMWPVA_PATH . 'includes/admin/autoupdater/installer.php';
        include_once BPVMWPVA_PATH . 'includes/admin/autoupdater/updater.php';
        include_once BPVMWPVA_PATH . 'includes/admin/metainfo/BpvmWpvaMetaInfo.php';
    }
}
