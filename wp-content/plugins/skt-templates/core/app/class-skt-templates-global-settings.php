<?php
/**
 * The global settings of the plugin.
 *
 * @link       https://www.sktthemes.org
 * @since      1.0.0
 *
 * @package    Skt_Templates
 * @subpackage Skt_Templates/app
 */

class Skt_Templates_Global_Settings {

	/**
	 * The main instance var.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Skt_Templates_Global_Settings $instance The instance of this class.
	 */
	public static $instance;

	/**
	 * Stores the default modules data.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     array $modules Modules List.
	 */
	public $modules = array();

	/**
	 * Stores an array of module objects.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     array $module_objects Stores references to modules Objects.
	 */
	public $module_objects = array();

	/**
	 * The instance method for the static class.
	 * Defines and returns the instance of the static class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return Skt_Templates_Global_Settings
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Skt_Templates_Global_Settings ) ) {
			self::$instance          = new Skt_Templates_Global_Settings();
			self::$instance->modules = apply_filters(
				'mods',
				array(
					'template-directory',
				)
			);
		}// End if().

		return self::$instance;
	}

	/**
	 * Registers a module object reference in the $module_objects array.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string                    $name The name of the module from $modules array.
	 * @param   Skt_Templates_Module_Abstract $module The module object.
	 */
	public function register_module_reference( $name, Skt_Templates_Module_Abstract $module ) {
		self::$instance->module_objects[ $name ] = $module;
	}

	/**
	 * Method to retrieve instance of modules.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function get_modules() {
		return self::instance()->modules;
	}

	/**
	 * Method to destroy singleton.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public static function destroy_instance() {
		static::$instance = null;
	}
}
