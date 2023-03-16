<?php

/**
 * Fired during plugin activation
 *
 * @package   Burger Companion
 */

/**
 * This class defines all code necessary to run during the plugin's activation.
 *
 */
class Burger_Companion_Activator {

	public static function activate() {

        $item_details_page = get_option('item_details_page'); 
		$theme = wp_get_theme(); // gets the current theme
		if(!$item_details_page){
			
			if ( 'Spintech' == $theme->name || 'ITpress' == $theme->name || 'Burgertech' == $theme->name || 'KitePress' == $theme->name  || 'SpinSoft' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/default-widgets/default-widget.php';
			}
			
			if ( 'CoziPress' == $theme->name || 'Sipri' == $theme->name || 'Anexa' == $theme->name || 'CoziWeb' == $theme->name || 'CoziPlus' == $theme->name  || 'CoziBee' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/default-widgets/default-widget.php';
			}
			
			if ( 'StoreBiz' == $theme->name || 'ShopMax' == $theme->name  || 'StoreWise' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/default-widgets/default-widget.php';
			}
			
			
			if ( 'SeoKart' == $theme->name  || 'DigiPress' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/default-widgets/default-widget.php';
			}
			
			if ( 'Appetizer' == $theme->name || 'Rasam' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/default-widgets/default-widget.php';
			}
			
			if ( 'OwlPress' == $theme->name || 'Crowl' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/default-widgets/default-widget.php';
			}
			
			if ( 'Setto' == $theme->name  || 'Setto Lifestyle' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/default-widgets/default-widget.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/default-pages/default-post.php';
			}
			
			if ( 'DecorMe' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/default-widgets/default-widget.php';
			}
			
			if ( 'SpaBiz' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/default-widgets/default-widget.php';
			}
			
			if ( 'SpaCare' == $theme->name ){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/default-widgets/default-widget.php';
			}
			
			update_option( 'item_details_page', 'Done' );
		}
	}

}