<?php
/**
 * Output class.
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
 * Output class.
 *
 * @since 1.0.0
 */
class OMAPI_Output {

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
	 * Holds the meta fields used for checking output statuses.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * Flag for determining if localized JS variable is output.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public $localized = false;

	/**
	 * Flag for determining if localized JS variable is output.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public $data_output = false;

	/**
	 * Holds JS slugs for maybe parsing shortcodes.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $slugs = array();

	/**
	 * Holds shortcode output.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $shortcodes = array();

	/**
	 * Whether we are in a live campaign preview.
	 *
	 * @since 2.2.0
	 *
	 * @var boolean
	 */
	protected static $live_preview = false;

	/**
	 * Whether we are in a live campaign rules preview.
	 *
	 * @since 2.2.0
	 *
	 * @var boolean
	 */
	protected static $live_rules_preview = false;

	/**
	 * Whether we are in a site verification request.
	 *
	 * @since 2.2.0
	 *
	 * @var boolean
	 */
	protected static $site_verification = false;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		add_filter( 'optinmonster_pre_campaign_should_output', array( $this, 'enqueue_helper_js_if_applicable' ), 999, 2 );

		// If no credentials have been provided, do nothing.
		if ( ! $this->base->get_api_credentials() ) {
			return;
		}

		// Add the hook to allow OptinMonster to process.
		add_action( 'pre_get_posts', array( $this, 'load_optinmonster_inline' ), 9999 );
		add_action( 'wp', array( $this, 'maybe_load_optinmonster' ), 9999 );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {

		self::$instance = $this;
		$this->base     = OMAPI::get_instance();

		$rules = new OMAPI_Rules();

		if ( OMAPI_Debug::can_output_debug() ) {
			add_action( 'wp_footer', array( 'OMAPI_Debug', 'output_general' ), 99 );
		}

		// Keep these around for back-compat.
		$this->fields = $rules->fields;

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		self::$live_preview       = ! empty( $_GET['om-live-preview'] )
			? wp_unslash( $_GET['om-live-preview'] )
			: false;
		self::$live_rules_preview = ! empty( $_GET['om-live-rules-preview'] )
			? wp_unslash( $_GET['om-live-rules-preview'] )
			: false;
		self::$site_verification  = ! empty( $_GET['om-verify-site'] )
			? wp_unslash( $_GET['om-verify-site'] )
			: false;
		// phpcs:enable
	}

	/**
	 * Conditionally loads the OptinMonster optin based on the query filter detection.
	 *
	 * @since 1.0.0
	 */
	public function maybe_load_optinmonster() {

		// Checking if AMP is enabled.
		if ( OMAPI_Utils::is_amp_enabled() ) {
			return;
		}

		// Load actions and filters.
		add_action( 'wp_enqueue_scripts', array( $this, 'api_script' ) );
		add_action( 'wp_footer', array( $this, 'localize' ), 9999 );
		add_action( 'wp_footer', array( $this, 'display_rules_data' ), 9999 );
		add_action( 'wp_footer', array( $this, 'maybe_parse_shortcodes' ), 11 );

		// Add the hook to allow OptinMonster to process.
		add_action( 'wp_footer', array( $this, 'load_optinmonster' ) );

		if ( self::$live_preview || self::$live_rules_preview ) {
			add_filter( 'optin_monster_api_final_output', array( $this, 'load_previews' ), 10, 2 );
			add_filter( 'optin_monster_api_empty_output', array( $this, 'load_previews' ), 10, 2 );
		}

		if ( self::$live_preview || self::$site_verification ) {
			add_action( 'wp_footer', array( $this, 'load_global_optinmonster' ) );
		}
	}

	/**
	 * Enqueues the OptinMonster API script.
	 *
	 * @since 1.0.0
	 */
	public function api_script() {

		// A hook to change the API location. Using this hook, we can force to load in header; default location is footer.
		$in_footer = apply_filters( 'optin_monster_api_loading_location', true );

		wp_enqueue_script(
			$this->base->plugin_slug . '-api-script',
			OMAPI_Urls::om_api(),
			array(),
			$this->base->asset_version(),
			$in_footer
		);

		if ( version_compare( get_bloginfo( 'version' ), '4.1.0', '>=' ) ) {
			add_filter( 'script_loader_tag', array( $this, 'filter_api_script' ), 10, 2 );
		} else {
			add_filter( 'clean_url', array( $this, 'filter_api_url' ) );
		}

	}

