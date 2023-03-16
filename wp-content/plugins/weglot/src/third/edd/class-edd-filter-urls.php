<?php

namespace WeglotWP\Third\Edd;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Helpers\Helper_Filter_Url_Weglot;


class Edd_Filter_Urls implements Hooks_Interface_Weglot {
	/**
	 * @var Edd_Active
	 */
	private $edd_active_services;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->edd_active_services = weglot_get_service( 'Edd_Active' );
	}

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @return void
	 */
	public function hooks() {
		if ( ! $this->edd_active_services->is_active() ) {
			return;
		}

		add_filter( 'edd_get_success_page_uri', array( '\WeglotWP\Helpers\Helper_Filter_Url_Weglot', 'filter_url_lambda' ) );
		add_filter( 'edd_get_checkout_uri', array( '\WeglotWP\Helpers\Helper_Filter_Url_Weglot', 'filter_url_lambda' ) );
	}


}
