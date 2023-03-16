<?php
/**
 * MemberPress Courses Addon class.
 *
 * @since 2.13.0
 *
 * @package OMAPI
 * @author  Eduardo Nakatsuka
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MemberPress Courses Addon class.
 *
 * @since 2.13.0
 */
class OMAPI_MemberPress_Courses {

	/**
	 * Holds the OMAPI_MemberPress_Courses class object.
	 *
	 * @since 2.13.0
	 *
	 * @var OMAPI_MemberPress_Courses
	 */
	public static $instance;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.13.0
	 *
	 * @var OMAPI
	 */
	public $base;

	/**
	 * The OMAPI_MemberPress instance.
	 *
	 * @since 2.13.0
	 *
	 * @var OMAPI_MemberPress
	 */
	public $mp;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.13.0
	 */
	public function __construct( OMAPI_MemberPress $mp ) {
		$this->mp = $mp;
	}

	/**
	 * Adds the addon data to the `memberpress` object.
	 *
	 * @since 2.13.0
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'courses'          => $this->get_entities( 'mpcs-course' ),
			'lessons'          => $this->get_entities( 'mpcs-lesson' ),
			'quizzes'          => $this->get_entities( 'mpcs-quiz' ),
			'wpFooterDisabled' => $this->is_memberpress_wp_footer_disabled(),
		);
	}

	/**
	 * Check to see if MemberPress courses are active and, if so, check if
	 * the WP footer hook is enabled.
	 *
	 * @since 2.13.0
	 *
	 * @return bool
	 */
	public function is_memberpress_wp_footer_disabled() {
		return class_exists( '\\memberpress\\courses\\helpers\\App' )
			? ! \memberpress\courses\helpers\App::is_classroom_wp_footer()
			: false;
	}

	/**
	 * Check if the MemberPress Courses addon is active.
	 *
	 * @since 2.13.0
	 *
	 * @return bool
	 */
	public static function is_active() {
		return defined( 'memberpress\courses\PLUGIN_SLUG' )
			&& class_exists( '\\memberpress\\courses\\models\\Course', true );
	}

	/**
	 * Retrieve MemberPress data.
	 *
	 * @since 2.13.0
	 *
	 * @param string $type ['mpcs-course', 'mpcs-lesson', 'mpcs-quiz']
	 * @return array       Retrieved data array
	 */
	private function retrieve_data( $type ) {
		// Bail if MemberPress and addons aren't currently active.
		if ( ! $this->mp->is_active() || ! self::is_active() ) {
			return array();
		}

		$args = array(
			'post_type'   => $type,
			'post_status' => array( 'publish', 'draft', 'future' ),
		);

		$query = new \WP_Query( $args );
		$data  = $query->get_posts();

		return $data;
	}

	/**
	 * Get the given MemberPress entities.
	 *
	 * @since 2.13.0
	 *
	 * @return array
	 */	
	public function get_entities( $slug ) {
		return $this->mp->format_data( $this->retrieve_data( $slug ) );		
	}
}
