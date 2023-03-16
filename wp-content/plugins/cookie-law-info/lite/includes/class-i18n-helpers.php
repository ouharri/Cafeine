<?php
/**
 * Translation helper functions
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 * @package    CookieYes\Lite\Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! function_exists( 'cky_default_language' ) ) {

	/**
	 * Check if a request is a rest request
	 *
	 * @return string
	 */
	function cky_default_language() {
		$settings = get_option( 'cky_settings' );
		return isset( $settings['languages']['default'] ) ? cky_sanitize_text( $settings['languages']['default'] ) : 'en';
	}
}
if ( ! function_exists( 'cky_selected_languages' ) ) {

	/**
	 * Check if a request is a rest request
	 *
	 * @param string $language Language to add temporarily to the existing list.
	 * @return array
	 */
	function cky_selected_languages( $language = '' ) {
		$settings  = get_option( 'cky_settings' );
		$languages = isset( $settings['languages']['selected'] ) ? cky_sanitize_text( $settings['languages']['selected'] ) : array();
		if ( ! in_array( cky_default_language(), $languages, true ) ) {
			array_push( $languages, cky_default_language() );
		}
		if ( '' !== $language && ! in_array( $language, $languages, true ) ) {
			array_push( $languages, $language );
		}
		return $languages;
	}
}

if ( ! function_exists( 'cky_i18n_is_multilingual' ) ) {

	/**
	 * Return true if multilingual plugin is active
	 *
	 * @return boolean
	 */
	function cky_i18n_is_multilingual() {
		$status = false;

		if ( defined( 'ICL_LANGUAGE_CODE' ) || defined( 'POLYLANG_FILE' ) ) {
			$status = true;
		}
		return $status;
	}
}

if ( ! function_exists( 'cky_current_language' ) ) {
	/**
	 * Returns the current language code of the site
	 *
	 * @return string
	 */
	function cky_current_language() {
		$current_language = null;

		if ( cky_i18n_is_multilingual() ) {
			// If the plugin used is Polylang.
			if ( function_exists( 'pll_current_language' ) ) {

				$current_language = pll_current_language();
				// If current_language is still empty, we have to get the default language.
				if ( empty( $current_language ) ) {
					$current_language = pll_default_language();
				}
			} else {
				// If the plugin used is WPML.
				$current_language = apply_filters( 'wpml_current_language', null );
			}

			// Fallback if neither WPML nor Polylang is used.
			if ( 'all' === $current_language ) {
				$current_language = cky_default_language();
			}
		} else {
			$current_language = cky_default_language();
		}
		$map              = cky_get_lang_map();
		$current_language = isset( $map[ $current_language ] ) ? $map[ $current_language ] : $current_language;
		if ( in_array( $current_language, cky_selected_languages(), true ) === false ) {
			$current_language = cky_default_language();
		}
		return apply_filters( 'cky_current_language', $current_language );
	}
}

if ( ! function_exists( 'cky_get_lang_map' ) ) {
	/**
	 * Returns the current language code of the site
	 *
	 * @return string
	 */
	function cky_get_lang_map() {
		$map = array(
			'pt-pt' => 'pt',
		);

		return apply_filters( 'cky_language_map', $map );
	}
}

if ( ! function_exists( 'cky_i18n_default_language' ) ) {
	/**
	 * Returns the current language code of the site
	 *
	 * @return string
	 */
	function cky_i18n_default_language() {
		if ( cky_i18n_is_multilingual() ) {
			if ( function_exists( 'pll_default_language' ) ) {
				$default = pll_default_language();
			} else {
				$null    = null;
				$default = apply_filters( 'wpml_default_language', $null );
			}
		} else {
			$default = cky_default_language();
		}
		return $default;
	}
}
if ( ! function_exists( 'cky_i18n_term_by_language' ) ) {
	/**
	 * Returns the current language code of the site
	 *
	 * @param integer $term_id Original term id.
	 * @param string  $language Language code.
	 * @return object
	 */
	function cky_i18n_term_by_language( $term_id, $language ) {
		$term = false;
		if ( cky_i18n_is_multilingual() ) {
			if ( function_exists( 'pll_get_term_translations' ) ) {
				$terms = pll_get_term_translations( $term_id );
				if ( isset( $terms[ $language ] ) ) {
					$original_term_id = $terms[ $language ];
					$term             = get_term_by( 'id', $original_term_id, 'cookielawinfo-category' );
				}
			} else {
				if ( function_exists( 'icl_object_id' ) ) {
					global $sitepress;
					if ( $sitepress ) {
						if ( version_compare( ICL_SITEPRESS_VERSION, '3.2.0' ) >= 0 ) {
							$original_term_id = apply_filters( 'wpml_object_id', $term_id, 'category', true, $language );
						} else {
							$original_term_id = icl_object_id( $term_id, 'category', true, $language );
						}
						remove_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1 );
						$term = get_term_by( 'id', $original_term_id, 'cookielawinfo-category' );
						add_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1, 1 );
					}
				}
			}
		}
		return $term;
	}
}