	/**
	 * Filters the API script tag to output the JS version embed and to add a custom ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag    The HTML script output.
	 * @param string $handle The script handle to target.
	 * @return string $tag   Amended HTML script with our ID attribute appended.
	 */
	public function filter_api_script( $tag, $handle ) {

		// If the handle is not ours, do nothing.
		if ( $this->base->plugin_slug . '-api-script' !== $handle ) {
			return $tag;
		}

		// Adjust the output to the JS version embed and to add our custom script ID.
		return self::om_script_tag(
			array(
				'id' => 'omapi-script',
			)
		);
	}

	/**
	 * Filters the API script tag to add a custom ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url  The URL to filter.
	 * @return string $url Amended URL with our ID attribute appended.
	 */
	public function filter_api_url( $url ) {
		// If the handle is not ours, do nothing.
		if ( false === strpos( $url, str_replace( 'https://', '', OMAPI_Urls::om_api() ) ) ) {
			return $url;
		}

		// Adjust the URL to add our custom script ID.
		return "$url' async='async' id='omapi-script";

	}

	/**
	 * Loads an inline optin form (sidebar and after post) by checking against the current query.
	 *
	 * @since 1.0.0
	 *
	 * @param object $query The current main WP query object.
	 */
	public function load_optinmonster_inline( $query ) {

		// If we are not on the main query or if in an rss feed, do nothing.
		if ( ! $query->is_main_query() || $query->is_feed() ) {
			return;
		}

		$priority = apply_filters( 'optin_monster_post_priority', 999 ); // Deprecated.
		$priority = apply_filters( 'optin_monster_api_post_priority', 999 );
		add_filter( 'the_content', array( $this, 'load_optinmonster_inline_content' ), $priority );

	}

	/**
	 * Filters the content to output a campaign form.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content  The current HTML string of main content.
	 * @return string $content Amended content with possibly a campaign.
	 */
	public function load_optinmonster_inline_content( $content ) {
		global $post;

		// Checking if AMP is enabled.
		if ( OMAPI_Utils::is_amp_enabled() ) {
			return $content;
		}

		// If the global $post is not set or the post status is not published, return early.
		if ( empty( $post ) || isset( $post->ID ) && 'publish' !== get_post_status( $post->ID ) ) {
			return $content;
		}

		// Don't do anything for excerpts.
		// This prevents the optin accidentally being output when get_the_excerpt() or wp_trim_excerpt() is
		// called by a theme or plugin, and there is no excerpt, meaning they call the_content and break us.
		if (
			doing_filter( 'get_the_excerpt' ) ||
			doing_filter( 'wp_trim_excerpt' )
		) {
			return $content;
		}

		// Prepare variables.
		$post_id = self::current_id();
		$optins  = $this->base->get_optins();

		// If no optins are found, return early.
		if ( empty( $optins ) ) {
			return $content;
		}

		// Loop through each optin and optionally output it on the site.
		foreach ( $optins as $optin ) {
			if ( OMAPI_Rules::check_inline( $optin, $post_id, true ) ) {
				$this->set_slug( $optin );

				// Prepare the optin campaign.
				$prepared = $this->prepare_campaign( $optin );
				$position = get_post_meta( $optin->ID, '_omapi_auto_location', true );
				$inserter = new OMAPI_Inserter( $content, $prepared );

				switch ( $position ) {
					case 'paragraphs':
						$paragraphs = get_post_meta( $optin->ID, '_omapi_auto_location_paragraphs', true );
						$content    = $inserter->after_paragraph( absint( $paragraphs ) );
						break;

					case 'words':
						$words   = get_post_meta( $optin->ID, '_omapi_auto_location_words', true );
						$content = $inserter->after_words( absint( $words ) );
						break;

					case 'above_post':
						$content = $inserter->prepend();
						break;

					case 'below_post':
					default:
						$content = $inserter->append();
						break;
				}
			}
		}

		// Return the content.
		return $content;

	}

