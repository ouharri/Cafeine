<?php

namespace WeglotWP\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 *
 * @since 2.0
 */
abstract class Helper_Json_Inline_Weglot {

	/**
	 * @since 2.3.0
	 *
	 * @param string $string
	 * @return boolean
	 */
	public static function is_json( $string ) {
		return is_string( $string ) && is_array( \json_decode( $string, true ) ) && ( JSON_ERROR_NONE === \json_last_error() ) ? true : false;
	}

	/**
	 * @since 2.3.0
	 *
	 * @param string $string
	 * @return boolean
	 */
	public static function is_xml( $string ) {
		return is_string( $string ) && simplexml_load_string( $string, "SimpleXMLElement", LIBXML_NOERROR ) ? true : false;
	}
}
