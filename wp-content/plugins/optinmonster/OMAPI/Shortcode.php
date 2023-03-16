<?php
/**
 * Shortcode class.
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
 * Shortcode class.
 *
 * @since 1.0.0
 */
class OMAPI_Shortcode {

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
	 * Holds the OMAPI_Shortcodes_Shortcode object.
	 *
	 * @since 2.6.9
	 *
	 * @var object
	 */
	public $shortcode;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Load actions and filters.
		add_shortcode( 'optin-monster', array( $this, 'shortcode' ) );
		add_shortcode( 'optin-monster-shortcode', array( $this, 'shortcode_v1' ) );
		add_shortcode( 'optin-monster-inline', array( $this, 'inline_campaign_shortcode_with_rules' ) );
		add_filter( 'widget_text', 'shortcode_unautop' );
		add_filter( 'widget_text', 'do_shortcode' );
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
	 * Creates the shortcode for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @global object $post The current post object.
	 *
	 * @param array $atts Array of shortcode attributes.
	 *
	 * @return string The shortcode HTML output.
	 */
	public function shortcode( $atts ) {
		global $post;
		$this->shortcode = new OMAPI_Shortcodes_Shortcode( $atts, $post );

		try {
			return $this->shortcode->handle();
		} catch ( Exception $e ) {
		}

		return '';
	}

	/**
	 * Backwards compat shortcode for v1.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Array of shortcode attributes.
	 *
	 * @return string The shortcode HTML output.
	 */
	public function shortcode_v1( $atts ) {
		// Run the v2 implementation.
		if ( ! empty( $atts['id'] ) ) {
			$atts['slug'] = $atts['id'];
			unset( $atts['id'] );
		}

		return $this->shortcode( $atts );
	}

	/**
	 * Creates the inline campaign shortcode, with followrules defaulted to true.
	 *
	 * @since 2.6.8
	 *
	 * @param array $atts Array of shortcode attributes.
	 *
	 * @return string The shortcode HTML output.
	 */
	public function inline_campaign_shortcode_with_rules( $atts = array() ) {
		global $post;

		$html            = '';
		$this->shortcode = new OMAPI_Shortcodes_Shortcode( $atts, $post );

		try {
			add_filter( 'optinmonster_check_should_output', array( $this, 'reject_non_inline_campaigns' ), 10, 2 );
			$html = $this->shortcode->handle_inline();
		} catch ( Exception $e ) {
		}

		remove_filter( 'optinmonster_check_should_output', array( $this, 'reject_non_inline_campaigns' ), 10, 2 );

		return $html;
	}

	/**
	 * Checks if optin type is inline, and rejects (returns html comment) if not.
	 *
	 * @since 2.6.8
	 *
	 * @param  OMAPI_Rules_Exception $e A rules exception object.
	 * @param  OMAPI_Rules           $rules The rules object.
	 *
	 * @return OMAPI_Rules_Exception A rules exception object.
	 */
	public function reject_non_inline_campaigns( $e, $rules ) {
		if (
			! empty( $rules->optin->campaign_type )
			&& ! empty( $rules->optin->ID )
			&& ! empty( $this->shortcode->optin->ID )
			&& (int) $this->shortcode->optin->ID === (int) $rules->optin->ID
			&& 'inline' !== $rules->optin->campaign_type
		) {
			$e = new OMAPI_Rules_False( 'campaign not inline for optin-monster-inline shortcode' );
		}

		return $e;
	}

}