	/**
	 * Possibly loads a campaign on a page.
	 *
	 * @since 1.0.0
	 */
	public function load_optinmonster() {
		$post_id = self::current_id();

		$prevented = is_singular() && $post_id && get_post_meta( $post_id, 'om_disable_all_campaigns', true );
		$prevented = apply_filters( 'optinmonster_prevent_all_campaigns', $prevented, $post_id );
		if ( $prevented ) {
			add_action( 'wp_footer', array( $this, 'prevent_all_campaigns' ), 11 );
		}

		$optins    = $prevented ? array() : $this->base->get_optins();
		$campaigns = array();

		if ( empty( $optins ) ) {

			// If no optins are found, send through filter to potentially add preview data.
			$campaigns = apply_filters( 'optin_monster_api_empty_output', $campaigns, $post_id );

		} else {
			// Loop through each optin and optionally output it on the site.
			foreach ( $optins as $campaign ) {
				$rules = new OMAPI_Rules( $campaign, $post_id );

				if ( $rules->should_output() ) {
					$this->set_slug( $campaign );

					// Prepare the optin campaign.
					$campaigns[ $campaign->post_name ] = $this->prepare_campaign( $campaign );
					continue;
				}

				$fields = $rules->field_values;

				// Allow devs to filter the final output for more granular control over optin targeting.
				// Devs should return the value for the slug key as false if the conditions are not met.
				$campaigns = apply_filters( 'optinmonster_output', $campaigns ); // Deprecated.
				$campaigns = apply_filters( 'optin_monster_output', $campaigns, $campaign, $fields, $post_id ); // Deprecated.
				$campaigns = apply_filters( 'optin_monster_api_output', $campaigns, $campaign, $fields, $post_id );
			}

			// Run a final filter for all items.
			$campaigns = apply_filters( 'optin_monster_api_final_output', $campaigns, $post_id );
		}

		// If the init code is empty, do nothing.
		if ( empty( $campaigns ) ) {
			return;
		}

		// Load the optins.
		foreach ( (array) $campaigns as $campaign ) {
			if ( $campaign ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, trusted data from post_content
				echo $campaign;
			}
		}

		$is_preview = apply_filters(
			'optin_monster_should_set_campaigns_as_preview',
			is_preview() || is_customize_preview()
		);

		if ( $is_preview ) {
			remove_action( 'wp_footer', array( $this, 'prevent_all_campaigns' ), 11 );
			add_action( 'wp_footer', array( $this, 'set_campaigns_as_preview' ), 99 );
		}
	}

	/**
	 * Possibly loads a campaign preview on a page.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $campaigns Array of campaign objects to output.
	 * @param  int   $post_id   The current post id.
	 *
	 * @return array            Array of campaign objects to output.
	 */
	public function load_previews( $campaigns, $post_id ) {
		if ( self::$live_preview || self::$live_rules_preview ) {
			$campaign_id = sanitize_title_with_dashes( self::$live_preview ? self::$live_preview : self::$live_rules_preview );

			$embed = self::om_script_tag(
				array(
					'id'         => 'omapi-script-preview-' . $campaign_id,
					'campaignId' => $campaign_id,
					'userId'     => $this->base->get_option( 'userId' ),
				)
			);

			$embed = apply_filters( 'optin_monster_api_preview_output', $embed, $campaign_id, $post_id );

			$this->set_preview_slug( $campaign_id );

			$campaigns[ $campaign_id ] = $embed;
		}

		return $campaigns;
	}

	/**
	 * Loads the global OM code on this page.
	 *
	 * @since 1.8.0
	 */
	public function load_global_optinmonster() {
		$option = $this->base->get_option();

		// If we don't have the data we need, return early.
		if ( empty( $option['userId'] ) || empty( $option['accountId'] ) ) {
			return;
		}

		$option['id'] = 'omapi-script-global';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, escaped function.
		echo self::om_script_tag( $option );
	}

