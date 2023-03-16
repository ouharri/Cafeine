<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.webtoffee.com/
 * @since      3.0.0
 *
 * @package    CookieYes
 * @subpackage CookieYes/includes
 */

namespace CookieYes\Lite\Includes;

use CookieYes\Lite\Admin\Modules\Banners\Includes\Banner;
use CookieYes\Lite\Admin\Modules\Banners\Includes\Controller;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      3.0.0
 * @package    CookieYes
 * @subpackage CookieYes/includes
 * @author     WebToffee <info@webtoffee.com>
 */
class Activator {

	/**
	 * Instance of the current class
	 *
	 * @var object
	 */
	private static $instance;
	/**
	 * Update DB callbacks.
	 *
	 * @var array
	 */
	private static $db_updates = array(
		'3.0.7' => array(
			'update_db_307',
		),
	);
	/**
	 * Return the current instance of the class
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Activate the plugin
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
	}
	/**
	 * Check the plugin version and run the updater is required.
	 *
	 * This check is done on all requests and runs if the versions do not match.
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( 'wt_cli_version', '2.1.3' ), CLI_VERSION, '<' ) ) {
			self::install();
		}
	}
	/**
	 * Install all the plugin
	 *
	 * @return void
	 */
	public static function install() {
		self::check_for_upgrade();
		if ( true === cky_first_time_install() ) {
			add_option( 'cky_first_time_activated_plugin', 'true' );
		}
		self::maybe_update_db();
		update_option( 'wt_cli_version', CLI_VERSION );
		do_action( 'cky_after_activate', CLI_VERSION );
		self::update_db_version();
	}

	/**
	 * Set a temporary flag during the first time installation.
	 *
	 * @return void
	 */
	public static function check_for_upgrade() {
		if ( false === get_option( 'cky_settings', false ) ) {
			if ( false === get_site_transient( '_cky_first_time_install' ) ) {
				set_site_transient( '_cky_first_time_install', true, 30 );
			}
		}
	}

	/**
	 * Update DB version to track changes to data structure.
	 *
	 * @param string $version Current version.
	 * @return void
	 */
	public static function update_db_version( $version = null ) {
		update_option( 'cky_cookie_consent_lite_db_version', is_null( $version ) ? CLI_VERSION : $version );
	}

	/**
	 * Check if any database changes is required on the latest release
	 *
	 * @return boolean
	 */
	private static function needs_db_update() {
		$current_version = get_option( 'cky_cookie_consent_lite_db_version', '3.0.7' ); // @since 3.0.7 introduced DB migrations
		$updates         = self::$db_updates;
		$update_versions = array_keys( $updates );
		usort( $update_versions, 'version_compare' );
		return ! is_null( $current_version ) && version_compare( $current_version, end( $update_versions ), '<' );
	}

	/**
	 * Update DB if required
	 *
	 * @return void
	 */
	public static function maybe_update_db() {
		if ( self::needs_db_update() ) {
			self::update();
		}
	}

	/**
	 * Run a update check during each release update.
	 *
	 * @return void
	 */
	private static function update() {
		$current_version = get_option( 'cky_cookie_consent_lite_db_version', '3.0.7' );
		foreach ( self::$db_updates as $version => $callbacks ) {
			if ( version_compare( $current_version, $version, '<' ) ) {
				foreach ( $callbacks as $callback ) {
					self::$callback();
				}
			}
		}
	}

	/**
	 * Migrate existing banner contents to support new CCPA/GPC changes
	 *
	 * @return void
	 */
	public static function update_db_307() {
		$items = Controller::get_instance()->get_items();
		foreach ( $items as $item ) {
			$banner   = new Banner( $item->banner_id );
			$contents = $banner->get_contents();
			foreach ( $contents as $language => $content ) {
				$translation = $banner->get_translations( $language );
				$text        = isset( $translation['optoutPopup']['elements']['buttons']['elements']['confirm'] ) ? $translation['optoutPopup']['elements']['buttons']['elements']['confirm'] : 'Save My Preferences';
				$content['optoutPopup']['elements']['buttons']['elements']['confirm'] = $text;
				$contents[ $language ] = $content;
			}
			$banner->set_contents( $contents );
			$banner->save();
		}
	}
}
