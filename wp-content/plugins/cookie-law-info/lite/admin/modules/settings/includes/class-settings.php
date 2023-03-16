<?php
/**
 * Class Banner file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Settings\Includes;

use CookieYes\Lite\Includes\Store;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Settings
 * @version     3.0.0
 * @package     CookieYes
 */
class Settings extends Store {
	/**
	 * Data array, with defaults.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Instance of the current class
	 *
	 * @var object
	 */
	private static $instance;

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
	 * Constructor
	 */
	public function __construct() {
		$this->data = $this->get_defaults();
	}

	/**
	 * Get default plugin settings
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'site'         => array(
				'url'       => get_site_url(),
				'installed' => time(),
			),
			'api'          => array(
				'token' => '',
			),
			'account'      => array(
				'email'       => '',
				'domain'      => '',
				'connected'   => false,
				'plan'        => 'free',
				'website_id'  => '',
				'website_key' => '',
			),
			'consent_logs' => array(
				'status' => true,
			),
			'languages'    => array(
				'selected' => array( 'en' ),
				'default'  => 'en',
			),
			'onboarding'   => array(
				'step' => 2,
			),
		);

	}
	/**
	 * Get settings
	 *
	 * @param string $group Name of the group.
	 * @param string $key Name of the key.
	 * @return array
	 */
	public function get( $group = '', $key = '' ) {
		$settings = get_option( 'cky_settings', $this->data );
		$settings = self::sanitize( $settings, $this->data );
		if ( empty( $key ) && empty( $group ) ) {
			return $settings;
		} elseif ( ! empty( $key ) && ! empty( $group ) ) {
			$settings = isset( $settings[ $group ] ) ? $settings[ $group ] : array();
			return isset( $settings[ $key ] ) ? $settings[ $key ] : array();
		} else {
			return isset( $settings[ $group ] ) ? $settings[ $group ] : array();
		}
	}

	/**
	 * Excludes a key from sanitizing multiple times.
	 *
	 * @return array
	 */
	public static function get_excludes() {
		return array(
			'selected',
		);
	}
	/**
	 * Update settings to database.
	 *
	 * @param array $data Array of settings data.
	 * @return void
	 */
	public function update( $data ) {
		$settings = get_option( 'cky_settings', $this->data );
		$settings = self::sanitize( $data, $settings );
		update_option( 'cky_settings', $settings );
		do_action( 'cky_after_update_settings', $settings );
	}

	/**
	 * Sanitize options
	 *
	 * @param array $settings Input settings array.
	 * @param array $defaults Default settings array.
	 * @return array
	 */
	public static function sanitize( $settings, $defaults ) {
		$result  = array();
		$exludes = self::get_excludes();
		foreach ( $defaults as $key => $data ) {
			$value = isset( $settings[ $key ] ) ? $settings[ $key ] : $data;
			if ( in_array( $key, $exludes, true ) ) {
				$result[ $key ] = self::sanitize_option( $key, $value );
				continue;
			}
			if ( is_array( $value ) ) {
				$result[ $key ] = self::sanitize( $value, $data );
			} else {
				if ( is_string( $key ) ) {
					$result[ $key ] = self::sanitize_option( $key, $value );
				}
			}
		}
		return $result;
	}

	/**
	 * Sanitize the option values
	 *
	 * @param string $option The name of the option.
	 * @param string $value  The unsanitised value.
	 * @return string Sanitized value.
	 */
	public static function sanitize_option( $option, $value ) {
		switch ( $option ) {
			case 'connected':
			case 'status':
			case 'connected':
				$value = cky_sanitize_bool( $value );
				break;
			case 'installed':
			case 'step':
				$value = absint( $value );
				break;
			default:
				$value = cky_sanitize_text( $value );
				break;
		}
		return $value;
	}

	// Getter Functions.

	/**
	 * Get account token for authentication.
	 *
	 * @return string
	 */
	public function get_token() {
		return $this->get( 'api', 'token' );
	}

	/**
	 * Check whether the site is connected to CookieYes Webapp.
	 *
	 * @return boolean
	 */
	public function is_connected() {
		return $this->get( 'account', 'connected' );
	}

	/**
	 * Get website ID
	 *
	 * @return string
	 */
	public function get_website_id() {
		return $this->get( 'account', 'website_id' );
	}
	/**
	 * Get website ID
	 *
	 * @return string
	 */
	public function get_plan() {
		return $this->get( 'account', 'plan' );
	}
	/**
	 * Get the website key
	 *
	 * @return string
	 */
	public function get_website_key() {
		return $this->get( 'account', 'website_key' );
	}
	/**
	 * Get current site URL.
	 *
	 * @return string
	 */
	public function get_url() {
		return $this->get( 'site', 'url' );
	}

	/**
	 * Get the script URL
	 *
	 * @return string
	 */
	public function get_script_url() {
		return CKY_APP_CDN_URL . '/client_data/' . $this->get_website_key() . '/script.js';
	}

	/**
	 * Get consent log status
	 *
	 * @return boolean
	 */
	public function get_consent_log_status() {
		return (bool) $this->get( 'consent_logs', 'status' );

	}

	/**
	 * Returns the default language code
	 *
	 * @return string
	 */
	public function get_default_language() {
		return sanitize_text_field( $this->get( 'languages', 'default' ) );
	}

	/**
	 * Returns the selected languages.
	 *
	 * @return array
	 */
	public function get_selected_languages() {
		return cky_sanitize_text( $this->get( 'languages', 'selected' ) );
	}

	/**
	 * First installed date of the plugin.
	 *
	 * @return string
	 */
	public function get_installed_date() {
		return $this->get( 'site', 'installed' );
	}
}
