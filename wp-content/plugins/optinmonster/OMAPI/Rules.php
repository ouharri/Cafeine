<?php
/**
 * Rules exception base class.
 *
 * @since 1.5.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rules class.
 *
 * @since 1.5.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */
class OMAPI_Rules {

	/**
	 * Holds the meta fields used for checking output statuses.
	 *
	 * @since 1.5.0
	 *
	 * @var array
	 */
	protected $fields = array(
		'enabled',
		'automatic',
		'users',
		'never',
		'only',
		'categories',
		'taxonomies',
		'show',
		'type',
		'test',
	);

	/**
	 * Holds the meta field values for optin.
	 *
	 * @since 1.5.0
	 *
	 * @var array
	 */
	protected $field_values = array();

	/**
	 * Whether we're checking for inline display.
	 *
	 * @since 1.5.0
	 *
	 * @var boolean
	 */
	protected $is_inline_check = false;

	/**
	 * The advanced settings for the campaign that should override inline.
	 *
	 * @since 1.6.2
	 *
	 * @var array
	 */
	protected $advanced_settings = array();

	/**
	 * The current post id.
	 *
	 * @since 1.5.0
	 *
	 * @var int
	 */
	protected $post_id = 0;

	/**
	 * The campaign post object.
	 *
	 * @since 1.5.0
	 *
	 * @var object
	 */
	protected $optin = null;

	/**
	 * The OMAPI_Rules_Exception if applicable.
	 *
	 * @var OMAPI_Rules_Exception
	 */
	protected $caught = null;

	/**
	 * Whether campaign in loop should be output globally.
	 *
	 * @since 1.5.0
	 *
	 * @var bool
	 */
	protected $global_override = true;

	/**
	 * The OMAPI_WooCommerce_Rules instance.
	 *
	 * @var OMAPI_WooCommerce_Rules
	 */
	protected static $woocommerce = null;

	/**
	 * The OMAPI_EasyDigitalDownloads_Rules instance.
	 *
	 * @var OMAPI_EasyDigitalDownloads_Rules
	 */
	protected static $edd = null;

	/**
	 * The OMAPI_MemberPress_Rules instance.
	 *
	 * @var OMAPI_MemberPress_Rules
	 */
	protected static $mp = null;

	/**
	 * The last instance called of this class.
	 *
	 * @var OMAPI_Rules
	 */
	public static $last_instance = null;

	/**
	 * Exceptions which were not caught/were ignored and may be the reason for the final exclusion.
	 *
	 * @var array
	 */
	public $reasons = array();

