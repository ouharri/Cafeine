<?php
/**
 * Rest API Class, where we register/execute any REST API Routes
 *
 * @since 1.8.0
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rest Api class.
 *
 * @since 1.8.0
 */
class OMAPI_Sites {

	/**
	 * Holds the class object.
	 *
	 * @since 2.3.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.3.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * The Base OMAPI Object
	 *
	 * @since 1.8.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Refresh the site data.
	 *
	 * @since 1.8.0
	 *
	 * @param mixed $api_key    If we want to use a custom API Key, pass it in.
	 * @param bool  $get_cached Whether to get the cached response. Defaults to false.
	 *
	 * @return array|null $sites An array of sites if the request is successful.
	 */
	public function fetch( $api_key = null, $get_cached = false ) {
		$cache_key = 'om_sites' . md5( $api_key );

		if ( $get_cached ) {
			$results = get_transient( $cache_key );
			if ( ! empty( $results ) ) {
				return $results;
			}
		}

		// Delete any cached sites.
		delete_transient( $cache_key );

		$creds = ! empty( $api_key ) ? array( 'apikey' => $api_key ) : array();
		$body  = OMAPI_Api::build( 'v2', 'sites/origin', 'GET', $creds )->request();

		if ( is_wp_error( $body ) ) {
			return $this->handle_payment_required_error( $body );
		}

		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$results = array(
			'siteId'       => '',
			'siteIds'      => array(),
			'customApiUrl' => '',
			'apiCname'     => '',
		);

		$domain = $this->get_domain();
		$tld    = $this->get_tld( $domain );

		if ( ! empty( $body->data ) ) {
			$check_cnames = true;
			foreach ( $body->data as $site ) {
				if ( empty( $site->domain ) ) {
					continue;
				}

				$matches         = $domain === (string) $site->domain;
				$wildcard_domain = '*.' === substr( $site->domain, 0, 2 ) && $tld === $this->get_tld( $site->domain );

				// Doesn't match, and not a wildcard? Bail.
				if ( ! $matches && ! $wildcard_domain ) {
					continue;
				}

				$results['siteIds'][] = (string) $site->siteId;

				// If we don't have a siteId yet, set it to this one.
				// If we DO already have a siteId and this one is NOT a wildcard,
				// we want to overwrite with this one.
				if ( empty( $results['siteId'] ) || ! $wildcard_domain ) {
					$results['siteId'] = (string) $site->siteId;
				}

				// Do we have a custom cnamed api url to use?
				if ( $check_cnames && $site->settings->enableCustomCnames ) {

					$found = false;
					if ( $site->settings->cdnCname && $site->settings->cdnCnameVerified ) {

						// If we have a custom CNAME, let's enable it and add the data to the output array.
						$results['customApiUrl'] = 'https://' . $site->settings->cdnUrl . '/app/js/api.min.js';
						$found                   = true;

						if (
							! empty( $site->settings->apiCname )
							&& ! empty( $site->settings->apiCnameVerified )
						) {
							$results['apiCname'] = $site->settings->apiCname;
						}
					}

					// If this isn't a wildcard domain, and we found a custom api url, we don't
					// need to continue checking cnames.
					if ( $found && ! $wildcard_domain ) {
						$check_cnames = false;
					}
				}
			}
		}

		if ( empty( $results['siteId'] ) ) {
			$result = $this->check_existing_site( $creds );
			if ( is_wp_error( $result ) ) {
				return $result;
			}

			$site = $this->attempt_create_site( $creds );
			if ( is_wp_error( $site ) ) {
				return $this->handle_payment_required_error( $site );
			}

			// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( ! empty( $site->siteId ) ) {
				$results['siteId'] = (string) $site->siteId;
				// phpcs:enable
			}
		}

		if ( ! is_wp_error( $results ) && ! empty( $results['siteIds'] ) ) {
			set_transient( $cache_key, $results, 5 * MINUTE_IN_SECONDS );
		}

