<?php
/**
 * Api class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Api class.
 *
 * @since 1.0.0
 */
class OMAPI_Api {

	/**
	 * Holds the last instantiated instance of this class.
	 *
	 * @var OMAPI_Api
	 */
	protected static $instance = null;

	/**
	 * Base API route.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $base = OPTINMONSTER_APP_URL;

	/**
	 * Current API route.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|string
	 */
	public $route = false;

	/**
	 * Full API URL endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|string
	 */
	public $url = false;

	/**
	 * Current API method.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|string
	 */
	public $method = false;

	/**
	 * API Username.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|string
	 */
	public $user = false;

	/**
	 * API Key.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|string
	 */
	public $key = false;

	/**
	 * New API Key.
	 *
	 * @since 1.3.4
	 *
	 * @var bool|string
	 */
	public $apikey = false;

	/**
	 * Plugin slug.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|string
	 */
	public $plugin = false;

	/**
	 * The Api Version (v1 or v2) for this request.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	public $version = 'v1';

	/**
	 * Additional data to add to request body
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $additional_data = array();

	/**
	 * The HTTP response array.
	 *
	 * @since 1.6.5
	 *
	 * @var null|array
	 */
	public $response = null;

	/**
	 * The HTTP response code.
	 *
	 * @since 1.6.5
	 *
	 * @var int
	 */
	public $response_code = 0;

	/**
	 * The parsed HTTP response body.
	 *
	 * @since 1.6.5
	 *
	 * @var mixed
	 */
	public $response_body = null;

	/**
	 * JSON decode error from decoding the response, if found.
	 *
	 * @since 2.6.6
	 *
	 * @var mixed
	 */
	public $decode_error = null;

	/**
	 * Builds the API Object
	 *
	 * @since 1.8.0
	 *
	 * @param string $version The Api Version (v1 or v2).
	 * @param string $route   The Api Endpoint/route.
	 * @param string $method  The Request method.
	 * @param array  $creds   Array of API credentials.
	 *
	 * @return self
	 */
	public static function build( $version, $route, $method = 'POST', $creds = array() ) {
		if ( empty( $creds ) ) {
			$creds = OMAPI::get_instance()->get_api_credentials();

			if ( ! empty( $creds ) ) {

				// Check if we have the new API and if so only use it.
				$creds = ! empty( $creds['apikey'] )
					? array( 'apikey' => $creds['apikey'] )
					: array(
						'user' => ! empty( $creds['user'] ) ? $creds['user'] : '',
						'key'  => ! empty( $creds['key'] ) ? $creds['key'] : '',
					);
			}
		}

		return new self( $route, $creds, $method, $version );
	}

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $route   The API route to target.
	 * @param array  $creds   Array of API credentials.
	 * @param string $method  The API method.
	 * @param string $version The version number of our API.
	 */
	public function __construct( $route, $creds, $method = 'POST', $version = 'v1' ) {
		// Set class properties.
		$this->route   = $route;
		$this->version = $version;
		$this->method  = $method;
		$this->user    = ! empty( $creds['user'] ) ? $creds['user'] : '';
		$this->key     = ! empty( $creds['key'] ) ? $creds['key'] : '';
		$this->apikey  = ! empty( $creds['apikey'] ) ? $creds['apikey'] : '';
		$this->plugin  = OMAPI::get_instance()->plugin_slug;

		self::$instance = $this;
	}

