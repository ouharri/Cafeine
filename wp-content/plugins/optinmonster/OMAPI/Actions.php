<?php
/**
 * Actions class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Actions class.
 *
 * @since 1.0.0
 */
class OMAPI_Actions {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Add validation messages.
		add_action( 'admin_init', array( $this, 'maybe_fetch_missing_data' ), 99 );

		// We can run upgrade routines on cron runs and admin requests.
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			add_action( 'optin_monster_api_global_loaded', array( $this, 'check_upgrade_routines' ), 99 );
		} else {
			add_action( 'admin_init', array( $this, 'check_upgrade_routines_admin' ), 100 );
		}
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * When the plugin is first installed
	 * Or Migrated from a pre-1.8.0 version
	 * We need to fetch some additional data
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function maybe_fetch_missing_data() {
		$creds   = $this->base->get_api_credentials();
		$option  = $this->base->get_option();
		$changed = false;

		// If we don't have an API Key yet, we can't fetch anything else.
		if ( empty( $creds['apikey'] ) && empty( $creds['user'] ) && empty( $creds['key'] ) ) {
			return;
		}

		// Fetch the userId and accountId, if we don't have them.
		if (
			empty( $option['userId'] )
			|| empty( $option['accountId'] )
			|| empty( $option['currentLevel'] )
			|| empty( $option['plan'] )
			|| empty( $creds['apikey'] )
		) {
			$result = OMAPI_Api::fetch_me( $option, $creds );

			if ( ! is_wp_error( $result ) ) {
				$changed = true;
				$option  = $result;
			}
		}

		// Fetch the SiteIds for this site, if we don't have them.
		if (
			empty( $option['siteIds'] )
			|| empty( $option['siteId'] )
			|| $this->site_ids_are_numeric( $option['siteIds'] )
			|| ! isset( $option['apiCname'] )
		) {

			$result = $this->base->sites->fetch();
			if ( ! is_wp_error( $result ) ) {
				$option  = array_merge( $option, $result );
				$changed = true;
			}
		}

		// Only update the option if we've changed something.
		if ( $changed ) {
			update_option( 'optin_monster_api', $option );
		}
	}

	/**
	 * In one version of the Plugin, we fetched the numeric SiteIds,
	 * But we actually needed the alphanumeric SiteIds.
	 *
	 * So we use this check to determine if we need to re-fetch Site Ids.
	 *
	 * @param array $site_ids Site ids to convert.
	 * @return bool True if the ids are numeric.
	 */
	protected function site_ids_are_numeric( $site_ids ) {
		foreach ( $site_ids as $id ) {
			if ( ! ctype_digit( (string) $id ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Runs upgrade routines in the admin, and refreshes the page if needed
	 * (if options changed, etc).
	 *
	 * @since 2.6.5
	 *
	 * @return void
	 */
	public function check_upgrade_routines_admin() {
		$refresh = $this->check_upgrade_routines();
		if ( $refresh ) {
			wp_safe_redirect( esc_url_raw( add_query_arg( 'om', 1 ) ) );
			exit;
		}
	}

	/**
	 * Handles running the upgrade routines for each version.
	 *
	 * @since 2.6.5
	 *
	 * @return bool Whether page should be refreshed.
	 */
	public function check_upgrade_routines() {
		$in_progress = get_option( 'optinmonster_current_upgrade' );
		if ( ! empty( $in_progress ) ) {
			return false;
		}

		$refresh           = false;
		$plugin_version    = $this->base->version;
		$upgrade_completed = get_option( 'optinmonster_upgrade_completed', 0 );
		$upgrade_map       = array(
			'2.6.5' => 'v265_upgrades',
			'2.9.0' => 'v290_upgrades',
		);
		foreach ( $upgrade_map as $upgrade_version => $method ) {
			if (
				version_compare( $plugin_version, $upgrade_version, '>=' )
				&& version_compare( $upgrade_completed, $upgrade_version, '<' )
			) {
				update_option( 'optinmonster_current_upgrade', $upgrade_version );
				$refresh = $this->{$method}();
				delete_option( 'optinmonster_current_upgrade' );
			}
		}

		if ( (string) $plugin_version !== (string) $upgrade_completed ) {
			if ( empty( $this->base->notifications ) ) {
				$this->base->notifications = new OMAPI_Notifications();
			}
			$this->base->notifications->update();
			update_option( 'optinmonster_upgrade_completed', $plugin_version );
		}

		return $refresh;
	}

	/**
	 * Upgrades for version 2.6.5.
	 *
	 * @since 2.6.5
	 *
	 * @return bool  Whether upgrade routine was completed successfully.
	 */
	public function v265_upgrades() {
		$creds = $this->base->get_api_credentials();

		// Missing previous api key to verify.
		if ( empty( $creds['apikey'] ) ) {
			return false;
		}

		$api     = OMAPI_Api::build( 'v1', 'verify/', 'POST', $creds );
		$results = $api->request();

		// Current key is fine.
		if ( ! is_wp_error( $results ) ) {
			return false;
		}

		$error_code = ! empty( $api->response_body->code )
			? $api->response_body->code
			: 0;
		if (
			in_array( (string) $api->response_code, array( '410', '401', '424', '403' ), true )
			&& '10051' === (string) $error_code
		) {
			OMAPI_ApiKey::regenerate( $creds['apikey'] );

			// Regenerated, so we want to refresh the page.
			return true;
		}

		// No luck.
		return false;
	}

	/**
	 * Upgrades for version 2.9.0.
	 *
	 * This adds an admin_url to the site.
	 *
	 * @since 2.9.0
	 *
	 * @return bool  Whether upgrade routine was completed successfully.
	 */
	public function v290_upgrades() {
		$creds  = $this->base->get_api_credentials();
		$siteId = $this->base->get_site_id();

		if ( empty( $creds['apikey'] ) || empty( $siteId ) ) {
			return false;
		}

		$args = array(
			'admin_url' => esc_url_raw( get_admin_url() ),
		);

		$api     = OMAPI_Api::build( 'v2', 'sites/' . $siteId, 'PUT', $creds );
		$results = $api->request( $args );

		return ! is_wp_error( $results );
	}
}
