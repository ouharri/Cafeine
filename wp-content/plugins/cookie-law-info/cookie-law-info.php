<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.cookieyes.com/
 * @since             1.6.6
 * @package           Cookie_Law_Info
 *
 * @wordpress-plugin
 * Plugin Name:       CookieYes | GDPR Cookie Consent
 * Plugin URI:        https://www.cookieyes.com/
 * Description:       A simple way to show your website complies with the EU Cookie Law / GDPR.
 * Version:           3.0.8
 * Author:            CookieYes
 * Author URI:        https://www.cookieyes.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       cookie-law-info
 */

/*
	Copyright 2018  WebToffee

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CLI_VERSION', '3.0.8' );
define( 'CLI_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'CLI_PLUGIN_BASEPATH', plugin_dir_path( __FILE__ ) );
define( 'CLI_SETTINGS_FIELD', 'CookieLawInfo-0.9' );
// Previous version settings (deprecated from 0.9 onwards).
define( 'CLI_ADMIN_OPTIONS_NAME', 'CookieLawInfo-0.8.3' );
define( 'CLI_PLUGIN_FILENAME', __FILE__ );
define( 'CLI_POST_TYPE', 'cookielawinfo' );
define( 'CLI_DEFAULT_LANGUAGE', cky_set_default_language() );

/** CookieYes web app URL */
if ( ! defined( 'CKY_APP_URL' ) ) {
	define( 'CKY_APP_URL', 'https://app.cookieyes.com' );
}

/** CookieYes web app script cdn URL. */
if ( ! defined( 'CKY_APP_CDN_URL' ) ) {
	define( 'CKY_APP_CDN_URL', 'https://cdn-cookieyes.com' );
}

/**
 * Load and set default language of the site.
 *
 * @return string
 */
function cky_set_default_language() {
	$default = get_option( 'WPLANG', 'en_US' );
	if ( empty( $default ) || strlen( $default ) <= 1 ) {
		$default = 'en';
	}
	return substr( $default, 0, 2 );
}

/**
 * Add an upgrade notice whenever an update is available.
 *
 * @param array $data Upgrade data.
 * @param array $response Upgrade response data.
 * @return void
 */
function cky_upgrade_notice( $data, $response ) {
	if ( isset( $data['upgrade_notice'] ) ) {
		add_action( 'admin_print_footer_scripts', 'cky_upgrade_notice_js' );
		$msg = str_replace( array( '<p>', '</p>' ), array( '<div>', '</div>' ), $data['upgrade_notice'] );
		echo '<style type="text/css">
        #cookie-law-info-update .update-message p:last-child{ display:none;}     
        #cookie-law-info-update ul{ list-style:disc; margin-left:30px;}
        .cky-upgrade-notice{ padding-left:30px;}
        </style>
        <div class="update-message cky-upgrade-notice"><div style="color: #f56e28;">' . esc_html__( 'Please make sure the cache is cleared after each plugin update especially if you have minified JS and/or CSS files.', 'cookie-law-info' ) . '</div>' . wp_kses_post( wpautop( $msg ) ) . '</div>';
	}
}

/**
 * Javascript for handling upgrade notice.
 *
 * @return void
 */
function cky_upgrade_notice_js() {     ?>
		<script>
			( function( $ ){
				var update_dv=$( '#cookie-law-info-update ');
				update_dv.find('.cky-upgrade-notice').next('p').remove();
				update_dv.find('a.update-link:eq(0)').click(function(){
					$('.cky-upgrade-notice').remove();
				});
			})( jQuery );
		</script>
		<?php
}

add_action( 'in_plugin_update_message-cookie-law-info/cookie-law-info.php', 'cky_upgrade_notice', 10, 2 );

/**
 * Return internal DB version.
 *
 * @return string
 */
function cky_get_consent_db_version() {
	return get_option( 'cky_cookie_consent_lite_db_version', '2.0' );
}

/**
 * Check if plugin is in legacy version.
 *
 * @return boolean
 */
function cky_is_legacy() {
	$current_version = cky_get_consent_db_version();
	if ( empty( get_option( CLI_SETTINGS_FIELD, array() ) ) || ( ! is_null( $current_version ) && version_compare( $current_version, '2.0', '>' ) === true ) ) {
		return false;
	} else {
		return true;
	}
}

if ( cky_is_legacy() ) {
	require_once CLI_PLUGIN_BASEPATH . 'legacy/loader.php';
} else {
	require_once CLI_PLUGIN_BASEPATH . 'lite/loader.php';
}
