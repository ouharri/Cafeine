<?php

namespace WeglotWP\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @since 3.0.0
 */
class Helper_Flag_Type {

	/**
	 * @var string
	 */
	const RECTANGLE_MAT = 'rectangle_mat';

	/**
	 * @var string
	 */
	const SHINY = 'shiny';

	/**
	 * @var string
	 */
	const SQUARE = 'square';

	/**
	 * @var string
	 */
	const CIRCLE = 'circle';

	/**
	 * @param string|int $number
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public static function get_flag_type_with_number( $number ) {
		switch ( (int) $number ) {
			case 0:
				return self::RECTANGLE_MAT;
				break;
			case 1:
				return self::SHINY;
				break;
			case 2:
				return self::SQUARE;
				break;
			case 3:
				return self::CIRCLE;
				break;
		}
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	public static function get_flag_number_with_type( $type ) {
		switch ( $type ) {
			case self::RECTANGLE_MAT:
				return 0;
			case self::SHINY:
				return 1;
			case self::SQUARE:
				return 2;
			case self::CIRCLE:
				return 3;
		}
	}

	/**
	 * @param boolean $is_admin
	 *
	 * @return string
	 */
	public static function get_new_flags( $is_admin = false ) {
		$options         = get_transient( 'weglot_cache_cdn', false );
		$custom_flag_css = '';

		for ( $flag_number = 0; $flag_number <= 3; $flag_number++ ) {
			if ( ! empty( $options['language_from_custom_flag'] ) ) {
				$custom_flag_css .= self::get_custom_flag_for_one_language( $options['language_from'], $options['language_from_custom_flag'], $flag_number );
			}

			if ( isset( $options['languages'] ) && ! empty( $options['languages'] ) ) {
				foreach ( $options['languages'] as $item ) {
					if ( ! empty( $item['custom_flag'] ) ) {
						$custom_flag_css .= self::get_custom_flag_for_one_language( $item['language_to'], $item['custom_flag'], $flag_number );
					}
				}
			}
		}


		if ( $is_admin == true ) {
			$custom_flag_css .= '.flag-style-openclose,#custom_flag_tips{display:none !important}';
		}

		wp_enqueue_style( 'new-flag-css', WEGLOT_DIRURL . 'app/styles/new-flags.css', array(), WEGLOT_VERSION );
		wp_register_style( 'custom-flag-handle', false );
		wp_enqueue_style( 'custom-flag-handle' );
		wp_add_inline_style( 'custom-flag-handle', $custom_flag_css );
		return $custom_flag_css;
	}

	/**
	 * @param string $language_code
	 * @param string $flag_code
	 * @param int $flag_number
	 *
	 * @return string
	 */
	public static function get_custom_flag_for_one_language( $language_code, $flag_code, $flag_number ) {
		$flag_type = self::get_flag_type_with_number( $flag_number );
		if ( strlen( $flag_code ) <= 5 ) {
			$flag_url = "https://cdn.weglot.com/flags/{$flag_type}/{$flag_code}.svg";
		} else {
			$flag_url = $flag_code;
		}

		return ".weglot-flags.flag-{$flag_number}.{$language_code}>a:before," .
				".weglot-flags.flag-{$flag_number}.{$language_code}>span:before {" .
				"background-image: url({$flag_url}); }";

	}
}
