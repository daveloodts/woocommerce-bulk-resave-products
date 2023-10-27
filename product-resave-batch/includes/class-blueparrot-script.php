<?php
/**
 * Script Class
 * Handles the script and style functionality of plugin
 *
 * @since 1.0.0
 */

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class BlueParrot_Script {

	function __construct() {

		// Action to add style & script in backend
		add_action( 'admin_enqueue_scripts', array( $this, 'blueparrot_admin_script_style' ) );
	}

	/**
	 * Function to add Scripts and Styles at admin side
	 * 
	 * @since 1.0.0
	 */
	function blueparrot_admin_script_style( $hook ) {

		// Registring admin css
		wp_register_style( 'blueparrot-admin', BLUEPARROT_URL.'assets/css/blueparrot-admin.css', array(), BLUEPARROT_VERSION );

		// Registring admin script
		wp_register_script( 'blueparrot-admin', BLUEPARROT_URL.'assets/js/blueparrot-admin.js', array('jquery'), BLUEPARROT_VERSION, true );

		// Enqueue Styles
		wp_enqueue_style( 'blueparrot-admin' );	// Admin style

		// Enqueue Scripts
		wp_enqueue_script( 'blueparrot-admin' ); // Admin script
	}
}

$blueparrot_script = new BlueParrot_Script();