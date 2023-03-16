<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Client\Api\Exception\ApiError;
use Weglot\Client\Api\LanguageEntry;
use Weglot\Util\Url;
use WeglotWP\Helpers\Helper_Json_Inline_Weglot;
use WeglotWP\Helpers\Helper_Keys_Json_Weglot;


/**
 * @since 2.3.0
 */
class Translate_Service_Weglot {
	/**
	 * @var Parser_Service_Weglot
	 */
	private $parser_services;
	/**
	 * @var string
	 */
	private $current_language;
	/**
	 * @var string
	 */
	private $original_language;
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;
	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;
	/**
	 * @var Replace_Url_Service_Weglot
	 */
	private $replace_url_services;
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Generate_Switcher_Service_Weglot
	 */
	private $generate_switcher_service;
	private $href_lang_services;


	/**
	 * @since 2.3.0
	 */
	public function __construct() {
		$this->option_services           = weglot_get_service( 'Option_Service_Weglot' );
		$this->request_url_services      = weglot_get_service( 'Request_Url_Service_Weglot' );
		$this->replace_url_services      = weglot_get_service( 'Replace_Url_Service_Weglot' );
		$this->parser_services           = weglot_get_service( 'Parser_Service_Weglot' );
		$this->generate_switcher_service = weglot_get_service( 'Generate_Switcher_Service_Weglot' );
		$this->language_services         = weglot_get_service( 'Language_Service_Weglot' );
	}


	/**
	 * @return void
	 * @since 2.3.0
	 */
	public function weglot_translate() {
		ob_start( array( $this, 'weglot_treat_page' ) );
	}

	/**
	 * @param LanguageEntry $current_language
	 *
	 * @return Translate_Service_Weglot
	 * @since 2.3.0
	 */
	public function set_current_language( $current_language ) {
		$this->current_language = $current_language->getInternalCode();

		return $this;
	}

