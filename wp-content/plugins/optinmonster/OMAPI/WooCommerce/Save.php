<?php
/**
 * WooCommerce Save class.
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
 * WooCommerce Save class.
 *
 * @since 2.8.0
 */
class OMAPI_WooCommerce_Save {

	/**
	 * Holds the class object.
	 *
	 * @since 2.8.0
	 *
	 * @var OMAPI_WooCommerce_Save
	 */
	public static $instance;

	/**
	 * Holds save error.
	 *
	 * @since 2.8.0
	 *
	 * @var mixed
	 */
	public $error = null;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.8.0
	 *
	 * @var OMAPI
	 */
	public $base;

	/**
	 * The OMAPI_WooCommerce instance.
	 *
	 * @since 2.13.0
	 *
	 * @var OMAPI_WooCommerce
	 */
	public $woo;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.8.0
	 *
	 * @param OMAPI_WooCommerce $woo
	 */
	public function __construct( OMAPI_WooCommerce $woo ) {
		$this->woo      = $woo;
		$this->base     = OMAPI::get_instance();
		self::$instance = $this;
	}

	/**
	 * Handles auto-generating WooCommerce API keys for use with OM.
	 *
	 * @since 1.7.0
	 * @since 2.8.0 Migrated from OMAPI_Save woocommerce_autogenerate
	 *
	 * @return array
	 */
	public function autogenerate() {
		$cookies = array();
		foreach ( $_COOKIE as $name => $val ) {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			$cookies[] = "$name=" . rawurlencode( is_array( $val ) ? serialize( $val ) : $val );
		}
		$cookies = implode( '; ', $cookies );

		$request_args = array(
			'sslverify' => apply_filters( 'https_local_ssl_verify', true ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'body'      => array(
				'action'      => 'woocommerce_update_api_key',
				'description' => esc_html__( 'OptinMonster API Read-Access (Auto-Generated)', 'optin-monster-api' ),
				'permissions' => 'read',
				'user'        => get_current_user_id(),
				'security'    => wp_create_nonce( 'update-api-key' ),
			),
			'timeout'   => 60,
			'headers'   => array(
			'cookie' => $cookies,
			),
		);
		$response     = wp_remote_post( admin_url( 'admin-ajax.php' ), $request_args );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if (
			200 === intval( $code )
			&& ! empty( $body->success )
			&& ! empty( $body->data->consumer_key )
			&& ! empty( $body->data->consumer_secret )
		) {

			return (array) $body->data;
		}

		return array();
	}

	/**
	 * Handles connecting WooCommerce when the connect button is clicked.
	 *
	 * @since 1.7.0
	 * @since 2.8.0 Migrated from OMAPI_Save woocommerce_connect
	 *
	 * @param array $data The data passed in via POST request.
	 *
	 * @return void
	 */
	public function connect( $data ) {
		$keys = $this->woo->validate_keys( $data );

		if ( isset( $keys['error'] ) ) {
			$this->error = $keys['error'];
		} else {

			// Get the version of the REST API we should use. The
			// `v3` route wasn't added until WooCommerce 3.5.0.
			$api_version = $this->woo->version_compare( '3.5.0' )
				? 'v3'
				: 'v2';

			// Get current site url.
			$url = esc_url_raw( site_url() );

			// Make a connection request.
			$response = $this->woo->connect(
				array(
					'consumerKey'    => $keys['consumer_key'],
					'consumerSecret' => $keys['consumer_secret'],
					'apiVersion'     => $api_version,
					'shop'           => $url,
					'name'           => esc_html( get_bloginfo( 'name' ) ),
				)
			);

			// Output an error or register a successful connection.
			if ( is_wp_error( $response ) ) {
				$this->error = isset( $response->message )
					? $response->message
					: esc_html__( 'WooCommerce could not be connected to OptinMonster. The OptinMonster API returned with the following response: ', 'optin-monster-api' ) . $response->get_error_message();
			} else {

				// Get the shop hostname.
				$site = OMAPI_Utils::parse_url( $url );
				$host = isset( $site['host'] ) ? $site['host'] : '';

				// Set up the connected WooCommerce options.
				$option                = $this->base->get_option();
				$option['woocommerce'] = array(
					'api_version' => $api_version,
					'key_id'      => $keys['key_id'],
					'shop'        => $host,
				);

				// Save the option.
				$this->base->save->update_option( $option, $data );
			}
		}
	}

	/**
	 * Handles disconnecting WooCommerce when the disconnect button is clicked.
	 *
	 * @since 1.7.0
	 * @since 2.8.0 Migrated from OMAPI_Save woocommerce_disconnect
	 *
	 * @param array $data The data passed in via POST request.
	 *
	 * @return void
	 */
	public function disconnect( $data ) {
		$response = $this->woo->disconnect();

		// Output an error or register a successful disconnection.
		if ( is_wp_error( $response ) ) {
			$this->error = isset( $response->message )
				? $response->message
				: esc_html__( 'WooCommerce could not be disconnected from OptinMonster. The OptinMonster API returned with the following response: ', 'optin-monster-api' ) . $response->get_error_message();
		} else {
			$option = $this->base->get_option();

			unset( $option['woocommerce'] );

			// Save the option.
			$this->base->save->update_option( $option, $data );
		}
	}
}
