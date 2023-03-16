<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wordpress.org/plugins/blossomthemes-toolkit/
 * @since      1.0.0
 *
 * @package    Blossomthemes_Toolkit
 * @subpackage Blossomthemes_Toolkit/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Blossomthemes_Toolkit
 * @subpackage Blossomthemes_Toolkit/includes
 * @author     blossomthemes <info@blossomthemes.com>
 */
class Blossomthemes_Toolkit {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Blossomthemes_Toolkit_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
		if ( defined( 'PLUGIN_VERSION' ) ) {
			$this->version = PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'blossomthemes-toolkit';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Blossomthemes_Toolkit_Loader. Orchestrates the hooks of the plugin.
	 * - Blossomthemes_Toolkit_i18n. Defines internationalization functionality.
	 * - Blossomthemes_Toolkit_Admin. Defines all hooks for the admin area.
	 * - Blossomthemes_Toolkit_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossomthemes-toolkit-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossomthemes-toolkit-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-blossomthemes-toolkit-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-blossomthemes-toolkit-public.php';

		/*
		 * All the required functions.
		 */
		require_once BTTK_BASE_PATH . '/includes/class-blossomthemes-toolkit-functions.php';

		/**
		 * Author Bio Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-author-bio.php';
		
		/**
		 * Popular Post Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-popular-post.php';

		/**
		 * Recent Post Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-recent-post.php';

		/**
		 * Custom Categories Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-custom-categories.php';

		/**
		 * Image Text Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-image-text.php';

		/**
		 * Posts Category Slider Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-posts-category-slider.php';

		/**
		 * Posts Category Slider Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-twitter-feeds.php';

		/**
		 * Facebook Page Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-facebook-page.php';

		/**
		 * Advertisement Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-advertisement.php';

		/**
		 * Pinterest Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-pinterest.php';

		/**
		 * Snapchat Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-snapchat.php';

		/**
		 * Social Media Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-socialmedia.php';

		/**
		 * Logo Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-client-logo.php';

		/**
		 * Featured page Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-featured-page.php';

		/**
		 * Call to action Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-cta.php';

		/**
		 * Testimonial Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-testimonial.php';

		/**
		 * Stat counter Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-stat-counter.php';

		/**
		 * Team member Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-team-member.php';

		/**
		 * Icon text Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-icon-text.php';

		/**
		 * Contact Widget.
		 */
		require_once BTTK_BASE_PATH . '/includes/widgets/widget-contact.php';

		/**
		 * The class responsible for defining all actions that occur in setting
		 * side.
		 */
		require_once BTTK_BASE_PATH . '/includes/class-blossomthemes-toolkit-settings.php';



		$this->loader = new Blossomthemes_Toolkit_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Blossomthemes_Toolkit_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Blossomthemes_Toolkit_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Blossomthemes_Toolkit_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_admin, 'enqueue_scripts' ); //hooked for elementor
		$this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_admin, 'enqueue_styles' ); //hooked for elementor
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'bttk_icon_list_enqueue' );
		$this->loader->add_action( 'category_add_form_fields', $plugin_admin, 'bttk_add_category_image' );
	    $this->loader->add_action( 'created_category', $plugin_admin, 'bttk_save_category_image' );
	    $this->loader->add_action( 'category_edit_form_fields', $plugin_admin, 'bttk_update_category_image' );
	    $this->loader->add_action( 'edited_category', $plugin_admin, 'bttk_updated_category_image' );
	    $arr = array('category'=>'category');
	    $array = apply_filters('bttk_custom_posts',$arr);
	    foreach ($array as $key => $value) {
		    if( isset($_GET['taxonomy']) && $_GET['taxonomy'] == $key )
		    {
		    	$this->loader->add_action( 'admin_footer', $plugin_admin, 'bttk_add_script' );
			}
	    }
		$this->loader->add_filter( 'manage_edit-category_columns', $plugin_admin, 'bttk_custom_column_header', 10);
		$this->loader->add_action( 'manage_category_custom_column', $plugin_admin, 'bttk_custom_column_content', 10, 3);
		$this->loader->add_action( 'admin_print_footer_scripts',  $plugin_admin,'bttk_client_logo_template', 0 );
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'bttk_faq_template' );
		$this->loader->add_action( 'init',  $plugin_admin, 'bttk_register_post_types' );
		$this->loader->add_action( 'init',  $plugin_admin,'bttk_create_post_type_taxonomies', 0 );
		$this->loader->add_action( 'wp_loaded', $plugin_admin, 'wpte_add_portfolio_templates' );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'wpte_add_portfolio_templates' );
		$this->loader->add_filter( 'template_include', $plugin_admin, 'bttk_include_template_function' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Blossomthemes_Toolkit_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'blossomthemes_toolkit_js_defer_files', 10 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Blossomthemes_Toolkit_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
