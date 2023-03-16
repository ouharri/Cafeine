<?php

namespace WeglotWP\Third\Wprocket;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Option_Service_Weglot;



/**
 * Wp_Optimize_Cache
 *
 * @since 3.1.4
 */
class Wprocket_Cache implements Hooks_Interface_Weglot {
	/**
	 * @var Wprocket_Active
	 */
	private $wprocket_active_services;
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;

	/**
	 * @return void
	 * @throws Exception
	 * @since 3.1.4
	 */
	public function __construct() {
		$this->wprocket_active_services = weglot_get_service( 'Wprocket_Active' );
		$this->option_services          = weglot_get_service( 'Option_Service_Weglot' );
	}

	/**
	 * @return void
	 * @throws Exception
	 * @since 3.1.4
	 * @see Hooks_Interface_Weglot
	 */
	public function hooks() {
		if ( ! $this->wprocket_active_services->is_active() ) {
			return;
		}

		if ( ! isset( $_COOKIE['weglot_wp_rocket_cache'] ) && $this->option_services->get_option( 'auto_redirect' ) ) {
			add_action( 'send_headers', array( $this, 'set_weglot_wp_rocket_cache' ), 10, 1 );
		}
	}

	public function set_weglot_wp_rocket_cache(){
		setcookie( 'weglot_wp_rocket_cache', 'true' ); //phpcs:ignore
	}
}
