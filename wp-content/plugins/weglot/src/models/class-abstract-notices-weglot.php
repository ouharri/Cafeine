<?php

namespace WeglotWP\Models;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Abstract class for manage admin notices
 *
 * @abstract
 * @since 2.0
 */
abstract class Abstract_Notices_Weglot {

	/**
	 * Get template file for admin notice
	 * @static
	 * @since 2.0
	 *
	 * @return string
	 */
	public static function get_template_file() {
		return '';
	}

	/**
	 * Callback for admin_notice hook
	 *
	 * @since 2.0
	 * @static
	 *
	 * @return string
	 */
	public static function admin_notice() {
		$class_call = get_called_class();
		if ( ! file_exists( $class_call::get_template_file() ) ) {
			return;
		}

		include_once $class_call::get_template_file();
	}
}