	/**
	 * Sets the slug for possibly parsing shortcodes.
	 *
	 * @since 1.0.0
	 *
	 * @param object $optin The optin object.
	 */
	public function set_slug( $optin ) {
		$slug = str_replace( '-', '_', $optin->post_name );

		// Set the slug.
		$this->slugs[ $slug ] = array(
			'slug'     => $slug,
			'mailpoet' => ! empty( $optin->ID ) && (bool) get_post_meta( $optin->ID, '_omapi_mailpoet', true ),
		);

		// Maybe set shortcode.
		if ( ! empty( $optin->ID ) && get_post_meta( $optin->ID, '_omapi_shortcode', true ) ) {
			$this->shortcodes[] = get_post_meta( $optin->ID, '_omapi_shortcode_output', true );
		}

		if ( ! empty( $this->slugs[ $slug ]['mailpoet'] ) ) {
			$this->wp_mailpoet();
		}

		return $this;
	}

	/**
	 * Sets the preview slug for possibly parsing shortcodes.
	 *
	 * @since 2.2.0
	 *
	 * @param object $slug The campaign Id slug.
	 */
	public function set_preview_slug( $slug ) {
		$optin = $this->base->get_optin_by_slug( $slug );
		if ( empty( $optin ) ) {
			$optin = (object) array(
				'post_name' => $slug,
				'ID'        => 0,
			);
		}

		$this->set_slug( $optin );

		// Request the shortcodes from the campaign preview object.
		$user_id = $this->base->get_option( 'userId' );
		$route   = "embed/{$user_id}/{$slug}/preview/shortcodes";
		$body    = OMAPI_Api::build( 'v2', $route, 'GET' )->request();

		if ( ! empty( $body->{$slug} ) ) {
			$this->shortcodes[] = OMAPI_Save::get_shortcodes_string( $body->{$slug} );
		}

		return $this;
	}

