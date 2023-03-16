<?php

namespace WeglotWP\Third\CacheEnabler;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Generate_Switcher_Service_Weglot;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;


/**
 * Cache_Enabler_Cache
 *
 * @since 3.1.4
 */
class Cache_Enabler_Cache implements Hooks_Interface_Weglot {
	/**
	 * @var Cache_Enabler_Active
	 */
	private $cache_enabler_active;
	/**
	 * @var Generate_Switcher_Service_Weglot
	 */
	private $generate_switcher_service;

	/**
	 * @since 3.1.4
	 * @return void
	 */
	public function __construct() {
		$this->cache_enabler_active      = weglot_get_service( 'Cache_Enabler_Active' );
		$this->generate_switcher_service = weglot_get_service( 'Generate_Switcher_Service_Weglot' );
	}

	/**
	 * @since 3.1.4
	 * @see Hooks_Interface_Weglot
	 * @return void
	 */
	public function hooks() {

		if ( ! $this->cache_enabler_active->is_active() ) {
			return;
		}

		add_filter( 'cache_enabler_bypass_cache', array( $this, 'bypass_cache' ) );
		add_action( 'wp_head', array( $this, 'buffer_start' ) );
	}

	/**
	 * @param $bypass_cache
	 * @return bool
	 * @since 3.1.4
	 */
	public function bypass_cache( $bypass_cache ) {

		/** @var $request_url_services Request_Url_Service_Weglot */
		$request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
		/** @var $language_services Language_Service_Weglot */
		$language_services = weglot_get_service( 'Language_Service_Weglot' );

		if ( $request_url_services->get_current_language() !== $language_services->get_original_language() ) {
			return true;
		}

		return $bypass_cache;
	}

	/**
	 * @since 3.1.4
	 * @return void
	 */
	public function buffer_start() {
		ob_start( array( $this, 'add_default_switcher' ) );
	}

	/**
	 * @param $dom
	 * @return string
	 * @since 3.1.4
	 */
	public function add_default_switcher( $dom ) {
		return $this->generate_switcher_service->generate_switcher_from_dom( $dom );
	}

}
