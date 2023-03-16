<?php
/**
 * Class Api file.
 *
 * @package Api
 */

namespace CookieYes\Lite\Admin\Modules\Consentlogs\Api;

use WP_REST_Server;
use WP_Error;
use stdClass;
use CookieYes\Lite\Includes\Rest_Controller;
use CookieYes\Lite\Admin\Modules\Consentlogs\Includes\Controller;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Consent logs API
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
	protected $rest_base = 'consent_logs';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ), 10 );
	}
	/**
	 * Register the routes for cookies.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/statistics',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_statistics' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

	}

	/**
	 * Get consent statistics
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_statistics( $request ) {
		$objects = array();
		$items   = array();
		if ( false === cky_is_cloud_request() ) {
			return $items;
		}
		$items = Controller::get_instance()->get_statistics();
		foreach ( $items as $data ) {
			$context   = ! empty( $request['context'] ) ? $request['context'] : 'view';
			$data      = $this->add_additional_fields_to_object( $data, $request );
			$data      = $this->filter_response_by_context( $data, $context );
			$objects[] = $this->prepare_response_for_collection( $data );
		}
		return rest_ensure_response( $objects );

	}

	/**
	 * Get the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		return array(
			'context'  => $this->get_context_param( array( 'default' => 'view' ) ),
			'paged'    => array(
				'description'       => __( 'Current page of the collection.', 'cookie-law-info' ),
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
				'minimum'           => 1,
			),
			'per_page' => array(
				'description'       => __( 'Maximum number of items to be returned in result set.', 'cookie-law-info' ),
				'type'              => 'integer',
				'default'           => 10,
				'minimum'           => 1,
				'maximum'           => 100,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'search'   => array(
				'description'       => __( 'Limit results to those matching a string.', 'cookie-law-info' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			),
		);
	}
	/**
	 * Get the Consent logs's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'consentlogs',
			'type'       => 'object',
			'properties' => array(
				'ip'         => array(
					'description' => __( 'Visitor IP.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'country'    => array(
					'description' => __( 'Visitor Country.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'status'     => array(
					'description' => __( 'Consent status.', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'log'        => array(
					'description' => __( 'Log.', 'cookie-law-info' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
				),
				'consent_id' => array(
					'description' => __( 'Unique visitor ID', 'cookie-law-info' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

} // End the class.
