<?php
/**
 * Rest API Class, where we register/execute any REST API Routes
 *
 * @since 1.8.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rest Api class.
 *
 * @since 1.8.0
 */
class OMAPI_RestApi extends OMAPI_BaseRestApi {

	/**
	 * Whether Access-Control-Allow-Headers header was set/updated by us.
	 *
	 * @since 1.9.12
	 *
	 * @var bool
	 */
	protected $allow_header_set = false;

	/**
	 * Registers our Rest Routes for this App
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {

		// Filter only available in WP 5.5.
		add_filter( 'rest_allowed_cors_headers', array( $this, 'set_allow_headers' ), 999 );

		// Fall-through to check if we still need to set header (WP < 5.5).
		add_filter( 'rest_send_nocache_headers', array( $this, 'fallback_set_allow_headers' ), 999 );

		// Fetch some quick info about this WP installation.
		register_rest_route(
			$this->namespace,
			'info',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'output_info' ),
			)
		);

		// Fetch in-depth support info about this WP installation.
		register_rest_route(
			$this->namespace,
			'support',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'support_info' ),
			)
		);

		// Toggles rule debug.
		register_rest_route(
			$this->namespace,
			'support/debug/enable',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'rule_debug_enable' ),
			)
		);
		register_rest_route(
			$this->namespace,
			'support/debug/disable',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'rule_debug_disable' ),
			)
		);

		// Proxy route for getting the /me data from the app.
		register_rest_route(
			$this->namespace,
			'me',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_me' ),
			)
		);

		// Route for triggering refreshing/syncing of all campaigns.
		register_rest_route(
			$this->namespace,
			'campaigns/refresh',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'refresh_campaigns' ),
			)
		);

		// Route for fetching the campaign data for specific campaign.
		register_rest_route(
			$this->namespace,
			'campaigns/(?P<id>\w+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_campaign_data' ),
			)
		);

		// Route for updating the campaign data.
		register_rest_route(
			$this->namespace,
			'campaigns/(?P<id>\w+)',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'update_campaign_data' ),
			)
		);

		// Route for triggering refreshing/syncing of a single campaign.
		register_rest_route(
			$this->namespace,
			'campaigns/(?P<id>[\w-]+)/sync',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'sync_campaign' ),
			)
		);

		// Route for fetching data/resources needed for the campaigns.
		register_rest_route(
			$this->namespace,
			'resources',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_wp_resources' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'notifications',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_notifications' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'notifications/dismiss',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'dismiss_notification' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'notifications/create',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'create_event_notification' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'plugins',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_am_plugins_list' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'plugins',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'handle_plugin_action' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'api',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_store_api_key' ),
				'callback'            => array( $this, 'init_api_key_connection' ),
			)
		);

		// Only register the regenerate route when we have a token in the DB.
		if ( OMAPI_ApiAuth::has_token() ) {
			register_rest_route(
				$this->namespace,
				'api/regenerate',
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'permission_callback' => array( $this, 'can_store_regenerated_api_key' ),
					'callback'            => array( $this, 'store_regenerated_api_key' ),
				)
			);
		}

		register_rest_route(
			$this->namespace,
			'api',
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'permission_callback' => array( $this, 'can_delete_api_key' ),
				'callback'            => array( $this, 'disconnect' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'settings',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_and_can_access_route' ),
				'callback'            => array( $this, 'get_settings' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'settings',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_update_settings' ),
				'callback'            => array( $this, 'update_settings' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'review/dismiss',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'can_dismiss_review' ),
				'callback'            => array( $this, 'dismiss_review' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'omu/courses',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_courses' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'omu/guides',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'get_guides' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'account/sync',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => array( $this, 'logged_in_or_has_api_key' ),
				'callback'            => array( $this, 'sync_account' ),
			)
		);

		do_action( 'optin_monster_api_rest_register_routes', $this );
	}

	/**
	 * Filters the list of request headers that are allowed for CORS requests,
	 * and ensures our API key is allowed.
	 *
	 * @since 1.9.12
	 *
	 * @param string[] $allow_headers The list of headers to allow.
	 *
	 * @return string[]
	 */
	public function set_allow_headers( $allow_headers ) {
		$allow_headers[]        = 'X-OptinMonster-ApiKey';
		$this->allow_header_set = true;

		// remove fall-through.
		remove_filter( 'rest_send_nocache_headers', array( $this, 'fallback_set_allow_headers' ), 999 );

		return $allow_headers;
	}

