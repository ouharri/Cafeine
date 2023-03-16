<?php

namespace WeglotWP\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Util\Regex\RegexEnum;


/**
 * @since 3.0.0
 */
class Helper_Excluded_Type {
	/**
	 * @since 3.0.0
	 * @return array
	 */
	public static function get_excluded_type() {
		return array(
			RegexEnum::START_WITH,
			RegexEnum::END_WITH,
			RegexEnum::CONTAIN,
			RegexEnum::IS_EXACTLY,
			RegexEnum::MATCH_REGEX,
		);
	}

	/**
	 * @since 3.0.0
	 * @param string $type
	 * @return string
	 */
	public static function get_label_type( $type ) {
		switch ( $type ) {
			case RegexEnum::START_WITH:
				return __( 'URL starts with', 'weglot' );
			case RegexEnum::END_WITH:
				return __( 'URL ends with', 'weglot' );
			case RegexEnum::CONTAIN:
				return __( 'URL contains substring', 'weglot' );
			case RegexEnum::IS_EXACTLY:
				return __( 'URL is exactly', 'weglot' );
			case RegexEnum::MATCH_REGEX:
				return __( 'URL matches regex', 'weglot' );
		}
	}
}


