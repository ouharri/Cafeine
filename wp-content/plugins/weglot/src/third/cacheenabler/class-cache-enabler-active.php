<?php

namespace WeglotWP\Third\CacheEnabler;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Cache_Enabler_Active
 *
 * @since 3.1.4
 */
class Cache_Enabler_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.1.4
	 * @return boolean
	 *
	 * Check if Cache Enabler plugin is active
	 * https://fr.wordpress.org/plugins/cache-enabler/
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'cache-enabler/cache-enabler.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_cache_enabler_is_active', $active );
	}
}
