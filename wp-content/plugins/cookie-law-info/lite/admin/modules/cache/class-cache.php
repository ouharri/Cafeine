<?php
/**
 * Class Cookies file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Cache;

use CookieYes\Lite\Includes\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Cookies
 * @version     3.0.0
 * @package     CookieYes
 */
class Cache extends Modules {

	/**
	 * Constructor.
	 */
	public function init() {
		add_action( 'plugins_loaded', array( $this, 'load_services' ) );
	}

	/**
	 * Load services classes.
	 *
	 * @return void
	 */
	public function load_services() {
		$modules = $this->get_services();
		foreach ( $modules as $module ) {
			$parts = explode( '_', $module );
			$temp  = array();
			foreach ( $parts as $part ) {
				$temp[] = ucfirst( $part );
			}
			$class      = implode( '_', $temp );
			$class_name = 'CookieYes\Lite\\Admin\\Modules\\Cache\\Services\\' . ucfirst( $class );

			if ( class_exists( $class_name ) ) {
				new $class_name( $module );
			}
		}
	}

	/**
	 * Get supported list of servies.
	 *
	 * @return array
	 */
	public function get_services() {
		return array(
			'wp_rocket',
			'autoptimize',
			'hummingbird',
			'w3_total_cache',
			'wp_fastest_cache',
			'wp_super_cache',
			'breeze',
			'siteground_optimize',
			'cache_enabler',
			'litespeed_cache',
		);
	}
}
