<?php
/**
 * Class to auto-insert snippets site-wide.
 *
 * @package wpcode
 */

/**
 * Class WPCode_Auto_Insert_Single.
 */
class WPCode_Auto_Insert_Everywhere extends WPCode_Auto_Insert_Type {

	/**
	 * This should is only available for PHP scripts.
	 *
	 * @var string
	 */
	public $code_type = 'php';

	/**
	 * Load the available options and labels.
	 *
	 * @return void
	 */
	public function init() {
		$this->label     = __( 'PHP Snippets Only', 'insert-headers-and-footers' );
		$this->locations = array(
			'everywhere'    => __( 'Run Everywhere', 'insert-headers-and-footers' ),
			'frontend_only' => __( 'Frontend Only', 'insert-headers-and-footers' ),
			'admin_only'    => __( 'Admin Only', 'insert-headers-and-footers' ),
			'frontend_cl'   => __( 'Frontend Conditional Logic', 'insert-headers-and-footers' ),
		);
	}

	/**
	 * Execute snippets.
	 *
	 * @return void
	 */
	public function run_snippets() {
		$snippets = $this->get_snippets_for_location( 'everywhere' );
		foreach ( $snippets as $snippet ) {
			wpcode()->execute->get_snippet_output( $snippet );
		}
		$location = is_admin() ? 'admin_only' : 'frontend_only';
		$snippets = $this->get_snippets_for_location( $location );
		foreach ( $snippets as $snippet ) {
			wpcode()->execute->get_snippet_output( $snippet );
		}
	}

	/**
	 * Execute snippets on the init hook to allow using more Conditional Logic options.
	 *
	 * @return void
	 */
	public function run_init_snippets() {
		$snippets = $this->get_snippets_for_location( 'frontend_cl' );
		foreach ( $snippets as $snippet ) {
			wpcode()->execute->get_snippet_output( $snippet );
		}
	}

	/**
	 * Override the default hook and short-circuit any other conditions
	 * checks as these snippets will run everywhere.
	 *
	 * @return void
	 */
	protected function add_start_hook() {
		add_action( 'plugins_loaded', array( $this, 'run_snippets' ), 5 );
		add_action( 'wp', array( $this, 'run_init_snippets' ) );
	}
}

new WPCode_Auto_Insert_Everywhere();
