<?php
/**
 * Partners class.
 *
 * @since 2.0.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Partners class.
 *
 * @since 2.0.0
 */
class OMAPI_Partners {

	/**
	 * The OM landing page url.
	 *
	 * @since 1.8.4
	 */
	const LANDING_URL = 'https://optinmonster.com/wp/?utm_source=orgplugin&utm_medium=link&utm_campaign=wpdashboard';

	/**
	 * The SaS affiliate url.
	 *
	 * @since 2.0.0
	 */
	const SAS_URL = 'https://www.shareasale.com/r.cfm?u=%1$s&b=601672&m=49337&afftrack=&urllink=optinmonster.com';

	/**
	 * Get the SAS Partner ID.
	 *
	 * 3 ways to specify an ID, ordered by highest to lowest priority:
	 *  - add_filter( 'optinmonster_sas_id', function() { return 1234; } );
	 *  - define( 'OPTINMONSTER_SAS_ID', 1234 );
	 *  - get_option( 'optinmonster_sas_id' ); (with the option being in the
	 *    wp_options table) If an ID is present, returns the affiliate link
	 *    with the affiliate ID.
	 *
	 * @since  2.0.0
	 *
	 * @return string
	 */
	public static function get_sas_id() {
		$sas_id = '';

		// Check if sas ID is a constant.
		if ( defined( 'OPTINMONSTER_SAS_ID' ) ) {
			$sas_id = OPTINMONSTER_SAS_ID;
		}

		// Now run any filters that may be on the sas ID.
		$sas_id = apply_filters( 'optinmonster_sas_id', $sas_id );

		/**
		 * If we still don't have a sas ID by this point
		 * check the DB for an option
		 */
		if ( empty( $sas_id ) ) {
			$sas_id = get_option( 'optinmonster_sas_id', $sas_id );
		}

		return $sas_id;
	}

	/**
	 * Get the trial Partner ID.
	 *
	 * 3 ways to specify an ID, ordered by highest to lowest priority:
	 *  - add_filter( 'optinmonster_trial_id', function() { return 1234; } );
	 *  - define( 'OPTINMONSTER_TRIAL_ID', 1234 );
	 *  - get_option( 'optinmonster_trial_id' ); (with the option being in the
	 *    wp_options table) If an ID is present, returns the affiliate link
	 *    with the affiliate ID.
	 *
	 * @since  2.0.0
	 *
	 * @return string
	 */
	public static function get_trial_id() {
		$trial_id = '';

		// Check if trial ID is a constant.
		if ( defined( 'OPTINMONSTER_TRIAL_ID' ) ) {
			$trial_id = OPTINMONSTER_TRIAL_ID;
		}

		// Now run any filters that may be on the trial ID.
		$trial_id = apply_filters( 'optinmonster_trial_id', $trial_id );

		/**
		 * If we still don't have a trial ID by this point
		 * check the DB for an option
		 */
		if ( empty( $trial_id ) ) {
			$trial_id = get_option( 'optinmonster_trial_id', $trial_id );
		}

		return $trial_id;
	}

	/**
	 * Get the affiliate url for given id.
	 *
	 * @since  1.8.4
	 *
	 * @param  mixed $partner_id The Partner ID.
	 *
	 * @return string            The affilaite url.
	 */
	protected static function get_affiliate_url( $partner_id ) {
		return sprintf( self::SAS_URL, rawurlencode( trim( $partner_id ) ) );
	}

	/**
	 * The partner urls are no longer used, but this method is in place to ensure
	 * back-compatibility with the optin_monster_action_link filter, to determine
	 * if certain partner features are visible.
	 *
	 * @since  2.0.0
	 *
	 * @return boolean
	 */
	protected static function get_partner_url() {
		$id   = self::get_trial_id();
		$type = 'trial';
		if ( empty( $id ) ) {
			$id   = self::get_sas_id();
			$type = 'sas';
		}

		// Return the regular WP landing page by default.
		$url = self::LANDING_URL;

		// Return the trial link if we have a trial ID.
		if ( ! empty( $id ) ) {
			$url = self::get_affiliate_url( $id );
		}

		return apply_filters(
			'optin_monster_action_link',
			$url,
			array(
				'type' => $type,
				'id'   => $id,
			)
		);
	}

	/**
	 * The partner urls are no longer used, but this method is in place to ensure
	 * back-compatibility with the optin_monster_action_link filter, to determine
	 * if certain partner features are visible.
	 *
	 * @since  2.0.0
	 *
	 * @return string|boolean
	 */
	public static function has_partner_url() {
		$url = self::get_partner_url();

		return false === strpos( $url, 'optinmonster.com/wp' )
			? $url
			: false;
	}

	/**
	 * Get the Partner ID.
	 *
	 * @since  2.0.0
	 *
	 * @return string
	 */
	public static function get_id() {
		$id = self::get_trial_id();
		if ( empty( $id ) ) {
			$id = self::get_sas_id();
		}

		return $id;
	}

	/**
	 * Get the referrer, if stored.
	 *
	 * @since 2.10.0
	 *
	 * @return string  Referrer
	 */
	public static function referred_by() {
		return sanitize_text_field( get_option( 'optinmonster_referred_by', '' ) );
	}

}
