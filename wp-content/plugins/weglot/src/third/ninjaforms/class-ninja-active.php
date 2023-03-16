<?php

namespace WeglotWP\Third\Ninjaforms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Ninja_Active_Weglot
 *
 * @since 2.5.0
 */
class Ninja_Active implements Third_Active_Interface_Weglot {

	/**
	 * Ninja forms is active ?
	 * @since 2.5.0
	 *
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'ninja-forms/ninja-forms.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_ninja_forms_is_active', $active );
	}
}
