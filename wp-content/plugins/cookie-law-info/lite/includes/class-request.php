<?php
/**
 * Request handler class
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 *
 * @author     Sarath GP <sarath.gp@mozilor.com>
 * @package    CookieYes\Lite\Includes
 */

namespace CookieYes\Lite\Includes;

use WP_Error;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Request
 */
abstract class Request {

	/**
	 * API Key
	 *
	 * @var string
	 */
	private $api_key = '';

	/**
	 * Module action path
	 *
	 * @var string
	 */
	private $path = '';

	/**
	 * Request Method
	 *
	 * @var string
	 */
	private $method = 'POST';

	/**
	 * Request max timeout
	 *
	 * @var int
	 */
	private $timeout = 180;

	/**
	 * Header arguments
	 *
	 * @var array
	 */
	private $headers = array();

	/**
	 * POST arguments
	 *
	 * @var array
	 */
	private $post_args = array();

	/**
	 * GET arguments
	 *
	 * @var array
	 */
	private $get_args = array();

	/**
	 * Set the Request API Key
	 *
	 * @param string $api_key  API key.
	 */
	public function set_api_key( $api_key ) {
		$this->api_key = $api_key;
	}

	/**
	 * Set the Request API Timeout
	 *
	 * @param int|float $timeout  Timeout.
	 */
	public function set_timeout( $timeout ) {
		$this->timeout = $timeout;
	}

	/**
	 * Add a new request argument for POST requests
	 *
	 * @param string $name   Argument name.
	 * @param string $value  Argument value.
	 */
	public function add_post_argument( $name, $value ) {
		$this->post_args[ $name ] = $value;
	}

	/**
	 * Add a new request argument for GET requests
	 *
	 * @param string $name   Argument name.
	 * @param string $value  Argument value.
	 */
	public function add_get_argument( $name, $value ) {
		$this->get_args[ $name ] = $value;
	}

	/**
	 * Add a new request argument for GET requests
	 *
	 * @param string $name   Argument name.
	 * @param string $value  Argument value.
	 */
	public function add_header_argument( $name, $value ) {
		$this->headers[ $name ] = $value;
	}

	/**
	 * Get the Request URL
	 *
	 * @param string $path  Endpoint route.
	 *
	 * @return mixed
	 */
	abstract public function get_api_url( $path = '' );

	/**
	 * Make a GET API Call
	 *
	 * @param string $path  Endpoint route.
	 * @param array  $data  Data.
	 *
	 * @return mixed
	 */
	public function get( $path, $data = array() ) {
		try {
			return $this->request( $path, $data, 'get' );
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Make a GET API Call
	 *
	 * @param string $path  Endpoint route.
	 * @param array  $data  Data.
	 *
	 * @return mixed
	 */
	public function post( $path, $data = array() ) {
		try {
			return $this->request( $path, $data );
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Make a PUT API Call
	 *
	 * @param string $path  Endpoint route.
	 * @param array  $data  Data.
	 *
	 * @return mixed
	 */
	public function put( $path, $data = array() ) {
		try {
			return $this->request( $path, $data, 'put' );
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Make a GET API Call
	 *
	 * @param string $path  Endpoint route.
	 * @param array  $data  Data.
	 *
	 * @return mixed
	 */
	public function head( $path, $data = array() ) {
		try {
			return $this->request( $path, $data, 'head' );
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}

	}

	/**
	 * Make a GET API Call
	 *
	 * @param string $path  Endpoint route.
	 * @param array  $data  Data.
	 *
	 * @return mixed
	 */
	public function delete( $path, $data = array() ) {
		try {
			return $this->request( $path, $data, 'delete' );
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Make an API Request
	 *
	 * @since 1.8.1 Timeout for non-blocking changed from 0.1 to 2 seconds.
	 *
	 * @param string $path    Path.
	 * @param array  $data    Arguments array.
	 * @param string $method  Method.
	 *
	 * @return array|mixed|object
	 */
	public function request( $path, $data = array(), $method = 'post' ) {
		$url = $this->get_api_url( $path );

		$this->make_auth_request();

		$url = add_query_arg( $this->get_args, $url );
		if ( 'post' !== $method && 'put' !== $method && 'delete' !== $method ) {
			$url = add_query_arg( $data, $url );
		}

		$args = array(
			'headers'   => $this->headers,
			'sslverify' => false,
			'method'    => strtoupper( $method ),
			'timeout'   => $this->timeout,
		);

		switch ( strtolower( $method ) ) {
			case 'put':
			case 'delete':
			case 'post':
				if ( is_array( $data ) ) {
					$args['body'] = array_merge( $data, $this->post_args );
				} else {
					$args['body'] = $data;
				}
				$response = wp_remote_post( $url, $args );
				break;
			case 'head':
				$response = wp_remote_head( $url, $args );
				break;
			case 'get':
				$response = wp_remote_get( $url, $args );
				break;
			default:
				$response = wp_remote_request( $url, $args );
				break;
		}

		return $response;
	}

	/**
	 * Sign request.
	 */
	protected function make_auth_request() {}

}
