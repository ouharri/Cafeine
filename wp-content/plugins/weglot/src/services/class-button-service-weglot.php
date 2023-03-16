<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Client\Api\LanguageEntry;
use WeglotWP\Helpers\Helper_Flag_Type;
use WeglotWP\Third\Amp\Amp_Service_Weglot;


/**
 * Button services
 *
 * @since 2.0
 */
class Button_Service_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;
	/**
	 * @var Amp_Service_Weglot
	 */
	private $amp_services;


	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services      = weglot_get_service( 'Option_Service_Weglot' );
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
		$this->language_services    = weglot_get_service( 'Language_Service_Weglot' );
		$this->amp_services         = weglot_get_service( 'Amp_Service_Weglot' );
	}

	/**
	 * @param array $switcher
	 *
	 * @return string
	 * @since 2.3.0
	 * @version 3.0.0
	 */
	public function get_flag_class( $switcher = [] ) {

		if ( ! empty( $switcher['style'] ) ) {
			$type_flags = $this->option_services->get_switcher_editor_option( 'flag_type', $switcher['style'] );
			$with_flags = $this->option_services->get_switcher_editor_option( 'with_flags', $switcher['style']);
		} else {
			$type_flags = $this->option_services->get_option_button( 'type_flags' );
			$with_flags = $this->option_services->get_option_button( 'with_flags' );
		}

		$flag_class = $with_flags ? 'weglot-flags ' : '';
		$type_flags = Helper_Flag_Type::get_flag_number_with_type( $type_flags );
		if ( '0' !== $type_flags ) {
			$flag_class .= sprintf( 'flag-%s ', $type_flags );
		}

		return apply_filters( 'weglot_get_flag_class', $flag_class );
	}

	/**
	 * @param LanguageEntry $language_entry
	 * @param array $switcher
	 *
	 * @return string
	 * @version 3.0.0
	 * @since 2.3.0
	 */
	public function get_name_with_language_entry( $language_entry, array $switcher = [] ) {

		if ( ! empty( $switcher['style'] ) ) {
			$with_name = $this->option_services->get_switcher_editor_option( 'with_name', $switcher['style'] );
			if ( $with_name ) {
				$name = ( $this->option_services->get_switcher_editor_option( 'full_name', $switcher['style'] ) ) ? $language_entry->getLocalName() : strtoupper( $language_entry->getExternalCode() );
			} else {
				$name = '';
				remove_filter( 'the_title', 'twenty_twenty_one_post_title' );
			}
		} else {
			$with_name = $this->option_services->get_option_button( 'with_name' );
			if ( $with_name ) {
				$name = ( $this->option_services->get_option( 'is_fullname' ) ) ? $language_entry->getLocalName() : strtoupper( $language_entry->getExternalCode() );
			} else {
				$name = '';
				remove_filter( 'the_title', 'twenty_twenty_one_post_title' );
			}
		}

		return apply_filters( 'weglot_get_name_with_language_entry', $name, $language_entry );
	}

	/**
	 * @param array $switcher
	 *
	 * @return string
	 * @since 2.3.0
	 * @version 3.0.0
	 */
	public function get_class_dropdown( $switcher = array() ) {
		if ( ! empty( $switcher['style'] ) ) {
			$is_dropdown = $this->option_services->get_switcher_editor_option( 'is_dropdown', $switcher['style'] );
		} else {
			$is_dropdown = $this->option_services->get_option_button( 'is_dropdown' );
		}
		$class = $is_dropdown ? 'weglot-dropdown ' : 'weglot-inline ';

		return apply_filters( 'weglot_get_class_dropdown', $class );
	}


	/**
	 * Get html button switcher
	 *
	 * @param string $add_class
	 * @param string $add_attr_target
	 * @param string $add_attr_sibling
	 * @param array $switcher
	 *
	 * @return string
	 * @version 2.3.1
	 * @since 2.0
	 */
	public function get_html( $add_class = '', $switcher = [], $add_attr_target = '', $add_attr_sibling = '' ) {

		$weglot_url                = $this->request_url_services->get_weglot_url();
		$amp_regex                 = $this->amp_services->get_regex( true );
		$current_language          = $this->request_url_services->get_current_language();
		$original_language         = $this->language_services->get_original_language();
		$is_dropdown               = $this->option_services->get_option_button( 'is_dropdown' );
		$display_original_language = $this->request_url_services->get_weglot_url()->getExcludeOption( $original_language, 'language_button_displayed' );
		$language_button_displayed = $this->request_url_services->get_weglot_url()->getExcludeOption( $weglot_url->getCurrentLanguage(), 'language_button_displayed' );

		$hide_all_language = true;
		$array_excluded    = array();
		foreach ( $this->language_services->get_original_and_destination_languages( $this->request_url_services->is_allowed_private() ) as $key => $language ) {
			if ( $this->request_url_services->get_weglot_url()->getExcludeOption( $language, 'language_button_displayed' ) ) {
				$hide_all_language = false;
			}
			$array_excluded[ $language->getInternalCode() ] = $this->request_url_services->get_weglot_url()->getExcludeOption( $language, 'language_button_displayed' );
		}

		if ( weglot_get_translate_amp_translation() && preg_match( '#' . $amp_regex . '#', $weglot_url->getUrl() ) === 1 ) {
			$add_class .= ' weglot-invert';
		}

		$flag_class  = $this->get_flag_class( $switcher );
		$class_aside = $this->get_class_dropdown( $switcher );

		$button_html  = sprintf( '<!--Weglot %s-->', WEGLOT_VERSION );

		if ( !empty ( $add_attr_target ) ) {
			$button_html .= sprintf( '<aside data-wg-notranslate="" class="country-selector %s" tabindex="0" aria-expanded="false" role="listbox" aria-activedescendant="weglot-language-'.$current_language->getExternalCode().'" aria-label="Language selected: '.$current_language->getEnglishName().'" data-wg-target="%s" data-wg-sibling="%s">', $class_aside . $add_class, $add_attr_target, $add_attr_sibling );
		} else {
			$button_html .= sprintf( '<aside data-wg-notranslate="" class="country-selector %s" tabindex="0" aria-expanded="false" role="listbox" aria-activedescendant="weglot-language-'.$current_language->getExternalCode().'" aria-label="Language selected: '.$current_language->getEnglishName().'">', $class_aside . $add_class );
		}

		$name = $this->get_name_with_language_entry( $current_language, $switcher );

		$display_first = false;
		if ( $this->request_url_services->is_eligible_url( $this->request_url_services->get_full_url() ) || $language_button_displayed ) {
			$uniq_id       = 'wg' . uniqid( strtotime( 'now' ) ) . wp_rand( 1, 1000 );
			$button_html  .= sprintf( '<input id="%s" class="weglot_choice" type="checkbox" name="menu"/><label data-l="'.$current_language->getExternalCode().'" tabindex="-1" id="weglot-language-'.$current_language->getExternalCode().'" role="none" for="%s" class="wgcurrent wg-li weglot-lang weglot-language %s" data-code-language="%s" data-name-language="%s"><span class="wglanguage-name">%s</span></label>', esc_attr( $uniq_id ), esc_attr( $uniq_id ), esc_attr( $flag_class . $current_language->getInternalCode() ), esc_attr( $current_language->getInternalCode() ), esc_html( $name ), esc_html( $name ) );
			$display_first = true;
		}

		if ( ! $display_first && ! $hide_all_language ) {
			$uniq_id       = 'wg' . uniqid( strtotime( 'now' ) ) . wp_rand( 1, 1000 );
			$button_html  .= sprintf( '<input id="%s" class="weglot_choice" type="checkbox" name="menu"/><label tabindex="-1" role="none" id="weglot-language-'.$current_language->getExternalCode().'" for="%s" class="wgcurrent wg-li weglot-lang weglot-language %s" data-code-language="%s" data-name-language="%s"><span class="wglanguage-name">%s</span></label>', esc_attr( $uniq_id ), esc_attr( $uniq_id ), esc_attr( $flag_class . $current_language->getInternalCode() ), esc_attr( $current_language->getInternalCode() ), esc_html( $name ), esc_html( $name ) );
			$display_first = true;
		}

		$button_html .= '<ul role="none">';

		foreach ( $this->language_services->get_original_and_destination_languages( $this->request_url_services->is_allowed_private() ) as $language ) {
			// check if for this button we ant to exclude the button from switcher.
			$language_button_displayed = $this->request_url_services->get_weglot_url()->getExcludeOption( $language, 'language_button_displayed' );
			$link_button               = $this->request_url_services->get_weglot_url()->getForLanguage( $language, true );

			if ( $language->getInternalCode() === $current_language->getInternalCode()
			     || ! $display_original_language && ! $display_first ) {
				continue;
			}

			if ( ! $language_button_displayed ) {
				if ( ! $is_dropdown && $this->language_services->get_original_language()->getInternalCode() === $language->getInternalCode() && $display_first ) {
					$link_button = $this->request_url_services->get_weglot_url()->getForLanguage( $language, true );
				} else {
					$link_button = $this->request_url_services->get_weglot_url()->getForLanguage( $language, false );
				}
			}

			if ( $link_button ) {
				$button_html .= sprintf( '<li data-l="'.$language->getExternalCode().'" class="wg-li weglot-lang weglot-language %s" data-code-language="%s" role="none">', $flag_class . $language->getInternalCode(), $language->getInternalCode() );
				$name         = $this->get_name_with_language_entry( $language, $switcher );

				if ( $this->option_services->get_option( 'auto_redirect' ) ) {
					$is_orig = $language === $this->language_services->get_original_language() ? 'true' : 'false';
					if ( strpos( $link_button, '?' ) !== false ) {
						$link_button = str_replace( '?', "?wg-choose-original=$is_orig&", $link_button );
					} else {
						$link_button .= "?wg-choose-original=$is_orig";
					}
				}

				$button_html .= sprintf(
					'<a title="Language switcher : '.$language->getEnglishName().'" id="weglot-language-'.$language->getExternalCode().'" role="option" data-wg-notranslate="" href="%s">%s</a>',
					esc_url( $link_button ),
					esc_html( $name )
				);

				$button_html .= '</li>';
			}
		}

		$button_html .= '</ul>';

		$button_html .= '</aside>';

		return apply_filters( 'weglot_button_html', $button_html, $add_class );
	}

}
