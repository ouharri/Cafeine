<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Compatibility WP < 4.7.0
 */
if ( ! function_exists( 'wp_doing_ajax' ) ) {
	function wp_doing_ajax() {
		/**
		 * Filters whether the current request is a WordPress Ajax request.
		 *
		 * @param bool $wp_doing_ajax Whether the current request is a WordPress Ajax request.
		 *
		 * @since 4.7.0
		 *
		 */
		return apply_filters( 'wp_doing_ajax', defined( 'DOING_AJAX' ) && DOING_AJAX );
	}
}


if ( ! function_exists( 'is_rest' ) ) {
	/**
	 * Checks if the current request is a WP REST API request.
	 *
	 * Case #1: After WP_REST_Request initialisation
	 * Case #2: Support "plain" permalink settings
	 * Case #3: URL Path begins with wp-json/ (your REST prefix)
	 * Also supports WP installations in subfolders
	 *
	 * @returns boolean
	 * @author matzeeable
	 */
	function is_rest() {
		$prefix = rest_get_url_prefix();
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST // (#1)
		     || isset( $_GET['rest_route'] ) //phpcs:ignore
		        && strpos( trim( $_GET['rest_route'], '\\/' ), $prefix, 0 ) === 0 ) { //phpcs:ignore
			return true;
		}
		// (#3)
		$rest_url    = wp_parse_url( site_url( $prefix ) );
		$current_url = wp_parse_url( add_query_arg( array() ) );

		return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
	}
}

/**
 * Compatibility for library weglot-php PHP 5.4
 */
if ( ! function_exists( 'array_column' ) ) {
	function array_column( array $input, $column_key, $index_key = null ) {
		$array = array();
		foreach ( $input as $value ) {
			if ( ! array_key_exists( $column_key, $value ) ) {
				trigger_error( "Key \"$column_key\" does not exist in array" );

				return false;
			}
			if ( is_null( $index_key ) ) {
				$array[] = $value[ $column_key ];
			} else {
				if ( ! array_key_exists( $index_key, $value ) ) {
					trigger_error( "Key \"$index_key\" does not exist in array" );

					return false;
				}
				if ( ! is_scalar( $value[ $index_key ] ) ) {
					trigger_error( "Key \"$index_key\" does not contain scalar value" );

					return false;
				}
				$array[ $value[ $index_key ] ] = $value[ $column_key ];
			}
		}

		return $array;
	}
}
