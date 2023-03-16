<?php

namespace WeglotWP\Actions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\User_Api_Service_Weglot;

class Ajax_User_Info implements Hooks_Interface_Weglot {
	/**
	 * @var User_Api_Service_Weglot
	 */
	private $user_services;

	public function __construct() {
		$this->user_services = weglot_get_service( 'User_Api_Service_Weglot' );
	}

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function hooks() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'wp_ajax_get_user_info', array( $this, 'get_user_info' ) );
	}

	/**
	 * @since 3.0.0
	 * @return array
	 */
	public function get_user_info() {
		if ( ! isset( $_POST['api_key'] ) ) { //phpcs:ignore
			wp_send_json_error();
			return;
		}

		$api_key = sanitize_title( $_POST['api_key'] ); //phpcs:ignore

		$response = $this->user_services->get_user_info( $api_key );

		if ( array_key_exists( 'not_exist', $response ) && ! $response['not_exist'] ) {
			wp_send_json_error();
			return;
		}

		wp_send_json_success( $response );
	}
}

