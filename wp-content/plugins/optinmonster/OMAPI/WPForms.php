<?php
/**
 * WPForms class.
 *
 * @since 2.9.0
 *
 * @package OMAPI
 * @author  Eduardo Nakatsuka
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The WPForms class.
 *
 * @since 2.9.0
 */
class OMAPI_WPForms extends OMAPI_Integrations_Base {

	/**
	 * The OMAPI_WPForms_RestApi instance.
	 *
	 * @since 2.13.0
	 *
	 * @var null|OMAPI_WPForms_RestApi
	 */
	public $rest = null;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.9.0
	 */
	public function __construct() {
		parent::__construct();
		$this->save = new OMAPI_WPForms_Save();

		add_action( 'optin_monster_api_rest_register_routes', array( $this, 'maybe_init_rest_routes' ) );

		// When WPForms is activated, connect it.
		add_action( 'activate_wpforms-lite/wpforms.php', array( $this->save, 'connect' ) );
		add_action( 'activate_wpforms/wpforms.php', array( $this->save, 'connect' ) );

		// When WPForms is deactivated, disconnect.
		add_action( 'deactivate_wpforms-lite/wpforms.php', array( $this->save, 'disconnect' ) );
		add_action( 'deactivate_wpforms/wpforms.php', array( $this->save, 'disconnect' ) );
	}

	/**
	 * Check if the WPForms plugin is active.
	 *
	 * @since 2.9.0
	 *
	 * @return bool
	 */
	public static function is_active() {
		return class_exists( 'WPForms', true );
	}

	/**
	 * Get WPForms forms array containing label and value.
	 *
	 * @since 2.9.0
	 *
	 * @return array
	 */
	public function get_forms_array() {
		$forms  = $this->get_forms();
		$result = array();

		if ( empty( $forms ) || ! is_array( $forms ) ) {
			return $result;
		}

		foreach ( $forms as $form ) {
			$result[] = array(
				'value' => $form->ID,
				'label' => $form->post_title,
			);
		}

		return $result;
	}

	/**
	 * Get forms from WPForms plugin.
	 *
	 * @since 2.9.0
	 *
	 * @return array All the forms in WPForms plugin.
	 */
	public function get_forms() {
		if ( ! function_exists( 'wpforms' ) ) {
			return array();
		}

		return wpforms()->form->get( '', array( 'order' => 'DESC' ) );
	}

	/**
	 * Get the currently installed WPForms version.
	 *
	 * @since 2.9.0
	 *
	 * @return string The WPForms version.
	 */
	public static function version() {
		if ( ! function_exists( 'wpforms' ) ) {
			return '0.0.0';
		}

		$version = wpforms()->version;

		return $version ?: '0.0.0';
	}

	/**
	 * Initiate our REST routes for EDD if EDD active.
	 *
	 * @since 2.13.0
	 *
	 * @return void
	 */
	public function maybe_init_rest_routes() {
		if ( self::is_active() ) {
			$this->rest = new OMAPI_WPForms_RestApi( $this );
		}
	}

}
