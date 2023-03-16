<?php

namespace WeglotWP\Third\WpOptimize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Wp_Optimize_Active
 *
 * @since 3.1.4
 */
class Wp_Optimize_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.1.4
	 * @return boolean
	 *
	 * Check if WP Optimize plugin is active
	 * https://fr.wordpress.org/plugins/wp-optimize/
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'wp-optimize/wp-optimize.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_wp_optimize_is_active', $active );
	}
}
