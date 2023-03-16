<?php
/**
 * Base Promos class.
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
 * Base Promos class.
 *
 * @since 2.10.0
 */
abstract class OMAPI_Promos_Base {

	/**
	 * Holds the class object.
	 *
	 * @since 2.10.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.10.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Holds the welcome slug.
	 *
	 * @since 2.10.0
	 *
	 * @var string
	 */
	public $hook;

	/**
	 * SeedProd plugin data.
	 *
	 * @since 2.10.0
	 *
	 * @var array
	 */
	public $plugin_data = array();

	/**
	 * The promo id.
	 *
	 * @since 2.10.0
	 *
	 * @var string
	 */
	protected $promo = '';

	/**
	 * The plugin id (from OMAPI_Plugins).
	 *
	 * @since 2.10.0
	 *
	 * @var string
	 */
	protected $plugin_id = '';

	/**
	 * OMAPI_Plugins_Plugin instance.
	 *
	 * @since 2.10.0
	 *
	 * @var OMAPI_Plugins_Plugin
	 */
	public $plugin;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.10.0
	 */
	public function __construct() {

		// If we are not in admin or admin ajax, return.
		if ( ! is_admin() ) {
			return;
		}

		// If user is in admin ajax or doing cron, return.
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			return;
		}

		// If user is not logged in, return.
		if ( ! is_user_logged_in() ) {
			return;
		}
			// return;

		// If user cannot manage_options, return.
		if ( ! OMAPI::get_instance()->can_access( $this->promo ) ) {
			return;
		}

		// Set our object.
		$this->set();

		// Register the menu item.
		add_action( 'admin_menu', array( $this, '_register_page' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 2.10.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();

		$this->plugin = OMAPI_Plugins_Plugin::get( $this->plugin_id );
	}

	/**
	 * Loads the OptinMonster admin menu and page.
	 *
	 * @since 2.10.0
	 */
	abstract protected function register_page();

	/**
	 * Loads the OptinMonster admin menu and page.
	 *
	 * @since 2.10.0
	 */
	public function _register_page() {
		$hook = $this->register_page();
		// If SeedProd is active, we want to redirect to its own landing page.
		if ( ! empty( $this->plugin['active'] ) ) {
			add_action( 'load-' . $hook, array( $this, 'redirect_plugin' ) );
		}

		// Load settings page assets.
		add_action( 'load-' . $hook, array( $this, 'assets' ) );
	}

	/**
	 * Redirects to the seedprod admin page.
	 *
	 * @since 2.10.0
	 */
	abstract public function redirect_plugin();

	/**
	 * Outputs the OptinMonster settings page.
	 *
	 * @since 2.10.0
	 */
	abstract public function display_page();

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 2.10.0
	 */
	public function assets() {
		add_filter( 'admin_body_class', array( $this, 'add_body_classes' ) );
		$this->base->menu->styles();
		$this->base->menu->scripts();
	}

	/**
	 * Add body classes.
	 *
	 * @since 2.10.0
	 */
	public function add_body_classes( $classes ) {

		$classes .= " omapi-{$this->promo} ";

		return $classes;
	}

}
