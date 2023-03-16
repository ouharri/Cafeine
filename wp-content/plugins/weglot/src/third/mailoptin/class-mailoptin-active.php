<?php

namespace WeglotWP\Third\MailOptin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Mailoptin_Active
 *
 * @since 3.1.2
 */
class Mailoptin_Active implements Third_Active_Interface_Weglot {

	/**
	 * MailOptin forms is active ?
	 * @since 3.1.2
	 *
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'mailoptin/mailoptin.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_mailoptin_forms_is_active', $active );
	}
}