	/**
	 * Processes the API request.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Request args.
	 *
	 * @return mixed $value The response to the API call.
	 */
	public function request( $args = array() ) {
		// Build the body of the request.
		$body = array(
			'omapi-user' => $this->user,
			'omapi-key'  => $this->key,
		);
		$body = array_filter( $body );

		// If a plugin API request, add the data.
		if ( 'info' === $this->route || 'update' === $this->route ) {
			$body['omapi-plugin'] = $this->plugin;
		}

		// Add in additional data if needed.
		if ( ! empty( $this->additional_data ) ) {
			$body['omapi-data'] = maybe_serialize( $this->additional_data );
		}

		$body = wp_parse_args( $args, $body );
		$url  = in_array( $this->method, array( 'GET', 'DELETE' ), true )
			? add_query_arg( array_map( 'urlencode', $body ), $this->get_url() )
			: $this->get_url();

		$url = esc_url_raw( $url );

		// Build the headers of the request.
		$headers = array(
			'Content-Type'  => 'application/x-www-form-urlencoded',
			'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0',
			'Pragma'        => 'no-cache',
			'Expires'       => 0,
			'Origin'        => site_url(),
			'OMAPI-Referer' => site_url(),
			'OMAPI-Sender'  => 'WordPress',
			'OMAPI-Site'    => esc_attr( get_option( 'blogname' ) ),
			'OMAPI-Version' => esc_attr( OMAPI::get_instance()->version ),
			'OMAPI-Plugins' => ( new OMAPI_Plugins() )->get_active_plugins_header_value(),
		);

		if ( $this->apikey ) {
			$headers['X-OptinMonster-ApiKey'] = $this->apikey;
		}
		// Setup data to be sent to the API.
		$data = array(
			'headers'   => $headers,
			'body'      => $body,
			'timeout'   => 3000,
			'sslverify' => false,
			'method'    => $this->method,
		);

		// Perform the query and retrieve the response.
		$this->handle_response( wp_remote_request( $url, $data ) );

		// Bail out early if there are any errors.
		if ( is_wp_error( $this->response ) ) {
			return $this->response;
		}

		// If we used the legacy api-creds, we'll get back a new api key.
		if (
			empty( $this->apikey )
			&& ! empty( $this->response['headers']['x-optinmonster-apikey'] )
		) {
			$this->apikey = sanitize_text_field( $this->response['headers']['x-optinmonster-apikey'] );
		}

		$error = $this->check_response_error();

		// Bail out early if there are any errors.
		if ( is_wp_error( $error ) ) {
			return $error;
		}

		// Return the json decoded content.
		return $this->response_body;
	}

	/**
	 * Handle setting up the object properties from the response.
	 *
	 * @since 2.6.6
	 *
	 * @param  object $response The response object from wp_remote_request.
	 *
	 * @return void
	 */
	public function handle_response( $response ) {
		$this->response = $response;

		// Get the response code and response body.
		$this->response_code = wp_remote_retrieve_response_code( $response );
		$this->response_body = json_decode( wp_remote_retrieve_body( $response ) );
		$this->decode_error  = json_last_error();
	}

	/**
	 * Check for an error response, and return an applicable WP_Error instance.
	 *
	 * @since 2.6.6
	 *
	 * @return boolean|WP_Error False if no errors, and WP_Error object if found.
	 */
	public function check_response_error() {
		$code = (int) $this->response_code;

		if ( $code < 400 ) {
			return false;
		}

		// If not successful status header, send back error.
		$type    = ! empty( $this->response_body->type ) ? $this->response_body->type : 'api-error';
		$message = ! empty( $this->response_body->message ) ? stripslashes( $this->response_body->message ) : '';
		if ( empty( $message ) ) {
			$message = ! empty( $this->response_body->status_message ) ? stripslashes( $this->response_body->status_message ) : '';
		}

		if ( empty( $message ) ) {
			$message = ! empty( $this->response_body->error ) ? stripslashes( $this->response_body->error ) : 'unknown';
		}

		$message = sprintf(
			/* translators: %1$s - API response code, %2$s - returned error from API. */
			__( 'The API returned a <strong>%1$s</strong> response with this message: <strong>%2$s</strong>', 'optin-monster-api' ),
			$this->response_code,
			$message
		);

		return new WP_Error( $type, $message, $this->response_code );
	}

	/**
	 * The gets the URL based on our base, endpoint and version
	 *
	 * @since 1.8.0
	 *
	 * @return string The API url.
	 */
	public function get_url() {
		return $this->base . '/' . $this->version . '/' . $this->route;
	}

	/**
	 * Sets a class property.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key The property to set.
	 * @param string $val The value to set for the property.
	 * @return mixed $value The response to the API call.
	 */
	public function set( $key, $val ) {
		$this->{$key} = $val;
	}

	/**
	 * Allow additional data to be passed in the request
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The data to set.
	 *
	 * @return void
	 */
	public function set_additional_data( array $data ) {
		$this->additional_data = array_merge( $this->additional_data, $data );
	}

	/**
	 * Clear additional data
	 *
	 * @since 1.9.0
	 *
	 * return void
	 */
	public function clear_additional_data() {
		$this->additional_data = null;

		return $this;
	}

