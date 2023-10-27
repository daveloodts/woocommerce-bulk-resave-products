<?php
/**
 * Plugin Name: Product Resave in Batches
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/popup-anything-click/
 * Text Domain: product-resave-in-batches
 * Description: A plugin to resave WooCommerce products in batches.
 * Domain Path: /languages/
 * Version: 1.0.0
 * Author: Essential Plugin
 * Author URI: https://www.essentialplugin.com/
 * Contributors: WP OnlineSupport, Essential Plugin
 *
 * @package Product Resave in Batches
*/

if( ! defined( 'BLUEPARROT_VERSION' ) ) {
    define( 'BLUEPARROT_VERSION', '1.0.0' ); // Version of plugin
}

if( ! defined( 'BLUEPARROT_DIR' ) ) {
    define( 'BLUEPARROT_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'BLUEPARROT_URL' ) ) {
    define( 'BLUEPARROT_URL', plugin_dir_url( __FILE__ )); // Plugin url
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Product Resave in Batches
 * @since 1.0
 */
function blueparrot_load_textdomain() {

    global $wp_version;

    // Set filter for plugin's languages directory
    $blueparrot_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
    $blueparrot_lang_dir = apply_filters( 'blueparrot_languages_directory', $blueparrot_lang_dir );

    // Traditional WordPress plugin locale filter.
    $get_locale = get_locale();

    if ( $wp_version >= 4.7 ) {
        $get_locale = get_user_locale();
    }

    // Traditional WordPress plugin locale filter
    $locale = apply_filters( 'plugin_locale',  $get_locale, 'product-resave-in-batches' );
    $mofile = sprintf( '%1$s-%2$s.mo', 'product-resave-in-batches', $locale );

    // Setup paths to current locale file
    $mofile_global  = WP_LANG_DIR . '/plugins/' . basename( BLUEPARROT_DIR ) . '/' . $mofile;

    if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
        load_textdomain( 'product-resave-in-batches', $mofile_global );
    } else { // Load the default language files
        load_plugin_textdomain( 'product-resave-in-batches', false, $blueparrot_lang_dir );
    }
}
add_action( 'plugins_loaded', 'blueparrot_load_textdomain' );

// Function File
require_once( BLUEPARROT_DIR . '/includes/blueparrot-functions.php' );

// Script Class File
require_once( BLUEPARROT_DIR . '/includes/class-blueparrot-script.php' );

// Load Admin Files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

    // Admin Class File
    require_once( BLUEPARROT_DIR . '/includes/admin/class-blueparrot-admin.php' );
}