	/**
	 * @param LanguageEntry $original_language
	 *
	 * @return Translate_Service_Weglot
	 * @since 2.3.0
	 */
	public function set_original_language( $original_language ) {
		$this->original_language = $original_language->getInternalCode();

		return $this;
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public function get_canonical_url_from_content( $content ) {
		$check_canonical = preg_match( '/<link rel="canonical"(.*?)?href=(\"|\')([^\s\>]+?)(\"|\')/', $content, $matches );

		if ( 1 === $check_canonical ) {
			if ( isset( $matches[3] ) && ! empty( $matches[3] ) ) {
				return $matches[3];
			} else {
				return '';
			}
		} else {
			return '';
		}
	}

	/**
	 * @return boolean
	 */
	public function check_ajax_exclusion_before_treat() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( ! $this->request_url_services->create_url_object( wp_get_referer() )->getForLanguage( $this->request_url_services->get_current_language(), false ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return boolean
	 */
	public function check_404_exclusion_before_treat() {
		if ( http_response_code() == 404 ) {
			$excluded_urls = $this->option_services->get_exclude_urls();
			foreach ( $excluded_urls as $item ) {
				if ( $item[0] === '^/404$' ) {
					return true;
				}
			}

			return false;
		} else {
			return false;
		}
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 * @throws \Exception
	 * @since 2.3.0
	 * @see weglot_init / ob_start
	 */
	public function weglot_treat_page( $content ) {

		if ( empty( $content ) ) {
			return $content;
		}
		$this->set_original_language( $this->language_services->get_original_language() );
		$this->set_current_language( $this->request_url_services->get_current_language() ); // Need to reset.

		// Choose type translate.
		$type = ( Helper_Json_Inline_Weglot::is_json( $content ) ) ? 'json' : 'html';
		if ( 'json' !== $type ) {
			$type = ( Helper_Json_Inline_Weglot::is_xml( $content ) ) ? 'xml' : 'html';
		}

		$type               = apply_filters( 'weglot_type_treat_page', $type );
		$active_translation = apply_filters( 'weglot_active_translation', true );
		$canonical          = $this->get_canonical_url_from_content( $content );

		// No need to translate but prepare new dom with button.
		if (
			$this->current_language === $this->original_language
			|| ! $active_translation
			|| $this->check_404_exclusion_before_treat()
			|| ! $this->request_url_services->get_weglot_url()->getForLanguage( $this->request_url_services->get_current_language(), false )
		) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				if ( ! $this->request_url_services->create_url_object( wp_get_referer() )->getForLanguage( $this->request_url_services->get_current_language(), false ) ) {
					// do nothing because the ajax referer are not exclude!
				} else {
					return $content;
				}
			} else {
				// if type is xml we render the content without treatment.
				if ( $type === 'xml' ) {
					return $content;
				} else {
					return $this->weglot_render_dom( $content, $canonical );
				}
			}
		}

		$parser = $this->parser_services->get_parser();

		try {
			switch ( $type ) {
				case 'json':
					$extra_keys         = apply_filters( 'weglot_add_json_keys', array() );
					$translated_content = $parser->translate( $content, $this->original_language, $this->current_language, $extra_keys );
					$translated_content = wp_json_encode( $this->replace_url_services->replace_link_in_json( json_decode( $translated_content, true ) ) );

					return apply_filters( 'weglot_json_treat_page', $translated_content );
				case 'xml':
					$translated_content = $parser->translate( $content, $this->original_language, $this->current_language, array(), $canonical );
					$translated_content = apply_filters( 'weglot_html_treat_page', $translated_content );

					return apply_filters( 'weglot_xml_treat_page', $translated_content );
				case 'html':
					$translated_content = $parser->translate( $content, $this->original_language, $this->current_language, array(), $canonical );
					$translated_content = apply_filters( 'weglot_html_treat_page', $translated_content );

					return $this->weglot_render_dom( $translated_content, $canonical );
				default:
					$name_filter = sprintf( 'weglot_%s_treat_page', $type );

					return apply_filters( $name_filter, $content, $parser, $this->original_language, $this->current_language );
			}
		} catch ( ApiError $e ) {
			if ( 'json' !== $type ) {
				if ( ! defined( 'DONOTCACHEPAGE' ) ) {
					define( 'DONOTCACHEPAGE', 1 );
				}
				nocache_headers();
				$content .= '<!--Weglot error API : ' . $this->remove_comments( $e->getMessage() ) . '-->';
			}

			return $content;
		} catch ( \Exception $e ) {
			if ( 'json' !== $type ) {
				if ( ! defined( 'DONOTCACHEPAGE' ) ) {
					define( 'DONOTCACHEPAGE', 1 );
				}
				nocache_headers();
				$content .= '<!--Weglot error : ' . $this->remove_comments( $e->getMessage() ) . '-->';
			}

			return $content;
		}
	}


	/**
	 * Remove comments from HTML.
	 *
	 * @param string $html the HTML string.
	 *
	 * @return string
	 * @since 2.3.0
	 */
	private function remove_comments( $html ) {
		return preg_replace( '/<!--(.*)-->/Uis', '', $html );
	}


	/**
	 * Replace links and add switcher on the final HTML.
	 *
	 * @param string $dom the final translated HTML.
	 * @param string $canonical the canonical link.
	 *
	 * @return string
	 * @since 2.3.0
	 */
	public function weglot_render_dom( $dom, $canonical = '' ) {
		$dom = $this->generate_switcher_service->generate_switcher_from_dom( $dom );

		// We only need this on translated page.
		if ( $this->current_language !== $this->original_language ) {
			$dom = $this->replace_url_services->replace_link_in_dom( $dom );
		}

		// Remove hreflangs if non canonical page.
		if ( '' !== $canonical ) {
			$canonical   = urldecode( $canonical );
			$current_url = $this->request_url_services->get_weglot_url();
			if ( $current_url->getPath() !== $this->request_url_services->create_url_object( $canonical )->getPath() ) {
				$dom = preg_replace( '/<link rel="alternate" href=(\"|\')([^\s\>]+?)(\"|\') hreflang=(\"|\')([^\s\>]+?)(\"|\')\/>/', '', $dom );
			}

			// update canonical if page excluded page.
			if ( ! $current_url->getForLanguage( $this->request_url_services->get_current_language(), false ) ) {
				$dom = preg_replace( '/<link rel="canonical"(.*?)?href=(\"|\')([^\s\>]+?)(\"|\')/', '<link rel="canonical" href="' . esc_url( $current_url->getForLanguage( $this->language_services->get_original_language() ) ) . '"', $dom );
			}
		}

		return apply_filters( 'weglot_render_dom', $dom );
	}
}



