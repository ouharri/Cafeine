<?php
/**
 * Type class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Type class.
 *
 * @since 1.0.0
 */
class OMAPI_Type {

	/**
	 * The Post-type slug.
	 */
	const SLUG = 'omapi';

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Load actions and filters.
		$this->type();
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Loads the OptinMonster API post type.
	 *
	 * @since 1.0.0
	 */
	public function type() {

		register_post_type(
			self::SLUG,
			array(
				'labels'          => apply_filters(
					'optin_monster_api_post_type_labels',
					array(
						'name'               => _x( 'Campaigns', 'post type general name', 'optin-monster-api' ),
						'singular_name'      => _x( 'Campaign', 'post type singular name', 'optin-monster-api' ),
						'add_new'            => esc_html__( 'Add New', 'optin-monster-api' ),
						'add_new_item'       => esc_html__( 'Add New Campaign', 'optin-monster-api' ),
						'edit_item'          => esc_html__( 'Edit Campaign', 'optin-monster-api' ),
						'new_item'           => esc_html__( 'New Campaign', 'optin-monster-api' ),
						'all_items'          => esc_html__( 'Campaigns', 'optin-monster-api' ),
						'view_item'          => esc_html__( 'View Campaign', 'optin-monster-api' ),
						'search_items'       => esc_html__( 'Search Campaigns', 'optin-monster-api' ),
						'not_found'          => esc_html__( 'No Campaigns found', 'optin-monster-api' ),
						'not_found_in_trash' => esc_html__( 'No Campaigns found in trash', 'optin-monster-api' ),
						'parent_item_colon'  => '',
						'menu_name'          => esc_html__( 'Campaigns', 'optin-monster-api' ),
					)
				),
				'public'          => false,
				'rewrite'         => false,
				'capability_type' => 'post',
				'has_archive'     => false,
				'hierarchical'    => false,
				'supports'        => array( 'title' ),
			)
		);
	}
}
