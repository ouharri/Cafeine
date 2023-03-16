<?php
/**
 * The factory logic for creating modules for plugin.
 *
 * @link       https://www.sktthemes.org
 * @since      1.0.0
 *
 * @package    Skt_Templates
 * @subpackage Skt_Templates/app
 */

class Skt_Templates_Module_Factory {

	/**
	 * The build method for creating a new SKTB_Module class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $module_name The name of the module to instantiate.
	 * @return mixed
	 */
	public static function build( $module_name ) {
		$module = str_replace( '-', '_', ucwords( $module_name ) ) . '_SKTB_Module';
		if ( class_exists( $module ) ) {
			return new $module();
		}
		return false;
	}
}
