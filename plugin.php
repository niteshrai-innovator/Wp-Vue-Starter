<?php
/**
 * @link            https://wk-wp-erp-addon.com
 * @since           1.0.0
 * @package         WP_Vue_KickStart
 *
 * Plugin Name: WP Vue KickStart
 * Plugin URI: https://wk-wp-erp-addon.com
 * Description: A wp vue starter for plugin development.
 * Version: 1.0.0
 * Author: Md. Rabiul Islam Robi
 * Author URI: https://robizstory.me
 * License: GPL v3
 * Text-Domain: textdomain
 */

if( ! defined( 'ABSPATH' ) ) exit(); // No direct access allowed

/**
 * Require Autoloader
 */
require_once 'vendor/autoload.php';

use WkwpErpAddon\Api\Api;
use WkwpErpAddon\Includes\Admin;
use WkwpErpAddon\Includes\Frontend;

final class WP_Vue_Kickstart {

    /**
     * Define Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Construct Function
     */
    public function __construct() {
        $this->plugin_constants();
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Plugin Constants
     * @since 1.0.0
     */
    public function plugin_constants() {
        define( 'WKWP_ERP_ADDON_VERSION', self::VERSION );
        define( 'WKWP_ERP_ADDON_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'WKWP_ERP_ADDON_PLUGIN_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
        define( 'WKWP_ERP_ADDON_NONCE', 'b?le*;K7.T2jk_*(+3&[G[xAc8O~Fv)2T/Zk9N:GKBkn$piN0.N%N~X91VbCn@.4' );
    }

    /**
     * Singletone Instance
     * @since 1.0.0
     */
    public static function init() {
        static $instance = false;

        if( !$instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * On Plugin Activation
     * @since 1.0.0
     */
    public function activate() {
        $is_installed = get_option( 'wkwp_erp_addon_is_installed' );

        if( ! $is_installed ) {
            update_option( 'wkwp_erp_addon_is_installed', time() );
        }

        update_option( 'wkwp_erp_addon_is_installed', WKWP_ERP_ADDON_VERSION );
    }

    /**
     * On Plugin De-actiavtion
     * @since 1.0.0
     */
    public function deactivate() {
        // On plugin deactivation
    }

    /**
     * Init Plugin
     * @since 1.0.0
     */
    public function init_plugin() {
        // init
        new Admin();
        new Frontend();
        new Api();
    }

}

/**
 * Initialize Main Plugin
 * @since 1.0.0
 */
function wp_vue_kickstart() {
    return WP_Vue_Kickstart::init();
}

// Run the Plugin
wp_vue_kickstart();