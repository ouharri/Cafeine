<?php
/**
 * Revenue attribution class.
 *
 * @since 2.6.13
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Revenue Attribution class.
 *
 * @since 2.6.13
 */
class OMAPI_RevenueAttribution {
	/**
	 * Holds the class object.
	 *
	 * @since 2.6.13
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.6.13
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.6.13
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.6.13
	 */
	public function __construct() {
		// Set our object.
		$this->set();
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 2.6.13
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Maybe stores revenue attribution data when a purchase is successful.
	 *
	 * @since 2.6.13
	 *
	 * @param array $data  An array of revenue attribution data to store.
	 *
	 * @return bool|WP_Error         True if successful, WP_Error or false otherwise.
	 */
	public function store( $data = array() ) {
		// If revenue attribution is not turned on, return early.
		$ra = $this->base->get_revenue_attribution();
		if ( empty( $ra['enabled'] ) || empty( $ra['currency'] ) ) {
			return false;
		}

		// If we can't find the account ID, return early.
		$accountId = $this->base->get_option( 'accountId' );
		if ( empty( $accountId ) ) {
			return false;
		}

		// Build and send the request.
		$api = OMAPI_Api::build( 'v2', 'revenue/' . $accountId, 'POST' );

		return $api->request( $data );
	}

	/**
	 * Returns revenue attribution data.
	 *
	 * @since 2.6.13
	 *
	 * @return array  An array of revenue attribution data.
	 */
	public function get_revenue_data() {
		// If we don't have any cookies set for OM campaigns, return early.
		if ( empty( $_COOKIE['_omra'] ) ) {
			return array();
		}

		// If revenue attribution is not turned on, return early.
		$ra = $this->base->get_revenue_attribution();
		if ( empty( $ra['enabled'] ) || empty( $ra['currency'] ) ) {
			return array();
		}

		// Loop through and prepare the campaign data. If it is empty, return early.
		$campaign_data = json_decode( stripslashes( rawurldecode( $_COOKIE['_omra'] ) ), true );
		if ( empty( $campaign_data ) ) {
			return array();
		}

		// Sanitize the campaign data before sending it back.
		$sanitized_campaigns = array();
		foreach ( $campaign_data as $campaign_id => $action ) {
			$sanitized_campaigns[ esc_html( $campaign_id ) ] = esc_html( $action );
		}

		// Return the default revenue attribution data. Additional revenue
		// data should be returned from the integration itself (such as
		// the total, transaction ID, etc.).
		return array(
			'campaigns' => $sanitized_campaigns,
			'currency'  => esc_html( $ra['currency'] ),
			'device'    => wp_is_mobile() ? 'mobile' : 'desktop',
			'type'      => 'sale',
		);
	}
}
