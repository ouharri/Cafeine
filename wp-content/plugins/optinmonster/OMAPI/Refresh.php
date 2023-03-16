<?php
/**
 * Refresh class.
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
 * Refresh class.
 *
 * @since 1.0.0
 */
class OMAPI_Refresh {

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
	 * Arguments for the API requests.
	 *
	 * @since 1.6.5
	 *
	 * @var array
	 */
	protected $api_args = array(
		'limit'  => 100,
		'status' => 'all',
	);

	/**
	 * OMAPI_Api object
	 *
	 * @var null|OMAPI_Api
	 */
	public $api = null;

	/**
	 * WP_Error object if refresh fails.
	 *
	 * @var null|WP_Error
	 */
	public $error = null;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();
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
	 * Refresh the optins.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $api_key API key.
	 *
	 * @return WP_Error|bool True if successful.
	 */
	public function refresh( $api_key = null ) {
		$this->api = OMAPI_Api::build( 'v1', 'optins', 'GET', $api_key ? array( 'apikey' => $api_key ) : $api_key );
		$args      = $this->setup_api( $api_key, $this->api_args );

		$results = array();
		$body    = $this->api->request( $args );

		// Loop through paginated requests until we have fetched all the campaigns.
		while ( ! is_wp_error( $body ) || empty( $body ) ) {
			$limit       = absint( wp_remote_retrieve_header( $this->api->response, 'limit' ) );
			$page        = absint( wp_remote_retrieve_header( $this->api->response, 'page' ) );
			$total       = absint( wp_remote_retrieve_header( $this->api->response, 'total' ) );
			$total_pages = ceil( $total / $limit );
			$results     = array_merge( $results, (array) $body );

			// If we've reached the end, prevent any further requests.
			if ( $page >= $total_pages || $limit === 0 ) {
				break;
			}

			$args['page'] = $page + 1;

			// Request the next page.
			$body = $this->api->request( $args );
		}

		if ( is_wp_error( $body ) ) {
			$this->handle_error( $body );
		} else {
			// Store the optin data.
			$this->base->save->store_optins( $results );

			// Update our sites as well
			$result = $this->base->sites->fetch( $api_key );

			// Update the option to remove stale error messages.
			$option                = $this->base->get_option();
			$option['is_invalid']  = false;
			$option['is_expired']  = false;
			$option['is_disabled'] = false;
			$option['connected']   = time();
			if ( is_wp_error( $result ) ) {
				$this->error = $result;
			} else {
				$option = array_merge( $option, $result );
			}

			$this->base->save->update_option( $option );
		}

		return $this->error ? $this->error : true;
	}

	/**
	 * Trigger a refresh for given campaign.
	 *
	 * @since  1.9.10
	 *
	 * @param  string $campaign_id The campaign id (slug).
	 * @param  mixed  $is_legacy   Whether campaign is legacy.
	 *
	 * @return WP_Error|bool True if successful.
	 */
	public function sync( $campaign_id, $is_legacy = false ) {
		$time = time();
		$path = "for-wp/{$campaign_id}?t={$time}";
		if ( $is_legacy ) {
			$path .= '&legacy=true';
		}

		$this->api = OMAPI_Api::build( 'v1', $path, 'GET' );

		$body = $this->api->request( $this->setup_api() );

		if ( is_wp_error( $body ) ) {

			// If campaign is gone, delete the optin.
			if (
				'campaign-error' === $body->get_error_code()
				&& (string) '404' === (string) $body->get_error_data()
			) {
				$result = $this->base->save->delete_optin( $campaign_id, true );
			}

			$this->handle_error( $body );
		} else {

			// Store the optin data.
			$this->base->save->add_optins( (array) $body, false );
		}

		return $this->error ? $this->error : true;
	}

	/**
	 * Gets contextual info for API requests.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $args Array of args.
	 *
	 * @return arry        Modified array of args.
	 */
	public function get_info_args( $args = array() ) {

		// Set additional flags.
		$args['wp'] = $GLOBALS['wp_version'];
		$args['av'] = $this->base->asset_version();
		$args['v']  = $this->base->version;

		if ( OMAPI_WooCommerce::is_active() ) {
			$args['wc'] = OMAPI_WooCommerce::version();
		}

		$args = array_merge( $args, OMAPI_Api::getUrlArgs() );

		return $args;
	}

	/**
	 * Handles setting up the API request.
	 *
	 * @since  1.9.10
	 *
	 * @param  string $api_key API key.
	 * @param  array  $args    Array of request args.
	 *
	 * @return arry             Modified array of request args.
	 */
	protected function setup_api( $api_key = null, $args = array() ) {
		if ( $api_key ) {
			$this->api->set( 'apikey', $api_key );
		}

		$this->api->clear_additional_data();

		// Set additional flags.
		return $this->get_info_args( $args );
	}

	/**
	 * Handles errors occurring during refresh.
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_Error $error WP_Error object.
	 *
	 * @return OMAPI_Refresh
	 */
	protected function handle_error( $error ) {
		switch ( $error->get_error_code() ) {
			// If no optins available, make sure they get deleted.
			case 'optins':
			case 'no-campaigns-error':
				$this->base->save->store_optins( array() );
				break;

			case 'referrer-error':
				$api    = OMAPI_Api::instance();
				$result = $this->base->sites->check_existing_site( $api->get_creds() );
				if ( is_wp_error( $result ) ) {
					$error = $result;
				}
				break;
		}

		// Set an error message.
		$this->error = $error;

		return $this;
	}

}
