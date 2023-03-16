<?php

namespace WeglotWP\Actions\Front;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Button_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;

/**
 *
 * @since 2.0
 */
class Shortcode_Weglot {
	/**
	 * @var Button_Service_Weglot
	 */
	private $button_services;
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->button_services      = weglot_get_service( 'Button_Service_Weglot' );
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );

		add_shortcode( 'weglot_switcher', array( $this, 'weglot_switcher_callback' ) );
	}

	/**
	 * @see weglot_switcher
	 * @since 2.0
	 *
	 * @return string
	 */
	public function weglot_switcher_callback() {
		return $this->button_services->get_html( 'weglot-shortcode' ); //phpcs:ignore
	}
}
