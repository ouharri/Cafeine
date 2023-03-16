<?php

namespace WeglotWP\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @since 2.4.0
 */
abstract class Helper_Menu_Options_Weglot {

	/**
	 * @var string
	 */
	const HIDE_CURRENT = 'hide_current';

	/**
	 * @var string
	 */
	const DROPDOWN = 'dropdown';

	/**
	 * @since 2.4.0
	 * @static
	 * @return array
	 */
	public static function get_menu_switcher_list_options() {
		return apply_filters(
			'weglot_menu_switcher_options',
			array(
				array(
					'key'   => self::HIDE_CURRENT,
					'title' => __( 'Hide the current language', 'weglot' ),
				),
			)
		);
	}

	/**
	 * @since 2.4.0
	 * @static
	 * @return array
	 */
	public static function get_keys() {
		return apply_filters(
			'weglot_menu_switcher_options_keys',
			array(
				self::HIDE_CURRENT,
				self::DROPDOWN,
			)
		);
	}
}
