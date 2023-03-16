<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Client\Api\LanguageEntry;
use Weglot\Util\Url;
use Weglot\Util\Server;
use WeglotWP\Third\Amp\Amp_Service_Weglot;


/**
 * Request URL
 *
 * @since 2.0
 */
class Request_Url_Service_Weglot {
	/**
	 * @since 2.0
	 *
	 * @var Url
	 */
	protected $weglot_url = null;

	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;

	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Amp_Service_Weglot
	 */
	private $amp_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services   = weglot_get_service( 'Option_Service_Weglot' );
		$this->amp_services      = weglot_get_service( 'Amp_Service_Weglot' );
		$this->language_services = weglot_get_service( 'Language_Service_Weglot' );
	}

	/**
	 * Use for abstract \Weglot\Util\Url
	 *
	 * @param string $url
	 *
	 * @return Url
	 */
	public function create_url_object( $url ) {

		return new Url(
			$url,
			$this->language_services->get_original_language(),
			$this->language_services->get_destination_languages( $this->is_allowed_private() ),
			$this->get_home_wordpress_directory(),
			$this->option_services->get_exclude_urls(),
			$this->option_services->get_option( 'custom_urls' )
		);
	}

	/**
	 * @return Request_Url_Service_Weglot
	 * @since 2.0
	 *
	 */
	public function init_weglot_url() {
		$this->weglot_url = $this->create_url_object( $this->get_full_url() );

		return $this;
	}

	/**
	 * Get request URL in process
	 * @return Url
	 * @since 2.0
	 */
	public function get_weglot_url() {
		if ( null === $this->weglot_url ) {
			$this->init_weglot_url();
		}

		return $this->weglot_url;
	}

	/**
	 * @return boolean
	 * @since 2.4.1
	 */
	public function is_rest() {
		$prefix = rest_get_url_prefix();
		if (
			defined( 'REST_REQUEST' ) && REST_REQUEST || isset( $_GET['rest_route'] ) && // phpcs:ignore
			                                             strpos( trim( $_GET['rest_route'], '\\/' ), $prefix, 0 ) === 0 ) { // phpcs:ignore
			return true;
		}
		$rest_url    = wp_parse_url( site_url( $prefix ) );
		$current_url = wp_parse_url( add_query_arg( array() ) );

		return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
	}

	/**
	 * Abstraction of \Weglot\Util\Url
	 * @return LanguageEntry
	 * @version 3.2.0
	 * @since 2.0
	 */
	public function get_current_language() {
		$current_language = $this->get_weglot_url()->getCurrentLanguage();

		if ( ( wp_doing_ajax() || $this->is_rest() ) && isset( $_SERVER['HTTP_REFERER'] ) ) { //phpcs:ignore
			$current_language = $this->create_url_object( $_SERVER['HTTP_REFERER'] )->getCurrentLanguage(); //phpcs:ignore
		} else {
			if ( strpos( $this->get_full_url(), 'wp-comments-post.php' ) !== false ) {
				$current_language = $this->create_url_object( $this->get_full_url() )->getCurrentLanguage(); //phpcs:ignore
			}
		}

		if ( empty( $current_language ) ) {
			return apply_filters( 'weglot_default_current_language_empty', $this->language_services->get_original_language() );
		}

		return $current_language;
	}


	/**
	 * @param mixed $use_forwarded_host
	 *
	 * @return string
	 * @since 2.0
	 *
	 */
	public function get_full_url( $use_forwarded_host = false ) {
		return Server::fullUrl( $_SERVER, $use_forwarded_host ); //phpcs:ignore
	}

	/**
	 * @return string|null
	 * @since 2.0
	 *
	 */
	public function get_home_wordpress_directory() {
		$opt_siteurl = trim( get_option( 'siteurl' ), '/' );
		$opt_home    = trim( get_option( 'home' ), '/' );
		if ( empty( $opt_siteurl ) || empty( $opt_home ) ) {
			return null;
		}

		if (
			( substr( $opt_home, 0, 7 ) === 'http://' && strpos( substr( $opt_home, 7 ), '/' ) !== false ) || ( substr( $opt_home, 0, 8 ) === 'https://' && strpos( substr( $opt_home, 8 ), '/' ) !== false ) ) {
			$parsed_url = parse_url( $opt_home ); // phpcs:ignore
			$path       = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '/';

			return $path;
		}

		return null;
	}


	/**
	 * Returns true if the URL is translated in at least one language
	 *
	 * @param string $url
	 * @param bool $even_excluded
	 *
	 * @return boolean
	 * @since 2.0
	 */
	public function is_eligible_url( $url = null, $even_excluded = false ) {

		if ( ! $url ) {
			$weglot_url = $this->get_weglot_url();
		} else {
			$weglot_url = $this->create_url_object( $url );
		}

		if ( empty( $weglot_url->availableInLanguages( $even_excluded ) ) ) {
			return apply_filters( 'weglot_is_eligible_url', false, $weglot_url );
		} elseif ( ! $weglot_url->isTranslableInLanguage( $this->get_weglot_url()->getCurrentLanguage(), $even_excluded ) ) {
			return apply_filters( 'weglot_is_eligible_url', false, $weglot_url );
		} else {
			return apply_filters( 'weglot_is_eligible_url', true, $weglot_url );
		}
	}

	/**
	 * @param string $url
	 *
	 * @return string
	 * @since 2.0
	 */

	public function url_to_relative( $url ) {
		if ( ( substr( $url, 0, 7 ) === 'http://' ) || ( substr( $url, 0, 8 ) === 'https://' ) ) {
			// the current link is an "absolute" URL - parse it to get just the path.
			$parsed   = wp_parse_url( $url );
			$path     = isset( $parsed['path'] ) ? $parsed['path'] : '';
			$query    = isset( $parsed['query'] ) ? '?' . $parsed['query'] : '';
			$fragment = isset( $parsed['fragment'] ) ? '#' . $parsed['fragment'] : '';

			if ( $this->get_home_wordpress_directory() ) {
				$relative = str_replace( $this->get_home_wordpress_directory(), '', $path );

				return ( empty( $relative ) ) ? '/' : $relative;
			}

			return $path . $query . $fragment;
		}

		return $url;
	}

	public function is_allowed_private() {
		if ( current_user_can( 'administrator' )
		     || strpos( $this->get_full_url(), 'weglot-private=1' ) !== false
		     || isset( $_COOKIE['weglot_allow_private'] )
		     || ( isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), "Weglot Visual Editor" ) !== false ) //phpcs:ignore
		) {
			return true;
		}


		return false;
	}
}


