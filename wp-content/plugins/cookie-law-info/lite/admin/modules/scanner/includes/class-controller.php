<?php
/**
 * Class Controller file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Scanner\Includes;

use CookieYes\Lite\Integrations\Cookieyes\Cookieyes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Controller
 * @version     3.0.0
 * @package     CookieYes
 */
class Controller extends Cookieyes {

	/**
	 * Instance of the current class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Base path for cookie scanner API
	 *
	 * @var string
	 */
	private $rest_path;

	/**
	 * Default scan data
	 *
	 * @var array
	 */
	private static $default = array(
		'id'     => 0,
		'status' => '',
		'type'   => '',
		'date'   => '',
	);

	/**
	 * Last scan info.
	 *
	 * @var array
	 */
	protected $last_scan_info;
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->make_auth_request();
		parent::__construct();
		$this->rest_path = 'websites/' . $this->get_website_id() . '/scans';
	}

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
	 * Fetch scan histories from the web app.
	 *
	 * @param array $args Array of arguments.
	 * @return array
	 */
	public function get_history( $args = array() ) {
		return $this->get( add_query_arg( $args, $this->rest_path ) );
	}

	/**
	 * Get the details of a scan with ID.
	 *
	 * @param integer $id Web app id of the corresponding scan.
	 * @return array
	 */
	public function get_scan_details( $id = 0 ) {
		return $this->get( $this->rest_path . '/' . $id );
	}

	/**
	 * Check whether the scan can be initiated
	 *
	 * @return array
	 */
	public function can_scan() {
		return $this->post( 'websites/' . $this->get_website_id() . '/can-scan' );
	}

	/**
	 * Send a API request to the web app to initiate the scan
	 *
	 * @return array
	 */
	public function initiate_scan() {
		return $this->post(
			$this->rest_path,
			wp_json_encode(
				array(
					'page_limit'  => 100,
					'type'        => 'deep',
					'concurrency' => 1,
				)
			)
		);
	}
	/**
	 * Get the last scan info
	 *
	 * @return array
	 */
	public function get_info() {
		if ( ! $this->last_scan_info ) {
			$data                 = get_option( 'cky_scan_details', self::$default );
			$timestamp            = strtotime( sanitize_text_field( $data['date'] ) );
			$formatted            = gmdate( 'd F Y H:i:s', $timestamp );
			$this->last_scan_info = array(
				'id'     => absint( $data['id'] ),
				'status' => sanitize_text_field( $data['status'] ),
				'type'   => sanitize_text_field( $data['type'] ),
				'date'   => sanitize_text_field( $formatted ),
			);
		}
		return $this->last_scan_info;

	}

	/**
	 * Update the last scan info to the option table
	 *
	 * @param array $data Scan data recieved from the CookieYes web app after initiating the scan.
	 * @return void
	 */
	public function update_info( $data = array() ) {
		$scan_data = get_option( 'cky_scan_details', self::$default );
		$data      = array(
			'id'     => absint( isset( $data['id'] ) ? $data['id'] : $scan_data['id'] ),
			'status' => sanitize_text_field( isset( $data['status'] ) ? $data['status'] : $scan_data['status'] ),
			'type'   => sanitize_text_field( isset( $data['type'] ) ? $data['type'] : $scan_data['type'] ),
			'date'   => sanitize_text_field( isset( $data['date'] ) ? $data['date'] : $scan_data['date'] ),
		);
		update_option( 'cky_scan_details', $data );
		$this->last_scan_info = $data;
	}
	/**
	 * Load scanner configs into WordPress localization function
	 *
	 * @return array
	 */
	public function load_scanner_config() {
		return $this->get_info();
	}
}
