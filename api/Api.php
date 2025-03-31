<?php
namespace WkwpErpAddon\Api;

use WP_REST_Controller;
use WkwpErpAddon\Api\Admin\Settings_Route;
use WkwpErpAddon\Api\Admin\Events;

/**
 * Rest API Handler
 */
class Api extends WP_REST_Controller {

    /**
     * Construct Function
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Register API routes
     */
    public function register_routes() {
        ( new Settings_Route() )->register_routes();
        ( new Events() )->register_routes();
    }

}