<?php
/**
 * Class Api file.
 *
 * @package Settings
 */

namespace CookieYes\Lite\Admin\Modules\Scanner\Api;

use WP_REST_Server;
use WP_Error;
use stdClass;
use CookieYes\Lite\Includes\Rest_Controller;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Cookies API
 *
 * @class       Api
 * @version     3.0.0
 * @package     CookieYes
 * @extends     Rest_Controller
 */
class Api extends Rest_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'cky/v1';
	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'scans';

	/**
	 * Base controller
	 *
	 * @var object
	 */
	protected $controller;

	/**
	 * Constructor
	 *
	 * @param object $controller Controller class object.
	 */
	public function __construct( $controller ) {
		add_action( 'rest_api_init', array( $this, 'register_routes' ), 10 );
		$this->controller = $controller;
	}

	/**
	 * Register the routes for cookies.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'cookie-law-info' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/details/(?P<id>[\d]+)',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'cookie-law-info' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_details' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
			)
		);
	}

	/**
	 * Get scan histories
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$history_details = array();
		$history_args    = array(
			'per_page' => isset( $request['per_page'] ) ? absint( $request['per_page'] ) : 10,
			'page'     => isset( $request['page'] ) ? absint( $request['page'] ) : 1,
		);
		$response        = $this->controller->get_history( $history_args );
		$response_code   = wp_remote_retrieve_response_code( $response );
		if ( 200 === $response_code ) {
			$items = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $items['data'] ) ) {
				foreach ( $items['data'] as $index => $item ) {
					$data                   = new stdClass();
					$data->id               = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
					$data->scan_status      = isset( $item['scan_status'] ) ? sanitize_text_field( $item['scan_status'] ) : '';
					$data->pages_scanned    = isset( $item['pages_scanned'] ) ? absint( $item['pages_scanned'] ) : 0;
					$data->total_categories = isset( $item['total_categories'] ) ? absint( $item['total_categories'] ) : 0;
					$data->total_cookies    = isset( $item['total_cookies'] ) ? absint( $item['total_cookies'] ) : 0;
					$data->total_scripts    = isset( $item['total_scripts'] ) ? absint( $item['total_scripts'] ) : 0;
					$data->created_at       = isset( $item['created_at'] ) ? sanitize_text_field( $item['created_at'] ) : '';
					if ( ! empty( $data ) ) {
						$history_details['data'][ $index ] = $data;
					}
				}
			}
			$pagination_data           = new stdClass();
			$pagination_data->per_page = isset( $items['per_page'] ) ? absint( $items['per_page'] ) : 10;
			$pagination_data->total    = isset( $items['total'] ) ? absint( $items['total'] ) : 0;
			if ( ! empty( $pagination_data ) ) {
				$history_details['pagination'] = $pagination_data;
			}
		}
		return $history_details;
	}

	/**
	 * Get individual scan details
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_details( $request ) {
		$scan_id      = (int) $request['id'];
		$scan_details = array();
		if ( 0 === $scan_id ) {
			return new WP_Error( 'cookieyes_rest_invalid_id', __( 'Invalid ID.', 'cookie-law-info' ), array( 'status' => 404 ) );
		}
		$response      = $this->controller->get_scan_details( $scan_id );
		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $response_code ) {
			$items = json_decode( wp_remote_retrieve_body( $response ), true );

			$data      = new stdClass();
			$data->id  = isset( $items['id'] ) ? absint( $items['id'] ) : 0;
			$scan_date = isset( $items['scan_date'] ) ? sanitize_text_field( $items['scan_date'] ) : '';

			if ( ! empty( $scan_date ) ) {
				$scan_date_time  = new \DateTime( $scan_date );
				$data->scan_date = cky_i18n_date( $scan_date_time->format( 'U' ) );
			}
			$data->scan_status      = isset( $items['scan_status'] ) ? sanitize_text_field( $items['scan_status'] ) : '';
			$data->total_pages      = isset( $items['total_pages'] ) ? absint( $items['total_pages'] ) : 0;
			$data->total_categories = isset( $items['total_categories'] ) ? absint( $items['total_categories'] ) : 0;
			$data->total_cookies    = isset( $items['total_cookies'] ) ? absint( $items['total_cookies'] ) : 0;
			$data->total_scripts    = isset( $items['total_scripts'] ) ? absint( $items['total_scripts'] ) : 0;

			if ( isset( $items['categories'] ) ) {
				foreach ( $items['categories'] as $category_index => $category ) {
					$data->categories[ $category_index ]['name'] = isset( $category['name'] ) ? sanitize_text_field( $category['name'] ) : '';
					if ( isset( $category['cookies'] ) ) {
						foreach ( $category['cookies'] as $cookie ) {
							$cookie_id = $cookie['cookie_id'];
							$data->categories[ $category_index ]['cookies'][ $cookie_id ]['cookie_id']   = isset( $cookie['cookie_id'] ) ? sanitize_text_field( $cookie['cookie_id'] ) : '';
							$data->categories[ $category_index ]['cookies'][ $cookie_id ]['description'] = isset( $cookie['description'] ) ? sanitize_text_field( $cookie['description'] ) : '';
							$data->categories[ $category_index ]['cookies'][ $cookie_id ]['duration']    = isset( $cookie['duration'] ) ? sanitize_text_field( $cookie['duration'] ) : '';
							$data->categories[ $category_index ]['cookies'][ $cookie_id ]['type']        = isset( $cookie['type'] ) ? sanitize_text_field( $cookie['type'] ) : '';
						}
					}
				}
			}

			if ( isset( $items['urls'] ) ) {
				foreach ( $items['urls'] as $url_index => $url ) {
					$data->urls[ $url_index ]['count'] = isset( $url['count'] ) ? absint( $url['count'] ) : 0;
					$data->urls[ $url_index ]['name']  = isset( $url['name'] ) ? sanitize_text_field( $url['name'] ) : '';
				}
			}
			$scan_details = $data;
		}
		return $scan_details;
	}

	/**
	 * Initiate a new scan by sending scan request to web app
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		$can_scan_response      = $this->controller->can_scan();
		$can_scan_response_code = wp_remote_retrieve_response_code( $can_scan_response );
		if ( 200 === $can_scan_response_code ) {
			$can_scan_response = json_decode( wp_remote_retrieve_body( $can_scan_response ), true );
			if ( ! $can_scan_response['canScan'] ) {
				return new WP_Error( 'cky_rest_scan_initiated', __( 'Could not initiate the scan, please try again', 'cookie-law-info' ), array( 'status' => 200 ) );
			}
		}
		$response      = $this->controller->initiate_scan();
		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $response_code || 400 === $response_code ) {
			$response = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( 200 === $response_code ) {
				$this->controller->update_info(
					array(
						'status' => 'initiated',
						'date'   => isset( $response['created_at'] ) ? $response['created_at'] : '',
						'type'   => isset( $response['type'] ) ? $response['type'] : '',
						'id'     => isset( $response['id'] ) ? $response['id'] : '',
					)
				);
				return $response;
			} else {
				$this->controller->update_info(
					array(
						'status' => 'initiated',
					)
				);
				return rest_ensure_response( $this->controller->get_info() );
			}
		} else {
			return new WP_Error( 'cky_rest_scan_initiated', __( 'Could not initiate the scan, please try again', 'cookie-law-info' ), array( 'status' => 200 ) );
		}
		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Format data
	 *
	 * @param object $object Item data.
	 * @return void
	 */
	protected function get_formatted_item_data( $object ) {
		$data = $object->get_data();
	}

	/**
	 * Get the Cookies's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'cookie_categories',
			'type'       => 'object',
			'properties' => array(
				'id'            => array(
					'description' => __( 'Unique identifier for the resource.', 'cookie-law-info' ),
					'type'        => 'integer',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'date_created'  => array(
					'description' => __( 'The date the cookie was created, as GMT.', 'cookie-law-info' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified' => array(
					'description' => __( 'The date the cookie was last modified, as GMT.', 'cookie-law-info' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'          => array(
					'description' => __( 'Cookie category name.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'          => array(
					'description' => __( 'Cookie category unique name', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'description'   => array(
					'description' => __( 'Cookie category description.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'default_state' => array(
					'description' => __( 'Cookie type.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'head_scripts'  => array(
					'description' => __( 'Cookie scripts.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'body_scripts'  => array(
					'description' => __( 'Cookie scripts.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'cookies'       => array(
					'description' => __( 'Cookie count.', 'cookie-law-info' ),
					'type'        => 'integer',
					'context'     => array( 'view' ),
				),

			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
} // End the class.
