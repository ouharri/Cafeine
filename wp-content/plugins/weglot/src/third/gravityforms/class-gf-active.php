<?php

namespace WeglotWP\Third\Gravityforms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * @since 3.0
 */
class Gf_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.0.0
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
			return false;
		}

		return true;
	}
}
