<?php
/**
 * Mailpoet integration class.
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
 * OM API Key management class.
 *
 * @since 2.0.0
 */
class OMAPI_ApiKey {

	/**
	 * Handles storing the API key and initiating the API connection.
	 *
	 * @since 2.0.0
	 *
	 * @param string $apikey The OM api key.
	 *
	 * @return bool True if the Key can be validated
	 */
	public static function init_connection( $apikey ) {
		$base = OMAPI::get_instance();

		$creds                   = compact( 'apikey' );
		$option                  = $base->get_option();
		$option['api']['apikey'] = $apikey;

		// Let's store the api-key first.
		$base->save->update_option( $option, $creds );

		// Go ahead and remove the old user and key.
		$option['api']['user'] = '';
		$option['api']['key']  = '';

		// Remove any error messages.
		$option['is_invalid']     = false;
		$option['is_expired']     = false;
		$option['is_disabled']    = false;
		$option['connected']      = time();
		$option['auto_updates']   = 'all';
		$option['usage_tracking'] = true;

		// Remove any pre-saved site/user/account data, so we re-fetch it elsewhere.
		unset( $option['siteId'] );
		unset( $option['siteIds'] );
		unset( $option['customApiUrl'] );
		unset( $option['apiCname'] );
		unset( $option['userId'] );
		unset( $option['accountId'] );
		unset( $option['currentLevel'] );
		unset( $option['plan'] );
		unset( $option['revenueAttribution'] );

		// Fetch the userId and accountId now.
		$option = OMAPI_Api::fetch_me( $option, $creds );
		if ( is_wp_error( $option ) ) {
			return $option;
		}

		// Fetch the SiteIds for this site now.
		$result = $base->sites->fetch( $apikey );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$option = array_merge( $option, $result );

		// Fetch the campaigns for this site now.
		$base->refresh->refresh( $apikey );

		// Save the option one more time, with all the new good stuff..
		$base->save->update_option( $option, $creds );

		return $option;
	}

	/**
	 * Remove the API key and disconnect from the OptinMonster app.
	 *
	 * @since  2.0.0
	 *
	 * @return mixed The results of update_option.
	 */
	public static function disconnect() {
		$option = OMAPI::get_instance()->get_option();

		$option['connected']     = 0;
		$option['api']['apikey'] = '';

		// Remove any pre-saved site/user/account data, so we re-fetch it elsewhere.
		unset( $option['userId'] );
		unset( $option['accountId'] );
		unset( $option['currentLevel'] );
		unset( $option['plan'] );
		unset( $option['siteId'] );
		unset( $option['siteIds'] );
		unset( $option['customApiUrl'] );
		unset( $option['apiCname'] );
		unset( $option['api']['user'] );
		unset( $option['api']['key'] );

		// Save the updated option.
		return OMAPI::get_instance()->save->update_option( $option );
	}

	/**
	 * Determine if we can store the given api key.
	 *
	 * @since 2.0.0
	 *
	 * @param string $apikey The OM api key.
	 *
	 * @return bool True if the Key can be validated
	 */
	public static function verify( $apikey ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		$creds = compact( 'apikey' );

		// Verify this new API Key works by posting to the Legacy route.
		return OMAPI_Api::build( 'v1', 'verify/', 'POST', $creds )->request();
	}

	/**
	 * Validate this API Key
	 * We validate an API Key by fetching the Sites this key can fetch
	 * And then confirming that this key has access to at least one of these sites
	 *
	 * @since 2.0.0
	 *
	 * @param string $apikey The OM api key.
	 *
	 * @return bool True if the Key can be validated
	 */
	public static function validate( $apikey ) {
		if ( empty( $apikey ) ) {
			return false;
		}

		$site_ids = OMAPI::get_instance()->get_site_ids();

		if ( empty( $site_ids ) ) {
			return false;
		}

		$api_key_sites = OMAPI::get_instance()->sites->fetch( $apikey, true );

		if ( is_wp_error( $api_key_sites ) || empty( $api_key_sites['siteIds'] ) ) {
			return false;
		}

		foreach ( $site_ids as $site_id ) {
			if ( in_array( $site_id, $api_key_sites['siteIds'] ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if we have a valid api key stored.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public static function has_credentials() {
		$creds = OMAPI::get_instance()->get_api_credentials();

		return ! empty( $creds['apikey'] ) || self::has_legacy();
	}

	/**
	 * Determine if we have legacy api credentials.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public static function has_legacy() {
		$creds = OMAPI::get_instance()->get_api_credentials();

		return ! empty( $creds['user'] ) && ! empty( $creds['key'] );
	}

	/**
	 * Handles regnerating api key.
	 *
	 * @since 2.6.5
	 *
	 * @param  string $apikey Api Key to replace after regeneration.
	 *
	 * @return mixed  $value  The response to the API call.
	 */
	public static function regenerate( $apikey ) {
		return OMAPI_Api::build( 'v2', 'key/regenerate', 'POST', compact( 'apikey' ) )
			->request(
				array(
					'tt' => OMAPI_ApiAuth::get_tt(),
				)
			);
	}

}

