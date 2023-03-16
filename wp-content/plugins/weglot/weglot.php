<?php
/*
Plugin Name: Weglot Translate
Plugin URI: http://wordpress.org/plugins/weglot/
Description: Translate your website into multiple languages in minutes without doing any coding. Fully SEO compatible.
Author: Weglot Translate team
Author URI: https://weglot.com/
Text Domain: weglot
Domain Path: /languages/
Version: 3.9.2
*/

/**
 * This file need to be compatible with PHP 5.3
 * Example : Don't use short syntax for array()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WEGLOT_NAME', 'Weglot' );
define( 'WEGLOT_SLUG', 'weglot-translate' );
define( 'WEGLOT_OPTION_GROUP', 'group-weglot-translate' );
define( 'WEGLOT_VERSION', '3.9.2' );
define( 'WEGLOT_PHP_MIN', '5.6' );
define( 'WEGLOT_BNAME', plugin_basename( __FILE__ ) );
define( 'WEGLOT_DIR', __DIR__ );
define( 'WEGLOT_DIR_LANGUAGES', WEGLOT_DIR . '/languages' );
define( 'WEGLOT_DIR_DIST', WEGLOT_DIR . '/dist' );

define( 'WEGLOT_DIRURL', plugin_dir_url( __FILE__ ) );
define( 'WEGLOT_URL_DIST', WEGLOT_DIRURL . 'dist' );
define( 'WEGLOT_LATEST_VERSION', '2.7.0' );
define( 'WEGLOT_DEBUG', false );
define( 'WEGLOT_DEV', false );

define( 'WEGLOT_TEMPLATES', WEGLOT_DIR . '/templates' );
define( 'WEGLOT_TEMPLATES_ADMIN', WEGLOT_TEMPLATES . '/admin' );
define( 'WEGLOT_TEMPLATES_ADMIN_NOTICES', WEGLOT_TEMPLATES_ADMIN . '/notices' );
define( 'WEGLOT_TEMPLATES_ADMIN_PAGES', WEGLOT_TEMPLATES_ADMIN . '/pages' );

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Compatibility Yoast premium Redirection
$dir_yoast_premium = plugin_dir_path( __DIR__ ) . 'wordpress-seo-premium';
if ( file_exists( $dir_yoast_premium . '/wp-seo-premium.php' ) ) {

	if ( ! weglot_is_compatible() ) {
		return;
	}

	$yoast_plugin_data        = get_plugin_data( $dir_yoast_premium . '/wp-seo-premium.php' );
	$dir_yoast_premium_inside = $dir_yoast_premium . '/premium/';

	// Override yoast redirect
	if (
		! is_admin() &&
		version_compare( $yoast_plugin_data['Version'], '7.1.0', '>=' ) &&
		is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) &&
		file_exists( $dir_yoast_premium_inside ) &&
		file_exists( $dir_yoast_premium_inside . 'classes/redirect/redirect-handler.php' ) &&
		file_exists( $dir_yoast_premium_inside . 'classes/redirect/redirect-util.php' )
	) {
		require_once __DIR__ . '/weglot-autoload.php';
		require_once __DIR__ . '/vendor/autoload.php';
		require_once __DIR__ . '/bootstrap.php';
		require_once __DIR__ . '/weglot-functions.php';
		include_once __DIR__ . '/src/third/yoast/redirect-premium.php';
	}
}

gtranslate_is_active();
polylang_is_active();
translatepress_is_active();
wpml_is_active();

/**
 * Check compatibility this Weglot with WordPress config.
 */
function weglot_is_compatible() {
	// Check php version.
	if ( version_compare( PHP_VERSION, WEGLOT_PHP_MIN ) < 0 ) {
		add_action( 'admin_notices', 'weglot_php_min_compatibility' );
		return false;
	}

	return true;
}

/**
 * Check if GTranslate is active.
 */
function gtranslate_is_active() {
	// Check gtranslate is active.
	if ( is_plugin_active( 'gtranslate/gtranslate.php' ) ) {
		add_action( 'admin_notices', 'weglot_gtranslate_activate' );
	}
}

/**
 * Check if Polylang is active.
 */
function polylang_is_active() {
	// Check polylang is active.
	if ( is_plugin_active( 'polylang/polylang.php' ) ) {
		add_action( 'admin_notices', 'weglot_polylang_activate' );
	}
}

/**
 * Check if TranslatePress is active.
 */
function translatepress_is_active() {
	// Check TranslatePress is active.
	if ( is_plugin_active( 'translatepress-multilingual/index.php' ) ) {
		add_action( 'admin_notices', 'weglot_translatepress_activate' );
	}
}

/**
 * Check if TranslatePress is active.
 */
function wpml_is_active() {
	// Check WPML is active.
	if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
		add_action( 'admin_notices', 'weglot_wpml_activate' );
	}
}

/**
 * Admin notices if GTranslate is install and activate
 *
 * @return void
 */
function weglot_gtranslate_activate() {
	if ( ! file_exists( WEGLOT_TEMPLATES_ADMIN_NOTICES . '/gtranslate-activate.php' ) ) {
		return;
	}

	include_once WEGLOT_TEMPLATES_ADMIN_NOTICES . '/gtranslate-activate.php';
}

/**
 * Admin notices if Polylang is install and activate
 *
 * @return void
 */
function weglot_polylang_activate() {
	if ( ! file_exists( WEGLOT_TEMPLATES_ADMIN_NOTICES . '/polylang-activate.php' ) ) {
		return;
	}

	include_once WEGLOT_TEMPLATES_ADMIN_NOTICES . '/polylang-activate.php';
}

