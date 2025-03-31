<?php
namespace WkwpErpAddon\Includes;

class Admin {

    /**
     * Construct Function
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts_styles' ] );
    }

    public function register_scripts_styles() {
        $this->load_scripts();
        $this->load_styles();
    }

    /**
     * Load Scripts
     *
     * @return void
     */
    public function load_scripts() {
        wp_register_script( 'wkwp-erp-addon-manifest', WKWP_ERP_ADDON_PLUGIN_URL . 'assets/js/manifest.js', [], rand(), true );
        wp_register_script( 'wkwp-erp-addon-vendor', WKWP_ERP_ADDON_PLUGIN_URL . 'assets/js/vendor.js', [ 'wkwp-erp-addon-manifest' ], rand(), true );
        wp_register_script( 'wkwp-erp-addon-admin', WKWP_ERP_ADDON_PLUGIN_URL . 'assets/js/admin.js', [ 'wkwp-erp-addon-vendor' ], rand(), true );

        wp_enqueue_script( 'wkwp-erp-addon-manifest' );
        wp_enqueue_script( 'wkwp-erp-addon-vendor' );
        wp_enqueue_script( 'wkwp-erp-addon-admin' );

        wp_localize_script( 'wkwp-erp-addon-admin', 'wkwp_erp_addonAdminLocalizer', [
            'adminUrl'  => admin_url( '/' ),
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'apiUrl'    => home_url( '/wp-json' ),
            'rest'                => [
                'root'    => esc_url_raw( get_rest_url() ),
                'nonce'   => wp_create_nonce( 'wp_rest' ),
                'version' => 'wkwp-erp-addon',
            ],
        ] );
    }

    public function load_styles() {
        wp_register_style( 'wkwp-erp-addon-admin', WKWP_ERP_ADDON_PLUGIN_URL . 'assets/css/admin.css' );

        wp_enqueue_style( 'wkwp-erp-addon-admin' );
    }

    /**
     * Register Menu Page
     * @since 1.0.0
     */
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';
        $slug       = 'wk-wp-erp-addon';

        $hook = add_menu_page(
            __( 'WP ERP ADDON', 'textdomain' ),
            __( 'WP ERP ADDON', 'textdomain' ),
            $capability,
            $slug,
            [ $this, 'menu_page_template' ],
            'dashicons-buddicons-replies',
            5
        );

        if( current_user_can( $capability )  ) {
            $submenu[ $slug ][] = [ __( 'Dashboard', 'textdomain' ), $capability, 'admin.php?page=' . $slug . '#/' ];
            $submenu[ $slug ][] = [ __( 'Events', 'textdomain' ), $capability, 'admin.php?page=' . $slug . '#/events' ];
            $submenu[ $slug ][] = [ __( 'Settings', 'textdomain' ), $capability, 'admin.php?page=' . $slug . '#/settings' ];
        }

        // add_action( 'load-' . $hook, [ $this, 'init_hooks' ] );
    }

    /**
     * Init Hooks for Admin Pages
     * @since 1.0.0
     */
    public function init_hooks() {
        add_action( 'admin_enqueu_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load Necessary Scripts & Styles
     * @since 1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'wkwp-erp-addon-admin' );
        wp_enqueue_script( 'wkwp-erp-addon-admin' );
    }

    /**
     * Render Admin Page
     * @since 1.0.0
     */
    public function menu_page_template() {
        echo '<div class="wrap"><div id="wkwp-erp-addon-admin-app"></div></div>';
    }

}