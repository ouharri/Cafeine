<?php
/**
 * Class Api file.
 *
 * @package CookieYes\Lite\Admin\Modules\Banners\Api
 */

namespace CookieYes\Lite\Admin\Modules\Dashboard\Api;

use WP_REST_Server;
use WP_Error;
use stdClass;
use CookieYes\Lite\Includes\Rest_Controller;
use CookieYes\Lite\Admin\Modules\Dashboard\Includes\Controller;

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
	protected $rest_base = 'dashboard';

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
			'/' . $this->rest_base . '/summary',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}
	/**
	 * Get a collection of items.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$data = Controller::get_instance()->get_items();
		return rest_ensure_response( $data );
	}

} // End the class.
