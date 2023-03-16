<?php
/**
 * Central handler of auto-inserting snippets.
 * Loads the different types and processes them.
 *
 * @package WPCode
 */

/**
 * Class WPCode_Auto_Insert.
 */
class WPCode_Auto_Insert {

	/**
	 * The auto-insert types.
	 *
	 * @var array
	 */
	public $types = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Add hooks.
	 *
	 * @return void
	 */
	private function hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_types' ), 1 );
	}

	/**
	 * Load and initialize the different types of auto-insert types.
	 *
	 * @return void
	 */
	public function load_types() {
		require_once WPCODE_PLUGIN_PATH . 'includes/auto-insert/class-wpcode-auto-insert-type.php';
		require_once WPCODE_PLUGIN_PATH . 'includes/auto-insert/class-wpcode-auto-insert-everywhere.php';
		require_once WPCODE_PLUGIN_PATH . 'includes/auto-insert/class-wpcode-auto-insert-site-wide.php';
		require_once WPCODE_PLUGIN_PATH . 'includes/auto-insert/class-wpcode-auto-insert-single.php';
		require_once WPCODE_PLUGIN_PATH . 'includes/auto-insert/class-wpcode-auto-insert-archive.php';
	}

	/**
	 * Register an auto-insert type.
	 *
	 * @param WPCode_Auto_Insert_Type $type The type to add to the available types.
	 *
	 * @return void
	 */
	public function register_type( $type ) {
		$this->types[] = $type;
	}

	/**
	 * Get the types of auto-insert options.
	 *
	 * @return WPCode_Auto_Insert_Type[]
	 */
	public function get_types() {
		return $this->types;
	}

	/**
	 * Get a location label from the class not the term.
	 *
	 * @param string $location The location slug/name.
	 *
	 * @return string
	 */
	public function get_location_label( $location ) {
		foreach ( $this->types as $type ) {
			/**
			 * Added for convenience.
			 *
			 * @var WPCode_Auto_Insert_Type $type
			 */
			if ( isset( $type->locations[ $location ] ) ) {
				return $type->locations[ $location ];
			}
		}

		return '';
	}
}
