<?php
/**
 * Urls class.
 *
 * @since 2.2.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Urls class.
 *
 * @since 2.2.0
 */
class OMAPI_Urls {

	/**
	 * Get the settings url.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function settings( $args = array() ) {
		return self::om_admin( 'settings', $args );
	}

	/**
	 * Get the campaigns url.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function campaigns( $args = array() ) {
		return self::om_admin( 'campaigns', $args );
	}

	/**
	 * Get the templates url.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function templates( $args = array() ) {
		return self::om_admin( 'templates', $args );
	}

	/**
	 * Get the playbooks url.
	 *
	 * @since 2.12.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function playbooks( $args = array() ) {
		return self::om_admin( 'playbooks', $args );
	}

	/**
	 * Get the OM wizard url.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function wizard( $args = array() ) {
		return self::om_admin( 'onboarding-wizard', $args );
	}

	/**
	 * Get the contextual OM dashboard url.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function dashboard( $args = array() ) {
		return self::om_admin( 'dashboard', $args );
	}

	/**
	 * Get the campaign output settings edit url.
	 *
	 * @since 2.2.0
	 *
	 * @param  string $campaign_slug The campaign slug to edit.
	 * @param  array  $args Array of query args.
	 *
	 * @return string
	 */
	public static function campaign_output_settings( $campaign_slug, $args = array() ) {
		$args = array_merge( $args, array( 'campaignId' => $campaign_slug ) );

		return self::campaigns( $args );
	}

	/**
	 * Get the OM onboarding dashboard url.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function onboarding( $args = array() ) {
		$args = array_merge( $args, array( 'info' => true ) );

		return self::dashboard( $args );
	}

	/**
	 * Get a link to an OM admin page.
	 *
	 * @since 2.2.0
	 *
	 * @param  string $page Page shortened slug.
	 * @param  array  $args Array of query args.
	 *
	 * @return string
	 */
	public static function om_admin( $page, $args ) {
		$defaults = array(
			'page' => 'optin-monster-' . $page,
		);

		return self::admin( wp_parse_args( $args, $defaults ) );
	}

	/**
	 * Get an admin page url.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return string
	 */
	public static function admin( $args = array() ) {
		$url = add_query_arg( $args, admin_url( 'admin.php' ) );

		return esc_url_raw( $url );
	}

	/**
	 * Get app url, with proper query args set to ensure going to correct account, and setting return
	 * query arg to come back (if relevant on the destination page).
	 *
	 * @since 2.2.0
	 *
	 * @param  string $path The path on the app.
	 * @param  string $return_url Url to return. Will default to wp_get_referer().
	 *
	 * @return string        The app url.
	 */
	public static function om_app( $path, $return_url = '' ) {
		$app_url           = OPTINMONSTER_APP_URL . '/';
		$final_destination = $app_url . $path;

		if ( empty( $return_url ) ) {

			$return_url = wp_get_referer();
			if ( empty( $return_url ) ) {
				$return_url = self::dashboard();
			}
		}
		$return_url = rawurlencode( $return_url );

		$final_destination = add_query_arg( 'return', $return_url, $final_destination );

		$url = add_query_arg( 'redirect_to', rawurlencode( $final_destination ), $app_url );

		$account_id = OMAPI::get_instance()->get_option( 'userId' );
		if ( ! empty( $account_id ) ) {
			$url = add_query_arg( 'accountId', $account_id, $url );
		}

		return $url;
	}

	/**
	 * Get upgrade url, with utm_medium param and optional feature.
	 *
	 * @since 2.4.0
	 *
	 * @param  string $utm_medium The utm_medium query param.
	 * @param  string $return_url Url to return. Will default to wp_get_referer().
	 * @param  array  $args       Additional query args.
	 *
	 * @return string        The upgrade url.
	 */
	public static function upgrade( $utm_medium, $feature = 'none', $return_url = '', $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'utm_source'   => 'WordPress',
				'utm_medium'   => $utm_medium,
				'utm_campaign' => 'Plugin',
				'feature'      => $feature,
			)
		);

		$path = add_query_arg( $args, 'account/wp-upgrade/' );

		return self::om_app( $path, $return_url );
	}

	/**
	 * Get marketing url, with utm_medium params.
	 *
	 * @since 2.11.0
	 *
	 * @param  string $path The path on the app.
	 * @param  array  $args Additional query args.
	 *
	 * @return string        The marketing url.
	 */
	public static function marketing( $path = '', $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'utm_source'   => 'WordPress',
				'utm_medium'   => '',
				'utm_campaign' => 'Plugin',
			)
		);

		return add_query_arg( $args, sprintf( OPTINMONSTER_URL . '/%1$s', $path ) );
	}

	/**
	 * Returns the API credentials for OptinMonster.
	 *
	 * @since 2.2.0
	 *
	 * @return string The API url to use for embedding on the page.
	 */
	public static function om_api() {
		$custom_api_url = OMAPI::get_instance()->get_option( 'customApiUrl' );
		return ! empty( $custom_api_url ) ? $custom_api_url : OPTINMONSTER_APIJS_URL;
	}
}
