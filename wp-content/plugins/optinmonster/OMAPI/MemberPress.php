<?php
/**
 * MemberPress class.
 *
 * @since 2.13.0
 *
 * @package OMAPI
 * @author  Eduardo Nakatsuka
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The MemberPress class.
 *
 * @since 2.13.0
 */
class OMAPI_MemberPress extends OMAPI_Integrations_Base {

	/**
	 * Holds the OMAPI_MemberPress_Courses class instance.
	 *
	 * @since 2.13.0
	 *
	 * @var OMAPI_MemberPress_Courses
	 */
	public $courses;

	/**
	 * The minimum MemberPress version required.
	 *
	 * @since 2.13.0
	 *
	 * @var string
	 */
	const MINIMUM_VERSION = '1.9.39';

	/**
	 * Primary class constructor.
	 *
	 * @since 2.13.0
	 */
	public function __construct() {
		parent::__construct();

		// Set our object.
		$this->courses = new OMAPI_MemberPress_Courses( $this );

		if ( self::is_active() && self::is_minimum_version() ) {
			add_filter( 'optin_monster_campaigns_js_api_args', array( $this, 'add_args' ) );
			add_filter( 'optin_monster_api_setting_ui_data', array( $this, 'add_args' ) );
		}
	}

	/**
	 * Check if the MemberPress plugin is active.
	 *
	 * @since 2.13.0
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return defined( 'MEPR_PLUGIN_SLUG' ) && class_exists( 'MeprCptModel', true );
	}

	/**
	 * Return the MemberPress Plugin version string.
	 *
	 * @since 2.13.0
	 *
	 * @return string
	 */
	public static function version() {
		return defined( 'MEPR_VERSION' ) ? MEPR_VERSION : '0.0.0';
	}

	/**
	 * Adds the `memberpress` object to payload, which is passed to the JS frontend.
	 *
	 * @since 2.13.0
	 *
	 * @param  array $args This is the array of parameters that will be passed to the JS file.
	 * @return array $args The array with the `memberpress` payload.
	 */
	public function add_args( $args ) {
		$args['memberpress'] = array(
			'groups'                  => self::format_data( $this->retrieve_mp_data( 'MeprGroup' ) ),
			'memberships'             => self::format_data( $this->retrieve_mp_data( 'MeprProduct' ) ),
			'isActive'                => self::is_active(),
			'isCoursesActive'         => OMAPI_MemberPress_Courses::is_active(),
			'checkoutTemplateEnabled' => self::isProTemplateEnabled( 'checkout' ),
		);

		$args['memberpress'] = array_merge( $args['memberpress'], $this->courses->get_args() );

		return $args;
	}

	/**
	 * Format data to be consumed by the front-end admin output settings.
	 *
	 * @since 2.13.0
	 *
	 * @param  array $payload The data to be formatted
	 * @return array          The formatted data
	 */
	static function format_data( $payload ) {
		$data = array();

		if ( empty( $payload ) || ! is_array( $payload ) ) {
			return $data;
		}

		foreach ( $payload as $entity ) {
			$data[] = array(
				'value' => $entity->ID,
				'label' => $entity->post_title,
				'name'  => $entity->post_title,
			);
		}

		return $data;
	}

	/**
	 * Retrieve MemberPress model data.
	 *
	 * @since 2.13.0
	 *
	 * @param  string $model The entity model name
	 * @return array         The array model data
	 */
	private function retrieve_mp_data( $model ) {
		// Bail if MemberPress isn't currently active.
		if ( ! self::is_active() || ! self::is_minimum_version() ) {
			return array();
		}

		$data = MeprCptModel::all( $model );

		if ( empty( $data ) ) {
			return array();
		}

		return $data;
	}

	/**
	 * Determine if a "pro" template is enabled.
	 *
	 * @param string $name The template name
	 * @return boolean     True if enabled
	 */
	static function isProTemplateEnabled( $name ) {
		if( ! class_exists( 'MeprOptions', true ) ) {
			return false;
		}

		$options   = MeprOptions::fetch();
		$attribute = 'design_enable_' . $name . '_template';

		return ! empty( $options->$attribute ) && filter_var( $options->$attribute, FILTER_VALIDATE_BOOLEAN );	
	}
}
