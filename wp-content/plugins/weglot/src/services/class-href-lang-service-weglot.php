<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @since 2.3.0
 */
class Href_Lang_Service_Weglot {

	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;


	/**
	 * @since 2.3.0
	 */
	public function __construct() {
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
	}

	/**
	 * @since 2.3.0
	 */
	public function generate_href_lang_tags() {
		$render = "\n";
		if ( ! $this->request_url_services->is_eligible_url() ) {
			return apply_filters( 'weglot_href_lang', $render );
		}

		$urls = $this->request_url_services->get_weglot_url()->getAllUrls();

		foreach ( $urls as $url ) {
			if ( ! $url['excluded'] ) {
				$render .= '<link rel="alternate" href="' . strtok( esc_url( $url['url'] ), '?' ) . '" hreflang="' . $url['language']->getExternalCode() . '"/>' . "\n";
			}
		}

		return apply_filters( 'weglot_href_lang', $render );
	}
}
