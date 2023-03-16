<?php
/**
 * Welcome class.
 *
 * @since 1.1.4
 *
 * @package OMAPI
 * @author  Devin Vinson
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Welcome class.
 *
 * @since 1.1.4
 */
class OMAPI_Welcome {

	/**
	 * Holds the class object.
	 *
	 * @since 1.1.4.2
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.1.4.2
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.1.4.2
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.1.4.2
	 */
	public function __construct() {
		// If we are not in admin or admin ajax, return.
		if ( ! is_admin() ) {
			return;
		}

		// If user is in admin ajax or doing cron, return.
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			return;
		}

		// If user is not logged in, return.
		if ( ! is_user_logged_in() ) {
			return;
		}

		// If user cannot manage_options, return.
		if ( ! OMAPI::get_instance()->can_access( 'welcome' ) ) {
			return;
		}

		// Set our object.
		$this->set();

		// Maybe load a dashboard widget.
		add_action( 'admin_init', array( $this, 'redirect' ), 9999 );
		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.1.4.2
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Onboarding redirect.
	 *
	 * This function checks if a new install or update has just occurred. If so,
	 * then we redirect the user to the appropriate page.
	 *
	 * @since 2.6.0
	 */
	public function redirect() {

		// Check if we should consider redirection.
		if ( ! get_transient( 'optin_monster_api_activation_redirect' ) ) {
			return;
		}

		// If we are redirecting, clear the transient so it only happens once.
		delete_transient( 'optin_monster_api_activation_redirect' );

		// Check option to disable welcome redirect.
		if ( get_option( 'optin_monster_api_activation_redirect_disabled', false ) ) {
			return;
		}

		// Only do this for single site installs.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate-multi'] ) || is_network_admin() ) {
			return;
		}

		// Don't initiate onboarding if they are already connected.
		if ( OMAPI_ApiKey::has_credentials() ) {
			return;
		}

		$goto = OMAPI_Urls::dashboard();

		// Check if they haven't yet been welcomed.
		if ( 'welcomed' !== $this->base->get_option( 'welcome', 'status', 'none' ) ) {

			$options                      = $this->base->get_option();
			$options['welcome']['status'] = 'welcomed';
			update_option( 'optin_monster_api', $options );

			$goto = OMAPI_Urls::wizard();
		}

		wp_safe_redirect( $goto );
		exit;
	}

	/**
	 * Loads a dashboard widget if the user has not entered and verified API credentials.
	 *
	 * @since 1.1.5.1
	 */
	public function dashboard_widget() {
		if ( OMAPI_ApiKey::has_credentials() ) {
			return;
		}

		wp_add_dashboard_widget(
			'optin_monster_db_widget',
			esc_html__( 'Please Connect OptinMonster', 'optin-monster-api' ),
			array( $this, 'dashboard_widget_callback' )
		);

		global $wp_meta_boxes;
		$normal_dashboard      = $wp_meta_boxes['dashboard']['normal']['core'];
		$example_widget_backup = array( 'optin_monster_db_widget' => $normal_dashboard['optin_monster_db_widget'] );
		unset( $normal_dashboard['optin_monster_db_widget'] );
		$sorted_dashboard                             = array_merge( $example_widget_backup, $normal_dashboard );
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}

	/**
	 * Dashboard widget callback.
	 *
	 * @since 1.1.5.1
	 */
	public function dashboard_widget_callback() {
		?>
		<div class="optin-monster-db-widget" style="text-align:center;">
			<p><img src="<?php echo plugins_url( '/assets/images/logo-om.png', OMAPI_FILE ); ?>" alt="<?php esc_attr_e( 'OptinMonster', 'optin-monster-api' ); ?>" width="300px" height="45px"></p>
			<h3 style="font-weight:normal;font-size:1.3em;"><?php esc_html_e( 'Please Connect OptinMonster', 'optin-monster-api' ); ?></h3>
			<p>
				<?php
					echo wp_kses_post( __( 'Instantly grow your email list, get more leads and increase sales with the <strong>#1 most powerful conversion optimization toolkit in the world.</strong>', 'optin-monster-api' ) );
				?>
			</p>
			<p><a href="<?php echo esc_url( OMAPI_Urls::onboarding() ); ?>" class="button button-primary" title="<?php esc_attr_e( 'Get Started', 'optin-monster-api' ); ?>"><?php esc_html_e( 'Get Started', 'optin-monster-api' ); ?></a><a style="margin-left:8px" href="<?php echo esc_url( OMAPI_Urls::onboarding() ); ?>" title="<?php esc_attr_e( 'Learn More', 'optin-monster-api' ); ?>"><?php esc_html_e( 'Learn More &rarr;', 'optin-monster-api' ); ?></a></p>
		</div>
		<?php
	}

}
