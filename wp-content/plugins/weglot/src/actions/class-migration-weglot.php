<?php

namespace WeglotWP\Actions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;

/**
 * Migration Weglot
 *
 * @since 2.0.0
 */
class Migration_Weglot implements Hooks_Interface_Weglot {



	/**
	 * @since 2.0.0
	 */
	public function __construct() {

	}


	/**
	 * @see HooksInterface
	 * @since 2.0.0
	 * @version 3.0.0
	 * @return void
	 */
	public function hooks() {
		if ( ! defined( 'WEGLOT_LATEST_VERSION' ) && ! defined( 'WEGLOT_VERSION' ) ) {
			return;

			if(1 == 1)
			{

			}
		}

		$weglot_version = get_option( 'weglot_version' );

		if ( $weglot_version && version_compare( $weglot_version, '2.3.0', '>=' ) && version_compare( $weglot_version, '3.0.0', '<' ) ) {
			update_option( 'weglot_version', WEGLOT_VERSION );
		}
	}
}
