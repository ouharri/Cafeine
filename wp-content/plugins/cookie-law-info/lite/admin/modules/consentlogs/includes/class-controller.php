<?php
/**
 * Class Controller file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Consentlogs\Includes;

use CookieYes\Lite\Integrations\Cookieyes\Includes\Cloud;
use CookieYes\Lite\Includes\Cache;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
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
	protected $cache_group = 'consent_logs';

	/**
	 * Consent log limit
	 *
	 * @var integer
	 */
	private static $limit = 100;
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
	 * Get statistics of consent log
	 *
	 * @return array
	 */
	public function get_statistics() {
		$logs = array();
		$this->make_auth_request();
		$response      = $this->get(
			'websites/' . $this->get_website_id() . '/consent-logs/chart-data'
		);
		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $response_code ) {
			$response = json_decode( wp_remote_retrieve_body( $response ), true );
			$items    = isset( $response['consent'] ) ? $response['consent'] : array();
			if ( empty( $items ) ) {
				return $logs;
			}
			$total = 0;
			foreach ( $items as $item ) {
				$type = 'partial';
				if ( isset( $item['name'] ) ) {
					if ( 'Accepted' === $item['name'] ) {
						$type = 'accepted';
					} elseif ( 'Rejected' === $item['name'] ) {
						$type = 'rejected';
					}
				}
				$count  = isset( $item['count'] ) ? absint( $item['count'] ) : 0;
				$total  = $total + $count;
				$logs[] = array(
					'type'  => $type,
					'count' => $count,
				);
			}
			if ( $total <= 0 ) {
				return array();
			}
		}
		Cache::set( 'statistics', $this->cache_group, $logs );
		return $logs;
	}

}
