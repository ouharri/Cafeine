<?php
/*
Plugin Name: Burger Companion
Plugin URI:
Description: The Burger Companion plugin adds sections functionality to the Spintech Theme.
Version: 6.5
Author: burgersoftware
Author URI: https://burgersoftwares.com
Text Domain: burger-companion
*/
define( 'BURGER_COMPANION_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BURGER_COMPANION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function burger_companion_activate() {
	
	/**
	 * Load Custom control in Customizer
	 */
	 if ( class_exists( 'WP_Customize_Control' ) ) {
		require_once('inc/custom-controls/range-validator/range-control.php');		
	}
	require_once('inc/custom-controls/customizer-repeater/functions.php');	
	$theme = wp_get_theme(); // gets the current theme
	
		if( 'Spintech' == $theme->name){
			require_once('inc/spintech/spintech.php');
		}
		
		if( 'ITpress' == $theme->name){
			require_once('inc/spintech/spintech.php');
		}
		
		if( 'Burgertech' == $theme->name){
			require_once('inc/burgertech/burgertech.php');
		}
		
		if( 'KitePress' == $theme->name){
			require_once('inc/kitepress/kitepress.php');
		}
		
		if( 'CoziPress' == $theme->name){
			require_once('inc/cozipress/cozipress.php');
		}
		
		if( 'Sipri' == $theme->name){
			require_once('inc/sipri/sipri.php');
		}
		
		if( 'Anexa' == $theme->name){
			require_once('inc/anexa/anexa.php');
		}
		
		if( 'CoziWeb' == $theme->name){
			require_once('inc/coziweb/coziweb.php');
		}
		
		if( 'CoziPlus' == $theme->name){
			require_once('inc/coziplus/coziplus.php');
		}
		
		if( 'StoreBiz' == $theme->name){
			require_once('inc/storebiz/storebiz.php');
		}
		
		if( 'ShopMax' == $theme->name){
			require_once('inc/shopmax/shopmax.php');
		}
		
		if( 'StoreWise' == $theme->name){
			require_once('inc/storewise/storewise.php');
		}
		
		if( 'SeoKart' == $theme->name){
			require_once('inc/seokart/seokart.php');
		}
		
		if( 'Appetizer' == $theme->name){
			require_once('inc/appetizer/appetizer.php');
		}
		
		if( 'OwlPress' == $theme->name){
			require_once('inc/owlpress/owlpress.php');
		}
		
		if( 'Crowl' == $theme->name){
			require_once('inc/crowl/crowl.php');
		}
		
		if( 'Rasam' == $theme->name){
			require_once('inc/rasam/rasam.php');
		}
		
		if( 'Setto' == $theme->name){
			require_once('inc/setto/setto.php');
		}
		
		if( 'Setto Lifestyle' == $theme->name){
			require_once('inc/setto-lifestyle/setto-lifestyle.php');
		}
		
		if( 'DecorMe' == $theme->name){
			require_once('inc/decorme/decorme.php');
		}
		
		if( 'DigiPress' == $theme->name){
			require_once('inc/digipress/digipress.php');
		}
		
		if( 'SpinSoft' == $theme->name){
			require_once('inc/spinsoft/spinsoft.php');
		}
		
		if( 'CoziBee' == $theme->name){
			require_once('inc/cozibee/cozibee.php');
		}
		
		if( 'SpaBiz' == $theme->name){
			require_once('inc/spabiz/spabiz.php');
		}
		
		if( 'SpaCare' == $theme->name){
			require_once('inc/spacare/spacare.php');
		}
	}
add_action( 'init', 'burger_companion_activate' );

$theme = wp_get_theme();

/**
 * The code during plugin activation.
 */
function burger_companion_activated() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/burger-comapnion-activator.php';
	Burger_Companion_Activator::activate();
}
register_activation_hook( __FILE__, 'burger_companion_activated' );