	/**
	 * Maybe outputs the JS variables to parse shortcodes.
	 *
	 * @since 1.0.0
	 */
	public function maybe_parse_shortcodes() {

		// If no slugs have been set, do nothing.
		if ( empty( $this->slugs ) ) {
			return;
		}

		// Loop through any shortcodes and output them.
		foreach ( $this->shortcodes as $shortcode_string ) {
			if ( empty( $shortcode_string ) ) {
				continue;
			}

			if ( strpos( $shortcode_string, '|||' ) !== false ) {
				$all_shortcode = explode( '|||', $shortcode_string );
			} else { // Backwards compat.
				$all_shortcode = explode( ',', $shortcode_string );
			}

			foreach ( $all_shortcode as $shortcode ) {
				if ( empty( $shortcode ) ) {
					continue;
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<script type="text/template" class="omapi-shortcode-helper">' . html_entity_decode( $shortcode, ENT_COMPAT, 'UTF-8' ) . '</script>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<script type="text/template" class="omapi-shortcode-parsed omapi-encoded">' . htmlentities( do_shortcode( html_entity_decode( $shortcode, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . '</script>';
			}
		}

		// Output the JS variables to signify shortcode parsing is needed.
		?>
		<script type="text/javascript">
		<?php
		foreach ( $this->slugs as $slug => $data ) {
			echo 'var ' . sanitize_title_with_dashes( $slug ) . '_shortcode = true;';
		}
		?>
		</script>
		<?php

	}

	/**
	 * Sets all OM campaigns to preview mode, which disables their form fields.
	 *
	 * @since 2.2.0
	 */
	public function set_campaigns_as_preview() {
		?>
		<script type="text/javascript">
			// Disable OM analytics.
			window._omdisabletracking = true;
			document.addEventListener('om.Optin.init', function(evt) {

				// Disables form submission.
				evt.detail.Optin.preview = true;
			} );
		</script>
		<?php
	}

	/**
	 * Prevents any OM campaigns from loading if we're on a singular post
	 * with the `om_disable_all_campaigns` meta set.
	 *
	 * @since 2.3.0
	 */
	public function prevent_all_campaigns() {
		?>
		<script type="text/javascript">
			document.addEventListener('om.Shutdown.init', function(evt) {
				evt.detail.Shutdown.preventAll = true;
			});
		</script>
		<?php
	}

	/**
	 * Possibly localizes a JS variable for output use.
	 *
	 * @since 1.0.0
	 */
	public function localize() {

		// If no slugs have been set, do nothing.
		if ( empty( $this->slugs ) ) {
			return;
		}

		// If already localized, do nothing.
		if ( $this->localized ) {
			return;
		}

		// Set flag to true.
		$this->localized = true;

		// Output JS variable.
		?>
		<script type="text/javascript">var omapi_localized = {
			ajax: '<?php echo esc_url_raw( add_query_arg( 'optin-monster-ajax-route', true, admin_url( 'admin-ajax.php' ) ) ); ?>',
			nonce: '<?php echo esc_js( wp_create_nonce( 'omapi' ) ); ?>',
			slugs:
			<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, method is escaping.
				echo OMAPI_Utils::json_encode( $this->slugs );
			?>
		};</script>
		<?php
	}

	/**
	 * Enqueues the WP mailpoet script for storing local optins.
	 *
	 * @since 1.8.2
	 */
	public function wp_mailpoet() {
		// Only try to use the MailPoet integration if it is active.
		if ( $this->base->is_mailpoet_active() ) {
			wp_enqueue_script(
				$this->base->plugin_slug . '-wp-mailpoet',
				$this->base->url . 'assets/js/mailpoet.js',
				array( 'jquery' ),
				$this->base->asset_version(),
				true
			);
		}
	}

	/**
	 * Enqueues the WP helper script for the API.
	 *
	 * @since 1.0.0
	 */
	public function wp_helper() {
		wp_enqueue_script(
			$this->base->plugin_slug . '-wp-helper',
			$this->base->url . 'assets/dist/js/helper.min.js',
			array(),
			$this->base->asset_version(),
			true
		);
	}

	/**
	 * Outputs a JS variable, in the footer of the site, with information about
	 * the current page, and the terms in use for the display rules.
	 *
	 * @since 1.6.5
	 *
	 * @return void
	 */
	public function display_rules_data() {
		global $wp_query;

		// If already localized, do nothing.
		if ( $this->data_output ) {
			return;
		}

		// Set flag to true.
		$this->data_output = true;

		$tax_terms    = array();
		$object       = get_queried_object();
		$object_id    = self::current_id();
		$object_class = is_object( $object ) ? get_class( $object ) : '';
		$object_type  = '';
		$object_key   = '';
		$post         = null;
		if ( 'WP_Post' === $object_class ) {
			$post        = $object;
			$object_type = 'post';
			$object_key  = $object->post_type;
		} elseif ( 'WP_Term' === $object_class ) {
			$object_type = 'term';
			$object_key  = $object->taxonomy;
		}

		// Get the current object's terms, if applicable. Defaults to public taxonomies only.
		if ( ! empty( $post->ID ) && is_singular() || ( $wp_query->is_category() || $wp_query->is_tag() || $wp_query->is_tax() ) ) {

			// Should we only check public taxonomies?
			$only_public = apply_filters( 'optinmonster_only_check_public_taxonomies', true, $post );
			$taxonomies  = get_object_taxonomies( $post, false );

			if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {

					// Private ones should remain private and not output in the JSON blob.
					if ( $only_public && ! $taxonomy->public ) {
						continue;
					}

					$terms = get_the_terms( $post, $taxonomy->name );
					if ( ! empty( $terms ) && is_array( $terms ) ) {
						$tax_terms = array_merge( $tax_terms, wp_list_pluck( $terms, 'term_id' ) );
					}
				}

				$tax_terms = wp_parse_id_list( $tax_terms );
			}
		}

		$output = array(
			'object_id'   => $object_id,
			'object_key'  => $object_key,
			'object_type' => $object_type,
			'term_ids'    => $tax_terms,
			'wp_json'     => untrailingslashit( get_rest_url() ),
			'wc_active'   => OMAPI_WooCommerce::is_active(),
			'edd_active'  => OMAPI_EasyDigitalDownloads::is_active(),
			'nonce'       => wp_create_nonce( 'wp_rest' ),
		);

		$output = apply_filters( 'optin_monster_display_rules_data_output', $output );

		// Output JS variable.
		?>
		<script type="text/javascript">var omapi_data = <?php echo OMAPI_Utils::json_encode( $output ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;</script>
		<?php
	}

	/**
	 * Prepare the optin campaign html.
	 *
	 * @since  1.5.0
	 *
	 * @param  object $optin The option post object.
	 *
	 * @return string         The optin campaign html.
	 */
	public function prepare_campaign( $optin ) {
		$optin          = $this->base->validate_is_campaign_type( $optin );
		$campaign_embed = ! empty( $optin->post_content )
			? trim( html_entity_decode( stripslashes( $optin->post_content ), ENT_QUOTES, 'UTF-8' ), '\'' )
			: '';

		return apply_filters( 'optin_monster_campaign_embed_output', $campaign_embed, $optin );
	}

	/**
	 * Enqueues the WP helper script if relevant optin fields are found.
	 *
	 * @since  1.5.0
	 *
	 * @param  bool        $should_output Whether it should output.
	 * @param  OMAPI_Rules $rules   OMAPI_Rules object.
	 *
	 * @return array
	 */
	public function enqueue_helper_js_if_applicable( $should_output, $rules ) {

		// Check to see if we need to load the WP API helper script.
		if ( $should_output ) {
			if ( ! $rules->field_empty( 'mailpoet' ) ) {
				$this->wp_mailpoet();
			}

			$this->wp_helper();
		}

		return $should_output;
	}

	/**
	 * Get the current page/post's post id.
	 *
	 * @since  1.6.9
	 *
	 * @return int
	 */
	public static function current_id() {
		$object = get_queried_object();
		if ( is_object( $object ) && ! $object instanceof WP_Post ) {
			return 0;
		}

		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$post_id = get_option( 'page_for_posts' );
			}
		}

