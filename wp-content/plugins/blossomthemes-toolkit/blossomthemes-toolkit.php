<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wordpress.org/plugins/blossomthemes-toolkit/
 * @since             1.0.0
 * @package           Blossomthemes_Toolkit
 *
 * @wordpress-plugin
 * Plugin Name:       BlossomThemes Toolkit
 * Plugin URI:        https://wordpress.org/plugins/blossomthemes-toolkit/
 * Description:       BlossomThemes Toolkit provides you necessary widgets for better and effective blogging.
 * Version:           2.2.4
 * Author:            blossomthemes
 * Author URI:        https://blossomthemes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blossomthemes-toolkit
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BTTK_PLUGIN_VERSION', '2.2.4' );
define( 'BTTK_BASE_PATH', dirname( __FILE__ ) );
define( 'BTTK_FILE_PATH', __FILE__ );
define( 'BTTK_FILE_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
add_image_size( 'post-slider-thumb-size', 330, 190, true );
add_image_size( 'post-category-slider-size', 330, 350, true );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blossomthemes-toolkit-activator.php
 */
function activate_blossomthemes_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-toolkit-activator.php';
	Blossomthemes_Toolkit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blossomthemes-toolkit-deactivator.php
 */
function deactivate_blossomthemes_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-toolkit-deactivator.php';
	Blossomthemes_Toolkit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_blossomthemes_toolkit' );
register_deactivation_hook( __FILE__, 'deactivate_blossomthemes_toolkit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-toolkit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_blossomthemes_toolkit() {

	$plugin = new Blossomthemes_Toolkit();
	$plugin->run();

}
run_blossomthemes_toolkit();
