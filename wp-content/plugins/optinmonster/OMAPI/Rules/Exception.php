<?php
/**
 * OMAPI_Rules_Exception class.
 *
 * @since 1.5.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rules exception base class.
 *
 * @since 1.5.0
 */
class OMAPI_Rules_Exception extends Exception {
	protected $bool       = null;
	protected $exceptions = array();
	public function __construct( $message = null, $code = 0, Exception $previous = null ) {
		if ( is_bool( $message ) ) {
			$this->bool = $message;
			$message    = null;
		}
		parent::__construct( $message, $code, $previous );
	}

	public function get_bool() {
		return $this->bool;
	}

	public function add_exceptions( array $exceptions ) {
		$this->exceptions = $exceptions;
	}

	public function get_exceptions() {
		return (array) $this->exceptions;
	}

	public function get_exception_messages() {
		$messages = array();
		foreach ( $this->get_exceptions() as $e ) {
			$messages[] = $e->getMessage();
		}

		return $messages;
	}
}
