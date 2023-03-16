<?php
/**
 * Rules Base class.
 *
 * @since 2.13.0
 *
 * @package OMAPI
 * @author  Jutin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OMAPI_Rules_Base class.
 *
 * @since 2.13.0
 */
abstract class OMAPI_Rules_Base {

	/**
	 * Holds the meta fields used for checking output statuses.
	 *
	 * @since 2.13.0
	 *
	 * @var array
	 */
	protected $fields = array();

	/**
	 * Holds the main Rules class instance.
	 *
	 * @since 2.13.0
	 *
	 * @var OMAPI_Rules
	 */
	public $rules;

	/**
	 * Initiates hooks
	 *
	 * @since 2.13.0
	 */
	public function init_hooks() {
		add_filter( 'optin_monster_api_output_fields', array( $this, 'merge_fields' ), 9 );
		add_action( 'optinmonster_campaign_should_output_plugin_checks', array( $this, 'set_rules_and_run_checks' ), 9 );
	}

	/**
	 * Getter for fields property
	 *
	 * @since 2.13.0
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Merge fields array with the fields for this rules object.
	 *
	 * @since 2.13.0
	 *
	 * @param  array $fields The meta fields used for checking output statuses.
	 *
	 * @return array
	 */
	public function merge_fields( $fields = array() ) {
		return array_merge( $fields, $this->get_fields() );
	}

	/**
	 * Sets the rules object, then runs rule checks.
	 *
	 * @since 2.13.0
	 *
	 * @param  OMAPI_Rules $rules The OMAPI_Rules object.
	 *
	 * @throws OMAPI_Rules_False|OMAPI_Rules_True
	 * @return void
	 */
	public function set_rules_and_run_checks( $rules ) {
		$this->rules = $rules;
		$this->run_checks();
	}

	/**
	 * Runs rule checks.
	 *
	 * @since 2.13.0
	 *
	 * @throws OMAPI_Rules_False|OMAPI_Rules_True
	 * @return void
	 */
	abstract public function run_checks();
}