		return $post_id;
	}

	/**
	 * AJAX callback for returning WooCommerce cart information.
	 *
	 * @since 1.7.0
	 * @since 2.8.0 All the logic was moved to OMAPI_WooCommerce class.
	 *
	 * @deprecated 2.8.0 Use `OMAPI_WooCommerce->get_cart()` instead.
	 *
	 * @return array An array of WooCommerce cart data.
	 */
	public function woocommerce_cart() {
		_deprecated_function( __FUNCTION__, '2.8.0', 'OMAPI_WooCommerce->get_cart()' );

		return $this->base->woocommerce->get_cart();
	}

	/**
	 * Get the OptinMonster embed script JS.
	 *
	 * @since  1.9.8
	 *
	 * @param  array $args Array of arguments for the script, including
	 *                     optional user id, account id, and script id.
	 *
	 * @return string        The embed script JS.
	 */
	public static function om_script_tag( $args = array() ) {

		$src = esc_url_raw( OMAPI_Urls::om_api() );

		$script_id = ! empty( $args['id'] )
			? sprintf( 's.id="%s";', esc_attr( $args['id'] ) )
			: '';

		$campaign_or_account_id = ! empty( $args['accountId'] )
			? sprintf( 's.dataset.account="%s";', esc_attr( $args['accountId'] ) )
			: '';

		if ( empty( $campaign_or_account_id ) && ! empty( $args['campaignId'] ) ) {
			$campaign_or_account_id = sprintf( 's.dataset.campaign="%s";', esc_attr( $args['campaignId'] ) );
		}

		$user_id = ! empty( $args['userId'] )
			? sprintf( 's.dataset.user="%s";', esc_attr( $args['userId'] ) )
			: '';

		$api_cname = OMAPI::get_instance()->get_option( 'apiCname' );
		$api_cname = ! empty( $api_cname )
			? sprintf( 's.dataset.api="%s";', esc_attr( $api_cname ) )
			: '';

		$env = defined( 'OPTINMONSTER_ENV' )
			? sprintf( 's.dataset.env="%s";', esc_attr( OPTINMONSTER_ENV ) )
			: '';

		$tag  = '<script>';
		$tag .= '(function(d){';
		$tag .= 'var s=d.createElement("script");';
		$tag .= 's.type="text/javascript";';
		$tag .= 's.src="%1$s";';
		$tag .= 's.async=true;';
		$tag .= '%2$s';
		$tag .= '%3$s';
		$tag .= '%4$s';
		$tag .= '%5$s';
		$tag .= '%6$s';
		$tag .= 'd.getElementsByTagName("head")[0].appendChild(s);';
		$tag .= '})(document);';
		$tag .= '</script>';

		$tag = sprintf(
			$tag,
			$src,
			$script_id,
			$campaign_or_account_id,
			$user_id,
			$api_cname,
			$env
		);

		return apply_filters( 'optin_monster_embed_script_tag', $tag, $args );
	}
}
