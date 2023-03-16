<?php
/**
 * Base Plugin Integration Class, extend this if implementing a plugin integration class.
 *
 * @since 2.13.0
 *
 * @package OMAPI
 * @author  Gabriel Oliveira and Eduardo Nakatsuka
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base Plugin Integration class.
 *
 * @since 2.13.0
 */
abstract class OMAPI_Integrations_Base {

	/**
	 * Holds the class object.
	 *
	 * @since 2.13.0
	 *
	 * @var static
	 */
	public static $instance;

	/**
	 * The Base OMAPI Object
	 *
	 *  @since 2.13.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	/**
	 * The minimum Plugin version required.
	 *
	 * @since 2.13.0
	 *
	 * @var string
	 */
	const MINIMUM_VERSION = '0.0.0';

	/**
	 * Build our object.
	 *
	 * @since 2.13.0
	 */
	public function __construct() {
		$this->base       = OMAPI::get_instance();
		static::$instance = $this;
	}

	/**
	 * Return the plugin version string.
	 *
	 * @since 2.13.0
	 *
	 * @return string
	 */
	abstract public static function version();

	/**
	 * Determines if the passed version string passes the operator compare
	 * against the currently installed version of plugin.
	 *
	 * Defaults to checking if the current plugin version is greater than
	 * the passed version.
	 *
	 * @since 2.13.0
	 *
	 * @param string $version  The version to check.
	 * @param string $operator The operator to use for comparison.
	 *
	 * @return string
	 */
	public static function version_compare( $version = '', $operator = '>=' ) {
		return version_compare( static::version(), $version, $operator );
	}

	/**
	 * Determines if the current WooCommerce version meets the minimum version
	 * requirement.
	 *
	 * @since 2.13.0
	 *
	 * @return boolean
	 */
	public static function is_minimum_version() {
		return static::version_compare( static::MINIMUM_VERSION );
	}

}
