<?php

/**
 * Cookies module to handle all the cookies related operations
 *
 * @version 1.9.6
 * @package CookieLawInfo
 */

 // https://wpml.org/wpml-hook/wpml_set_element_language_details/
 // https://wpml.org/forums/topic/programmatically-create-post-in-different-languages/
 // https://polylang.wordpress.com/documentation/documentation-for-developers/functions-reference/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cookie_Law_Info_Languages {


	private static $instance;

	public function __construct() {

	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Check whether a multi language plugin is active or not
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_multilanguage_plugin_active() {
		$status = false;

		if ( defined( 'ICL_LANGUAGE_CODE' ) || defined( 'POLYLANG_FILE' ) ) {
			$status = true;
		}

		return $status;
	}

	/**
	 * Get default language of the site
	 *
	 * @access public
	 * @return string
	 */
	public function get_default_language_code() {
		$default = null;

		if ( $this->is_multilanguage_plugin_active() ) {
			// Polylang
			if ( function_exists( 'pll_default_language' ) ) {
				$default = pll_default_language();
			} else {
				// WPML
				$null    = null;
				$default = apply_filters( 'wpml_default_language', $null );
			}
		} else {
			$default = CLI_DEFAULT_LANGUAGE;
		}

		return $default;
	}

	/**
	 * Returns the current lanugage of the site
	 *
	 * @access public
	 * @return string
	 */
	public function get_current_language_code() {
		$current_language = null;

		if ( $this->is_multilanguage_plugin_active() ) {
			// Polylang
			if ( function_exists( 'pll_current_language' ) ) {

				$current_language = pll_current_language();

				// If current_language is still empty, we have to get the default language
				if ( empty( $current_language ) ) {
					$current_language = pll_default_language();
				}
			} else {
				// WPML
				$null             = null;
				$current_language = apply_filters( 'wpml_current_language', $null );
			}

			// Fallback
			if ( $current_language === 'all' ) {
				$current_language = $this->get_default_language_code();
			}
		} else {
			$current_language = CLI_DEFAULT_LANGUAGE;
		}

		return $current_language;
	}
	public function get_term_by_language( $term_id, $language ) {
		$term = false;
		if ( $this->is_multilanguage_plugin_active() ) {
			// Polylang
			if ( function_exists( 'pll_get_term_translations' ) ) {
				$terms = pll_get_term_translations( $term_id );
				if ( isset( $terms[ $language ] ) ) {
					$original_term_id = $terms[ $language ];
					$term             = get_term_by( 'id', $original_term_id, 'cookielawinfo-category' );
				}
			} else {
				// WPML
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

	public function maybe_set_term_language( $term_id ) {
		if ( $this->is_multilanguage_plugin_active() ) {
			// Polylang
			if ( function_exists( 'pll_set_term_language' ) ) {
				$language = $this->get_default_language_code();
				pll_set_term_language( $term_id, $language );
			}
		}
	}
}
