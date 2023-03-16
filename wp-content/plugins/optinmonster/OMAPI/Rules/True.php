<?php
/**
 * OMAPI_Rules_True class.
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
 * Rules exception true class.
 *
 * @since 1.5.0
 */
class OMAPI_Rules_True extends OMAPI_Rules_Exception {
	protected $bool = true;
}
