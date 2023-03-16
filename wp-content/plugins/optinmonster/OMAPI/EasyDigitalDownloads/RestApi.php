<?php
/**
 * EasyDigitalDownloads API routes for usage in WP's RestApi.
 *
 * @since 2.8.0
 *
 * @author  Gabriel Oliveira
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
class OMAPI_EasyDigitalDownloads_RestApi extends OMAPI_BaseRestApi {

	/**
	 * The OMAPI_EasyDigitalDownloads instance.
	 *
	 * @since 2.8.0
	 *
	 * @var OMAPI_EasyDigitalDownloads
	 */
	public $edd;

	/**
	 * Constructor
	 *
	 * @since 2.13.0
	 *
	 * @param OMAPI_EasyDigitalDownloads $edd
	 */
	public function __construct( OMAPI_EasyDigitalDownloads $edd ) {
		$this->edd = $edd;
		parent::__construct();
	}

	/**
	 * Registers the Rest API routes for EasyDigitalDownloads
	 *
	 * @since 2.8.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {

		register_rest_route(
			$this->namespace,
			'edd/autogenerate',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_manage_shop' ),
				'callback'            => array( $this, 'autogenerate' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'edd/save',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'save' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'edd/disconnect',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'disconnect' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'edd/settings',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_settings' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'edd/display-rules',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'get_display_rules_info' ),
			)
		);
	}

	/**
	 * Determine if logged in user can manage the shop
	 *
	 * @since 2.8.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_manage_shop( $request ) {
		return $this->can_update_settings( $request ) && $this->edd->can_manage_shop();
	}

	/**
	 * Handles autogenerating and connecting EDD plugin with our app.
	 *
	 * Route: POST omapp/v1/edd/autogenerate
	 *
	 * @since 2.8.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function autogenerate( $request ) {
		try {
			$connected = $this->edd->save->autogenerate();

			if ( is_wp_error( $connected ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $connected );
			}

			return $this->get_settings( $request );
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles connect the EDD API key/token to our app.
	 *
	 * Route: POST omapp/v1/edd/save
	 *
	 * @since 2.8.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function save( $request ) {
		try {
			$public_key = $request->get_param( 'publicKey' );

			if ( empty( $public_key ) ) {
				throw new Exception( esc_html__( 'Public Key is missing!', 'optin-monster-api' ), 400 );
			}

			$token = $request->get_param( 'token' );

			if ( empty( $token ) ) {
				throw new Exception( esc_html__( 'Token is missing!', 'optin-monster-api' ), 400 );
			}

			$connected = $this->edd->save->connect( $public_key, $token );

			if ( is_wp_error( $connected ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $connected );
			}

			return $this->get_settings( $request );
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles disconnecting the EDD API key/token.
	 *
	 * Route: POST omapp/v1/edd/disconnect
	 *
	 * @since 2.8.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function disconnect( $request ) {
		try {
			$disconnected = $this->edd->save->disconnect();

			if ( is_wp_error( $disconnected ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $disconnected );
			}

			return $this->get_settings( $request );
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Gets the associated EDD settings, such as is connected, key, token, etc.
	 *
	 * Route: GET omapp/v1/edd/settings
	 *
	 * @since 2.8.0
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function get_settings() {
		try {
			$truncated_key   = substr( $this->base->get_option( 'edd', 'key' ), 0, 8 );
			$truncated_token = substr( $this->base->get_option( 'edd', 'token' ), 0, 8 );
			$user_id         = absint( $this->base->get_option( 'edd', 'user', 0 ) );
			$path            = ! $user_id || $user_id === get_current_user_id()
				? 'profile.php#publickey'
				: sprintf( 'user-edit.php?user_id=%d#publickey', $user_id );

			$description = __( 'token', 'optin-monster-api' );
			if ( $user_id ) {
				$user = get_user_by( 'ID', $user_id );
				if ( ! empty( $user->user_login ) ) {
					$description = sprintf(
						esc_html__( '%s -', 'optin-monster-api' ),
						$user->user_login
					);
				}
			}
			return new WP_REST_Response(
				array(
					'key'                 => $truncated_key,
					'token'               => $truncated_token,
					'editUrl'             => esc_url_raw( admin_url( $path ) ),
					'description'         => $description,
					'isEddConnected'      => OMAPI_EasyDigitalDownloads::is_connected(),
					'isEddMinimumVersion' => OMAPI_EasyDigitalDownloads::is_minimum_version(),
					'currentVersion'      => OMAPI_EasyDigitalDownloads::version(),
					'minimumVersion'      => OMAPI_EasyDigitalDownloads::MINIMUM_VERSION,
				),
				200
			);

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Gets the necessary information for Display Rules.
	 * This is used when there's an event on cart page to update information in the frontend.
	 *
	 * Route: GET omapp/v1/edd/display-rules
	 *
	 * @since 2.8.0
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function get_display_rules_info() {
		$edd_output = new OMAPI_EasyDigitalDownloads_Output();

		return new WP_REST_Response(
			$edd_output->display_rules_data(),
			200
		);
	}
}
