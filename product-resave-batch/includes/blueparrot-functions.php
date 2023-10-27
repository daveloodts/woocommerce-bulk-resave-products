<?php
/**
 * Plugin generic functions file
 *
 * @since 1.0.0
 */

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 1.0.0
 */
function blueparrot_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'blueparrot_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash($data);
	}
}

/**
 * Sanitize URL
 * 
 * @since 1.0.0
 */
function blueparrot_clean_url( $url ) {
	return esc_url_raw( trim( $url ) );
}