if ( ! function_exists( 'cky_i18n_post_by_language' ) ) {
	/**
	 * Returns the current language code of the site
	 *
	 * @param integer $term_id Original term id.
	 * @param string  $language Language code.
	 * @return object
	 */
	function cky_i18n_post_by_language( $post_id, $language ) {
		$post = false;
		if ( cky_i18n_is_multilingual() ) {
			if ( function_exists( 'pll_get_post_translations' ) ) {
				$posts = pll_get_post_translations( $post_id );
				if ( isset( $posts[ $language ] ) ) {
					$original_post_id = $posts[ $language ];
					$post             = get_post( $original_post_id );
				}
			} else {
				if ( function_exists( 'icl_object_id' ) ) {
					$type = apply_filters( 'wpml_element_type', get_post_type( $post_id ) );
					$trid = apply_filters( 'wpml_element_trid', false, $post_id, $type );

					$translations = apply_filters( 'wpml_get_element_translations', array(), $trid, $type );
					if ( isset( $translations[ $language ] ) ) {
						$original_post_id = isset( $translations[ $language ]->element_id ) ? $translations[ $language ]->element_id : false;
						if ( $original_post_id ) {
							$post = get_post( $original_post_id );
						}
					}
				}
			}
		}
		return $post;
	}
}

if ( ! function_exists( 'cky_wpml_active' ) ) {
	function cky_wpml_active() {
		return class_exists( 'SitePress' );
	}
}

if ( ! function_exists( 'cky_i18n_selected_languages' ) ) {
	function cky_i18n_selected_languages() {
		$languages = array( cky_i18n_default_language() );
		if ( cky_i18n_is_multilingual() ) {
			if ( cky_wpml_active() ) {
				return cky_i18n_wpml_languages();
			} else {
				return cky_i18n_pll_languages();
			}
		}
		return $languages;
	}
}

if ( ! function_exists( 'cky_i18n_pll_languages' ) ) {
	function cky_i18n_pll_languages() {
		$languages = array();
		if ( function_exists( 'pll_languages_list' ) ) {
			$configured = pll_languages_list();
			if ( empty( $configured ) ) {
				return $languages;
			}
			foreach ( $configured as $language ) {
				$languages[] = $language;
			}
		}
		return $languages;
	}
}
if ( ! function_exists( 'cky_i18n_wpml_languages' ) ) {
	function cky_i18n_wpml_languages() {
		$languages  = array();
		$configured = apply_filters( 'wpml_active_languages', null );
		if ( empty( $configured ) ) {
			return $languages;
		}
		foreach ( $configured as $key => $language ) {
			$languages[] = $key;
		}
		return $languages;
	}
}

if ( ! function_exists( 'cky_i18n_translate_string' ) ) {
	function cky_i18n_translate_string( $string, $key, $language, $context = 'CookieLawInfo-0.9' ) {
		if ( function_exists( 'pll_translate_string' ) ) {
			return pll_translate_string( $string, $language );
		} else {
			return apply_filters( 'wpml_translate_single_string', $string, "admin_texts_{$context}", "[{$context}]" . $key, $language );
		}
	}
}

if ( ! function_exists( 'cky_i18n_term_language' ) ) {
	function cky_i18n_term_language( $term ) {
		$language = cky_i18n_default_language();
		if ( cky_i18n_is_multilingual() ) {
			if ( function_exists( 'pll_get_term_language' ) ) {
				$language = pll_get_term_language( $term );
			}
		}
		return $language;
	}
}
