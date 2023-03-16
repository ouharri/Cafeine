<?php
/**
 * Classic Editor class.
 *
 * @since 2.3.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classic Editor class.
 *
 * @since 2.3.0
 */
class OMAPI_ClassicEditor {

	/**
	 * Holds the class object.
	 *
	 * @since 2.3.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.3.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.3.0
	 *
	 * @var OMAPI
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 2.3.0
	 */
	public function __construct() {
		// Set our object.
		$this->set();

		add_action( 'media_buttons', array( $this, 'media_button' ), 15 );
		add_action( 'add_meta_boxes', array( $this, 'settings_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_metabox_data' ), 10, 2 );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 2.3.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Allow easy shortcode insertion via a custom media button.
	 *
	 * @since 2.3.0
	 *
	 * @param string $editor_id Unique editor identifier, e.g. 'content'.
	 */
	public function media_button( $editor_id ) {

		if ( ! $this->base->can_access( 'campaign_media_button' ) ) {
			return;
		}

		// Provide the ability to conditionally disable the button, so it can be
		// disabled for custom fields or front-end use such as bbPress. We default
		// to only showing within the post editor page.
		if ( ! apply_filters( 'optin_monster_display_media_button', $this->is_post_editor_page(), $editor_id ) ) {
			return;
		}

		// Setup the icon.
		$icon = '<span class="wp-media-buttons-icon optin-monster-menu-icon">' . $this->base->menu->icon_svg( 'currentColor', false ) . '</span>';

		printf(
			'<button type="button" class="button optin-monster-insert-campaign-button" data-editor="%s" title="%s">%s %s</button>',
			esc_attr( $editor_id ),
			esc_attr__( 'Add OptinMonster', 'optin-monster-api' ),
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, unable to escape SVG.
			$icon,
			esc_html__( 'Add OptinMonster', 'optin-monster-api' )
		);

		// If we have made it this far then load the JS.
		$handle = $this->base->plugin_slug . '-editor';
		wp_enqueue_script(
			$handle,
			$this->base->url . 'assets/dist/js/editor.min.js',
			array( 'jquery' ),
			$this->base->asset_version(),
			true
		);

		$i18n                   = $this->base->blocks->get_data_for_js( 'i18n' );
		$i18n['or_monsterlink'] = esc_html__( 'Or link to a popup campaign', 'optin-monster-api' );

		OMAPI_Utils::add_inline_script(
			$handle,
			'OMAPI_Editor',
			array(
				'monsterlink'    => esc_url_raw( OPTINMONSTER_SHAREABLE_LINK ) . '/c/',
				'canMonsterlink' => $this->base->blocks->get_data_for_js( 'canMonsterlink' ),
				'upgradeUri'     => $this->base->blocks->get_data_for_js( 'upgradeUri' ),
				'i18n'           => $i18n,
			)
		);

		add_action( 'admin_footer', array( $this, 'shortcode_modal' ) );
	}

	/**
	 * Check if we are on the post editor admin page.
	 *
	 * @since 2.3.0
	 *
	 * @returns boolean True if it is post editor admin page.
	 */
	public function is_post_editor_page() {

		if ( ! is_admin() ) {
			return false;
		}

		// get_current_screen() is loaded after 'admin_init' hook and may not exist yet.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();

		return null !== $screen && 'edit' === $screen->parent_base;
	}

	/**
	 * Modal window for inserting the optin-monster shortcode into TinyMCE.
	 *
	 * Thickbox is old and busted so we don't use that. Creating a custom view in
	 * Backbone would make me pull my hair out. So instead we offer a small clean
	 * modal that is based off of the WordPress insert link modal.
	 *
	 * @since 2.3.0
	 */
	public function shortcode_modal() {
		$campaigns           = $this->base->blocks->get_campaign_options( true );
		$campaigns['inline'] = ! empty( $campaigns['inline'] )
			? array_merge( array( '' => esc_html__( 'Select Campaign...', 'optin-monster-api' ) ), $campaigns['inline'] )
			: array();

		$campaigns['other'] = ! empty( $campaigns['other'] )
			? array_merge( array( '' => esc_html__( 'Select Campaign...', 'optin-monster-api' ) ), $campaigns['other'] )
			: array();

		$this->base->output_view(
			'shortcode-modal.php',
			array(
				'templatesUri'   => $this->base->blocks->get_data_for_js( 'templatesUri' ),
				'upgradeUri'     => OMAPI_Urls::upgrade( 'gutenberg', 'monster-link' ),
				'canMonsterlink' => $this->base->blocks->get_data_for_js( 'canMonsterlink' ),
				'campaigns'      => $campaigns,
			)
		);
		$this->base->output_min_css( 'shortcode-modal-css.php' );
	}

	/**
	 * Register the global OptinMonster Settings metabox.
	 *
	 * @since 2.3.0
	 */
	public function settings_meta_box() {
		$types = array_values( get_post_types( array( 'public' => true ) ) );
		foreach ( $types as $type ) {
			$supports_custom_fields = post_type_supports( $type, 'custom-fields' );
			// If custom fields aren't supported, change title to avoid duplicate 'OptinMonster' sections.
			// @see https://github.com/awesomemotive/optin-monster-wp-api/issues/391
			$title = $supports_custom_fields ? 'OptinMonster Settings' : 'Campaign Settings';
			add_meta_box(
				'om-global-post-settings',
				esc_html__( $title, 'optin-monster-api' ),
				array( $this, 'settings_meta_box_output' ),
				$type,
				'side',
				'default',
				array( '__back_compat_meta_box' => $supports_custom_fields )
			);
		}
	}

	/**
	 * Output the markup for the global OptinMonster Settings metabox.
	 *
	 * @since 2.3.0
	 *
	 * @param WP_Post $post The post object.
	 */
	public function settings_meta_box_output( $post ) {
		$disabled = get_post_meta( $post->ID, 'om_disable_all_campaigns', true );
		wp_nonce_field( 'om_disable_all_campaigns', 'om_disable_all_campaigns_nonce' );
		?>
		<p>
			<label for="om_disable_all_campaigns">
				<input class="widefat" type="checkbox" <?php checked( ! empty( $disabled ) ); ?> name="om_disable_all_campaigns" id="om_disable_all_campaigns" value="1" />&nbsp;
				<?php esc_html_e( 'Disable all OptinMonster campaigns.', 'optin-monster-api' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Save the global OptinMonster settings.
	 *
	 * @since 2.3.0
	 *
	 * @param int     $post_id Post Id.
	 * @param WP_Post $post    Post object.
	 */
	public function save_metabox_data( $post_id, $post ) {
		if (
			empty( $_POST['om_disable_all_campaigns_nonce'] )
			|| ! wp_verify_nonce( $_POST['om_disable_all_campaigns_nonce'], 'om_disable_all_campaigns' )
			|| empty( $post->post_type )
		) {
			return;
		}

		$type = get_post_type_object( $post->post_type );
		if (
			empty( $type->cap->edit_post )
			|| ! current_user_can( $type->cap->edit_post, $post_id )
		) {
			return;
		}

		$disabled = ! empty( $_POST['om_disable_all_campaigns'] );
		update_post_meta( $post_id, 'om_disable_all_campaigns', $disabled );
	}

}
