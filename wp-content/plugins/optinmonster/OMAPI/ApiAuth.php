<?php
/**
 * Api Auth class.
 *
 * @since 2.6.5
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Api Auth class.
 *
 * @since 2.6.5
 */
class OMAPI_ApiAuth {

	/**
	 * Get the auth token from the DB..
	 *
	 * @since 2.6.5
	 *
	 * @return array  Auth token array.
	 */
	public static function get_token() {
		return get_option(
			'optinmonster_site_token',
			array(
				'expires' => 0,
				'tt'      => '',
			)
		);
	}

	/**
	 * Check if token exists in DB.
	 *
	 * @since 2.6.5
	 *
	 * @return boolean Whether it exists.
	 */
	public static function has_token() {
		$token = self::get_token();

		return ! empty( $token['expires'] ) && ! empty( $token['tt'] );
	}

	/**
	 * Get the tt value from the auth token (or generate the auth token).
	 *
	 * @since 2.6.5
	 *
	 * @return string  The tt value from the auth token.
	 */
	public static function get_tt() {
		$token = self::get_token();

		if ( empty( $token['tt'] ) ) {

			// if TT is empty, generate a new one, save it and then return it.
			$token = array(
				'expires' => time() + ( 2 * MINUTE_IN_SECONDS ),
				'tt'      => self::generate_tt(),
			);
			update_option( 'optinmonster_site_token', $token );
		}

		return $token['tt'];
	}

	/**
	 * Generate the tt value (long random string).
	 *
	 * @since 2.6.5
	 *
	 * @return string  Tt value.
	 */
	public static function generate_tt() {
		return hash( 'sha512', wp_generate_password( 128, true, true ) . AUTH_SALT . uniqid( '', true ) );
	}

	/**
	 * Validate whether given tt value matches auth token tt value,
	 * and whether the auth token has expired.
	 *
	 * @since 2.6.5
	 *
	 * @param  string $passed_tt The tt value to validate.
	 *
	 * @return bool              Whether tt value is validated with the token.
	 */
	public static function validate_token( $passed_tt = '' ) {
		if ( empty( $passed_tt ) ) {
			return false;
		}

		$token = self::get_token();
		if ( empty( $token ) ) {
			return false;
		}

		$expired = ! empty( $token['expires'] ) ? $token['expires'] < time() : true;
		$tt      = ! empty( $token['tt'] ) ? $token['tt'] : '';
		$matches = hash_equals( $tt, $passed_tt );

		return $matches && ! $expired;
	}

	/**
	 * Delete the auth token.
	 *
	 * @since 2.6.5
	 *
	 * @return bool True if the option was deleted, false otherwise.
	 */
	public static function delete_token() {
		return delete_option( 'optinmonster_site_token' );
	}
}
