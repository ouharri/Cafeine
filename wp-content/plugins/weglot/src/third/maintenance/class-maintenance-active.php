<?php

namespace WeglotWP\Third\Maintenance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Maintenance_Active
 *
 * @since 3.1.4
 */
class Maintenance_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.1.4
	 * @return boolean
	 *
	 * Check if Maintenance plugin is active
	 * https://fr.wordpress.org/plugins/maintenance/
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'maintenance/maintenance.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_maintenance_is_active', $active );
	}
}
