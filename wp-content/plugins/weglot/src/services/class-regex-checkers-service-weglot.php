<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Parser\Check\Regex\RegexChecker;
use Weglot\Util\SourceType;
use Weglot\Util\Text;


/**
 * Dom Checkers
 *
 * @since 2.0
 * @version 2.0.6
 */
class Regex_Checkers_Service_Weglot {

	/**
	 * @since 2.3.0
	 */
	public function __construct() {

	}

	/**
	 * @since 2.0
	 * @return array
	 */
	public function get_regex_checkers() {

		$checkers = array();

		$other_words = apply_filters( 'weglot_words_translate', array() );
		foreach ( $other_words as $other_word ) {
			array_push( $checkers, new RegexChecker( '#\b' . $other_word . '\b#u', SourceType::SOURCE_TEXT, 0 ) );
		}

		$thirds = array_diff( scandir( WEGLOT_DIR . '/src/third' ), array( '..', '.' ) );
		foreach ( $thirds as $third ) {
			$files = array_diff( scandir( WEGLOT_DIR . '/src/third/' . $third ), array( '..', '.' ) );

			foreach ( $files as $file ) {
				if ( strpos( $file, 'active.php' ) !== false ) {
					$file    = Text::removeFileExtension( $file );
					$file    = str_replace( 'class-', '', $file );
					$file    = implode( '_', array_map( 'ucfirst', explode( '-', $file ) ) );
					$service = weglot_get_service( $file );
					if ( isset( $service ) ) {
						$active = $service->is_active();
						if ( $active ) {
							$regex_dir = WEGLOT_DIR . '/src/third/' . $third . '/regexcheckers/';
							if ( is_dir( $regex_dir ) ) {
								$regex_files = array_diff( scandir( WEGLOT_DIR . '/src/third/' . $third . '/regexcheckers/' ), array( '..', '.' ) );

								foreach ( $regex_files as $regex_file ) {
									$filename = Text::removeFileExtension( $regex_file );
									$filename = str_replace( 'class-', '', $filename );
									$filename = implode( '_', array_map( 'ucfirst', explode( '-', $filename ) ) );
									$class    = '\\WeglotWP\\Third\\' . implode( '', array_map( 'ucfirst', explode( '-', $third ) ) ) . '\\Regexcheckers\\' . $filename;
									array_push( $checkers, new RegexChecker( $class::REGEX, $class::TYPE, $class::VAR_NUMBER, $class::$KEYS ) );
								}
							}
						}
					}
				}
			}
		}

		return apply_filters( 'weglot_get_regex_checkers', $checkers );
	}

}
