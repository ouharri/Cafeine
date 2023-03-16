<?php
/**
 * Notifications class.
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
 * Notifications class.
 *
 * @since 2.0.0
 */
class OMAPI_Notifications {

	/**
	 * Holds the class object.
	 *
	 * @since 2.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Source of notifications content.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const SOURCE_URL = 'https://a.omwpapi.com/production/wp/notifications.json';

	/**
	 * The option where the notifications are stored.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const OPTION_NAME = 'om_notifications';

	/**
	 * Option value.
	 *
	 * @since 2.0.0
	 *
	 * @var bool|array
	 */
	protected $option = null;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();
		$this->hooks();
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 2.0.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Register hooks.
	 *
	 * @since 2.0.0
	 */
	public function hooks() {
		add_action( 'optin_monster_api_rest_loaded', array( $this, 'schedule_next_update' ) );
		add_action( 'optin_monster_api_admin_loaded', array( $this, 'schedule_next_update' ) );

		add_action( 'optin_monster_api_admin_notifications_update', array( $this, 'update' ) );
		add_filter( 'optin_monster_api_notifications_count', array( $this, 'get_count' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Schedule the next notifications fetch.
	 *
	 * @since 2.11.1
	 *
	 * @return void
	 */
	public function schedule_next_update() {
		$hook      = 'optin_monster_api_admin_notifications_update';
		$args      = array( 'wpcron' );
		$scheduled = wp_next_scheduled( $hook, $args );

		if ( $scheduled ) {

			// Nothing to do here.
			return;
		}

		$timezone = new DateTimeZone( 'America/New_York' );
		$now      = new DateTime( 'now', $timezone );
		$todayAm  = DateTime::createFromFormat( 'H:iA', '10:10am', $timezone );
		$date     = $todayAm;

		// If past 10am already...
		if ( $now > $todayAm ) {

			// Try to schedule for 10pm instead.
			$date = DateTime::createFromFormat( 'H:iA', '10:10pm', $timezone );

			// If past 10pm already...
			if ( $now > $date ) {

				// Schedule for 10am tomorrow.
				$date = $todayAm->modify( '+1 day' );
			}
		}

		wp_schedule_single_event( $date->getTimestamp(), $hook, $args );
	}

	/**
	 * Check if user has access and is enabled.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function has_access() {
		$access = (
			$this->base->can_access( 'notifications' )
			&& ! $this->base->get_option( 'hide_announcements' )
		);

		return apply_filters( 'optin_monster_api_admin_notifications_has_access', $access );
	}

	/**
	 * Get option value.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $key   The option value to get for given key.
	 * @param bool   $cache Reference property cache if available.
	 *
	 * @return mixed       The notification option array, or requsted value.
	 */
	public function get_option( $key = '', $cache = true ) {
		if ( ! $this->option || ! $cache ) {
			$option = get_option( self::OPTION_NAME, array() );

			$this->option = array(
				'updated'   => ! empty( $option['updated'] ) ? $option['updated'] : 0,
				'events'    => ! empty( $option['events'] ) ? $option['events'] : array(),
				'feed'      => ! empty( $option['feed'] ) ? $option['feed'] : array(),
				'dismissed' => ! empty( $option['dismissed'] ) ? $option['dismissed'] : array(),
			);
		}

		if ( ! empty( $key ) ) {
			return isset( $this->option[ $key ] ) ? $this->option[ $key ] : false;
		}

		return $this->option;
	}

	/**
	 * Fetch notifications from feed.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function fetch_feed() {
		$url  = add_query_arg( 't', strtotime( 'today' ), self::SOURCE_URL );
		$args = array(
			'sslverify' => false,
		);

		add_filter( 'https_ssl_verify', '__return_false', 98765 );
		$response = wp_remote_get( $url, $args );
		remove_filter( 'https_ssl_verify', '__return_false', 98765 );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			return array();
		}

		return $this->verify( json_decode( $body, true ) );
	}

	/**
	 * Verify notification data before it is saved.
	 *
	 * @since 2.0.0
	 *
	 * @param array $notifications Array of notifications items to verify.
	 * @param array $dismissed     Array of dismissed notification ids.
	 *                             Defaults to fetching them from option.
	 *
	 * @return array
	 */
	public function verify( $notifications, $dismissed = null ) {
		$data = array();
		if ( ! empty( $notifications ) && is_array( $notifications ) ) {

			$dismissed = null !== $dismissed ? $dismissed : $this->get_option( 'dismissed' );
			$installed = $this->base->get_option( 'installed', '', time() );

			foreach ( $notifications as $notification ) {
				$notification = $this->verify_notification( $notification, $dismissed, $installed );
				if ( ! empty( $notification ) ) {
					$data[] = $notification;
				}
			}
		}

		return $data;
	}


	/**
	 * Verify a notification before it is saved.
	 *
	 * @since 2.0.0
	 *
	 * @param array $notification Array of notification data.
	 * @param array $dismissed    Array of dismissed notifications.
	 * @param int   $installed    The installation timestamp.
	 *
	 * @return bool|array The notification if verified, false if not.
	 */
	public function verify_notification( $notification, $dismissed, $installed = null ) {
		$installed = null !== $installed ? $installed : $this->base->get_option( 'installed', '', time() );

		if ( empty( $notification['content'] ) ) {

			// The message should never be empty. If they are, ignore.
			return false;
		}

		/*
		 * - Empty levels  means show to everyone, regardless of plan/connected status.
		 * - `none` is to all who are not connected
		 * - `all` is to all who are connected regardless of license
		 */
		if ( ! empty( $notification['levels'] ) ) {
			if ( ! $this->verify_notification_level( (array) $notification['levels'] ) ) {
				// If notification level verification fails, stop here.
				return false;
			}

			// Otherwise, proceed to the next checks.
		}

		if ( ! empty( $notification['end'] ) && time() > strtotime( $notification['end'] ) ) {

			// Ignore if expired.
			return false;
		}

		if (
			! empty( $notification['min'] )
			&& version_compare( $this->base->version, $notification['min'], '<' )
		) {

			// Ignore if below minimum plugin version.
			return false;
		}

		if (
			! empty( $notification['max'] )
			&& version_compare( $this->base->version, $notification['max'], '>' )
		) {

			// Ignore if above maximum plugin version.
			return false;
		}

		if ( ! empty( $dismissed ) && in_array( $notification['id'], $dismissed ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

			// Ignore if notification has already been dismissed.
			return false;
		}

		// Ignore if notification existed before installing the plugin and
		// the end date has not been set.
		if (
			! empty( $installed ) &&
			! empty( $notification['start'] ) &&
			empty( $notification['end'] ) &&
			$installed > strtotime( $notification['start'] )
		) {
			return false;
		}

		return $notification;
	}

	/**
	 * Verify if the notification levels match.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $levels Array of notification levels.
	 *
	 * @return bool          Whether notification passes level verification.
	 */
	public function verify_notification_level( $levels ) {
		$account_level     = $this->base->get_level();
		$is_connected      = ! empty( $account_level );
		$for_all_connected = in_array( 'all', $levels, true );
		$for_not_connected = in_array( 'none', $levels, true );
		$for_custom        = in_array( 'vbp_custom', $levels, true );
		$level_matches     = in_array( $account_level, $levels, true );

		if ( $for_not_connected ) {
			return ! $is_connected;
		}

		if ( $for_all_connected ) {
			return $is_connected;
		}

		if ( $for_custom ) {
			return $this->base->is_custom_plan();
		}

		return $level_matches;
	}

	/**
	 * Verify saved notification data for active notifications.
	 *
	 * @since 2.0.0
	 *
	 * @param array $notifications Array of notifications items to verify.
	 *
	 * @return array
	 */
	public function verify_active( $notifications ) {

		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return array();
		}

		$notifications = $this->verify( $notifications );
		if ( empty( $notifications ) ) {
			return array();
		}

		$now = time();

		// Remove notfications that are not active.
		foreach ( $notifications as $key => $notification ) {
			if ( ! empty( $notification['start'] ) && $now < strtotime( $notification['start'] ) ) {

				// Notification is not yet active.
				unset( $notifications[ $key ] );
			}

			if ( ! empty( $notification['end'] ) && $now > strtotime( $notification['end'] ) ) {

				// Notification is expired.
				unset( $notifications[ $key ] );
			}

			if (
				! empty( $notification['min'] )
				&& version_compare( $this->base->version, $notification['min'], '<' )
			) {

				// Ignore if below minimum plugin version.
				unset( $notifications[ $key ] );
			}

			if (
				! empty( $notification['max'] )
				&& version_compare( $this->base->version, $notification['max'], '>' )
			) {

				// Ignore if above maximum plugin version.
				unset( $notifications[ $key ] );
			}
		}

		return $notifications;
	}

	/**
	 * Get notification data.
	 *
	 * @since  2.0.0
	 *
	 * @param  bool $can_update Whether we can fetch/update the feed/options.
	 *
	 * @return array
	 */
	public function get( $can_update = false ) {

		if ( ! $this->has_access() ) {
			return array();
		}

		// Update notifications using async task.
		if ( $this->should_update() && $can_update ) {
			$this->update();
		}

		$option = $this->get_option();
		$events = ! empty( $option['events'] ) ? $this->verify_active( $option['events'] ) : array();
		$feed   = ! empty( $option['feed'] ) ? $this->verify_active( $option['feed'] ) : array();

		$notifications = array_merge( $feed, $events );

		set_transient( 'om_notification_count', count( $notifications ), ( 12 * HOUR_IN_SECONDS ) );

		if ( ! $this->base->get_api_credentials() ) {
			$notifications = array_merge(
				$notifications,
				array(
					array(
						'type'       => 'action',
						'title'      => esc_html__( 'You haven\'t finished setting up your site.', 'optin-monster-api' ),
						'content'    => esc_html__( 'You\'re losing subscribers, leads and sales! Click on the button below to get started with OptinMonster.', 'optin-monster-api' ),
						'btns'       => array(
							'main' => array(
								'text' => esc_html__( 'Connect Your Site', 'optin-monster-api' ),
								'url'  => '?page=optin-monster-onboarding-wizard',
							),
						),
						'canDismiss' => false,
					),
				)
			);
		}

		return $notifications;
	}

	/**
	 * Get notification count.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_count() {
		$count = get_transient( 'om_notification_count' );
		if ( ! is_numeric( $count ) ) {
			$this->get();
			$count = get_transient( 'om_notification_count' );
		}
		$count = absint( $count );

		if ( ! $this->base->get_api_credentials() ) {
			$count++;
		}

		return $count;
	}

	/**
	 * Add a manual notification event.
	 *
	 * @since 2.0.0
	 *
	 * @param array $notification Notification data.
	 *
	 * @return bool Whether update occurred.
	 */
	public function add_event( $notification ) {
		$notification = self::sanitize_notification( (array) $notification );
		$update       = ! empty( $notification['update'] );

		// ID (string) is required!
		if ( empty( $notification['id'] ) && $update ) {
			return new WP_Error(
				'omapp_notification_event_error',
				esc_html__( 'Event notification update requires the "id" parameter', 'optin-monster-api' )
			);
		}

		if ( empty( $notification['id'] ) ) {
			$notification['id'] = uniqid( 'event-' );
		}

		$notification['id'] = (string) $notification['id'];

		// ID is required to be a string!
		if ( ctype_digit( $notification['id'] ) ) {
			return new WP_Error(
				'omapp_notification_event_error',
				esc_html__( 'Event notification requires an "id" parameter which is a unique string.', 'optin-monster-api' )
			);
		}

		$events    = (array) $this->get_option( 'events' );
		$dismissed = (array) $this->get_option( 'dismissed' );

		if ( $update ) {

			$index = array_search( $notification['id'], $dismissed, true );
			if ( false !== $index ) {
				unset( $dismissed[ $index ] );
			}

			unset( $notification['update'] );

		} else {

			// Already dismissed.
			if ( in_array( $notification['id'], $dismissed, true ) ) {
				return false;
			}

			foreach ( $events as $item ) {
				if ( $item['id'] === $notification['id'] ) {
					return false;
				}
			}
		}

		$notification = $this->verify_notification( $notification, $dismissed );

		if ( empty( $notification ) ) {
			return new WP_Error(
				'omapp_notification_event_error',
				esc_html__( 'Event notification verification failed.', 'optin-monster-api' )
			);
		}

		$notification = self::set_created_timestamp( $notification );

		$updated = false;
		if ( $update ) {
			foreach ( $events as $key => $item ) {
				if ( $item['id'] === $notification['id'] ) {
					$updated = true;
					foreach ( $notification as $name => $val ) {
						$events[ $key ][ $name ] = $val;
					}
					$events[ $key ]['updated'] = time();
				}
			}
		}

		if ( ! $updated ) {
			$events[] = $notification;
		}

		$this->handle_update(
			array(
				'events'    => $events,
				'dismissed' => $dismissed,
			)
		);

		return true;
	}

	/**
	 * Update notification data from feed.
	 *
	 * @param string $context The context for this update. Used by cron event.
	 *
	 * @since 2.0.0
	 */
	public function update( $context = 'default' ) {
		$feed = $this->fetch_feed();

		if ( 'wpcron' === $context ) {
			$this->schedule_next_update();
		}

		// If there was an error with the fetch, do not update the option.
		if ( is_wp_error( $feed ) ) {
			return;
		}

		foreach ( $feed as $key => $notification ) {
			$feed[ $key ] = self::set_created_timestamp( $notification );
		}

		delete_transient( 'om_notification_count' );

		$this->handle_update(
			array(
				'updated' => time(),
				'feed'    => $feed,
			)
		);
	}

	/**
	 * Dismiss notification(s).
	 *
	 * @since  2.0.0
	 *
	 * @param  array|string|int $ids Array of ids or single id.
	 *
	 * @return bool Whether dismiss update occurred.
	 */
	public function dismiss( $ids ) {

		// Check for access and required param.
		if ( ! $this->has_access() || empty( $ids ) ) {
			return false;
		}

		$ids    = self::sanitize_string( (array) $ids );
		$option = $this->get_option();

		foreach ( $ids as $id ) {
			if ( ! is_scalar( $id ) ) {
				continue;
			}

			$id   = (string) $id;
			$type = ctype_digit( $id ) ? 'feed' : 'events';

			$option['dismissed'][] = $id;

			// Remove notification.
			if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
				foreach ( $option[ $type ] as $key => $notification ) {
					if ( (string) $notification['id'] === $id ) {
						unset( $option[ $type ][ $key ] );
						break;
					}
				}
			}
		}

		$option['dismissed'] = array_unique( $option['dismissed'] );
		$option['dismissed'] = array_values( $option['dismissed'] );
		$option['dismissed'] = array_filter( $option['dismissed'] );

		$this->handle_update( $option );

		return true;
	}

	/**
	 * Sanitize notification data.
	 *
	 * @since  2.0.0
	 *
	 * @param  array|string|int $data The notification data.
	 *
	 * @return mixed The sanitized id(s).
	 */
	public static function sanitize_notification( array $data ) {
		foreach ( $data as $key => $value ) {
			$data[ $key ] = 'content' === $key
				? wp_kses_post( $value )
				: self::sanitize_string( $value );
		}

		return $data;
	}

	/**
	 * Sanitize string(s).
	 *
	 * @since  2.0.0
	 *
	 * @param  array|string|int $string The notification string(s).
	 *
	 * @return mixed The sanitized string(s).
	 */
	public static function sanitize_string( $string ) {
		if ( is_array( $string ) ) {
			return array_map( array( __CLASS__, __FUNCTION__ ), $string );
		}

		return sanitize_text_field( wp_unslash( $string ) );
	}

	/**
	 * Updates our notification option in the DB (disable option autoload).
	 *
	 * @since  2.0.0
	 *
	 * @param  array $option Option value.
	 *
	 * @return mixed          Result from update_option.
	 */
	protected function handle_update( $option ) {
		$required_keys = array(
			'updated',
			'feed',
			'events',
			'dismissed',
		);

		foreach ( $required_keys as $key ) {
			if ( ! isset( $option[ $key ] ) ) {
				$option[ $key ] = $this->get_option( $key );
			}
		}

		$result = update_option( self::OPTION_NAME, $option, false );

		if ( false !== $result ) {

			// Re-cache value.
			$this->get_option( '', false );
		}

		return $result;
	}

	/**
	 * Set the created timestamp.
	 *
	 * Will add it if it doesn't exist, and will convert to timestamp if applicable.
	 *
	 * @since 2.0.0
	 *
	 * @param array $notification Notification array.
	 */
	protected function set_created_timestamp( array $notification ) {
		// Set created timestamp if it's not already set.
		if ( empty( $notification['created'] ) ) {
			$notification['created'] = time();
		}

		// Convert to timestamp, if it's not already.
		if ( ! ctype_digit( (string) $notification['created'] ) ) {
			$notification['created'] = strtotime( $notification['created'] );
		}

		return $notification;
	}

	/**
	 * Checks if our notifications should be updated.
	 *
	 * @since 2.6.1
	 *
	 * @return bool Whether notifications should be updated.
	 */
	public function should_update() {
		$updated = $this->get_option( 'updated' );

		return empty( $updated ) || time() > ( $updated + ( 12 * HOUR_IN_SECONDS ) );
	}

	/**
	 * Register and enqueue admin specific JS.
	 *
	 * @since 2.1.1
	 */
	public function scripts() {
		$handle = $this->base->plugin_slug . '-global';
		wp_enqueue_script(
			$handle,
			$this->base->url . 'assets/dist/js/global.min.js',
			array( 'jquery' ),
			$this->base->asset_version(),
			true
		);

		OMAPI_Utils::add_inline_script(
			$handle,
			'OMAPI_Global',
			array(
				'url'                => esc_url_raw( rest_url( 'omapp/v1/notifications' ) ),
				'nonce'              => wp_create_nonce( 'wp_rest' ),
				'fetchNotifications' => $this->should_update(),
			)
		);
	}
}
