<?php
/**
 * Abstract class to handle all the modules on the plugin.
 *
 * @package CookieYes\Lite\Includes
 */

namespace CookieYes\Lite\Admin\Modules\Cache\Services;

use CookieYes\Lite\Admin\Modules\Cache\Services\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Module
 */
class Wp_Rocket extends Services {

	/**
	 * Load plugin hooks
	 *
	 * @return void
	 */
	public function run() {
		$this->load_hooks();
	}
	/**
	 * Check if the the cache service is installed/active;
	 *
	 * @return boolean
	 */
	public function is_active() {
		return function_exists( 'rocket_clean_domain' );
	}

	/**
	 * Clear the cache if any.
	 *
	 * @return boolean
	 */
	public function clear_cache() {
		return rocket_clean_domain();
	}

}
