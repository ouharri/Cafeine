<?php
/**
 * WooCommerce API routes for usage in WP's RestApi.
 *
 * @since 2.8.0
 *
 * @author  Eduardo Nakatsuka
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rest Api class.
 *
 * @since 2.8.0
 */
class OMAPI_WooCommerce_RestApi extends OMAPI_BaseRestApi {

	/**
	 * The OMAPI_WooCommerce_Save instance.
	 *
	 * @since 2.13.0
	 *
	 * @var OMAPI_WooCommerce_Save
	 */
	public $save;

	/**
	 * Constructor
	 *
	 * @since 2.13.0
	 *
	 * @param OMAPI_WooCommerce_Save $save
	 */
	public function __construct( OMAPI_WooCommerce_Save $save ) {
		$this->save = $save;
		parent::__construct();
	}

	/**
	 * Registers the Rest API routes for WooCommerce
	 *
	 * @since 2.8.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			$this->namespace,
			'woocommerce/autogenerate',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'autogenerate' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/save',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'save' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/disconnect',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'disconnect' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/key',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_key' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'woocommerce/display-rules',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'get_display_rules_info' ),
			)
		);
	}

	/**
	 * Handles auto-generating the WooCommerce API key/secret.
	 *
	 * Route: POST omapp/v1/woocommerce/autogenerate
	 *
	 * @since 2.0.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi woocommerce_autogenerate
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function autogenerate( $request ) {
		try {

			$auto_generated_keys = $this->save->autogenerate();
			if ( is_wp_error( $auto_generated_keys ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $auto_generated_keys );
			}

			if ( empty( $auto_generated_keys ) ) {
				throw new Exception( esc_html__( 'WooCommerce REST API keys could not be auto-generated on your behalf. Please try again.', 'optin-monster-api' ), 400 );
			}

			$data = $this->base->get_option();

			// Merge data array, with auto-generated keys array.
			$data = array_merge( $data, $auto_generated_keys );

			$this->save->connect( $data );

			if ( ! empty( $this->save->error ) ) {
				throw new Exception( $this->save->error, 400 );
			}

			return $this->get_key( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles saving the WooCommerce API key/secret.
	 *
	 * Route: POST omapp/v1/woocommerce/save
	 *
	 * @since 2.0.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi woocommerce_save
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function save( $request ) {
		try {

			$woo_key = $request->get_param( 'key' );
			if ( empty( $woo_key ) ) {
				throw new Exception( esc_html__( 'Consumer key missing!', 'optin-monster-api' ), 400 );
			}

			$woo_secret = $request->get_param( 'secret' );
			if ( empty( $woo_secret ) ) {
				throw new Exception( esc_html__( 'Consumer secret missing!', 'optin-monster-api' ), 400 );
			}

			$data = array(
				'consumer_key'    => $woo_key,
				'consumer_secret' => $woo_secret,
			);

			$this->save->connect( $data );

			if ( ! empty( $this->save->error ) ) {
				throw new Exception( $this->save->error, 400 );
			}

			return $this->get_key( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles disconnecting the WooCommerce API key/secret.
	 *
	 * Route: POST omapp/v1/woocommerce/disconnect
	 *
	 * @since 2.0.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi woocommerce_disconnect
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function disconnect( $request ) {
		try {

			$this->save->disconnect( array() );

			if ( ! empty( $this->save->error ) ) {
				throw new Exception( $this->save->error, 400 );
			}

			return new WP_REST_Response(
				array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
				200
			);

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Gets the associated WooCommerce API key data.
	 *
	 * Route: GET omapp/v1/woocommerce/key
	 *
	 * @since 2.0.0
	 * @since 2.8.0 Migrated from OMAPI_RestApi woocommerce_get_key
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function get_key( $request ) {
		try {

			$keys_tab       = OMAPI_WooCommerce::version_compare( '3.4.0' ) ? 'advanced' : 'api';
			$keys_admin_url = admin_url( "admin.php?page=wc-settings&tab={$keys_tab}&section=keys" );

			if ( ! OMAPI_WooCommerce::is_minimum_version() && OMAPI_WooCommerce::is_connected() ) {

				$error = '<p>' . esc_html( sprintf( __( 'OptinMonster requires WooCommerce %s or above.', 'optin-monster-api' ), OMAPI_WooCommerce::MINIMUM_VERSION ) ) . '</p>'
					. '<p>' . esc_html_x( 'This site is currently running: ', 'the current version of WooCommerce: "WooCommerce x.y.z"', 'optin-monster-api' )
					. '<code>WooCommerce ' . esc_html( OMAPI_WooCommerce::version() ) . '</code>.</p>'
					. '<p>' . esc_html__( 'Please upgrade to the latest version of WooCommerce to enjoy deeper integration with OptinMonster.', 'optin-monster-api' ) . '</p>';

				throw new Exception( $error, 404 );
			}

			if ( ! OMAPI_WooCommerce::is_connected() ) {
				$error = '<p>' . sprintf( __( 'In order to integrate WooCommerce with the Display Rules in the campaign builder, OptinMonster needs <a href="%s" target="_blank">WooCommerce REST API credentials</a>. OptinMonster only needs Read access permissions to work.', 'optin-monster-api' ), esc_url( $keys_admin_url ) ) . '</p>';

				throw new Exception( $error, 404 );
			}

			// Set some default key details.
			$defaults = array(
				'key_id'        => '',
				'description'   => esc_html__( 'no description found', 'optin-monster-api' ),
				'truncated_key' => esc_html__( 'no truncated key found', 'optin-monster-api' ),
			);

			// Get the key details.
			$key_id  = $this->base->get_option( 'woocommerce', 'key_id' );
			$details = OMAPI_WooCommerce::get_key_details_by_id( $key_id );
			$r       = wp_parse_args( array_filter( $details ), $defaults );

			return new WP_REST_Response(
				array(
					'id'          => $key_id,
					'description' => esc_html( $r['description'] ),
					'truncated'   => esc_html( $r['truncated_key'] ),
					'editUrl'     => esc_url_raw( add_query_arg( 'edit-key', $r['key_id'], $keys_admin_url ) ),
				),
				200
			);

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Retrieves the WooCommerce cart data for display rules.
	 *
	 * Route: GET omapp/v1/woocommerce/display-rules
	 *
	 * @since 2.12.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function get_display_rules_info( $request ) {
		return new WP_REST_Response(
			$this->base->woocommerce->get_cart(),
			200
		);
	}
}
