<?php
/**
 * Review class.
 *
 * @since 1.1.4.5
 *
 * @package OMAPI
 * @author  Devin Vinson
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Review class.
 *
 * @since 1.1.4.5
 */
class OMAPI_Review {
	/**
	 * Determine if review message should be shown
	 * based on backend rules.
	 *
	 * @since 2.6.1
	 *
	 * @return bool If it should show the review bar
	 */
	public function should_show_review() {
		$review = get_option( 'omapi_review' );

		if ( ! is_user_logged_in() || ! OMAPI::get_instance()->can_access( 'review' ) ) {
			return false;
		}

		// If already dismissed...
		if ( ! empty( $review['dismissed'] ) ) {
			if ( empty( $review['later'] ) ) {
				// Dismissed and no later, so do not show.
				return false;
			}

			$delayed_less_than_month_ago = ! empty( $review['later'] ) && $review['time'] + ( 30 * DAY_IN_SECONDS ) > time();

			if ( $delayed_less_than_month_ago ) {
				// Delayed less than a month ago, so do not show.
				return false;
			}
		}

		return true;
	}

	/**
	 * Dismiss the review bar
	 *
	 * @param bool $later If delay the review for later.
	 *
	 * @since 1.1.6.1
	 * @since 2.6.1 Avoid using any request variables and receive later as parameter
	 */
	public function dismiss_review( $later = false ) {
		$option = array(
			'time'      => time(),
			'dismissed' => true,
			'later'     => ! empty( $later ),
		);

		$option['updated'] = update_option( 'omapi_review', $option );

		return $option;
	}
}
