<?php

namespace WeglotWP\Third\WpOptimize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;


/**
 * Wp_Optimize_Cache
 *
 * @since 3.1.4
 */
class Wp_Optimize_Cache implements Hooks_Interface_Weglot {
	/**
	 * @var Wp_Optimize_Active
	 */
	private $wp_optimize_active_services;

	/**
	 * @since 3.1.4
	 * @return void
	 */
	public function __construct() {
		$this->wp_optimize_active_services = weglot_get_service( 'Wp_Optimize_Active' );
	}

	/**
	 * @since 3.1.4
	 * @see Hooks_Interface_Weglot
	 * @return void
	 */
	public function hooks() {

		if ( ! $this->wp_optimize_active_services->is_active() ) {
			return;
		}

		add_filter( 'wpo_can_cache_page', [ $this, 'weglot_wpo_can_cache_page' ] );
	}


	/**
	 * @param $can_cache_page
	 * @return bool
	 * @since 3.1.4
	 */
	public function weglot_wpo_can_cache_page( $can_cache_page ) {

		/** @var $request_url_services Request_Url_Service_Weglot */
		$request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
		/** @var $language_services Language_Service_Weglot */
		$language_services = weglot_get_service( 'Language_Service_Weglot' );

		if ( $request_url_services->get_current_language() !== $language_services->get_original_language() ) {
			return false;
		}

		return $can_cache_page;
	}

}
