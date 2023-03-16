<?php

namespace WeglotWP\Helpers;

use WeglotWP\Services\Language_Service_Weglot;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Function helper for URL replace filter
 *
 * @since 2.0
 */
abstract class Helper_Filter_Url_Weglot {

	/**
	 * @since 2.4.0
	 * @param string $url
	 * @return string
	 */
	public static function filter_url_lambda( $url ) {
		$request_url_service = weglot_get_request_url_service();
		$replaced_url        = $request_url_service->create_url_object( $url )->getForLanguage( $request_url_service->get_current_language() );
		if ( $replaced_url ) {
			return $replaced_url;
		} else {
			return $url;
		}
	}

	/**
	 * Filter URL log redirection
	 * @since 2.0
	 * @version 2.0.2
	 * @param string $url_filter
	 * @return string
	 */
	public static function filter_url_log_redirect( $url_filter ) {

		$request_url_service = weglot_get_request_url_service();
		/** @var  $language_service Language_Service_Weglot */
		$language_service = weglot_get_service( 'Language_Service_Weglot' );

		$url = $request_url_service->create_url_object( $url_filter );

		if ( $request_url_service->get_current_language() === $language_service->get_original_language()
			&& isset( $_SERVER['HTTP_REFERER'] ) //phpcs:ignore
		) {
			$url_referer = $request_url_service->create_url_object( $_SERVER['HTTP_REFERER'] ); //phpcs:ignore
			$current_language = $url_referer->getCurrentLanguage();
			return $url->getForLanguage( $current_language );
		}
		return $url->getForLanguage( $request_url_service->get_current_language() );
	}
}
