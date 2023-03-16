<?php
/**
 * TrustPulse class.
 *
 * @since 1.9.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TrustPulse class.
 *
 * @since 1.9.0
 */
class OMAPI_Promos_TrustPulse extends OMAPI_Promos_Base {

	/**
	 * The promo id.
	 *
	 * @var string
	 */
	protected $promo = 'trustpulse';

	/**
	 * The plugin id.
	 *
	 * @var string
	 */
	protected $plugin_id = 'trustpulse-api/trustpulse.php';

	/**
	 * Whether the TrustPulse plugin has been setup.
	 *
	 * @since 1.9.0
	 *
	 * @var bool
	 */
	public $trustpulse_setup;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.9.0
	 */
	public function __construct() {
		if ( ! defined( 'TRUSTPULSE_APP_URL' ) ) {
			define( 'TRUSTPULSE_APP_URL', 'https://app.trustpulse.com/' );
		}

		if ( ! defined( 'TRUSTPULSE_URL' ) ) {
			define( 'TRUSTPULSE_URL', 'https://trustpulse.com/' );
		}

		parent::__construct();
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.9.0
	 */
	public function set() {
		parent::set();
		$account_id             = get_option( 'trustpulse_script_id', null );
		$this->trustpulse_setup = ! empty( $account_id );
	}

	/**
	 * Loads the OptinMonster admin menu.
	 *
	 * @since 1.9.0
	 */
	protected function register_page() {
		return add_submenu_page(
			// If trustpulse is active/setup, don't show the TP sub-menu item under OM.
			! empty( $this->plugin['active'] ) && $this->trustpulse_setup
				? $this->base->menu->parent_slug() . '-no-menu'
				: $this->base->menu->parent_slug(), // Parent slug
			esc_html__( 'TrustPulse', 'optin-monster-api' ), // Page title
			esc_html__( 'Social Proof Widget', 'optin-monster-api' ),
			$this->base->access_capability( 'optin-monster-trustpulse' ), // Cap
			'optin-monster-trustpulse', // Slug
			array( $this, 'display_page' ) // Callback
		);
	}

	/**
	 * Redirects to the trustpulse admin page.
	 *
	 * @since  1.9.0
	 */
	public function redirect_plugin() {
		$url = esc_url_raw( admin_url( 'admin.php?page=trustpulse' ) );
		wp_safe_redirect( $url );
		exit;
	}

	/**
	 * Outputs the OptinMonster settings page.
	 *
	 * @since 1.9.0
	 */
	public function display_page() {
		$plugin_search_url = is_multisite()
			? network_admin_url( 'plugin-install.php?tab=search&type=term&s=trustpulse' )
			: admin_url( 'plugin-install.php?tab=search&type=term&s=trustpulse' );

		$this->base->output_view(
			'trustpulse-settings-page.php',
			array(
				'plugin'            => $this->plugin,
				'plugin_search_url' => $plugin_search_url,
				'button_activate'   => __( 'Activate the TrustPulse Plugin', 'optin-monster-api' ),
				'button_install'    => __( 'Install & Activate the TrustPulse Plugin', 'optin-monster-api' ),
			)
		);
	}

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 1.9.0
	 */
	public function assets() {
		parent::assets();
		wp_enqueue_style( 'om-tp-admin-css', $this->base->url . 'assets/dist/css/trustpulse.min.css', false, $this->base->asset_version() );
		add_action( 'in_admin_header', array( $this, 'render_banner' ) );
	}

	/**
	 * Renders TP banner in the page header
	 *
	 * @return void
	 */
	public function render_banner() {
		$this->base->output_view( 'trustpulse-banner.php' );
	}
}
