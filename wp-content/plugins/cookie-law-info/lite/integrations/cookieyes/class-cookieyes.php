<?php
/**
 * CookieYes Integration
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 *
 * @package    CookieYes\Lite\Integrations\Cookieyes
 */

namespace CookieYes\Lite\Integrations\Cookieyes;

use CookieYes\Lite\Includes\Request;
use CookieYes\Lite\Admin\Modules\Settings\Includes\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Request
 */
class Cookieyes extends Request {

	/**
	 * API Key
	 *
	 * @var string
	 */
	private $api_key = '';

	/**
	 * CookieYes web site id
	 *
	 * @var integer
	 */
	private $website_id;
	/**
	 * License object
	 *
	 * @var object
	 */
	/**
	 * Base URL of CookieYes API
	 */
	const API_BASE_PATH = CKY_APP_URL . '/api/v2/';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
	}
	/**
	 * Initialize necessary variables to make an API call
	 *
	 * @return void
	 */
	public function init() {
		$settings         = new Settings();
		$this->api_key    = $settings->get_token();
		$this->website_id = $settings->get_website_id();
		$this->add_header_argument( 'Content-Type', 'application/json' );
		$this->add_header_argument( 'Accept', 'application/json' );
	}
	/**
	 * Get API URL.
	 *
	 * @param string $path  Endpoint path.
	 *
	 * @return string
	 */
	public function get_api_url( $path = '' ) {
		if ( defined( 'self::API_BASE_PATH' ) && self::API_BASE_PATH ) {
			return self::API_BASE_PATH . $path;
		}
	}

	/**
	 * Get API key.
	 *
	 * @return string
	 */
	protected function get_api_key() {
		return $this->api_key;
	}

	/**
	 * Make a authenticated request by adding
	 *
	 * @return void
	 */
	protected function make_auth_request() {
		$api_key = $this->get_api_key();
		if ( ! empty( $api_key ) ) {
			$this->add_header_argument( 'Authorization', 'Bearer ' . $api_key );
		}
	}

	/**
	 * Returns the website id
	 *
	 * @return integer
	 */
	protected function get_website_id() {
		return $this->website_id;
	}
	/**
	 * Get the license info
	 *
	 * @return array
	 */
	protected function get_license() {
		return true;
	}

}
