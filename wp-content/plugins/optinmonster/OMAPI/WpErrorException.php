<?php
/**
 * WP_Error Exception class.
 *
 * @since 2.0.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP_Error Exception class.
 *
 * @since 2.0.0
 */
class OMAPI_WpErrorException extends Exception {

	/**
	 * The WP_Error object to this exception.
	 *
	 * @since 2.0.0
	 *
	 * @var null|WP_Error
	 */
	protected $wp_error = null;

	/**
	 * Sets the WP_Error object to this exception.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Error $error The WP_Error object.
	 */
	public function setWpError( WP_Error $error ) {
		$this->wp_error = $error;

		return $this;
	}

	public function getWpError() {
		return $this->wp_error;
	}
}
