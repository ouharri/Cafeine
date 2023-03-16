<?php

namespace WeglotWP\Third\TheEventsCalendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Theeventscalendar_Active
 *
 * @since 3.1.2
 */
class Theeventscalendar_Active implements Third_Active_Interface_Weglot {

	/**
	 * @since 3.1.2
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$active = true;

		if ( ! is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) {
			$active = false;
		}

		return apply_filters( 'weglot_theeventscalendar_is_active', $active );
	}
}