	/**
	 * Fallback to make sure we set the allow headers.
	 *
	 * @since  1.9.12
	 *
	 * @param bool $rest_send_nocache_headers Whether to send no-cache headers.
	 *                                        We ignore this, because we're simply using this
	 *                                        as an action hook.
	 *
	 * @return bool Unchanged result.
	 */
	public function fallback_set_allow_headers( $rest_send_nocache_headers ) {
		if ( ! $this->allow_header_set && ! headers_sent() ) {
			foreach ( headers_list() as $header ) {
				if ( 0 === strpos( $header, 'Access-Control-Allow-Headers: ' ) ) {

					list( $key, $value ) = explode( 'Access-Control-Allow-Headers: ', $header );
					if ( false === strpos( $value, 'X-OptinMonster-ApiKey' ) ) {
						header( 'Access-Control-Allow-Headers: ' . $value . ', X-OptinMonster-ApiKey' );
					}

					$this->allow_header_set = true;
					break;
				}
			}
		}

		return $rest_send_nocache_headers;
	}

	/**
	 * Gets the /me data from the app.
	 *
	 * Route: GET omapp/v1/me
	 *
	 * @since 2.6.6
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function get_me() {
		$data = OMAPI_Api::fetch_me_cached( true );

		return is_wp_error( $data )
			? $this->wp_error_to_response( $data, OMAPI_Api::instance()->response_body )
			: new WP_REST_Response( $data, 200 );
	}

	/**
	 * Triggers refreshing our campaigns.
	 *
	 * Route: POST omapp/v1/campaigns/refresh
	 *
	 * @since 1.9.10
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function refresh_campaigns() {
		$result = $this->base->refresh->refresh();

		return is_wp_error( $result )
			? $this->wp_error_to_response( $result, OMAPI_Api::instance()->response_body )
			: new WP_REST_Response(
				array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
				200
			);
	}

	/**
	 * Fetch some quick info about this WP installation
	 * (WP version, plugin version, rest url, home url, WooCommerce version)
	 *
	 * Route: GET omapp/v1/info
	 *
	 * @since  1.9.10
	 *
	 * @return WP_REST_Response
	 */
	public function output_info() {
		return new WP_REST_Response( $this->base->refresh->get_info_args(), 200 );
	}

	/**
	 * Fetch in-depth support info about this WP installation.
	 * Used for the debug PDF, but can also be requested by support staff with the right api key.
	 *
	 * Route: GET omapp/v1/support
	 *
	 * @since  1.9.10
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response
	 */
	public function support_info( $request ) {
		$support = new OMAPI_Support();

		$format = $request->get_param( 'format' );
		if ( empty( $format ) ) {
			$format = 'raw';
		}

		return new WP_REST_Response( $support->get_support_data( $format ), 200 );
	}

	/**
	 * Enables the rules debug output for this site.
	 * (Still requires the omwpdebug query var on the frontend)
	 *
	 * Route: GET omapp/v1/support/debug/enable
	 *
	 * @since 2.4.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response
	 */
	public function rule_debug_enable() {
		return $this->toggle_rule_debug( true );
	}

	/**
	 * Disables the rules debug output for this site.
	 *
	 * Route: GET omapp/v1/support/debug/disable
	 *
	 * @since 2.4.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response
	 */
	public function rule_debug_disable() {
		return $this->toggle_rule_debug( false );
	}

