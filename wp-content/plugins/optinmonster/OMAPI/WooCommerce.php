<?php
/**
 * WooCommerce class.
 *
 * @since 1.7.0
 *
 * @package OMAPI
 * @author  Brandon Allen
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The WooCommerce class.
 *
 * @since 1.7.0
 */
class OMAPI_WooCommerce extends OMAPI_Integrations_Base {

	/**
	 * Path to the file.
	 *
	 * @since 1.7.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * The minimum WooCommerce version required.
	 *
	 * @since 1.9.0
	 *
	 * @var string
	 */
	const MINIMUM_VERSION = '3.2.0';

	/**
	 * Holds the cart class object.
	 *
	 * @since 2.8.0
	 *
	 * @var array
	 */
	protected $cart;

	/**
	 * OMAPI_WooCommerce_Save object
	 *
	 * @since 2.8.0
	 *
	 * @var OMAPI_WooCommerce_Save
	 */
	public $save;

	/**
	 * The OMAPI_EasyDigitalDownloads_RestApi instance.
	 *
	 * @since 2.13.0
	 *
	 * @var null|OMAPI_EasyDigitalDownloads_RestApi
	 */
	public $rest = null;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {
		parent::__construct();

		// Set our object.
		$this->save = new OMAPI_WooCommerce_Save( $this );

		add_action( 'optin_monster_api_rest_register_routes', array( $this, 'maybe_init_rest_routes' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'handle_enqueuing_assets' ) );

		// Register WooCommerce Education Meta Boxes.
		add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );

		// Add custom OptinMonster note.
		add_action( 'admin_init', array( $this, 'maybe_store_note' ) );

