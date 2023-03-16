<?php
/**
 * Install Skin  class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WordPress class extended for on-the-fly addon installations.
 */
class OMAPI_InstallSkin extends WP_Upgrader_Skin {

	/**
	 * Empty out the header of its HTML content and only check to see if it has
	 * been performed or not.
	 *
	 * @since 1.9.10
	 */
	public function header() {}

	/**
	 * Empty out the footer of its HTML contents.
	 *
	 * @since 1.9.10
	 */
	public function footer() {}

	/**
	 * Instead of outputting HTML for errors, json_encode the errors and send them
	 * back to the Ajax script for processing.
	 *
	 * @since 1.9.10
	 *
	 * @param array $errors Array of errors with the install process.
	 */
	public function error( $errors ) {
		if ( ! empty( $errors ) ) {
			wp_send_json_error( $errors );
		}
	}

	/**
	 * Empty out the feedback method to prevent outputting HTML strings as the install
	 * is progressing.
	 *
	 * @since 1.9.10
	 *
	 * @param string $string The feedback string.
	 */
	public function feedback( $string, ...$args ) {}

	/**
	 * Empty out JavaScript output that calls function to decrement the update counts.
	 *
	 * @since 1.9.10
	 *
	 * @param string $type Type of update count to decrement.
	 */
	public function decrement_update_count( $type ) {}
}
