<?php

namespace WeglotWP\Third\Wprocket;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Wprocket_Active
 *
 * @since 3.1.4
 */
class Wprocket_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.1.4
	 * @return boolean
	 *
	 * Check if WP Rocket plugin is active
	 * https://fr.wordpress.org/plugins/cache-enabler/
	 */
	public function is_active() {


		$active = false;

		if ( defined( 'WP_ROCKET_VERSION' ) && WP_ROCKET_VERSION ) {
			$active = true;
		}
		return apply_filters( 'weglot_wprocket_is_active', $active );
	}
}
