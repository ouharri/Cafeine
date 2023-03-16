<?php
/**
 * Formatting helper function class
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 * @package    CookieYes\Lite\Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! function_exists( 'cky_sanitize_text' ) ) {

	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 * @return string|array
	 */
	function cky_sanitize_text( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'cky_sanitize_text', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists( 'cky_sanitize_bool' ) ) {

	/**
	 * Converts a string (e.g. 'yes' or 'no') to a bool.
	 *
	 * @since 3.0.0
	 * @param string|bool $string String to convert. If a bool is passed it will be returned as-is.
	 * @return bool
	 */
	function cky_sanitize_bool( $string ) {
		if ( is_string( $string ) ) {
			$string = strtolower( $string );
			if ( in_array( $string, array( 'false', '0' ), true ) ) {
				$string = false;
			}
		}
		// Everything else will map nicely to boolean.
		return (bool) $string;
	}
}

if ( ! function_exists( 'cky_allowed_html' ) ) {
	/**
	 * Returns list of HTML tags allowed in HTML fields for use in declaration of wp_kset field validation.
	 * Deliberately allows class and ID declarations to assist with custom CSS styling.
	 * To customise further, see the excellent article at: http://ottopress.com/2010/wp-quickie-kses/
	 *
	 * @return array
	 */
	function cky_allowed_html() {
		$html = array_merge(
			array(
				'input' => array(
					'type'  => 'true',
					'style' => true,
					'id'    => true,
					'class' => true,
				),
			),
			wp_kses_allowed_html( 'post' )
		);
		$html = array_map( '_cky_global_attributes', $html );
		return apply_filters( 'cky_allowed_html', $html );
	}
	/**
	 * Global attributes for any html tags
	 *
	 * @param string $value Default attribute.
	 * @return array
	 */
	function _cky_global_attributes( $value ) {
		$global_attributes = array(
			'aria-describedby' => true,
			'aria-details'     => true,
			'aria-label'       => true,
			'aria-labelledby'  => true,
			'aria-hidden'      => true,
			'class'            => true,
			'id'               => true,
			'style'            => true,
			'title'            => true,
			'role'             => true,
			'data-*'           => true,
			'data-cky-tag'     => true,
		);
		if ( true === $value ) {
			$value = array();
		}

		if ( is_array( $value ) ) {
			return array_merge( $value, $global_attributes );
		}

		return $value;
	}
}

if ( ! function_exists( 'cky_sanitize_content' ) ) {

	/**
	 * Sanitizes content for allowed HTML tags for post content.
	 *
	 * Post content refers to the page contents of the 'post' type and not `$_POST`
	 * data from forms.
	 *
	 * This function expects unslashed data.
	 *
	 * @since 3.0.0
	 *
	 * @param string $string Post content to filter.
	 * @return string Filtered post content with allowed HTML tags and attributes intact.
	 */
	function cky_sanitize_content( $string ) {
		if ( is_array( $string ) ) {
			return array_map( 'cky_sanitize_content', $string );
		} else {
			return is_scalar( $string ) ? wp_kses( $string, cky_allowed_html() ) : $string;
		}
	}
}
if ( ! function_exists( 'cky_sanitize_color' ) ) {

	/**
	 * Sanitize color value.
	 *
	 * @param string $value The color value.
	 * @return string
	 */
	function cky_sanitize_color( $value ) {
		if ( 'transparent' === strtolower( $value ) ) {
			return sanitize_text_field( $value );
		}
		if ( false === strpos( $value, 'rgba' ) ) {
			return sanitize_hex_color( $value );
		}

		// rgba value.
		$red   = '';
		$green = '';
		$blue  = '';
		$alpha = '';
		sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
}
