<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.sktthemes.org
 * @since      1.0.0
 *
 * @package    Skt_Templates
 * @subpackage Skt_Templates/includes
 */

class Skt_Templates {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Skt_Templates_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'skt-templates';

		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->prepare_modules();
		$this->define_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Skt_Templates_Loader. Orchestrates the hooks of the plugin.
	 * - Skt_Templates_i18n. Defines internationalization functionality.
	 * - Skt_Templates_Admin. Defines all hooks for the admin area.
	 * - Skt_Templates_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Skt_Templates_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Skt_Templates_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Skt_Templates_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Check Modules and register them.
	 *
	 * @since   1.0.0
	 * @access  private
	 */
	private function prepare_modules() {
		$global_settings = new Skt_Templates_Global_Settings();
		$modules_to_load = $global_settings->instance()->get_modules();
		$sktb_model      = new Skt_Templates_Model();

		$module_factory = new Skt_Templates_Module_Factory();
		foreach ( $modules_to_load as $module_name ) {
			$module = $module_factory::build( $module_name );
			if ( $module === false ) {
				continue;
			}
			$global_settings->register_module_reference( $module_name, $module );
			if ( $module->enable_module() ) {
				$module->register_loader( $this->get_loader() );
				$module->register_model( $sktb_model );
				if ( $module->get_is_active() ) {
					$module->set_enqueue( $this->get_version() ); // @codeCoverageIgnore
					$module->hooks(); // @codeCoverageIgnore
				}
				$this->loader->add_action( 'skt_templates_modules', $module, 'load' );
			}
		}
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Skt_Templates_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {

		$plugin_admin = new Skt_Templates_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'load_modules' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'visit_dashboard_notice_dismiss' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'menu_pages' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'visit_dashboard_notice' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$plugin_public = new Skt_Templates_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'after_setup_theme', $this, 'load_onboarding', 999999 );
	}

	/**
	 * Load onboarding, if missing.
	 */
	public function load_onboarding() {
		if ( defined( 'TI_ONBOARDING_DISABLED' ) ) {
			return;
		}
		$theme_support = get_theme_support( 'sktthemes-demo-import' );

		if ( empty( $theme_support ) ) {
			return;
		}
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

}