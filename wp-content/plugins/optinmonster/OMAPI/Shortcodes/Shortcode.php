<?php
/**
 * Shortcode class.
 *
 * @since 2.6.9
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode class.
 *
 * @since 2.6.9
 */
class OMAPI_Shortcodes_Shortcode {

	/**
	 * Holds the base class object.
	 *
	 * @since 2.6.9
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Shorcode attributes.
	 *
	 * @since 2.6.9
	 *
	 * @var array
	 */
	public $atts = array();

	/**
	 * The shorcode campaign identifier, slug (or ID for legacy/back-compat).
	 *
	 * @since 2.6.9
	 *
	 * @var string|int
	 */
	public $identifier = '';

	/**
	 * The global post object.
	 *
	 * @since 2.6.9
	 *
	 * @var WP_Post
	 */
	public $post = null;

	/**
	 * The OM campaign post object.
	 *
	 * @since 2.6.9
	 *
	 * @var WP_Post
	 */
	public $campaign = null;

	/**
	 * Class constructor.
	 *
	 * @param array  $atts Array of shortcode attributes.
	 * @param object $post The post object to check against.
	 *
	 * @since 2.6.9
	 */
	public function __construct( $atts, $post ) {
		$this->atts = $atts;
		$this->post = $post;
		$this->base = OMAPI::get_instance();
	}

	/**
	 * Sends the shortcode html.
	 *
	 * @uses wp_validate_boolean
	 *
	 * @since 2.6.9
	 *
	 * @return string The shortcode HTML output.
	 * @throws OMAPI_Shortcodes_Exception
	 */
	public function handle() {
		return $this
			->check_amp()
			->set_atts(
				array(
					'slug'        => '',
					'followrules' => 'false',
					// id attribute is deprecated.
					'id'          => '',
				),
				'optin-monster'
			)
			->set_identifier()
			->set_campaign_object()
			->validate_rules()
			->get_campaign_html();
	}

	/**
	 * Sends the inline-campaign shortcode html.
	 *
	 * @since 2.6.9
	 *
	 * @return string The shortcode HTML output.
	 * @throws OMAPI_Shortcodes_Exception
	 */
	public function handle_inline() {
		return $this
			->check_amp()
			->set_atts(
				array(
					'slug' => '',
				),
				'optin-monster-inline'
			)
			->set_identifier()
			->set_campaign_object()
			->validate_rules( true )
			->get_campaign_html();
	}

	/**
	 * Checking if AMP is enabled.
	 *
	 * @since 2.6.9
	 *
	 * @return OMAPI_Shortcodes_Shortcode
	 * @throws OMAPI_Shortcodes_Exception
	 */
	public function check_amp() {
		if ( OMAPI_Utils::is_amp_enabled() ) {
			throw new OMAPI_Shortcodes_Exception( 'Amp enabled' );
		}

		return $this;
	}

	/**
	 * Set the attributes array using shortcode_atts function.
	 *
	 * @since 2.6.9
	 *
	 * @uses shortcode_atts
	 *
	 * @param array  $defaults       Array of default attributes.
	 * @param string $shortcode_name The shortcode name.
	 *
	 * @return OMAPI_Shortcodes_Shortcode
	 */
	public function set_atts( $defaults, $shortcode_name ) {

		// Merge default attributes with passed attributes.
		$this->atts = shortcode_atts( $defaults, $this->atts, $shortcode_name );

		return $this;
	}

	/**
	 * Set the campaign identifier from the given attributes, either ID or slug.
	 *
	 * @since 2.6.9
	 *
	 * @return OMAPI_Shortcodes_Shortcode
	 * @throws OMAPI_Shortcodes_Exception
	 */
	public function set_identifier() {
		$identifier = false;

		if ( ! empty( $this->atts['slug'] ) ) {
			$identifier = $this->atts['slug'];
		}

		if ( ! empty( $this->atts['id'] ) ) {
			$identifier = $this->atts['id'];
		}

		if ( empty( $identifier ) ) {
			// A custom attribute must have been passed. Allow it to be filtered to grab the campaign ID from a custom source.
			$identifier = apply_filters( 'optin_monster_api_custom_optin_id', false, $this->atts, $this->post );
		}

		// Allow the campaign ID to be filtered before it is stored and used to create the campaign output.
		$identifier = apply_filters( 'optin_monster_api_pre_optin_id', $identifier, $this->atts, $this->post );

		// If there is no identifier, do nothing.
		if ( empty( $identifier ) ) {
			throw new OMAPI_Shortcodes_Exception( 'Missing identifier in attributes' );
		}

		$this->identifier = $identifier;

		return $this;
	}

	/**
	 * Set the campaign object, from the ID/slug.
	 *
	 * @since 2.6.9
	 *
	 * @return OMAPI_Shortcodes_Shortcode
	 * @throws OMAPI_Shortcodes_Exception
	 */
	public function set_campaign_object() {
		$campaign = ctype_digit( (string) $this->identifier )
			? $this->base->get_optin( absint( $this->identifier ) )
			: $this->base->get_optin_by_slug( sanitize_text_field( $this->identifier ) );

		// If no campaign found, do nothing.
		if ( empty( $campaign ) ) {
			throw new OMAPI_Shortcodes_Exception( 'Could not find campaign object for identifier' );
		}

		$this->campaign = $campaign;

		return $this;
	}

	/**
	 * Checks the given campaign against the output settings rules.
	 *
	 * @since 2.6.9
	 *
	 * @return OMAPI_Shortcodes_Shortcode
	 * @throws OMAPI_Shortcodes_Exception
	 */
	public function validate_rules( $force = false ) {
		$should_check = $force || wp_validate_boolean( $this->atts['followrules'] );

		if (
			$should_check
			// Do OMAPI Output rules check.
			&& ! OMAPI_Rules::check_shortcode( $this->campaign, $this->post->ID )
		) {
			throw new OMAPI_Shortcodes_Exception( 'Failed the WordPress rules' );
		}

		return $this;
	}

	/**
	 * Sends the campaign html, passed through optin_monster_shortcode_output filter.
	 *
	 * @since 2.6.9
	 *
	 * @return string Campaign html.
	 * @throws OMAPI_Shortcodes_Exception
	 */
	public function get_campaign_html() {
		// Try to grab the stored HTML.
		$html = $this->base->output->prepare_campaign( $this->campaign );
		if ( ! $html ) {
			throw new OMAPI_Shortcodes_Exception( 'Optin object missing campaign html in post_content' );
		}

		// Make sure to apply shortcode filtering.
		$this->base->output->set_slug( $this->campaign );

		// Return the HTML.
		return apply_filters( 'optin_monster_shortcode_output', $html, $this->campaign, $this->atts );
	}

}