	/**
	 * Toggles the rules debug setting.
	 *
	 * @since 2.4.0
	 *
	 * @param  boolean $enable Whether to enable/disable the rules debug setting.
	 *
	 * @return WP_REST_Response
	 */
	protected function toggle_rule_debug( $enable ) {
		$options = $this->base->get_option();

		if ( $enable ) {
			$options['api']['omwpdebug'] = true;
		} else {
			unset( $options['api']['omwpdebug'] );
		}

		$updated = $this->base->save->update_option( $options );

		return new WP_REST_Response(
			array(
				'message' => $updated
					? esc_html__( 'OK', 'optin-monster-api' )
					: esc_html__( 'Not Modified', 'optin-monster-api' ),
			),
			$updated ? 200 : 202
		);
	}

	/**
	 * Triggering refreshing/syncing of a single campaign.
	 *
	 * Route: POST omapp/v1/campaigns/(?P<id>[\w-]+)/sync
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function sync_campaign( $request ) {
		$campaign_id = $request->get_param( 'id' );

		if ( empty( $campaign_id ) ) {
			return new WP_REST_Response(
				array( 'message' => esc_html__( 'No campaign ID given.', 'optin-monster-api' ) ),
				400
			);
		}

		$this->base->refresh->sync( $campaign_id, $request->get_param( 'legacy' ) );

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Gets all the data needed for the campaign dashboard for a given campaign.
	 *
	 * Route: GET omapp/v1/campaigns/(?P<id>\w+)
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function get_campaign_data( $request ) {
		try {
			$campaign_id = $request->get_param( 'id' );

			if ( empty( $campaign_id ) ) {
				return new WP_REST_Response(
					array( 'message' => esc_html__( 'No campaign ID given.', 'optin-monster-api' ) ),
					400
				);
			}

			$campaign = $this->base->get_optin_by_slug( $campaign_id );

			if ( empty( $campaign->ID ) ) {
				$this->base->refresh->sync( $campaign_id );
				if ( is_wp_error( $this->base->refresh->error ) ) {
					$e = new OMAPI_WpErrorException();
					throw $e->setWpError( $this->base->refresh->error );
				}

				$campaign = $this->base->get_optin_by_slug( $campaign_id );
			}

			if ( empty( $campaign->ID ) ) {
				return new WP_REST_Response(
					array(
						/* translators: %s: the campaign post id. */
						'message' => sprintf( esc_html__( 'Could not find campaign by given ID: %s. Are you sure campaign is associated with this site?', 'optin-monster-api' ), $campaign_id ),
					),
					404
				);
			}

			// Get Campaigns Data.
			$data = $this->base->collect_campaign_data( $campaign );
			$data = apply_filters( 'optin_monster_api_setting_ui_data_for_campaign', $data, $campaign );

			return new WP_REST_Response( $data, 200 );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Updates data for given campaign.
	 *
	 * Route: POST omapp/v1/campaigns/(?P<id>\w+)
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function update_campaign_data( $request ) {
		$campaign_id = $request->get_param( 'id' );

		// If no campaign_id, return error.

		$campaign = $this->base->get_optin_by_slug( $campaign_id );

		// If no campaign, return 404.

		// Get the Request Params.
		$fields = json_decode( $request->get_body(), true );

		if ( ! empty( $fields['taxonomies'] ) ) {

			if ( isset( $fields['taxonomies']['categories'] ) ) {
				$fields['categories'] = $fields['taxonomies']['categories'];
			}

			// Save the data from the regular taxonomies fields into the WC specific tax field.
			// For back-compatibility.
			$fields['is_wc_product_category'] = isset( $fields['taxonomies']['product_cat'] )
				? $fields['taxonomies']['product_cat']
				: array();
			$fields['is_wc_product_tag']      = isset( $fields['taxonomies']['product_tag'] )
				? $fields['taxonomies']['product_tag']
				: array();
		}

		// Escape Parameters as needed.
		// Update Post Meta.
		foreach ( $fields as $key => $value ) {
			$value = $this->sanitize( $value );

			switch ( $key ) {
				default:
					update_post_meta( $campaign->ID, '_omapi_' . $key, $value );
			}
		}

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Gets all the data/resources needed for the campaigns.
	 *
	 * Route: GET omapp/v1/resources
	 *
	 * @since 1.9.10
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function get_wp_resources( $request ) {
		global $wpdb;

		$excluded = $request->get_param( 'excluded' );
		$excluded = ! empty( $excluded )
			? explode( ',', $excluded )
			: array();

		if ( $request->get_param( 'refresh' ) ) {
			$result = $this->refresh_campaigns();
			if ( is_wp_error( $result ) ) {
				$error_data = $result->get_error_data();

				if ( empty( $error_data['type'] ) || 'no-campaigns-error' !== $error_data['type'] ) {
					return $result;
				}
			}
		}

		$campaign_data = array();
		if ( ! in_array( 'campaigns', $excluded, true ) ) {
			// Get Campaigns Data.
			$campaigns = $this->base->get_optins( array( 'post_status' => 'any' ) );
			$campaigns = ! empty( $campaigns ) ? $campaigns : array();

			foreach ( $campaigns as $campaign ) {
				$campaign_data[] = $this->base->collect_campaign_data( $campaign );
			}
		}

		$mailpoet = $this->base->is_mailpoet_active();

		$taxonomy_map = array();
		if ( ! in_array( 'taxonomies', $excluded, true ) ) {

			// Get Taxonomies Data.
			$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
			$taxonomies = apply_filters( 'optin_monster_api_setting_ui_taxonomies', $taxonomies );

			foreach ( $taxonomies as $taxonomy ) {
				if ( 'category' === $taxonomy->name ) {
					$cats                     = get_categories();
					$taxonomy_map['category'] = array(
						'name'  => 'category',
						'label' => ucwords( $taxonomy->label ),
						'terms' => is_array( $cats ) ? array_values( $cats ) : array(),
						'for'   => $taxonomy->object_type,
					);
					continue;
				}

				$terms = get_terms(
					array(
						'taxonomy' => $taxonomy->name,
						'get'      => 'all',
					)
				);

				$taxonomy_map[ $taxonomy->name ] = array(
					'name'  => $taxonomy->name,
					'label' => ucwords( $taxonomy->label ),
					'terms' => is_array( $terms ) ? array_values( $terms ) : array(),
					'for'   => $taxonomy->object_type,
				);
			}
		}

		$posts = array();
		if ( ! in_array( 'posts', $excluded, true ) ) {

			// Posts query.
			$post_types = implode( '","', esc_sql( get_post_types( array( 'public' => true ) ) ) );
			$posts      = $wpdb->get_results( "SELECT ID AS `value`, post_title AS `name` FROM {$wpdb->prefix}posts WHERE post_type IN (\"{$post_types}\") AND post_status IN('publish', 'future') ORDER BY post_title ASC", ARRAY_A );
		}

		$post_types = ! in_array( 'post_types', $excluded, true )
			? array_values( get_post_types( array( 'public' => true ), 'object' ) )
			: array();

		// Get "Config" data.
		$config = array(
			'hasMailPoet'     => $mailpoet,
			'isWooActive'     => OMAPI_WooCommerce::is_active(),
			'isWooConnected'  => OMAPI_WooCommerce::is_connected(),
			'isWPFormsActive' => OMAPI_WPForms::is_active(),
			'isEddActive'     => OMAPI_EasyDigitalDownloads::is_active(),
			'isEddConnected'  => OMAPI_EasyDigitalDownloads::is_connected(),
			'mailPoetLists'   => $mailpoet && ! in_array( 'mailPoetLists', $excluded, true )
				? $this->base->mailpoet->get_lists()
				: array(),
			'mailPoetFields'  => $mailpoet && ! in_array( 'mailPoetFields', $excluded, true )
				? $this->base->mailpoet->get_custom_fields()
				: array(),
		);

		$response_data = apply_filters(
			'optin_monster_api_setting_ui_data',
			array(
				'config'      => $config,
				'campaigns'   => $campaign_data,
				'taxonomies'  => $taxonomy_map,
				'posts'       => $posts,
				'post_types'  => $post_types,
				'siteId'      => $this->base->get_site_id(),
				'siteIds'     => $this->base->get_site_ids(),
				'pluginsInfo' => ( new OMAPI_Plugins() )->get_active_plugins_header_value(),
			)
		);

		return new WP_REST_Response( $response_data, 200 );
	}

	/**
	 * Gets the list of AM notifications.
	 *
	 * Route: GET omapp/v1/notifications
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function get_notifications( $request ) {
		add_filter( 'optin_monster_api_admin_notifications_has_access', array( $this, 'maybe_allow' ) );

		// Make sure we have all the required user parameters.
		$this->base->actions->maybe_fetch_missing_data();

		if ( ! $this->base->notifications->has_access() ) {
			return new WP_REST_Response( array(), 206 );
		}

		return new WP_REST_Response( $this->base->notifications->get( true ), 200 );
	}

	/**
	 * Dismiss a given notifications.
	 *
	 * Route: POST omapp/v1/notifications/dismiss
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function dismiss_notification( $request ) {
		add_filter( 'optin_monster_api_admin_notifications_has_access', array( $this, 'maybe_allow' ) );

		$ids = $request->get_json_params();
		if ( $this->base->notifications->dismiss( $ids ) ) {
			return new WP_REST_Response( $this->base->notifications->get( true ), 200 );
		}

		return new WP_REST_Response(
			array(
				'message' => sprintf(
					/* translators: %s: the notification id(s). */
					esc_html__( 'Could not dismiss: %s', 'optin-monster-api' ),
					implode( ', ', $ids )
				),
			),
			400
		);
	}

	/**
	 * Dismiss a given notifications.
	 *
	 * Route: POST omapp/v1/notifications/create
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function create_event_notification( $request ) {
		add_filter( 'optin_monster_api_admin_notifications_has_access', array( $this, 'maybe_allow' ) );

		$payload = $request->get_json_params();

		$errors = array();
		foreach ( $payload as $notification ) {
			$added = $this->base->notifications->add_event( $notification );
			if ( is_wp_error( $added ) ) {
				$errors[] = $added;
			}
		}

		$updated = $this->base->notifications->get( true );

		if ( ! empty( $errors ) ) {
			$message = count( $payload ) > 1
				? sprintf(
					/* translators: %s: "Some" or "one". */
					esc_html__( 'Could not create %s of the event notifications!', 'optin-monster-api' ),
					count( $errors ) > 1 ? esc_html__( 'some', 'optin-monster-api' ) : esc_html__( 'one', 'optin-monster-api' )
				)
				: esc_html__( 'Could not create event notification!', 'optin-monster-api' );

			foreach ( $errors as $error ) {
				$message .= '<br>- ' . $error->get_error_message();
			}

			return new WP_REST_Response(
				array(
					'message'       => $message,
					'notifications' => $updated,
				),
				400
			);
		}

		return new WP_REST_Response( $updated, 200 );
	}

	/**
	 * Maybe allow api-key authenticated user to see notifications.
	 *
	 * @since  2.0.0
	 *
	 * @param  bool $access If current user has access to notifications.
	 *
	 * @return bool          Maybe modified access.
	 */
	public function maybe_allow( $access ) {
		if ( ! $access && $this->has_valid_api_key ) {

			$access = ! $this->base->get_option( 'hide_announcements' );
		}

		return $access;
	}

	/**
	 * Gets the list of AM plugins.
	 *
	 * Route: GET omapp/v1/plugins
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function get_am_plugins_list( $request ) {
		$plugins = new OMAPI_Plugins();
		$data    = $plugins->get_list_with_status();

		$action_nonce = wp_create_nonce( 'om_plugin_action_nonce' );
		foreach ( $data as $plugin_id => $plugin ) {
			$data[ $plugin_id ]['actionNonce'] = $action_nonce;
		}

		return new WP_REST_Response( array_values( $data ), 200 );
	}

	/**
	 * Handles installing or activating an AM plugin.
	 *
	 * Route: POST omapp/v1/plugins
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function handle_plugin_action( $request ) {
		try {

			$nonce = $request->get_param( 'actionNonce' );

			// Check the nonce.
			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'om_plugin_action_nonce' ) ) {
				throw new Exception( esc_html__( 'Security token invalid!', 'optin-monster-api' ), rest_authorization_required_code() );
			}

			$id = $request->get_param( 'id' );
			if ( empty( $id ) ) {
				throw new Exception( esc_html__( 'Plugin Id required.', 'optin-monster-api' ), 400 );
			}

			$plugins = new OMAPI_Plugins();
			$plugin  = $plugins->get( $id );

			if ( empty( $plugin['installed'] ) ) {
				if ( empty( $plugin['url'] ) ) {
					throw new Exception( esc_html__( 'Plugin install URL required.', 'optin-monster-api' ), 400 );
				}

				return new WP_REST_Response( $plugins->install_plugin( $plugin ), 200 );
			}

			$which = 'default' === $plugin['which'] ? $id : $plugin['which'];

			return new WP_REST_Response( $plugins->activate_plugin( $which ), 200 );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles storing the API key and initiating the API connection.
	 *
	 * Route: POST omapp/v1/api
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function init_api_key_connection( $request ) {
		try {
			$apikey = $request->get_param( 'key' );
			if ( empty( $apikey ) ) {
				throw new Exception( esc_html__( 'API Key Missing!', 'optin-monster-api' ), 400 );
			}

			$result = OMAPI_ApiKey::init_connection( $apikey );
			if ( is_wp_error( $result ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $result );
			}

			return new WP_REST_Response( $result, 200 );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Determine if we can store the given api key.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_store_api_key( $request ) {
		try {

			$this->verify_request_nonce( $request );

			$apikey = $request->get_param( 'key' );
			if ( empty( $apikey ) ) {
				throw new Exception( esc_html__( 'API Key Missing!', 'optin-monster-api' ), 400 );
			}

			$result = OMAPI_ApiKey::verify( $apikey );

			if ( is_wp_error( $result ) ) {
				$e = new OMAPI_WpErrorException();
				throw $e->setWpError( $result );
			}
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}

		return OMAPI::get_instance()->can_access( 'store_api_key' );
	}

	/**
	 * Handles storing the regenerated API key.
	 *
	 * Route: POST omapp/v1/api/regenerate
	 *
	 * @since 2.6.5
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 */
	public function store_regenerated_api_key( $request ) {
		try {
			$apikey = $request->get_param( 'key' );
			if ( empty( $apikey ) ) {
				throw new Exception( esc_html__( 'API Key Missing!', 'optin-monster-api' ), 400 );
			}

			$options                  = $this->base->get_option();
			$options['api']['apikey'] = $apikey;
			$this->base->save->update_option( $options );

			OMAPI_ApiAuth::delete_token();

			return $this->output_info();

		} catch ( Exception $e ) {
			OMAPI_ApiAuth::delete_token();

			return $this->exception_to_response( $e );
		}

	}

	/**
	 * Determine if we can store given regenerated api key.
	 *
	 * @since 2.6.5
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_store_regenerated_api_key( $request ) {
		try {
			$tt     = $request->get_param( 'tt' );
			$apikey = $request->get_param( 'key' );

			if ( empty( $tt ) || empty( $apikey ) ) {
				throw new Exception( esc_html__( 'Required Credentials Missing!', 'optin-monster-api' ), rest_authorization_required_code() );
			}

			$validated = OMAPI_ApiAuth::validate_token( $tt );
			if ( empty( $validated ) ) {
				throw new Exception( esc_html__( 'Invalid token!', 'optin-monster-api' ), 403 );
			}

			return true;

		} catch ( Exception $e ) {

			OMAPI_ApiAuth::delete_token();

			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Handles disconnecting the API key.
	 *
	 * Route: DELETE omapp/v1/api
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function disconnect( $request ) {
		try {

			OMAPI_ApiKey::disconnect();

			return new WP_REST_Response(
				array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
				204
			);

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Determine if we can disconnect the api key.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_delete_api_key( $request ) {
		try {

			$this->verify_request_nonce( $request );

			if ( ! OMAPI_ApiKey::has_credentials() ) {
				throw new Exception( esc_html__( 'API Key Missing!', 'optin-monster-api' ), 400 );
			}
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}

		return OMAPI::get_instance()->can_access( 'delete_api_key' );
	}

	/**
	 * Handles getting the misc. settings.
	 *
	 * Route: GET omapp/v1/settings
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function get_settings( $request ) {

		$defaults = $this->base->default_options();
		$options  = $this->base->get_option();

		$misc_settings = array();
		foreach ( array( 'auto_updates', 'usage_tracking', 'hide_announcements' ) as $key ) {
			$misc_settings[ $key ] = isset( $options[ $key ] )
				? $options[ $key ]
				: $defaults[ $key ];
		}

		return new WP_REST_Response( $misc_settings, 200 );
	}

	/**
	 * Handles updating settings.
	 *
	 * Route: POST omapp/v1/settings
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response The API Response
	 * @throws Exception If plugin action fails.
	 */
	public function update_settings( $request ) {
		try {

			$settings = $request->get_param( 'settings' );
			if ( empty( $settings ) ) {
				throw new Exception( esc_html__( 'Settings Missing!', 'optin-monster-api' ), 400 );
			}

			$allowed_settings = array(
				'auto_updates'       => array(
					'validate' => 'is_string',
				),
				'usage_tracking'     => array(
					'validate' => 'is_bool',
				),
				'hide_announcements' => array(
					'validate' => 'is_bool',
				),
				'accountId'          => array(
					'validate' => 'is_string',
				),
				'currentLevel'       => array(
					'validate' => 'is_string',
				),
				'plan'               => array(
					'validate' => 'is_string',
				),
				'customApiUrl'       => array(
					'validate' => 'is_string',
				),
				'apiCname'           => array(
					'validate' => 'is_string',
				),
			);

			$options      = $this->base->get_option();
			$has_settings = false;

			foreach ( $settings as $setting => $value ) {
				if ( empty( $allowed_settings[ $setting ] ) ) {
					continue;
				}

				$has_settings = true;

				if ( isset( $options[ $setting ] ) && $value === $options[ $setting ] ) {
					continue;
				}

				$validator = $allowed_settings[ $setting ]['validate'];

				if ( call_user_func( $validator, $value ) ) {
					switch ( $validator ) {
						case 'is_bool':
							$options[ $setting ] = ! ! $value;
							break;
						case 'is_string':
							$options[ $setting ] = sanitize_text_field( $value );
							break;
					}
					switch ( $setting ) {
						case 'customApiUrl':
							$options[ $setting ] = $value
								? 0 === strpos( $value, 'https://' )
									? $value
									: 'https://' . $value . '/app/js/api.min.js'
								: '';
							break;
					}
				}
			}

			// Looks like we want to toggle the omwpdebug setting.
			if ( isset( $settings['omwpdebug'] ) ) {
				$enabled = wp_validate_boolean( $settings['omwpdebug'] );
				if ( empty( $enabled ) ) {
					unset( $option['api']['omwpdebug'] );
				} else {
					$options['api']['omwpdebug'] = true;
				}
				$has_settings = true;
			}

			// Looks like we want to toggle the beta setting.
			if ( isset( $settings['omwpbeta'] ) ) {
				$enabled         = wp_validate_boolean( $settings['omwpdebug'] );
				$options['beta'] = ! empty( $enabled );
				$has_settings    = true;
			}

			if ( ! $has_settings ) {
				throw new Exception( esc_html__( 'Invalid Settings!', 'optin-monster-api' ), 400 );
			}

			// Save the updated option.
			$this->base->save->update_option( $options );

			return $this->get_settings( $request );

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Sanitize value recursively.
	 *
	 * @since  1.9.10
	 *
	 * @param  mixed $value The value to sanitize.
	 *
	 * @return mixed The sanitized value.
	 */
	public function sanitize( $value ) {
		if ( empty( $value ) ) {
			return $value;
		}

		if ( is_scalar( $value ) ) {
			return sanitize_text_field( $value );
		}

		if ( is_array( $value ) ) {
			return array_map( array( $this, 'sanitize' ), $value );
		}
	}

	/**
	 * Determine if user can dismiss review.
	 *
	 * @since 2.6.1
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function can_dismiss_review( $request ) {
		try {
			$this->verify_request_nonce( $request );
		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}

		return is_user_logged_in() &&
			OMAPI::get_instance()->can_access( 'review' );
	}

	/**
	 * Dismisses review.
	 *
	 * Route: POST omapp/v1/review/dismiss
	 *
	 * @since 2.6.1
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return bool
	 */
	public function dismiss_review( $request ) {
		$this->base->review->dismiss_review( $request->get_param( 'later' ) );

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'OK', 'optin-monster-api' ) ),
			200
		);
	}

	/**
	 * Fetch courses from OMU.
	 *
	 * Route: GET omapp/v1/omu/courses
	 *
	 * @since 2.6.6
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response|WP_Error The API Response or WP_Error object.
	 */
	public function get_courses( $request ) {
		return $this->handle_omu_request( 'courses' );
	}

	/**
	 * Fetch guides from OMU.
	 *
	 * Route: GET omapp/v1/omu/guides
	 *
	 * @since 2.6.6
	 *
	 * @param  WP_REST_Request $request The REST Request.
	 *
	 * @return WP_REST_Response|WP_Error The API Response or WP_Error object.
	 */
	public function get_guides( $request ) {
		return $this->handle_omu_request( 'guides' );
	}

	/**
	 * Fetch object from OMU.
	 *
	 * @since 2.6.6
	 *
	 * @param  string $object The API object to fetch.
	 *
	 * @return WP_REST_Response|WP_Error The API Response or WP_Error object.
	 */
	protected function handle_omu_request( $object ) {
		try {
			$result = OMAPI_OmuApi::cached_request( $object );
			$api    = OMAPI_OmuApi::instance();

			if ( is_wp_error( $result ) ) {
				return $this->wp_error_to_response( $result, $api->response_body );
			}

			$result = wp_parse_args(
				$result,
				array(
					'data'       => array(),
					'total'      => 0,
					'totalpages' => 0,
				)
			);

			$response = new WP_REST_Response( $result['data'], 200 );

			$response->header( 'X-WP-Total', $result['total'] );
			$response->header( 'X-WP-TotalPages', $result['totalpages'] );

			return $response;

		} catch ( Exception $e ) {
			return $this->exception_to_response( $e );
		}
	}

	/**
	 * Triggering refreshing/syncing of account settings.
	 *
	 * Route: POST omapp/v1/account/sync
	 *
	 * @since 2.6.13
	 *
	 * @param WP_REST_Request $request The REST Request.
	 * @return WP_REST_Response The API Response
	 */
	public function sync_account( $request ) {
		$data = OMAPI_Api::fetch_me_cached( true );
		if ( is_wp_error( $data ) ) {
			return new WP_REST_Response(
				array( 'message' => esc_html__( 'Sync failed!', 'optin-monster-api' ) ),
				400
			);
		}

		return new WP_REST_Response(
			array( 'message' => esc_html__( 'Sync succeeded!', 'optin-monster-api' ) ),
			200
		);
	}
}
