<?php
/**
 * Constant Contact class.
 *
 * @since 1.6.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constant Contact class.
 *
 * @since 1.6.0
 */
class OMAPI_ConstantContact {

	/**
	 * Holds the class object.
	 *
	 * @since 1.6.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the review slug.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	public $hook;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.6.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Sign up link.
	 *
	 * @since 1.6.0
	 * @var string
	 */
	public $sign_up = 'https://optinmonster.com/refer/constant-contact/';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.6.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Pages
		add_action( 'admin_menu', array( $this, 'register_cc_page' ) );
		add_action( 'admin_notices', array( $this, 'constant_contact_cta_notice' ) );
		add_action( 'wp_ajax_om_constant_contact_dismiss', array( $this, 'constant_contact_dismiss' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.6.0
	 */
	public function set() {

		self::$instance = $this;
		$this->base     = OMAPI::get_instance();

	}

	/**
	 * Loads the OptinMonster admin menu.
	 *
	 * @since 1.6.0
	 */
	public function register_cc_page() {
		$slug        = 'optin-monster-constant-contact';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_current  = isset( $_GET['page'] ) && $slug === sanitize_key( wp_unslash( $_GET['page'] ) );
		$parent_slug = $this->base->menu->parent_slug();
		if ( ! $is_current ) {
			$parent_slug .= '-no-menu';
		}

		$this->hook = add_submenu_page(
			$parent_slug, // parent slug
			esc_html__( 'OptinMonster with Constant Contact', 'optin-monster-api' ), // page title,
			esc_html__( 'OptinMonster + Constant Contact', 'optin-monster-api' ),
			$this->base->access_capability( $slug ), // cap
			$slug, // slug
			array( $this, 'display_page' ) // callback
		);

		// Load settings page assets.
		if ( $this->hook ) {
			add_action( 'load-' . $this->hook, array( $this, 'assets' ) );
		}

	}

	/**
	 * Add admin notices to connect to Constant Contact.
	 *
	 * @since 1.6.0
	 */
	public function constant_contact_cta_notice() {

		// Only consider showing the notice when WPForms plugin is not active.
		// Here WPForms_Constant_Contact class existence is checked which shows the notice in WPForms plugin.
		if ( class_exists( 'WPForms_Constant_Contact' ) ) {
			return;
		}

		// Only consider showing the review request to admin users.
		if ( ! is_super_admin() ) {
			return;
		}

		// Only display the notice if it has not been dismissed.
		$dismissed = get_option( 'optinmonster_constant_contact_dismiss', false );

		if ( $dismissed ) {
			return;
		}

		// Only show on the main dashboard page (wp-admin/index.php)
		// or any OptinMonster plugin-specific screen.
		$can_show = $is_om_page = $this->base->menu->is_om_page();
		if ( ! $can_show ) {
			$can_show = function_exists( 'get_current_screen' ) && 'dashboard' === get_current_screen()->id;
		}

		if ( ! $can_show ) {
			return;
		}

		$connect    = OMAPI_Urls::onboarding();
		$learn_more = OMAPI_Urls::admin( array( 'page' => 'optin-monster-constant-contact' ) );

		// Output the notice message.
		?>
		<div class="notice notice-info is-dismissible om-constant-contact-notice">
			<p>
				<?php
				echo wp_kses(
					__( 'Get the most out of the <strong>OptinMonster</strong> plugin &mdash; use it with an active Constant Contact account.', 'optin-monster-api' ),
					array(
						'strong' => array(),
					)
				);
				?>
			</p>
			<p>
				<a href="<?php echo esc_url( $this->sign_up ); ?>" class="button-primary" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Try Constant Contact for Free', 'optin-monster-api' ); ?>
				</a>
				<?php if ( ! $is_om_page ) { ?>
					<a href="<?php echo esc_url( $connect ); ?>" class="button-secondary">
						<?php esc_html_e( 'Get Started', 'optin-monster-api' ); ?>
					</a>
				<?php } ?>
				<?php
				printf(
					wp_kses(
						/* translators: %s - OptinMonster Constant Contact internal URL. */
						__( 'Learn More about the <a href="%s">power of email marketing</a>', 'optin-monster-api' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( $learn_more )
				);
				?>
			</p>
			<style type="text/css">
				.om-constant-contact-notice p:first-of-type {
					margin: 16px 0 8px;
				}

				.om-constant-contact-notice p:last-of-type {
					margin: 8px 0 16px;
				}

				.om-constant-contact-notice .button-primary,
				.om-constant-contact-notice .button-secondary {
					display: inline-block;
					margin: 0 10px 0 0;
				}
			</style>
			<script type="text/javascript">
				jQuery( function ( $ ) {
					$( document ).on( 'click', '.om-constant-contact-notice button', function ( event ) {
						event.preventDefault();
						$.post( ajaxurl, { action: 'om_constant_contact_dismiss' } );
						$( '.om-constant-contact-notice' ).remove();
					} );
				} );
			</script>
		</div>
		<?php
	}

	/**
	 * Dismiss the Constant Contact admin notice.
	 *
	 * @since 1.6.0
	 */
	public function constant_contact_dismiss() {

		update_option( 'optinmonster_constant_contact_dismiss', 1, false );
		wp_send_json_success();
	}

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 1.6.0
	 */
	public function assets() {
		add_filter( 'admin_body_class', array( $this, 'add_body_classes' ) );
		add_action( 'admin_enqueue_scripts', array( $this->base->menu, 'styles' ) );
		add_filter( 'admin_footer_text', array( $this, 'footer' ) );
		add_action( 'in_admin_header', array( $this->base->menu, 'output_plugin_screen_banner' ) );
	}

	/**
	 * Add body classes.
	 *
	 * @since 2.0.0
	 */
	public function add_body_classes( $classes ) {
		$classes .= ' omapi-constant-contact ';

		return $classes;
	}

	/**
	 * Customizes the footer text on the OptinMonster settings page.
	 *
	 * @since 1.6.0
	 *
	 * @param string $text  The default admin footer text.
	 * @return string $text Amended admin footer text.
	 */
	public function footer( $text ) {

		$url  = 'https://wordpress.org/support/plugin/optinmonster/reviews?filter=5#new-post';
		$text = sprintf( __( 'Please rate <strong>OptinMonster</strong> <a href="%1$s" target="_blank" rel="noopener">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%1$s" target="_blank" rel="noopener noreferrer">WordPress.org</a> to help us spread the word. Thank you from the OptinMonster team!', 'optin-monster-api' ), $url );
		return $text;

	}

	/**
	 * Outputs the Review Page.
	 *
	 * @since 1.6.0
	 */
	public function display_page() {
		$this->base->output_view(
			'constantcontact.php',
			array(
				'images_url' => esc_url( $this->base->url . 'assets/css/images/' ),
				'signup_url' => esc_url( $this->sign_up ),
			)
		);
	}

}
