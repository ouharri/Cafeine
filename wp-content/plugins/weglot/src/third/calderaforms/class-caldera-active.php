<?php

namespace WeglotWP\Third\Calderaforms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Caldera_Active
 *
 * @since 2.6.0
 */
class Caldera_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 2.6.0
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = defined( 'CFCORE_VER' );

		return apply_filters( 'weglot_caldera_forms_is_active', $active );
	}
}
