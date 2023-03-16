<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.webtoffee.com/
 * @since      3.0.0
 *
 * @package    CookieYes
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( defined( 'CKY_REMOVE_ALL_DATA' ) && true === CKY_REMOVE_ALL_DATA ) {
	try {
		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'cky_banners' ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'cky_cookie_categories' ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'cky_cookies' ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		$prefix = $wpdb->esc_like( '_transient_cky' ) . '%';
		$keys   = $wpdb->get_results( $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s", $prefix ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( ! is_wp_error( $keys ) ) {
			$transients = array_map(
				function( $key ) {
					return ltrim( $key['option_name'], '_transient_' );
				},
				$keys
			);
			foreach ( $transients as $key ) {
				delete_transient( $key );
			}
		}
		$options = array(
			'cky_banners_table_version',
			'cky_cookie_category_table_version',
			'cky_cookie_table_version',
			'cky_consent_table_version',
			'cky_scan_details',
			'cky_settings',
			'cky_admin_notices',
			'wt_cli_version',
			'CookieLawInfo-0.9',
			'cky_cookie_consent_lite_db_version',
			'cky_missing_tables',
			'cky_migration_options',
		);
		foreach ( $options as $option_name ) {
			delete_option( $option_name );
		}
	} catch ( Exception $e ) {
		error_log( __( 'Failed to delete CookieYes plugin data!', 'cookie-law-info' ) );
	}
}
