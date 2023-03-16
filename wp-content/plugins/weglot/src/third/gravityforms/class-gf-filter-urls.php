<?php

namespace WeglotWP\Third\Gravityforms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Helpers\Helper_Filter_Url_Weglot;


/**
 * @since 3.0.0
 */
class GF_Filter_Urls implements Hooks_Interface_Weglot {
	/**
	 * @var Gf_Active
	 */
	private $gf_active_services;

	/**
	 * @return void
	 * @since 2.0
	 */
	public function __construct() {
		$this->gf_active_services = weglot_get_service( 'Gf_Active' );
	}

	/**
	 * @return void
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 3.0.0
	 */
	public function hooks() {
		if ( ! $this->gf_active_services->is_active() ) {
			return;
		}

		add_filter( 'gform_confirmation', array( $this, 'weglot_gform_confirmation' ) );
		add_filter( 'weglot_init', array( $this, 'weglot_gform_input_upload' ) ); //phpcs:ignore
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 * @since 3.0.0
	 */
	public function weglot_gform_confirmation( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}

		if ( ! array_key_exists( 'redirect', $data ) ) {
			return $data;
		}

		#todo change logic in multisite context
		if ( ! is_multisite() ) {
			$data['redirect'] = Helper_Filter_Url_Weglot::filter_url_lambda( $data['redirect'] );
		}

		return $data;

	}

	/**
	 * @return bool
	 * @since 3.0.0
	 */
	public function weglot_gform_input_upload() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && isset( $_SERVER['REQUEST_URI'] ) ) { //phpcs:ignore
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && strpos( sanitize_url( $_SERVER['REQUEST_URI'] ), '?gf_page' ) !== false ) { //phpcs:ignore
				add_filter( 'weglot_autoredirect_only_home', '__return_true' );
			}
		}

		return false;
	}

}
