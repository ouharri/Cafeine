<?php
/**
 * OMU API class.
 *
 * @since 2.6.6
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OMU API class.
 *
 * @since 2.6.6
 */
class OMAPI_OmuApi extends OMAPI_Api {

	/**
	 * Holds the last instantiated instance of this class.
	 *
	 * @var OMAPI_Api
	 */
	protected static $instance = null;

	/**
	 * Base API route.
	 *
	 * @since 2.6.6
	 *
	 * @var string
	 */
	public $base = OPTINMONSTER_URL;

	/**
	 * The OMU routes map.
	 *
	 * @since 2.6.6
	 *
	 * @var string
	 */
	protected static $routes = array(
		'courses' => 'wp-json/ldlms/v1/courses?per_page=8&orderby=menu_order&order=desc&_embed=1',
		'guides'  => 'wp-json/wp/v2/omu-guides?per_page=8&orderby=menu_order&order=desc&_embed=1',
	);

	/**
	 * The cached-request TTL.
	 */
	public $cache_ttl = DAY_IN_SECONDS;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.6.6
	 *
	 * @param string $route   The API route to target.
	 * @param string $method  The API method.
	 */
	public function __construct( $route, $method = 'GET' ) {
		// Set class properties.
		$this->route  = $route;
		$this->method = $method;

		self::$instance = $this;
	}

	/**
	 * Processes the OMU REST API request.
	 *
	 * @since 2.6.6
	 *
	 * @param string $route   The API route to target.
	 * @param string $method  The API method.
	 * @param array  $args    Request args.
	 * @param bool   $refresh Whether to refresh the cache.
	 *
	 * @return mixed $value The response to the API call.
	 */
	public static function cached_request( $route, $method = 'GET', $args = array(), $refresh = false ) {
		$key_args   = $args;
		$key_args[] = $method;
		$key_args[] = $route;

		$cache_key = 'omapp_omu_cached' . md5( serialize( $key_args ) );
		$result    = get_transient( $cache_key );

		if ( empty( $result ) || $refresh ) {
			$api    = new self( $route, $method );
			$result = $api->request( $args );

			if ( ! is_wp_error( $result ) ) {

				$headers = wp_remote_retrieve_headers( $api->response );
				$result  = array(
					'data'       => $result,
					'total'      => isset( $headers['x-wp-total'] )
						? (int) $headers['x-wp-total']
						: 0,
					'totalpages' => isset( $headers['x-wp-totalpages'] )
						? (int) $headers['x-wp-totalpages']
						: 0,
				);

				set_transient( $cache_key, $result, $api->cache_ttl );
			}
		}

		return $result;
	}

	/**
	 * Processes the OMU REST API request.
	 *
	 * @since 2.6.6
	 *
	 * @param array $args Request args.
	 *
	 * @return mixed $value The response to the API call.
	 */
	public function request( $args = array() ) {
		$url = in_array( $this->method, array( 'GET', 'DELETE' ), true )
			? add_query_arg( array_map( 'urlencode', $args ), $this->get_url() )
			: $this->get_url();

		$url = esc_url_raw( $url );

		// Build the headers of the request.
		$headers = array(
			'Content-Type' => 'application/json',
		);

		// Setup data to be sent to the API.
		$data = array(
			'headers'   => $headers,
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

		$error = $this->check_response_error();

		// Bail out early if there are any errors.
		if ( is_wp_error( $error ) ) {
			return $error;
		}

		// Return the json decoded content.
		return $this->response_body;
	}

	/**
	 * The gets the URL based on our base, endpoint and version
	 *
	 * @since 2.6.6
	 *
	 * @return string The API url.
	 */
	public function get_url() {
		if ( empty( self::$routes[ $this->route ] ) ) {
			throw new Exception( sprintf( 'Missing route information for %s', $this->route ), 400 );
		}

		return trailingslashit( $this->base ) . self::$routes[ $this->route ];
	}

	/**
	 * Returns the last instantiated instance of this class.
	 *
	 * @since 2.6.6
	 *
	 * @return  A single instance of this class.
	 */
	public static function instance() {
		return self::$instance;
	}
}
