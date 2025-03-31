<?php
namespace WkwpErpAddon\Api\Admin;

use WP_REST_Controller;

class Events extends WP_REST_Controller  {

    protected $namespace;
    protected $rest_base;

    public function __construct() {
        $this->namespace = 'wkwp-erp-addon/v1';
        $this->rest_base = 'events';
    }

    /**
     * Register Routes
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_events' ],
                    'permission_callback' => [ $this, 'get_events_permission_check' ],
                    'args'                => $this->get_collection_params()
                ],
                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_events' ],
                    'permission_callback' => [ $this, 'create_events_permission_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema(true )
                ]
            ]
        );
    }

    /**
     * Get events response
     */
    public function get_events( $request ) {

        $response = [
            ['id' => 1,
            'first_name' => 'Nitesh',
            'last_name'  => 'Rai',
            "email" => "niteshrai375@gmail.com",
            "phone" => "+917860767440",
            "company" => "Search Webway",
            ]
        ];

        $response = rest_ensure_response( $response );

        $response->set_status( 200 );

        return $response;
    }

    /**
     * Get events permission check
     */
    public function get_events_permission_check( $request ) {
        // if( current_user_can( 'administrator' ) ) {
        //     return true;
        // }

        return true;
    }

    /**
     * Create item response
     */
    public function create_events( $request ) {

        // Data validation
        $firstname = isset( $request['firstname'] ) ? sanitize_text_field( $request['firstname'] ): '';
        $lastname  = isset( $request['lastname'] ) ? sanitize_text_field( $request['lastname'] )  : '';
        $email     = isset( $request['email'] ) && is_email( $request['email'] ) ? sanitize_email( $request['email'] ) : '';

        // Save option data into WordPress
        update_option( 'wkwp_erp_addon_settings_firstname', $firstname );
        update_option( 'wkwp_erp_addon_settings_lastname', $lastname );
        update_option( 'wkwp_erp_addon_settings_email', $email );

        $response = [
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email
        ];

        return rest_ensure_response( $response );
        
    }

    /**
     * Create item permission check
     */
    public function create_events_permission_check( $request ) {
        return true;
    }

    /**
     * Retrives the query parameters for the events collection
     */
    public function get_collection_params() {
        return [];
    }

}