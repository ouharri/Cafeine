<?php

namespace WeglotWP\Third\ContactForm7;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Contactform7_Active
 *
 * @since 3.1.2
 */
class Contactform7_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.1.2
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_contactform7_is_active', $active );
	}
}
