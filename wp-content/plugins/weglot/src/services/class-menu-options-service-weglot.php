<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Menu_Options_Weglot;


/**
 * @since 2.4.0
 */
class Menu_Options_Service_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;

	/**
	 * @since 2.4.0
	 */
	public function __construct() {
		$this->option_services = weglot_get_service( 'Option_Service_Weglot' );
	}

	/**
	 * @since 2.4.0
	 * @return array
	 */
	public function get_options_default() {
		$keys = Helper_Menu_Options_Weglot::get_keys();

		return apply_filters(
			'weglot_menu_switcher_options_default',
			array_map(
				function() {
					return false;
				},
				array_flip( $keys )
			)
		);
	}

	/**
	 * @since 2.4.0
	 * @return array
	 */
	public function get_list_options_menu_switcher() {
		return Helper_Menu_Options_Weglot::get_menu_switcher_list_options();
	}
}
