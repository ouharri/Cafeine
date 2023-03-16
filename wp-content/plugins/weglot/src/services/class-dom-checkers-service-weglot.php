<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Util\Text;


/**
 * Dom Checkers
 *
 * @since 2.0
 * @version 2.0.6
 */
class Dom_Checkers_Service_Weglot {

	/**
	 * @since 2.0
	 * @return array
	 */
	public function get_dom_checkers() {
		$files    = array_diff( scandir( __DIR__ . '/../domcheckers' ), array( '..', '.' ) );
		$checkers = array_map(
			function ( $filename ) {
				// Thanks WPCS :)
				$filename = Text::removeFileExtension( $filename );
				$filename = str_replace( 'class-', '', $filename );
				$filename = implode( '_', array_map( 'ucfirst', explode( '-', $filename ) ) );
				return '\\WeglotWP\\Domcheckers\\' . $filename;
			},
			$files
		);

		return apply_filters( 'weglot_get_dom_checkers', $checkers );
	}
}
