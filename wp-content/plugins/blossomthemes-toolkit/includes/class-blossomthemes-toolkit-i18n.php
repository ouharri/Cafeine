<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wordpress.org/plugins/blossomthemes-toolkit/
 * @since      1.0.0
 *
 * @package    Blossomthemes_Toolkit
 * @subpackage Blossomthemes_Toolkit/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Blossomthemes_Toolkit
 * @subpackage Blossomthemes_Toolkit/includes
 * @author     blossomthemes <info@blossomthemes.com>
 */
class Blossomthemes_Toolkit_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'blossomthemes-toolkit',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
