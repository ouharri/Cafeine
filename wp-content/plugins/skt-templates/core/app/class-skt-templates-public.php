<?php
/**
 * The public-specific functionality of the plugin.
 *
 * @link       https://www.sktthemes.org
 * @since      1.0.0
 *
 * @package    Skt_Templates
 * @subpackage Skt_Templates/app
 */

class Skt_Templates_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Skt_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Skt_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		do_action( 'sktb_public_enqueue_styles' );
	}

	/**
	 * Register the JavaScript for the public area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Skt_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Skt_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		do_action( 'sktb_public_enqueue_scripts' );
	}
}