	/**
	 * Get the request credentials for this API object.
	 *
	 * @since 2.3.0
	 *
	 * @return array Array containing API credentials.
	 */
	public function get_creds() {
		return ! empty( $this->apikey )
			? array( 'apikey' => $this->apikey )
			: array(
				'user' => $this->user,
				'key'  => $this->key,
			);
	}

	/**
	 * Returns the last instantiated instance of this class.
	 *
	 * @since 1.9.10
	 *
	 * @return  A single instance of this class.
	 */
	public static function instance() {
		return self::$instance;
	}

	/**
	 * Fetch from the OM /me route, and cache results if no error..
	 *
	 * @since 2.6.6
	 *
	 * @param  bool  $refresh Whether to refresh the cache.
	 * @param  array $creds   Existing credentials array.
	 *
	 * @return array          Requested /me data.
	 */
	public static function fetch_me_cached( $refresh = false, $creds = array() ) {
		$api = self::build( 'v2', 'me?includeOnboarding=true', 'GET', $creds );

		$creds     = array( $api->user, $api->key, $api->apikey );
		$creds     = array_filter( $creds );
		$creds     = array_values( $creds );
		$cache_key = 'omapp_me_cached' . md5( implode( ':', $creds ) );
		$result    = get_transient( $cache_key );

		if ( empty( $result ) || $refresh ) {
			$result = $api->request();

			if ( ! is_wp_error( $result ) ) {
				set_transient( $cache_key, $result, DAY_IN_SECONDS );

				// Force the option to be updated when we gather new data from the API.
				self::return_option_from_fetch( $result, array(), $creds, true );
			}
		}

		return $result;
	}

	/**
	 * Fetch from the OM /me route, and store data to our options.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $option Existing options array.
	 * @param  array $creds  Existing credentials array.
	 *
	 * @return array           Updated options array.
	 */
	public static function fetch_me( $option = array(), $creds = array() ) {
		$result = self::fetch_me_cached( true, $creds );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return self::return_option_from_fetch( $result, $option, $creds, empty( $option ) );
	}

	/**
	 * Return the option after fetching data from the /me route, potentially
	 * updating it in the database as well.
	 *
	 * @since 2.6.13
	 *
	 * @param  stdClass $result        The /me route result.
	 * @param  array    $option        Possible option to be passed.
	 * @param  array    $creds         Possible creds to be passed.
	 * @param  bool     $should_update Flag to update the option in the database or not.
	 *
	 * @return array                   Updated options array.
	 */
	public static function return_option_from_fetch( $result, $option = array(), $creds = array(), $should_update = false ) {
		$api = self::instance();
		if ( $should_update ) {
			$option = OMAPI::get_instance()->get_option();
		}

		// Make sure to set the new api key, if we have it.
		if ( empty( $option['api']['apikey'] ) && ! empty( $api->apikey ) ) {
			$option['api'] = array( 'apikey' => $api->apikey );

			if ( $api->user && $api->key ) {
				// Notify user of credentials replacement.
				OMAPI::get_instance()->notifications->add_event(
					array(
						'type'    => 'success',
						'title'   => 'Your API Access Credentials have been updated',
						'content' => 'We have automatically replaced your deprecated user/key OptinMonster connection credentials with a new API key.',
						'btns'    => array(
							'main' => array(
								'text' => 'Manage API Keys',
								'url'  => esc_url_raw( OPTINMONSTER_APP_URL . '/account/api/' ),
							),
						),
					)
				);
			}
		}

		if ( isset( $result->id ) ) {
			$option['userId'] = $result->id;
		}

		$to_store = array( 'accountId', 'currentLevel', 'plan', 'revenueAttribution' );
		foreach ( $to_store as $key ) {
			if ( isset( $result->{$key} ) ) {
				$option[ $key ] = is_object( $result->{$key} ) ? (array) $result->{$key} : $result->{$key};
			}
		}

		if ( $should_update ) {
			OMAPI::get_instance()->save->update_option( $option, $creds );
		}

		return $option;
	}

	/**
	 * Get the home/rest/admin url args.
	 *
	 * @since 2.13.0
	 *
	 * @return array
	 */
	public static function getUrlArgs() {
		return array(
			'homeUrl'  => esc_url_raw( home_url() ),
			'restUrl'  => esc_url_raw( get_rest_url() ),
			'adminUrl' => esc_url_raw( get_admin_url() ),
		);
	}

}
