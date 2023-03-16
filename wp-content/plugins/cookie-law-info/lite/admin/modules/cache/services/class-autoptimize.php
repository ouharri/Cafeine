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
class Autoptimize extends Services {

	/**
	 * Load hooks of each plugin.
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
		return class_exists( \autoptimizeCache::class );
	}

	/**
	 * Clear the cache if any.
	 *
	 * @return boolean
	 */
	public function clear_cache() {
		return \autoptimizeCache::clearall();
	}

}
