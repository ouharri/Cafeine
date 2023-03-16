<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Multisite service
 *
 * @since 2.0
 */
class Multisite_Service_Weglot {
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
	}

	/**
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_list_of_network_path() {
		$paths = array();

		if ( is_multisite() ) {
			$sites = \get_sites(
				array(
					'number' => 0,
				)
			);

			foreach ( $sites as $site ) {
				$path = $site->path;
				array_push( $paths, $path );
			}
		} else {
			array_push( $paths, $this->request_url_services->get_home_wordpress_directory() );
		}

		return $paths;
	}
}

