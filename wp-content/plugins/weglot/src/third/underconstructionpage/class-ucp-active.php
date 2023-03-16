<?php

namespace WeglotWP\Third\UnderConstructionPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Ucp_Active
 *
 * @since 3.1.1
 */
class Ucp_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.1.1
	 * @return boolean
	 *
	 * Check if under-construction-page plugin is active
	 * https://fr.wordpress.org/plugins/under-construction-page/
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'under-construction-page/under-construction.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_ucp_is_active', $active );
	}
}
