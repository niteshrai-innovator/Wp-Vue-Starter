<?php
namespace WkwpErpAddon\Includes;

class Frontend {

    public function __construct() {
        add_shortcode( 'wkwp-erp-addon-app', [ $this, 'render_frontend' ] );
    }

    /**
     * Render Frontend
     * @since 1.0.0
     */
    public function render_frontend( $atts, $content = '' ) {
        wp_enqueue_style( 'wkwp-erp-addon-frontend' );
        wp_enqueue_script( 'wkwp-erp-addon-frontend' );

        $content .= '<div id="wkwp-erp-addon-frontend-app"></div>';

        return $content;
    }

}