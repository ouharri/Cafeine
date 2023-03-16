<?php

namespace WeglotWP\Third\TheEventsCalendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;


/**
 * Theeventscalendar_Active
 *
 * @since 3.1.2
 */
class Theeventscalendar_Words implements Hooks_Interface_Weglot {

	/**
	 * @var Theeventscalendar_Active
	 */
	private $theeventcalendar_active_services;

	/**
	 * @since 3.1.2
	 * @return void
	 */
	public function __construct() {
		$this->theeventcalendar_active_services = weglot_get_service( 'Theeventscalendar_Active' );
	}

	/**
	 * @since 3.1.2
	 * @see Hooks_Interface_Weglot
	 * @return void
	 */
	public function hooks() {

		if ( ! $this->theeventcalendar_active_services->is_active() ) {
			return;
		}

		add_filter( 'weglot_words_translate', array( $this, 'weglot_theeventscalendar_words' ) );
	}


	/**
	 * @return array
	 * @since 3.1.2
	 */
	public function weglot_theeventscalendar_words( $words ) {

		$s = array(
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday',
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December',
		);

		$words = array_merge( $words, $s );

		return $words;
	}

}
