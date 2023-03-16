<?php
/**
 * Load legacy files.
 */

if ( ! function_exists( 'cky_define_constants' ) ) {
	/**
	 * Return parsed URL
	 *
	 * @return void
	 */
	function cky_define_constants() {
		if ( ! defined( 'CLI_PLUGIN_URL' ) ) {
			define( 'CLI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'CLI_PLUGIN_PATH' ) ) {
			define( 'CLI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		}
	}
}

/**
 * Function to activate the plugin.
 *
 * @return void
 */
function cky_activate() {
	Cookie_Law_Info_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cookie-law-info-deactivator.php
 */
function cky_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cookie-law-info-deactivator.php';
	Cookie_Law_Info_Deactivator::deactivate();
}

cky_define_constants();
require_once plugin_dir_path( __FILE__ ) . 'includes/class-cookie-law-info-activator.php';
register_activation_hook( __FILE__, 'cky_activate' );
register_deactivation_hook( __FILE__, 'cky_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cookie-law-info.php';

$cky_loader = new Cookie_Law_Info();
$cky_loader->run();
