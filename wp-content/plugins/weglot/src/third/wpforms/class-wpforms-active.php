<?php

namespace WeglotWP\Third\WPForms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Wpforms_Active
 *
 * @since 3.0.5
 */
class Wpforms_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.0.5
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'wpforms-lite/wpforms.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_wpforms_is_active', $active );
	}
}
