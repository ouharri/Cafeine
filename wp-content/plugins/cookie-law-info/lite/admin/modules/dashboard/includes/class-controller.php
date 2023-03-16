<?php
/**
 * Dashboard controller class.
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 *
 * @package    CookieYes\Lite\Admin\Modules\Dashboard\Includes
 */

namespace CookieYes\Lite\Admin\Modules\Dashboard\Includes;

use CookieYes\Lite\Integrations\Cookieyes\Includes\Cloud;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dashboard controller class.
 *
 * @class       Controller
 * @version     3.0.0
 * @package     CookieYes
 */
class Controller extends Cloud {

	/**
	 * Instance of the current class
	 *
	 * @var object
	 */
	private static $instance;
	/**
	 * Cookie items
	 *
	 * @var array
	 */
	public $languages;

	/**
	 * Return the current instance of the class
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load data
	 *
	 * @return array
	 */
	public function get_items() {
		$data = array();
		if ( ! $this->get_website_id() ) {
			return $data;
		}
		$response      = $this->get(
			'websites/' . $this->get_website_id() . '/dashboard'
		);
		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $response_code ) {
			$response = json_decode( wp_remote_retrieve_body( $response ), true );
			$stats    = isset( $response['statistics'] ) ? $response['statistics'] : array();
			$data     = array(
				'cookies'    => isset( $stats['total_cookies'] ) ? $stats['total_cookies'] : 0,
				'scripts'    => isset( $stats['total_scripts'] ) ? $stats['total_scripts'] : 0,
				'categories' => isset( $stats['total_categories'] ) ? $stats['total_categories'] : 0,
				'pages'      => isset( $stats['total_pages'] ) ? $stats['total_pages'] : 0,
			);
		}
		return $data;
	}
}
