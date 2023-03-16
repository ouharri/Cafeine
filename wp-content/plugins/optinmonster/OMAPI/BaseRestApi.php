<?php
/**
 * Base Rest API Class, extend this if implementing a RestApi class.
 * Most of the code was migrated from OMAPI_RestApi.
 *
 * @since 2.8.0
 *
 * @package OMAPI
 * @author  Gabriel Oliveira and Eduardo Nakatsuka
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base Rest Api class.
 *
 * @since 2.8.0
 */
abstract class OMAPI_BaseRestApi {
	/**
	 * The Base OMAPI Object
	 *
	 *  @since 2.8.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	/**
	 * The REST API Namespace
	 *
	 *  @since 2.8.0
	 *
	 * @var string The namespace
	 */
	protected $namespace = 'omapp/v1';

	/**
	 * Whether request was given a valid api key.
	 *
	 *  @since 2.8.0
	 *
	 * @var null|bool
	 */
	protected $has_valid_api_key = null;

	/**
	 * Build our object.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$this->base = OMAPI::get_instance();
		$this->register_rest_routes();
	}

	/**
	 * Registers the Rest API routes for this class
	 *
	 * @since 2.8.0
	 *
	 * @return void
	 */
	abstract public function register_rest_routes();

	/**
	 * Determine if we can store settings.
	 *
	 * @since 2.0.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_update_settings( $request ) {
		try {

			$this->verify_request_nonce( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}

		return OMAPI::get_instance()->can_access( 'settings_update' );
	}

	/**
	 * Determine if OM API key is provided and valid.
	 *
	 * @since  1.9.10
	 * @since 2.8.0 Migrated from OMAPI_RestApi
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function has_valid_api_key( $request ) {
		$header = $request->get_header( 'X-OptinMonster-ApiKey' );

		// Use this API Key to validate.
		if ( ! $this->validate_api_key( $header ) ) {
			return new WP_Error(
				'omapp_rest_forbidden',
				esc_html__( 'Could not verify your API Key.', 'optin-monster-api' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return $this->has_valid_api_key;
	}

	/**
	 * Determine if logged in or OM API key is provided and valid.
	 *
	 * @since  1.9.10
	 * @since 2.8.0 Migrated from OMAPI_RestApi
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function logged_in_or_has_api_key( $request ) {
		return $this->logged_in_and_can_access_route( $request )
			|| true === $this->has_valid_api_key( $request );
	}

	/**
	 * Determine if logged in user can access this route (calls current_user_can).
	 *
	 * @since 2.6.4
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function logged_in_and_can_access_route( $request ) {
		return OMAPI::get_instance()->can_access( $request->get_route() );
	}

	/**
	 * Validate this API Key
	 * We validate an API Key by fetching the Sites this key can fetch
	 * And then confirming that this key has access to at least one of these sites
	 *
	 * @since 1.8.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi
	 *
	 * @param string $apikey The OM api key.
	 *
	 * @return bool True if the Key can be validated
	 */
	public function validate_api_key( $apikey ) {
		$this->has_valid_api_key = OMAPI_ApiKey::validate( $apikey );

		return $this->has_valid_api_key;
	}

	/**
	 * Convert an exception to a REST API WP_Error object.
	 *
	 * @since  2.0.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi
	 *
	 * @param  Exception $e The exception.
	 *
	 * @return WP_Error
	 */
	protected function exception_to_response( Exception $e ) {
		// Return WP_Error objects directly.
		if ( $e instanceof OMAPI_WpErrorException && $e->getWpError() ) {
			return $e->getWpError();
		}

		$code = $e->getCode();
		if ( empty( $code ) || $code < 400 ) {
			$code = 400;
		}

		$data = ! empty( $e->data ) ? $e->data : array();
		$data = wp_parse_args(
			$data,
			array(
				'status' => $code,
			)
		);

		$error_code = rest_authorization_required_code() === $code
			? 'omapp_rest_forbidden'
			: 'omapp_rest_error';

		return new WP_Error( $error_code, $e->getMessage(), $data );
	}

	/**
	 * Convert a WP_Error to a proper REST API WP_Error object.
	 *
	 * @since 2.6.5
	 * @since 2.8.0 Migrated from OMAPI_RestApi
	 *
	 * @param  WP_Error $e The WP_Error object.
	 * @param  mixed    $data Data to include in the error data.
	 *
	 * @return WP_Error
	 */
	protected function wp_error_to_response( WP_Error $e, $data = array() ) {
		$api = OMAPI_Api::instance();

		$data          = is_array( $data ) || is_object( $data ) ? (array) $data : array();
		$error_data    = $e->get_error_data();
		$error_message = $e->get_error_message();
		$error_code    = $e->get_error_code();

		if ( empty( $error_data['status'] ) ) {

			$status     = is_numeric( $error_data ) ? $error_data : 400;
			$error_code = (string) rest_authorization_required_code() === (string) $status
				? 'omapp_rest_forbidden'
				: 'omapp_rest_error';

			$error_data = wp_parse_args(
				array(
					'status' => $status,
				),
				$data
			);

		} else {
			$error_data = wp_parse_args( $error_data, $data );
		}

		return new WP_Error( $error_code, $error_message, $error_data );
	}

	/**
	 * Verify the request nonce and throw an exception if verification fails.
	 *
	 * @since  2.0.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi
	 *
	 * @param  WP_REST_Request $request The REST request.
	 *
	 * @return void
	 */
	public function verify_request_nonce( $request ) {
		$nonce = $request->get_param( 'nonce' );
		if ( empty( $nonce ) ) {
			$nonce = $request->get_header( 'X-WP-Nonce' );
		}

		if ( empty( $nonce ) ) {
			throw new Exception( esc_html__( 'Missing security token!', 'optin-monster-api' ), rest_authorization_required_code() );
		}

		// Check the nonce.
		$result = wp_verify_nonce( $nonce, 'wp_rest' );
		if ( ! $result ) {
			throw new Exception( esc_html__( 'Security token invalid!', 'optin-monster-api' ), rest_authorization_required_code() );
		}
	}
}
