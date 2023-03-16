<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Client\Api\LanguageEntry;
use Weglot\Client\Api\LanguageCollection;
use Weglot\Client\Endpoint\LanguagesList;


/**
 * Language service
 *
 * @since 2.0
 */
class Language_Service_Weglot {
	protected $languages = null;
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services = weglot_get_service( 'Option_Service_Weglot' );
	}

	/**
	 * @param array $a
	 * @param array $b
	 *
	 * @return bool
	 * @since 2.0.6
	 */
	protected function compare_language( $a, $b ) {
		return strcmp( $a['english'], $b['english'] );
	}

	/**
	 * Get defaults languages available from API
	 *
	 * @param array $params
	 *
	 * @return LanguageCollection
	 * @since 2.0
	 * @version 2.0.6
	 */
	public function get_languages_available( $params = array() ) {
		if ( null !== $this->languages ) {
			return $this->languages;
		}

		$client = weglot_get_service( 'Parser_Service_Weglot' )->get_client();

		$languages = new LanguagesList( $client );

		$this->languages = $languages->handle();

		// We add the weglot_language_code_replace filter for custom code
		$this->languages = $this->languages->jsonSerialize();

		if ( isset( $params['sort'] ) && $params['sort'] ) {
			usort( $this->languages, array( $this, 'compare_language' ) );
		}

		$language_collection = new LanguageCollection();
		foreach ( $this->languages as $language ) {
			$language_code_rewrited = apply_filters( 'weglot_language_code_replace', array() );
			$external_code          = array_key_exists( $language['internal_code'], $language_code_rewrited ) ? $language_code_rewrited[ $language['internal_code'] ] : $language['external_code'];
			$entry                  = new LanguageEntry( $language['internal_code'], $external_code, $language['english'], $language['local'], $language['rtl'] );
			$language_collection->addOne( $entry );
		}
		$this->languages = $language_collection;

		return $this->languages;
	}

	/**
	 * Adds a language to the language collection
	 *
	 * @param $internal_code
	 * @param $external_code
	 * @param $english_name
	 * @param $local_name
	 * @param bool $is_rtl
	 *
	 * @return array
	 */
	public function add_language( $internal_code, $external_code, $english_name, $local_name, $is_rtl = false ) {
		$entry = new LanguageEntry( $internal_code, $external_code, $english_name, $local_name, $is_rtl );
		$this->languages->addOne( $entry );

		return $this->languages;
	}

	/**
	 * Get all languages : list of 109 default languages merged with custom languages taken from options
	 *
	 * @return array|LanguageCollection|null
	 */
	public function get_all_languages() {
		if ( null !== $this->languages ) {
			return $this->languages;
		}

		$this->languages       = $this->get_languages_available( array( 'sort' => true ) );
		$destination_languages = $this->option_services->get_destination_languages();
		$original_language     = $this->get_original_language();

		if ( ! empty( $this->get_original_language_name_custom() ) ) {
			$this->languages = $this->add_language( $original_language->getInternalCode(), $original_language->getExternalCode(), $this->get_original_language_name_custom(), $this->get_original_language_name_custom(), $original_language->isRtl() );
		}

		foreach ( $destination_languages as $d ) {
			$language_data = $this->languages->getCode( $d['language_to'] );
			if ( ! $language_data ) {
				$this->languages = $this->add_language( $d['language_to'], $d['custom_code'], $d['custom_name'], $d['custom_local_name'] );
			} else {
				$external_code     = isset( $d['custom_code'] ) ? $d['custom_code'] : $language_data->getExternalCode();
				$custom_local_name = isset( $d['custom_name'] ) ? $d['custom_name'] : $language_data->getLocalName(); // TODO: remove after core fixes bug.
				$custom_local_name = isset( $d['custom_local_name'] ) ? $d['custom_local_name'] : $custom_local_name;
				$custom_name       = isset( $d['custom_name'] ) ? $d['custom_name'] : $language_data->getEnglishName();
				$this->languages   = $this->add_language( $d['language_to'], $external_code, $custom_name, $custom_local_name, $language_data->isRtl() ); // côté core : il faut que le nom qui change soit le custom_local_name.
			}
		}

		return $this->languages;
	}

	/**
	 * Get language entry from the internal code
	 *
	 * @param string $internal_code
	 *
	 * @return LanguageEntry
	 * @since 3.2.1
	 */
	public function get_language_from_internal( $internal_code ) {
		return $this->get_all_languages()[ $internal_code ];
	}

	/**
	 * Get language entry from the external code
	 *
	 * @param string $external_code
	 *
	 * @return LanguageEntry
	 * @since 3.2.1
	 */
	public function get_language_from_external( $external_code ) {
		foreach ( $this->get_all_languages() as $language ) {
			if ( $language->getExternalCode() === $external_code ) {
				return $language;
			}
		}

		return null;
	}

	/**
	 * Get destination languages as language entries
	 *
	 * @param bool $allowed_private
	 *
	 * @return LanguageEntry[]
	 */
	public function get_destination_languages( $allowed_private = false ) {
		$destination_languages_as_array = $this->option_services->get_destination_languages();
		$destination_languages          = array();
		foreach ( $destination_languages_as_array as $destination_language_as_array ) {
			if ( $destination_language_as_array['public'] || $allowed_private ) {
				if ( $this->get_all_languages()[ $destination_language_as_array['language_to'] ] ) {
					$destination_languages[] = $this->get_all_languages()[ $destination_language_as_array['language_to'] ];
				}
			}
		}

		return $destination_languages;
	}

	/**
	 * Get destination languages as language entries
	 *
	 * @param bool $allowed_private
	 *
	 * @return string[]
	 */
	public function get_destination_languages_external( $allowed_private = false ) {
		return array_map(
			function ( $l ) {
				return $l->getExternalCode();
			},
			$this->get_destination_languages( $allowed_private )
		);
	}

	/**
	 * Get original language as language entry
	 *
	 * @return LanguageEntry
	 */
	public function get_original_language() {
		$original_language_code = $this->option_services->get_option( 'original_language' );

		return $this->get_language_from_internal( $original_language_code );
	}

	/**
	 * Get original language as language entry
	 *
	 * @return LanguageEntry
	 */
	public function get_original_language_name_custom() {
		return $this->option_services->get_option( 'language_from_custom_name' );
	}

	/**
	 * Get original language and destination languages as language entries
	 *
	 * @param bool $allowed_private
	 *
	 * @return LanguageEntry[]
	 */
	public function get_original_and_destination_languages( $allowed_private = false ) {
		$languages = $this->get_destination_languages( $allowed_private );
		array_unshift( $languages, $this->get_original_language() );

		return $languages;
	}
}
