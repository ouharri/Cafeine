<?php
/**
 * Initialize the plugin.
 */

if ( ! function_exists( 'cky_define_constants' ) ) {
	/**
	 * Return parsed URL
	 *
	 * @return void
	 */
	function cky_define_constants() {
		if ( ! defined( 'CKY_PLUGIN_URL' ) ) {
			define( 'CKY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'CKY_APP_ASSETS_URL' ) ) {
			define( 'CKY_APP_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'frontend/images/' );
		}
	}
}

cky_define_constants();

require_once CLI_PLUGIN_BASEPATH . 'class-autoloader.php';

$autoloader = new \CookieYes\Lite\Autoloader();
$autoloader->register();

register_activation_hook( __FILE__, array( \CookieYes\Lite\Includes\Activator::get_instance(), 'install' ) );

$cky_loader = new \CookieYes\Lite\Includes\CLI();
$cky_loader->run();