	/**
	 * Primary class constructor.
	 *
	 * @since 1.5.0
	 *
	 * @param  null|object $optin           The campaign post object.
	 * @param  int         $post_id         The current post id.
	 * @param  bool        $is_inline_check Whether we're checking for inline display.
	 */
	public function __construct( $optin = null, $post_id = 0, $is_inline_check = false ) {
		// Default to allowing global override if not an inline check.
		$this->optin           = $optin;
		$this->post_id         = $post_id;
		$this->is_inline_check = $is_inline_check;
		$this->global_override = ! $is_inline_check;

		// Set our object.
		$this->set();
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 2.8.0
	 */
	public function set() {
		self::$last_instance = $this;

		self::init_extensions();

		$this->fields = apply_filters( 'optin_monster_api_output_fields', $this->fields );
	}

	/**
	 * Initiates the 3rd party extension rules.
	 *
	 * @since 2.13.0
	 *
	 * @return void
	 */
	public static function init_extensions() {
		if ( self::$woocommerce ) {
			return;
		}

		self::$woocommerce = new OMAPI_WooCommerce_Rules();
		self::$edd         = new OMAPI_EasyDigitalDownloads_Rules();
		self::$mp          = new OMAPI_MemberPress_Rules();

		self::$woocommerce->init_hooks();
		self::$edd->init_hooks();
		self::$mp->init_hooks();
	}

	/**
	 * Determines if given campaign should show inline.
	 *
	 * @since  1.5.0
	 *
	 * @param  int    $post_id The current post id.
	 * @param  object $optin   The campaign post object.
	 *
	 * @return boolean|array
	 */
	public static function check_inline( $optin, $post_id = 0 ) {
		return self::check( $optin, $post_id, true );
	}

	/**
	 * Determines if given campaign should show for a shortcode.
	 *
	 * @since  1.5.0
	 *
	 * @param  int    $post_id The current post id.
	 * @param  object $optin   The campaign post object.
	 *
	 * @return boolean|array
	 */
	public static function check_shortcode( $optin, $post_id = 0 ) {
		return self::check( $optin, $post_id, 'shortcode' );
	}

	/**
	 * Determines if given campaign should show.
	 *
	 * @since  1.5.0
	 *
	 * @param  int    $post_id         The current post id.
	 * @param  object $optin           The campaign post object.
	 * @param  bool   $is_inline_check Whether we're checking for inline display.
	 *
	 * @return boolean|array
	 */
	public static function check( $optin, $post_id = 0, $is_inline_check = false ) {
		$rules = new self( $optin, $post_id, $is_inline_check );

		return $rules->should_output();
	}

	public function get_field_value( $field ) {
		return isset( $this->field_values[ $field ] )
			? $this->field_values[ $field ]
			: null;
	}

	public function field_empty( $field ) {
		return empty( $this->field_values[ $field ] );
	}

	public function field_not_empty_array( $field ) {
		return OMAPI_Utils::field_not_empty_array( $this->field_values, $field );
	}

	public function item_in_field( $item, $field ) {
		return OMAPI_Utils::item_in_field( $item, $this->field_values, $field );
	}

	public function field_is( $field, $value ) {
		return isset( $this->field_values[ $field ] ) && $value === $this->field_values[ $field ];
	}

	public function is_inline_type() {
		return OMAPI_Utils::is_inline_type( $this->get_field_value( 'type' ) );
	}

	/**
	 * Checks if current campaign is a non-shortcode inline type on a post.
	 *
	 * @since  1.6.2
	 *
	 * @return bool
	 */
	public function is_inline_post_type() {
		return $this->is_inline_check
			&& 'shortcode' !== $this->is_inline_check
			&& is_singular(
				apply_filters( 'optinmonster_automatic_inline_post_types', 'post', $this )
			);
	}

	/**
	 * Determines if given campaign should show.
	 *
	 * @since  1.5.0
	 *
	 * @return boolean|array
	 */
	public function should_output() {

		// Collect all the fields to check against.
		$this->collect_optin_fields();

		$should_output = $this->check_should_output();

		return apply_filters( 'optinmonster_pre_campaign_should_output', $should_output, $this );
	}

	/**
	 * Runs all checks to determine if given campaign should show.
	 *
	 * @since  1.5.0
	 *
	 * @return boolean|array
	 */
	protected function check_should_output() {
		try {

			$this->exclude_if_not_enabled();
			$this->exclude_on_campaign_types();
			$this->exclude_on_user_logged_in_checks();
			$this->exclude_if_inline_and_not_automatic();

			$this->default_checks();
			$this->plugin_checks();
			$this->include_if_inline_and_automatic_and_no_advanced_settings();
			$this->include_if_shortcode_and_no_advanced_settings();
			$this->output_if_global_override();

			$e = new OMAPI_Rules_False( 'default no show' );

			if ( ! empty( $this->reasons ) ) {
				$e->add_exceptions( $this->reasons );
			}

			throw $e;

		} catch ( OMAPI_Rules_Exception $e ) {

			$e = apply_filters( 'optinmonster_check_should_output', $e, $this );

			$this->caught  = $e;
			$should_output = $e instanceof OMAPI_Rules_True;
		}

		// If query var is set and user can manage OM, output debug data.
		if ( OMAPI_Debug::can_output_debug() ) {
			$this->output_rules_debug();
		}

		return $should_output;
	}

	/**
	 * Collect all the field values for an optin.
	 *
	 * @since  1.5.0
	 *
	 * @return OMAPI_Rules
	 */
	public function collect_optin_fields() {
		foreach ( $this->fields as $field ) {
			$this->field_values[ $field ] = get_post_meta( $this->optin->ID, '_omapi_' . $field, true );
		}

		return $this;
	}

	/**
	 * Excludes campaign from showing if its 'enabled' field is falsey or missing.
	 *
	 * @since  1.5.0
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function exclude_if_not_enabled() {
		if (
			// Ensure the optin is enabled and should output.
			$this->field_empty( 'enabled' )
			// Unless it's a shortcode, in which case, we don't want to check
			// if the campaign in the shortcode is "enabled".
			// This is for legacy reasons. ¯\_(ツ)_/¯
			&& 'shortcode' !== $this->is_inline_check
		) {
			throw new OMAPI_Rules_False( 'not enabled' );
		}
	}

	/**
	 * Excludes campaign from showing if its 'type' field is not an inline type
	 * and it's an inline request, or if it IS an inline type but NOT an inline request.
	 *
	 * @since  1.5.0
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function exclude_on_campaign_types() {
		// If inline check
		if ( $this->is_inline_check ) {
			// And if the type is not an inline type...
			if ( ! $this->is_inline_type() ) {
				// exclude it from outputting.
				throw new OMAPI_Rules_False( 'only inline for inline check' );
			}
		} else {
			// Ok, it's not an inline check
			// So check if the type is an inline or sidebar type
			if ( $this->field_is( 'type', 'sidebar' ) || $this->is_inline_type() ) {
				// and exclude it from outputting.
				throw new OMAPI_Rules_False( 'no inline for global check' );
			}
		}
	}

	/**
	 * Checks if campaign should be shown based on its field and if user is logged in.
	 *
	 * @since  1.5.0
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function exclude_on_user_logged_in_checks() {
		$is_logged_in = is_user_logged_in();

		// If in legacy test mode but not logged in, skip over the optin.
		if ( ! $this->field_empty( 'test' ) && ! $is_logged_in ) {
			throw new OMAPI_Rules_False( 'test mode' );
		}

		// If the optin is to be shown only to logged in users but is not logged in, pass over it.
		if ( $this->field_is( 'users', 'in' ) && ! $is_logged_in ) {
			throw new OMAPI_Rules_False( 'exclude for logged out' );
		}

		// If the optin is to be shown only to visitors but is logged in, pass over it.
		if ( $this->field_is( 'users', 'out' ) && $is_logged_in ) {
			throw new OMAPI_Rules_False( 'exclude for logged in' );
		}
	}

	/**
	 * The default checks to see if given campaign should show.
	 *
	 * @since  1.5.0
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function default_checks() {

		// Check for global disable.
		if ( get_post_meta( $this->post_id, 'om_disable_all_campaigns', true ) ) {
			$this->set_global_override( false );
			throw new OMAPI_Rules_False( "all campaigns disabled for this post ($this->post_id)" );
		}

		// Exclude posts/pages from optin display

		// Set flag for possibly not loading globally.
		if ( $this->field_not_empty_array( 'only' ) ) {
			$this->set_global_override( false );
			$this->set_advanced_settings_field( 'show', $this->get_field_value( 'only' ) );

			// If the optin is only to be shown on specific post IDs...
			if ( $this->item_in_field( $this->post_id, 'only' ) ) {
				throw new OMAPI_Rules_True( "include on only $this->post_id" );
			}
		}

		// Exclude posts/pages from optin display
		if ( $this->item_in_field( $this->post_id, 'never' ) ) {
			// No global check on purpose. Global is still true if only this setting is populated.
			throw new OMAPI_Rules_False( "exclude on never $this->post_id" );
		}

		try {
			// If the optin is only to be shown on particular categories...
			$this->check_categories_field();
		} catch ( OMAPI_Rules_Exception $e ) {
			if ( $e instanceof OMAPI_Rules_True ) {
				throw new OMAPI_Rules_True( 'include on categories', 0, $e );
			}
			$this->reasons[] = $e;
		}

		try {
			// If the optin is only to be shown on particular taxonomies...
			$this->check_taxonomies_field();
		} catch ( OMAPI_Rules_Exception $e ) {
			if ( $e instanceof OMAPI_Rules_True ) {
				throw new OMAPI_Rules_True( 'include on taxonomies', 0, $e );
			}
			$this->reasons[] = $e;
		}

		if ( $this->field_not_empty_array( 'show' ) ) {
			// Set flag for not loading globally.
			$this->set_global_override( false );
			$this->set_advanced_settings_field( 'show', $this->get_field_value( 'show' ) );
		}

		if (
			! $this->is_inline_check
			&& $this->item_in_field( 'index', 'show' ) && OMAPI_Utils::is_front_or_search()
		) {
			throw new OMAPI_Rules_True( 'is front or search and show on index' );
		}

		// Check if we should show on a selected post type.
		if ( $this->item_in_field( get_post_type(), 'show' ) && ! OMAPI_Utils::is_front_or_search() ) {
			throw new OMAPI_Rules_True( 'include on post type but not front/search' );
		}

		// Check if we should show on a selected singular post type.
		if ( $this->field_not_empty_array( 'show' ) ) {
			foreach ( $this->get_field_value( 'show' ) as $show_value ) {
				if ( 0 === strpos( $show_value, 'singular___' ) ) {
					$post_type = str_replace( 'singular___', '', $show_value );
					if ( is_singular( $post_type ) ) {
						throw new OMAPI_Rules_True( 'include on singular post type: ' . $post_type );
					}
				}
			}
		}
	}

	/**
	 * Run checks for for external plugins rules.
	 *
	 * @since 2.8.0
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function plugin_checks() {
		do_action( 'optinmonster_campaign_should_output_plugin_checks', $this );
	}

	/**
	 * Disable campaign from showing if it is being checked inline and is NOT set to automatic.
	 *
	 * @since  1.5.3
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function exclude_if_inline_and_not_automatic() {
		if (
			$this->is_inline_post_type()
			&& $this->field_empty( 'automatic' )
		) {
			throw new OMAPI_Rules_False( 'exclude inline if not automatic on singular post' );
		}
	}

	/**
	 * Enable campaign to show if it is being checked inline and is set to automatic.
	 *
	 * @since 1.6.2
	 *
	 * @throws OMAPI_Rules_True
	 * @return void
	 */
	public function include_if_inline_and_automatic_and_no_advanced_settings() {
		if (
			empty( $this->advanced_settings )
			&& $this->is_inline_post_type()
			&& ! $this->field_empty( 'automatic' )
		) {
			throw new OMAPI_Rules_True( 'include inline automatic on singular post' );
		}
	}

	/**
	 * Enable campaign to show if it is a shortcode and there are no advanced settings.
	 *
	 * @since 2.6.8
	 *
	 * @throws OMAPI_Rules_True
	 * @return void
	 */
	public function include_if_shortcode_and_no_advanced_settings() {
		if (
			empty( $this->advanced_settings )
			&& 'shortcode' === $this->is_inline_check
		) {
			throw new OMAPI_Rules_True( 'include if shortcode and no advanced settings' );
		}
	}

	/**
	 * Enable campaign to show if it's global override to show is still true.'
	 *
	 * @since  1.5.0
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function output_if_global_override() {
		if ( $this->global_override ) {
			// TODO: Track how often this occurs to determine the importance
			// of the $this->global_override logic.
			throw new OMAPI_Rules_True( 'include with global override' );
		}
	}

	protected function check_categories_field() {
		$categories = $this->get_field_value( 'categories' );
		if ( empty( $categories ) ) {
			throw new OMAPI_Rules_False( 'no categories' );
		}

		if ( $this->field_not_empty_array( 'categories' ) ) {
			// Set flag for possibly not loading globally.
			$this->set_global_override( false );
			$this->set_advanced_settings_field( 'categories', $categories );
		}

		// If this is the home page, check to see if they have decided to load on certain archive pages.
		// Run a check for archive-type pages.
		// If showing on home and we are on an index page, show the optin.
		// TODO: this originally checked is_front_page() || is_home() || is_archive() || is_search()
		// but only is_home() would work originally (https://github.com/awesomemotive/optin-monster-wp-api/blame/948b74254284a57f1a338dfc70bc6db59ed3ce8b/OMAPI/Output.php#L407).
		// Maybe reintroduce other conditions?
		$this->check_is_home_and_show_on_index();

		// TODO: Add check for: "Check if we should show on a selected post type."
		// This was in the old logic (https://github.com/awesomemotive/optin-monster-wp-api/blame/948b74254284a57f1a338dfc70bc6db59ed3ce8b/OMAPI/Output.php#L385-L392)
		// But has not worked for 3+ years as the is_home() condition was precluding it.
		// Also applies to logic in check_taxonomies_field
		// if ( in_array( 'post', (array) $fields['show'] ) && ! ( is_front_page() || is_home() || is_archive() || is_search() ) ) {
		// $omapi_output->set_slug( $optin );
		// return $html;
		// }

		if ( $this->post_id ) {
			$all_cats = get_the_category( $this->post_id );

			if ( ! empty( $all_cats ) ) {
				foreach ( $all_cats as $term ) {
					$has_term = in_array( $term->term_id, $categories );

					if ( ! $has_term ) {
						continue;
					}

					if ( ! $this->is_inline_check ) {
						throw new OMAPI_Rules_True( "post has category $term->name" );
					}

					if ( ! is_archive() ) {
						throw new OMAPI_Rules_True( "post has category $term->name & is not archive" );
					}
				}
			}
		}

		if ( ! $this->is_inline_check && is_category( $categories ) ) {
			throw new OMAPI_Rules_True( 'post on category' );
		}

		$tax = get_taxonomy( 'category' );
		if (
			! empty( $tax->object_type )
			&& ! in_array( get_post_type( $this->post_id ), $tax->object_type, true )
		) {

			unset( $this->advanced_settings['categories'] );
			throw new OMAPI_Rules_False(
				sprintf(
					'categories not associated with this post-type (%s).',
					get_post_type( $this->post_id )
				)
			);
		}

		throw new OMAPI_Rules_False( 'no category matches found' );
	}

	protected function check_taxonomies_field() {
		$taxonomies = $this->get_field_value( 'taxonomies' );
		if ( empty( $taxonomies ) ) {
			throw new OMAPI_Rules_False( 'no taxonomies' );
		}

		$values = $this->field_not_empty_array( 'taxonomies' );
		if ( $values ) {
			foreach ( $values as $i => $value ) {
				if ( OMAPI_Utils::field_not_empty_array( $values, $i ) ) {
					$this->set_global_override( false );
					$this->set_advanced_settings_field( 'taxonomies', $taxonomies );
					break;
				}
			}
		}

		// If the optin is only to be shown on particular taxonomies...
		if ( $this->is_inline_check && ! is_singular() ) {
			throw new OMAPI_Rules_False( 'not singular template' );
		}

		// If this is the home page, check to see if they have decided to load on certain archive pages.
		// Run a check for archive-type pages.
		// If showing on index pages and we are on an index page, show the optin.
		// TODO: potentially move this above check_taxonomies_field && check_categories_field
		$this->check_is_home_and_show_on_index();

		foreach ( $taxonomies as $taxonomy => $ids_to_check ) {
			// Tags are saved differently.
			// https://github.com/awesomemotive/optin-monster-wp-api/issues/104
			if ( ! empty( $ids_to_check[0] ) && false !== strpos( $ids_to_check[0], ',' ) ) {
				$ids_to_check = explode( ',', (string) $ids_to_check[0] );
			}

			$ids_to_check = (array) $ids_to_check;

			if ( $this->post_id ) {
				$tax       = get_taxonomy( $taxonomy );
				$post_type = get_post_type( $this->post_id );

				$valid = ! empty( $tax->object_type ) && in_array( $post_type, $tax->object_type, true );

				if ( $valid ) {
					$all_terms = get_the_terms( $this->post_id, $taxonomy );
					if ( ! empty( $all_terms ) ) {
						foreach ( $all_terms as $term ) {
							// TODO: determine why this logic is different than in check_categories_field.
							if ( in_array( $term->term_id, $ids_to_check ) ) {
								throw new OMAPI_Rules_True( "{$post_type} has {$taxonomy} {$term->name}" );
							}
						}
					}
				} else {
					unset( $this->advanced_settings['taxonomies'][ $taxonomy ] );
				}
			}

			if ( ! $this->is_inline_check ) {
				foreach ( $ids_to_check as $tax_id ) {
					if ( OMAPI_Utils::is_term_archive( $tax_id, $taxonomy ) ) {
						throw new OMAPI_Rules_True( "not inline and is on $taxonomy archive" );
					}
				}
			}
		}

		if ( isset( $this->advanced_settings['taxonomies'] ) ) {
			$taxonomies = array_filter( $this->advanced_settings['taxonomies'] );
			if ( empty( $taxonomies ) ) {
				unset( $this->advanced_settings['taxonomies'] );
				throw new OMAPI_Rules_False(
					sprintf(
						'taxonomies not associated with this post-type (%s).',
						get_post_type( $this->post_id )
					)
				);
			}
		}

		throw new OMAPI_Rules_False( 'no taxonomy matches found' );
	}

	protected function check_is_home_and_show_on_index() {
		if ( is_home() && $this->item_in_field( 'index', 'show' ) ) {
			throw new OMAPI_Rules_True( 'is_home and show on index' );
		}
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  1.5.0
	 *
	 * @param  string $property
	 * @throws Exception Throws an exception if the field is invalid.
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		switch ( $property ) {
			case 'is_inline_check':
			case 'post_id':
			case 'optin':
			case 'fields':
			case 'field_values':
			case 'caught':
			case 'global_override':
			case 'advanced_settings':
				return $this->$property;
			case 'woocommerce':
			case 'edd':
			case 'mp':
				return self::$$property;
			default:
				break;
		}

		throw new Exception( sprintf( esc_html__( 'Invalid %1$s property: %2$s', 'optin-monster-api' ), __CLASS__, $property ) );
	}

	/**
	 * Add a new reason to the reasons array
	 *
	 * @since 2.8.0
	 *
	 * @param  mixed $reason The reason to add
	 *
	 * @return self
	 */
	public function add_reason( $reason ) {
		 $this->reasons[] = $reason;

		return $this;
	}

	/**
	 * Setter for $advanced_settings attribute
	 *
	 * @since 2.8.0
	 *
	 * @param  string $field The advanced settings field to set
	 * @param  string $value The field value
	 *
	 * @return self
	 */
	public function set_advanced_settings_field( $field, $value ) {
		 $this->advanced_settings[ $field ] = $value;

		return $this;
	}

	/**
	 * Setter for $global_override attribute
	 *
	 * @since 2.8.0
	 *
	 * @param  bool $value The new value
	 *
	 * @return self
	 */
	public function set_global_override( $value ) {
		 $this->global_override = $value;

		return $this;
	}

	/**
	 * Outputs some debug data for the current campaign object.
	 *
	 * @since  1.6.2
	 *
	 * @return void
	 */
	protected function output_rules_debug() {
		$show    = $this->caught instanceof OMAPI_Rules_True;
		$reasons = $this->caught->get_exception_messages();

		?>
		<xmp class="_om-debugging _om-campaign-sep"><?php echo esc_html( str_repeat( '-', 10 ) . $this->optin->post_name . str_repeat( '-', 10 ) ); ?></xmp>
		<xmp class="_om-debugging _om-post-id">$post_id: <?php echo esc_html( print_r( $this->post_id, true ) ); ?></xmp>
		<xmp class="_om-debugging _om-post-id">$debug_setting_enabled: <?php echo esc_html( print_r( OMAPI::get_instance()->get_option( 'api', 'omwpdebug' ), true ) ); ?></xmp>
		<xmp class="_om-debugging _om-campaign-status" style="color: <?php echo ( $show ? 'green' : 'red' ); ?>;"><?php echo esc_html( $this->optin->post_name . ":\n" . print_r( $this->caught->getMessage(), true ) ); ?><?php echo ! empty( $reasons ) ? ":\n\t- " . implode( "\n\t- ", array_map( 'esc_html', $reasons ) ) : ''; ?>
		</xmp>
		<?php if ( ! empty( $this->advanced_settings ) ) { ?>
			<xmp class="_om-debugging _om-advanced-settings">$advanced_settings: <?php print_r( $this->advanced_settings ); ?></xmp>
		<?php } ?>
		<?php if ( ! empty( $this->field_values ) ) { ?>
			<xmp class="_om-debugging _om-field-values" style="display:none;">$field_values: <?php print_r( $this->field_values ); ?></xmp>
		<?php } ?>
		<xmp class="_om-debugging _om-is-inline-check" style="display:none;">$is_inline_check?: <?php echo esc_html( print_r( $this->is_inline_check, true ) ); ?></xmp>
		<xmp class="_om-debugging _om-global-override" style="display:none;">$global_override?: <?php echo esc_html( print_r( $this->global_override, true ) ); ?></xmp>
		<xmp class="_om-debugging _om-optin" style="display:none;">$optin: <?php print_r( $this->optin ); ?></xmp>
		<?php
	}
}
