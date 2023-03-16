<?php
/**
 * OMAPI_Rules_False class.
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
 * Rules exception false class.
 *
 * @since 1.5.0
 */
class OMAPI_Rules_False extends OMAPI_Rules_Exception {
	protected $bool = false;
}