		// Revenue attribution support.
		add_action( 'woocommerce_thankyou', array( $this, 'maybe_store_revenue_attribution' ) );
		add_action( 'woocommerce_order_status_changed', array( $this, 'maybe_store_revenue_attribution_on_order_status_change' ), 10, 3 );
	}

	/**
	 * Enqueue Metabox Assets
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function handle_enqueuing_assets() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( empty( $screen->id ) ) {
			return;
		}

		switch ( $screen->id ) {
			case 'shop_coupon':
			case 'product':
				return $this->enqueue_metabox_assets();
			case 'woocommerce_page_wc-admin':
				return $this->enqueue_marketing_education_assets();
		}
	}

	/**
	 * Enqueue Metabox Assets
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function enqueue_metabox_assets() {
		wp_enqueue_style(
			$this->base->plugin_slug . '-metabox',
			$this->base->url . 'assets/dist/css/metabox.min.css',
			array(),
			$this->base->asset_version()
		);

		wp_enqueue_script(
			$this->base->plugin_slug . '-metabox-js',
			$this->base->url . 'assets/dist/js/metabox.min.js',
			array(),
			$this->base->asset_version(),
			true
		);
	}

	/**
	 * Enqueue marketing box script.
	 * Adds an OM product education box on the WooCommerce Marketing page.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function enqueue_marketing_education_assets() {
		wp_enqueue_script(
			$this->base->plugin_slug . '-wc-marketing-box-js',
			$this->base->url . 'assets/dist/js/wc-marketing.min.js',
			array(),
			$this->base->asset_version(),
			true
		);

		add_action( 'admin_footer', array( $this, 'output_marketing_card_template' ) );
	}

	/**
	 * Handles outputting the marketing card html to the page.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function output_marketing_card_template() {
		$this->base->output_view( 'woocommerce-marketing-card.php' );
	}

	/**
	 * Connects WooCommerce to OptinMonster.
	 *
	 * @param array $data The array of consumer key and consumer secret.
	 *
	 * @since 1.7.0
	 *
	 * @returns WP_Error|array
	 */
	public function connect( $data ) {
		if ( empty( $data['consumerKey'] ) || empty( $data['consumerSecret'] ) ) {
			return new WP_Error(
				'omapi-invalid-woocommerce-keys',
				esc_html__( 'The consumer key or consumer secret appears to be invalid. Try again.', 'optin-monster-api' )
			);
		}

		$data['woocommerce'] = self::version();
		$data                = array_merge( $data, OMAPI_Api::getUrlArgs() );

		// Get the OptinMonster API credentials.
		$creds = $this->get_request_api_credentials();

		// Initialize the API class.
		$api = new OMAPI_Api( 'woocommerce/shop', $creds, 'POST', 'v2' );

		return $api->request( $data );
	}

	/**
	 * Disconnects WooCommerce from OptinMonster.
	 *
	 * @since 1.7.0
	 */
	public function disconnect() {

		// Get the OptinMonster API credentials.
		$creds = $this->get_request_api_credentials();

		// Get the shop.
		$shop = esc_attr( $this->base->get_option( 'woocommerce', 'shop' ) );

		if ( empty( $shop ) ) {
			return true;
		}

		// Initialize the API class.
		$api = new OMAPI_Api( 'woocommerce/shop/' . rawurlencode( $shop ), $creds, 'DELETE', 'v2' );

		return $api->request();
	}

	/**
	 * Returns the API credentials to be used in an API request.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */
	public function get_request_api_credentials() {
		$creds = $this->base->get_api_credentials();

		// If set, return only the API key, not the legacy API credentials.
		if ( $creds['apikey'] ) {
			$_creds = array(
				'apikey' => $creds['apikey'],
			);
		} else {
			$_creds = array(
				'user' => $creds['user'],
				'key'  => $creds['key'],
			);
		}

		return $_creds;
	}

	/**
	 * Validates the passed consumer key and consumer secret.
	 *
	 * @since 1.7.0
	 *
	 * @param array $data The consumer key and consumer secret.
	 *
	 * @return array
	 */
	public function validate_keys( $data ) {
		$key    = isset( $data['consumer_key'] ) ? $data['consumer_key'] : '';
		$secret = isset( $data['consumer_secret'] ) ? $data['consumer_secret'] : '';

		if ( ! $key ) {
			return array(
				'error' => esc_html__( 'Consumer key is missing.', 'optin-monster-api' ),
			);
		}

		if ( ! $secret ) {
			return array(
				'error' => esc_html__( 'Consumer secret is missing.', 'optin-monster-api' ),
			);
		}

		// Attempt to find the passed consumer key in the database.
		$keys = $this->get_keys_by_consumer_key( $data['consumer_key'] );

		// If the consumer key is valid, then validate the consumer secret.
		if (
			empty( $keys['error'] )
			&& $this->is_consumer_secret_valid( $keys['consumer_secret'], $secret )
		) {
			$keys['consumer_key'] = $key;
		} else {
			$keys['error'] = esc_html__( 'Consumer secret is invalid.', 'optin-monster-api' );
		}

		return $keys;
	}

	/**
	 * Return the keys for the given consumer key.
	 *
	 * This is a rough copy of the same method used by WooCommerce.
	 *
	 * @since 1.7.0
	 *
	 * @param string $consumer_key The consumer key passed by the user.
	 *
	 * @return array
	 */
	private function get_keys_by_consumer_key( $consumer_key ) {
		global $wpdb;

		$consumer_key = wc_api_hash( sanitize_text_field( $consumer_key ) );

		$keys = $wpdb->get_row(
			$wpdb->prepare(
				"
					SELECT key_id, consumer_secret
					FROM {$wpdb->prefix}woocommerce_api_keys
					WHERE consumer_key = %s
				",
				$consumer_key
			),
			ARRAY_A
		);

		if ( empty( $keys ) ) {
			$keys = array(
				'error' => esc_html__( 'Consumer key is invalid.', 'optin-monster-api' ),
			);
		}

		return $keys;
	}

	/**
	 * Check if the consumer secret provided for the given user is valid
	 *
	 * This is a copy of the same method used by WooCommerce.
	 *
	 * @since 1.7.0
	 *
	 * @param string $keys_consumer_secret The consumer secret from the database.
	 * @param string $consumer_secret      The consumer secret passed by the user.
	 *
	 * @return bool
	 */
	private function is_consumer_secret_valid( $keys_consumer_secret, $consumer_secret ) {
		return hash_equals( $keys_consumer_secret, $consumer_secret );
	}

	/**
	 * Get WooCommerce API description and truncated key info by the key id.
	 *
	 * @since 1.7.0
	 *
	 * @param string $key_id The WooCommerce API key id.
	 *
	 * @return array
	 */
	public static function get_key_details_by_id( $key_id ) {
		if ( empty( $key_id ) ) {
			return array();
		}

		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare(
				"
					SELECT key_id, description, truncated_key
					FROM {$wpdb->prefix}woocommerce_api_keys
					WHERE key_id = %d
				",
				absint( $key_id )
			),
			ARRAY_A
		);

		return $data;
	}

	/**
	 * Determines if the current site is has WooCommerce connected.
	 *
	 * Checks that the site stored in the OptinMonster option matches the
	 * current `siteurl` WP option, and that the saved key id still exists in
	 * the WooCommerce key table. If these two things aren't true, then the
	 * current site is not connected.
	 *
	 * @since 1.7.0
	 *
	 * @return boolean
	 */
	public static function is_connected() {

		// If not active, then it is not connected as well.
		if ( ! self::is_active() ) {
			return false;
		}

		// Get current site details.
		$site = OMAPI_Utils::parse_url( site_url() );
		$host = isset( $site['host'] ) ? $site['host'] : '';

		// Get any options we have stored.
		$option = OMAPI::get_instance()->get_option( 'woocommerce' );
		$shop   = isset( $option['shop'] ) ? $option['shop'] : '';
		$key_id = isset( $option['key_id'] ) ? $option['key_id'] : '';
		$key    = $key_id ? self::get_key_details_by_id( $key_id ) : array();

		$is_connected = ! empty( $key['key_id'] ) && $host === $shop;

		return apply_filters( 'optinmonster_woocommerce_is_connected', $is_connected );
	}

	/**
	 * Add the category base to the category REST API response.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Response $response The REST API response.
	 *
	 * @return WP_REST_Response
	 */
	public static function add_category_base_to_api_response( $response ) {
		return self::add_base_to_api_response( $response, 'category_rewrite_slug' );
	}

	/**
	 * Add the tag base to the tag REST API response.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Response $response The REST API response.
	 *
	 * @return WP_REST_Response
	 */
	public static function add_tag_base_to_api_response( $response ) {
		return self::add_base_to_api_response( $response, 'tag_rewrite_slug' );
	}

	/**
	 * Add the category/tag base to the category/tag REST API response.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Response $response The REST API response.
	 * @param string           $base     The base setting to retrieve.
	 *
	 * @return WP_REST_Response
	 */
	public static function add_base_to_api_response( $response, $base ) {
		$permalink_options = wc_get_permalink_structure();
		if ( isset( $permalink_options[ $base ] ) ) {
			$response->data['base'] = $permalink_options[ $base ];
		}

		return $response;
	}

	/**
	 * Return the WooCommerce versions string.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	public static function version() {
		return defined( 'WC_VERSION' ) ? WC_VERSION : '0.0.0';
	}

	/**
	 * Add a OM product education metabox on the WooCommerce coupon and product pages.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function register_metaboxes() {
		add_meta_box(
			'woocommerce_promote_coupon_metabox',
			__( 'Promote this coupon', 'optin-monster-api' ),
			array( $this, 'output_coupon_metabox' ),
			'shop_coupon'
		);
		add_meta_box(
			'woocommerce_popup_metabox',
			__( 'Product Popups', 'optin-monster-api' ),
			array( $this, 'output_product_metabox' ),
			'product'
		);
	}

	/**
	 * Output the markup for the coupon metabox.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function output_coupon_metabox() {
		$args = $this->metabox_args();
		if ( ! $args['has_sites'] ) {
			$args['not_connected_message'] = esc_html__( 'Please create a Free Account or Connect an Existing Account to promote coupons.', 'optin-monster-api' );
		}
		$this->base->output_view( 'coupon-metabox.php', $args );
	}

	/**
	 * Output the markup for the product metabox.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function output_product_metabox() {
		$args = $this->metabox_args();
		if ( ! $args['has_sites'] ) {
			$args['not_connected_message'] = esc_html__( 'Please create a Free Account or Connect an Existing Account to use Product Popups.', 'optin-monster-api' );
		}
		$this->base->output_view( 'product-metabox.php', $args );
	}

	/**
	 * Get the site-connected args for the metaboxes.
	 *
	 * @since 2.3.0
	 *
	 * @return array  Array of site-connected args.
	 */
	protected function metabox_args() {
		$args = array(
			'has_sites' => $this->base->get_site_id(),
		);

		if ( ! $args['has_sites'] ) {
			$args['not_connected_title'] = esc_html__( 'You Have Not Connected with OptinMonster', 'optin-monster-api' );
		}

		return $args;
	}

	/**
	 * Adds a note to the WooCommerce inbox.
	 *
	 * @since 2.2.0
	 *
	 * @return int
	 */
	public function maybe_store_note() {

		// Check for Admin Note support.
		if ( ! class_exists( 'Automattic\WooCommerce\Admin\Notes\Notes', false ) || ! class_exists( 'Automattic\WooCommerce\Admin\Notes\Note', false ) ) {
			return;
		}

		// Make sure the WooCommerce Data Store is available.
		if ( ! class_exists( 'WC_Data_Store', false ) ) {
			return;
		}

		$note_name = 'om-wc-grow-revenue';

		try {

			// Load the Admin Notes from the WooCommerce Data Store.
			$data_store = WC_Data_Store::load( 'admin-note' );

			$note_ids = $data_store->get_notes_with_name( $note_name );

		} catch ( Exception $e ) {
			return;
		}

		// This ensures we don't create a duplicate note.
		if ( ! empty( $note_ids ) ) {
			return;
		}

		// If we're here, we can create a new note.
		$note = new Automattic\WooCommerce\Admin\Notes\Note();
		$note->set_title( __( 'Grow your store revenue with OptinMonster', 'optin-monster-api' ) );
		$note->set_content( __( 'Create high-converting OptinMonster campaigns to promote product sales, reduce cart abandonment and incentivize purchases with time-sensitive coupon offers.', 'optin-monster-api' ) );
		$note->set_type( Automattic\WooCommerce\Admin\Notes\Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
		$note->set_layout( 'plain' );
		$note->set_source( 'optinmonster' );
		$note->set_name( $note_name );
		$note->add_action(
			'om-note-primary',
			__( 'Create a campaign', 'optin-monster-api' ),
			'admin.php?page=optin-monster-templates',
			'unactioned',
			true
		);
		$note->add_action(
			'om-note-seconday',
			__( 'Learn more', 'optin-monster-api' ),
			'admin.php?page=optin-monster-about&selectedTab=getting-started',
			'unactioned',
			false
		);

		$note->save();
	}

	/**
	 * Maybe stores revenue attribution data when a purchase is successful.
	 *
	 * @since 2.6.13
	 *
	 * @param int  $order_id The WooCommerce order ID.
	 * @param bool $force    Flag to force storing the revenue attribution data.
	 *
	 * @return void
	 */
	public function maybe_store_revenue_attribution( $order_id = 0, $force = false ) {
		// If we have already stored revenue attribution data before, return early.
		$stored = get_post_meta( $order_id, '_om_revenue_attribution_complete', true );
		if ( $stored ) {
			return;
		}

		// Grab the order. If we can't, return early.
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		// Grab some necessary data to send.
		$data_on_order = get_post_meta( $order_id, '_om_revenue_attribution_data', true );
		$data          = wp_parse_args(
			array(
				'transaction_id' => absint( $order_id ),
				'value'          => esc_html( $order->get_total() ),
			),
			! empty( $data_on_order ) ? $data_on_order : $this->base->revenue->get_revenue_data()
		);

		// If the order is not complete, return early.
		// This will happen for payments where further
		// work is required (such as checks, etc.). In those
		// instances, we need to store the data to be processed
		// at a later time.
		if ( ! $order->has_status( 'completed' ) && ! $force ) {
			update_post_meta( $order_id, '_om_revenue_attribution_data', $data );
			return;
		}

		// Attempt to make the revenue attribution request.
		// It checks to determine if campaigns are set, etc.
		$ret = $this->base->revenue->store( $data );
		if ( ! $ret || is_wp_error( $ret ) ) {
			return;
		}

		// Update the payment meta for storing revenue attribution data.
		update_post_meta( $order_id, '_om_revenue_attribution_complete', time() );
	}

	/**
	 * Maybe stores revenue attribution data when a purchase is successful.
	 *
	 * @since 2.6.13
	 *
	 * @param int    $order_id   The WooCommerce order ID.
	 * @param string $old_status The old order status.
	 * @param string $new_status The new order status.
	 *
	 * @return void
	 */
	public function maybe_store_revenue_attribution_on_order_status_change( $order_id, $old_status, $new_status ) {
		// If we don't have the proper new status, return early.
		if ( 'completed' !== $new_status ) {
			return;
		}

		// Maybe store the revenue attribution data.
		return $this->maybe_store_revenue_attribution( $order_id, true );
	}

	/**
	 * Retrieve the cart from Woocommerce
	 *
	 * @since 2.8.0 Moved from OMAPI_Output->woocommerce_cart.
	 *
	 * @return array An array of WooCommerce cart data.
	 */
	public function get_cart() {
		if ( ! empty( $this->cart ) ) {
			return $this->cart;
		}

		// Bail if WooCommerce isn't currently active.
		if ( ! self::is_active() ) {
			return array();
		}

		// Check if WooCommerce is the minimum version.
		if ( ! self::is_minimum_version() ) {
			return array();
		}

		// Initialize the cart.
		wc_load_cart();

		// Bail if we don't have a cart object.
		if ( ! isset( WC()->cart ) || '' === WC()->cart ) {
			return array();
		}

		// Calculate the cart totals.
		WC()->cart->calculate_totals();

		// Get initial cart data.
		$cart               = WC()->cart->get_totals();
		$cart['cart_items'] = WC()->cart->get_cart();

		// Set the currency data.
		$currencies       = get_woocommerce_currencies();
		$currency_code    = get_woocommerce_currency();
		$cart['currency'] = array(
			'code'   => $currency_code,
			'symbol' => get_woocommerce_currency_symbol( $currency_code ),
			'name'   => isset( $currencies[ $currency_code ] ) ? $currencies[ $currency_code ] : '',
		);

		// Add in some extra data to the cart item.
		foreach ( $cart['cart_items'] as $key => $item ) {
			$item_details = array(
				'type'              => $item['data']->get_type(),
				'sku'               => $item['data']->get_sku(),
				'categories'        => $item['data']->get_category_ids(),
				'tags'              => $item['data']->get_tag_ids(),
				'regular_price'     => $item['data']->get_regular_price(),
				'sale_price'        => $item['data']->get_sale_price() ? $item['data']->get_sale_price() : $item['data']->get_regular_price(),
				'virtual'           => $item['data']->is_virtual(),
				'downloadable'      => $item['data']->is_downloadable(),
				'sold_individually' => $item['data']->is_sold_individually(),
			);
			unset( $item['data'] );
			$cart['cart_items'][ $key ] = array_merge( $item, $item_details );
		}

		// Save for later use if necessary
		$this->cart = $cart;

		// Send back a response.
		return $this->cart;
	}

	/**
	 * Check if the Woocommerce plugin is active.
	 *
	 * @since 2.8.0 Moved from OMAPI class
	 *
	 * @return bool
	 */
	public static function is_active() {
		return class_exists( 'WooCommerce', true );
	}

	/**
	 * Initiate our REST routes for WooCommerce if WooCommerce active.
	 *
	 * @since 2.13.0
	 *
	 * @return void
	 */
	public function maybe_init_rest_routes() {
		if ( self::is_active() ) {
			$this->rest = new OMAPI_WooCommerce_RestApi( $this->save );
		}
	}

}
