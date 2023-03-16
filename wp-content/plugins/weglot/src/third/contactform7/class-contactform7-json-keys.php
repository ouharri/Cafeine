<?php

namespace WeglotWP\Third\ContactForm7;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;


/**
 * Contactform7_Add_Json_Keys
 *
 * @since 3.1.2
 */
class Contactform7_Json_Keys implements Hooks_Interface_Weglot {
	/**
	 * @var Contactform7_Active
	 */
	private $contactform7_active_services;

	/**
	 * @since 3.1.2
	 * @return void
	 */
	public function __construct() {
		$this->contactform7_active_services = weglot_get_service( 'Contactform7_Active' );
	}

	/**
	 * @since 3.1.2
	 * @see Hooks_Interface_Weglot
	 * @return void
	 */
	public function hooks() {

		if ( ! $this->contactform7_active_services->is_active() ) {
			return;
		}

		add_filter( 'weglot_add_json_keys', array( $this, 'weglot_contactform7_keys' ) );
	}


	/**
	 * @since 3.1.2
	 * @return void
	 */
	public function weglot_contactform7_keys( $keys ) {
		$keys[] = 'message';
		return $keys;
	}

}