		// phpcs:enable
		return $results;
	}

	/**
	 * Attempt to create the associated site in the app.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $creds Array of credentials for request.
	 *
	 * @return mixed         Site-created response or WP_Error.
	 */
	public function attempt_create_site( $creds ) {
		$settings              = OMAPI_Api::getUrlArgs();
		$settings['wordpress'] = 1;

		$site_args = array(
			'domain'   => esc_url_raw( site_url() ),
			'name'     => esc_attr( get_option( 'blogname' ) ),
			'settings' => $settings,
		);

		// Create/update the site for this WordPress site.
		$result = OMAPI_Api::build( 'v2', 'sites', 'POST', $creds )
			->request( $site_args );

		return 201 === (int) OMAPI_Api::instance()->response_code
			? OMAPI_Api::instance()->response_body
			: $result;
	}

	/**
	 * Get the domain for this WP site.
	 * Borrowed heavily from AwesomeMotive\OptinMonsterApp\Utils\Url
	 *
	 * @since  1.9.10
	 *
	 * @return string
	 */
	public function get_domain() {
		$url      = site_url();
		$parsed   = OMAPI_Utils::parse_url( $url );
		$hostname = ! empty( $parsed['host'] ) ? $parsed['host'] : $url;
		$domain   = preg_replace( '/^www\./', '', $hostname );

		return $domain;
	}

	/**
	 * Get the top-level-domain for the given domain.
	 *
	 * @since  2.0.1
	 *
	 * @param  string $domain Domain to get tld for.
	 *
	 * @return string          The tld.
	 */
	public function get_tld( $domain ) {
		$parts = explode( '.', $domain );
		$count = count( $parts );
		$tld   = array_slice( $parts, max( 0, $count - 2 ) );

		return implode( '.', $tld );
	}

	/**
	 * Updates the error text when we try to auto-create this WP site, but it fails.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_Error $error The error object.
	 *
	 * @return WP_Error
	 */
	public function handle_payment_required_error( $error ) {
		$instance = OMAPI_Api::instance();
		if ( 402 === (int) $error->get_error_data() && ! empty( $instance->response_body->siteAmount ) ) {

			$message = sprintf(
				/* translators: %1$s - Link to account upgrade page, %2$s Link to account page to purchase additional licenses */
				__( 'We tried to register your WordPress site with OptinMonster, but You have reached the maximum number of registered sites for your current OptinMonster plan.<br>Additional sites can be added to your account by <a href="%1$s" target="_blank" rel="noopener">upgrading</a> or <a href="%2$s" target="_blank" rel="noopener">purchasing additional site licenses</a>.', 'optin-monster-api' ),
				esc_url_raw( OPTINMONSTER_APP_URL . '/account/upgrade/?utm_source=app&utm_medium=upsell&utm_campaign=header&feature=sites/' ),
				esc_url_raw( OPTINMONSTER_APP_URL . '/account/billing/#additional-licenses' )
			);

			$error = new WP_Error( $error->get_error_code(), $message, array( 'status' => 402 ) );
		}

		return $error;
	}

	/**
	 * Check if user has already connected existing site, and return error.
	 *
	 * @since 2.3.0
	 *
	 * @param  array $creds Array of credentials for request.
	 *
	 * @return WP_Error|bool WP_Error if user already has connected site.
	 */
	public function check_existing_site( $creds ) {

		// Check if they already have a registered site.
		$site_id = $this->base->get_site_id();
		if ( empty( $site_id ) ) {
			return false;
		}

		// Now check for that previously-registered site in our API.
		$body = OMAPI_Api::build( 'v2', "sites/{$site_id}", 'GET', $creds )->request();
		if ( empty( $body->name ) ) {
			return false;
		}

		$site_edit_url = OMAPI_Urls::om_app( "sites/{$site_id}/edit/" );

		// 'This domain does not match your registered site, %s (%s)'
		$message = sprintf(
			/* translators: %s - Current site domain, Link to registered OptinMonster site, name of registered OptinMonster site, domain for registered OptinMonster site */
			__( 'This domain (%1$s) does not match your registered site — <a href="%2$s" target="_blank" rel="noopener">%3$s (%4$s)</a>', 'optin-monster-api' ),
			$this->get_domain(),
			esc_url_raw( $site_edit_url ),
			sanitize_text_field( $body->name ),
			sanitize_text_field( $body->domain )
		);

		$args = array(
			'status' => 404,
			'site'   => array(
				'name'    => $body->name,
				'domain'  => $body->domain,
				'editUrl' => $site_edit_url,
			),
		);

		return new WP_Error( 'omapp_wrong_site', $message, $args );
	}

}
