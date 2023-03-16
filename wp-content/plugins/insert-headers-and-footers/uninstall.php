<?php
/**
 * Uninstall WPCode.
 *
 * Remove:
 * - custom capabilities.
 *
 * @package WPCode
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// If the function already exists we shouldn't run the uninstall as another version of the plugin is active.
if ( function_exists( 'WPCode' ) ) {
	return;
}

require_once 'ihaf.php';

if ( class_exists( 'WPCode_Capabilities' ) ) {
	// Remove custom capabilities on uninstall.
	WPCode_Capabilities::uninstall();
}

if ( class_exists( 'WPCode_Notifications' ) ) {
	WPCode_Notifications::delete_notifications_data();
}
