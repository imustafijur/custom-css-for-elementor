<?php

if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'admin_enqueue_scripts', function() {
    // Check if we're on the Elementor settings page
    $current_screen = get_current_screen();
    if ( $current_screen && $current_screen->id === 'elementor_page_elementor-settings' ) {
        // Enqueue CSS for the popup with version based on file modification time
        wp_enqueue_style(
            'soovex-documentation-popup',
            plugins_url( 'documentation-popup.css', __FILE__ ),
            [],
            filemtime( plugin_dir_path( __FILE__ ) . 'documentation-popup.css' )
        );

        // Enqueue JavaScript for the popup with version based on file modification time
        wp_enqueue_script(
            'soovex-documentation-popup',
            plugins_url( 'documentation-popup.js', __FILE__ ),
            [ 'jquery' ], // Dependencies
            filemtime( plugin_dir_path( __FILE__ ) . 'documentation-popup.js' ),
            true // Load in footer
        );
    }
} );