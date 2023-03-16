<?php

namespace WeglotWP\Actions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Permalink Weglot
 *
 * @since 2.0
 */
class Permalink_Weglot {

	/**
	 * @since 2.0
	 * @return void
	 */
	public function activate() {
		$structure = get_option( 'permalink_structure' );
		if ( empty( $structure ) ) {
			add_option( 'weglot_old_permalink_structure', $structure );
			update_option( 'permalink_structure', '/%postname%/' );
		}
	}

	/**
	 *
	 * @since 2.0
	 * @return void
	 */
	public function deactivate() {
		$old_structure = get_option( 'weglot_old_permalink_structure' );
		if ( $old_structure ) {
			delete_option( 'weglot_old_permalink_structure' );
			update_option( 'permalink_structure', $old_structure );
		}
	}
}

