<?php
/**
 * SeedProd class.
 *
 * @since 2.10.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SeedProd class.
 *
 * @since 2.10.0
 */
class OMAPI_Promos_SeedProd extends OMAPI_Promos_Base {

	/**
	 * The promo id.
	 *
	 * @var string
	 */
	protected $promo = 'seedprod';

	/**
	 * The plugin id.
	 *
	 * @var string
	 */
	protected $plugin_id = 'coming-soon/coming-soon.php';

	/**
	 * Loads the OptinMonster admin menu.
	 *
	 * @since 2.10.0
	 */
	protected function register_page() {
		return add_submenu_page(
			$this->base->menu->parent_slug(), // Parent slug
			esc_html__( 'SeedProd', 'optin-monster-api' ), // Page title
			esc_html__( 'Landing Pages', 'optin-monster-api' ),
			$this->base->access_capability( 'optin-monster-seedprod' ), // Cap
			'optin-monster-seedprod', // Slug
			array( $this, 'display_page' ) // Callback
		);
	}

	/**
	 * Redirects to the seedprod admin page.
	 *
	 * @since 2.10.0
	 */
	public function redirect_plugin() {
		$slug = ! empty( $this->plugin['which'] ) && 'default' !== $this->plugin['which']
			? 'seedprod_pro'
			: 'seedprod_lite';
		$url  = add_query_arg( 'page', $slug, admin_url( 'admin.php' ) );
		wp_safe_redirect( esc_url_raw( $url ) );
		exit;
	}

	/**
	 * Outputs the OptinMonster settings page.
	 *
	 * @since 2.10.0
	 */
	public function display_page() {
		$plugin_search_url = is_multisite()
			? network_admin_url( 'plugin-install.php?tab=search&type=term&s=seedprod' )
			: admin_url( 'plugin-install.php?tab=search&type=term&s=seedprod' );

		$this->base->output_view(
			'seedprod-settings-page.php',
			array(
				'plugin'            => $this->plugin,
				'plugin_search_url' => $plugin_search_url,
				'button_activate'   => __( 'Start Creating Landing Pages', 'optin-monster-api' ),
				'button_install'    => __( 'Start Creating Landing Pages', 'optin-monster-api' ),
			)
		);
	}

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 2.10.0
	 */
	public function assets() {
		parent::assets();
		wp_enqueue_style( 'om-tp-admin-css', $this->base->url . 'assets/dist/css/seedprod.min.css', false, $this->base->asset_version() );
	}

	/**
	 * Add body classes.
	 *
	 * @since 2.10.0
	 */
	public function add_body_classes( $classes ) {

		$classes .= ' omapi-seedprod ';

		return $classes;
	}

}