/** Admin notices if TranslatePress is install and activate
 *
 * @return void
 */
function weglot_translatepress_activate() {
	if ( ! file_exists( WEGLOT_TEMPLATES_ADMIN_NOTICES . '/translatepress-activate.php' ) ) {
		return;
	}

	include_once WEGLOT_TEMPLATES_ADMIN_NOTICES . '/translatepress-activate.php';
}


/** Admin notices if WPML is install and activate
 *
 * @return void
 */
function weglot_wpml_activate() {
	if ( ! file_exists( WEGLOT_TEMPLATES_ADMIN_NOTICES . '/wpml-activate.php' ) ) {
		return;
	}

	include_once WEGLOT_TEMPLATES_ADMIN_NOTICES . '/wpml-activate.php';
}

/**
 * Admin notices if weglot not compatible
 *
 * @return void
 */
function weglot_php_min_compatibility() {
	if ( ! file_exists( WEGLOT_TEMPLATES_ADMIN_NOTICES . '/php-min.php' ) ) {
		return;
	}

	include_once WEGLOT_TEMPLATES_ADMIN_NOTICES . '/php-min.php';
}

/**
 * Activate Weglot.
 *
 * @since 2.0
 */
function weglot_plugin_activate() {
	if ( ! weglot_is_compatible() ) {
		return;
	}

	require_once __DIR__ . '/weglot-autoload.php';
	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/weglot-compatibility.php';
	require_once __DIR__ . '/weglot-functions.php';
	require_once __DIR__ . '/bootstrap.php';

	Context_Weglot::weglot_get_context()->activate_plugin();

	$dir_wp_rocket = plugin_dir_path( __DIR__ ) . 'wp-rocket';
	if ( file_exists( $dir_wp_rocket . '/wp-rocket.php' ) ) {
		if(  weglot_get_service( 'Wprocket_Active' )->is_active()) {

			add_filter( 'rocket_htaccess_mod_rewrite', '__return_false' );
			add_filter( 'rocket_cache_mandatory_cookies', 'weglot_mandatory_cookie' );
			flush_wp_rocket();
		}
	}
}

/**
 * Deactivate Weglot.
 *
 * @since 2.0
 */
function weglot_plugin_deactivate() {
	require_once __DIR__ . '/weglot-autoload.php';
	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/weglot-compatibility.php';
	require_once __DIR__ . '/weglot-functions.php';
	require_once __DIR__ . '/bootstrap.php';

	Context_Weglot::weglot_get_context()->deactivate_plugin();

	$dir_wp_rocket = plugin_dir_path( __DIR__ ) . 'wp-rocket';
	if ( file_exists( $dir_wp_rocket . '/wp-rocket.php' ) ) {
		if(  weglot_get_service( 'Wprocket_Active' )->is_active()) {
			remove_filter( 'rocket_htaccess_mod_rewrite', '__return_true' );
			remove_filter( 'rocket_cache_mandatory_cookies', 'weglot_mandatory_cookie' );
			flush_wp_rocket();
		}
	}
}

/**
 * Uninstall Weglot.
 *
 * @since 2.0
 */
function weglot_plugin_uninstall() {
	delete_option( WEGLOT_SLUG );
}

/**
 * Rollback v2 => v1
 *
 * @return void
 */
function weglot_rollback() {
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( esc_url_raw( $_GET['_wpnonce'] ), 'weglot_rollback' ) ) {
		wp_nonce_ays( '' );
	}

	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	$plugin  = 'weglot';
	$title   = sprintf( __( '%s Update Rollback', 'weglot' ), WEGLOT_NAME );
	$nonce   = 'upgrade-plugin_' . $plugin;
	$url     = 'update.php?action=upgrade-plugin&plugin=' . rawurlencode( $plugin );
	$version = WEGLOT_LATEST_VERSION;

	$upgrader_skin = new Plugin_Upgrader_Skin( compact( 'title', 'nonce', 'url', 'plugin', 'version' ) );

	$rollback = new \WeglotWP\Helpers\Helper_Rollback_Weglot( $upgrader_skin );
	$rollback->rollback( $version );
}

/**
 * Load Weglot.
 *
 * @since 2.0
 */
function weglot_plugin_loaded() {
	require_once __DIR__ . '/weglot-autoload.php';
	require_once __DIR__ . '/weglot-compatibility.php';

	add_action( 'admin_post_weglot_rollback', 'weglot_rollback' );

	if ( weglot_is_compatible() ) {
		require_once __DIR__ . '/vendor/autoload.php';
		require_once __DIR__ . '/bootstrap.php';
		require_once __DIR__ . '/weglot-functions.php';

		weglot_init();
	}
}

register_activation_hook( __FILE__, 'weglot_plugin_activate' );
register_deactivation_hook( __FILE__, 'weglot_plugin_deactivate' );
register_uninstall_hook( __FILE__, 'weglot_plugin_uninstall' );

// Change priority to 7 if amp present
$priority = 10;
$dir_amp = plugin_dir_path( __DIR__ ) . 'amp';
if ( file_exists( $dir_amp . '/amp.php' ) ) {
	$priority = 7;
}
add_action( 'plugins_loaded', 'weglot_plugin_loaded' , $priority);


// Add registration hooks if WP Rocket present
$dir_wp_rocket = plugin_dir_path( __DIR__ ) . 'wp-rocket';
if ( file_exists( $dir_wp_rocket . '/wp-rocket.php' ) ) {
	include_once __DIR__ . '/src/third/wprocket/wp-rocket-weglot.php';
}


