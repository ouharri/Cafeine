<?php
/**
 * Wordfence class.
 *
 * @since 2.10.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wordfence class.
 *
 * @since 2.10.0
 */
class OMAPI_Wordfence {

	/**
	 * Holds the class object.
	 *
	 * @since 2.10.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.10.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Array of Wordfence rules that are expired in these newer versions of the WordPress plugin.
	 *
	 * @since 2.10.0
	 *
	 * @var array
	 */
	protected static $expired_om_rules = array(
		401 => 1, // "OptinMonster <= 2.6.0 Reflected Cross-Site Scripting"
		408 => 1, // "Optinmonster <= 2.6.1 Stored Cross-Site Scripting"
	);

	/**
	 * Primary class constructor.
	 *
	 * @since 2.10.0
	 */
	public function __construct() {
		if ( ! self::is_active() ) {
			return;
		}

		// Set our object.
		$this->set();

		add_action( 'all_admin_notices', array( $this, 'handle_messages' ) );
		add_action( 'admin_post_om_update_wf_rules', array( $this, 'auto_update_rules' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 2.10.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Handles outputting the warning and success messages.
	 *
	 * @since 2.10.0
	 */
	public function handle_messages() {
		if (
			self::has_expired_rules()
			&& ( $this->base->is_om_page() || self::is_wf_page() )
		) {
			$this->output_wordfence_rules_warning();
		} else {
			$this->maybe_output_success_message();
		}
	}

	/**
	 * Outputs error message about WF rules not working with OptinMonster.
	 *
	 * @since 2.10.0
	 */
	protected function output_wordfence_rules_warning() {
		$url = add_query_arg(
			array(
				'action' => 'om_update_wf_rules',
				'nonce'  => wp_create_nonce( 'om_fix_waf_rules', 'om_fix_waf_rules' ),
				'return' => urlencode( remove_query_arg( 'om_fix_waf_rules' ) ),
			),
			admin_url( 'admin-post.php' )
		);

		echo '
		<div id="message" class="notice notice-error is-dismissible">
			<p style="margin:0.5em 0;">
			<span>' .
					esc_html__( 'Your site is using Wordfence Firewall rules which will prevent OptinMonster from working correctly. ', 'optin-monster-api' ) .
				'</span>
				<a style="margin-left: 10px;" class="button" href="' . esc_url( $url ) . '">' .
					esc_html__( 'Fix this for me', 'optin-monster-api' ),
				'</a>
			</p>
		</div>';
	}

	/**
	 * Outputs success message after WF rules updated.
	 *
	 * @since 2.10.0
	 */
	protected function maybe_output_success_message() {
		if (
			empty( $_GET['om_fix_waf_rules'] )
			|| 'success' !== $_GET['om_fix_waf_rules']
		) {
			return;
		}

		echo '
		<div id="message" class="notice notice-success is-dismissible">
			<p style="margin:0.5em 0;">' .
			esc_html__( 'Success! Your Wordfence Firewall rules have been optimized to work with OptinMonster.', 'optin-monster-api' ) .
			'</p>
		</div>
		<script>
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, ' . json_encode( remove_query_arg( 'om_fix_waf_rules' ) ) . ' );
			}
		</script>
		';
	}

	/**
	 * Handles auto-fixing the WF rules after user clicks the "Fix this for me" button.
	 *
	 * @since 2.10.0
	 */
	public function auto_update_rules() {
		if ( ! self::has_expired_rules() ) {
			return;
		}

		// Make sure nonce check passes.
		check_admin_referer( 'om_fix_waf_rules', 'nonce' );

		self::remove_expired_rules();

		$url = add_query_arg(
			'om_fix_waf_rules',
			'success',
			! empty( $_GET['return'] ) ? $_GET['return'] : admin_url()
		);
		wp_safe_redirect( esc_url_raw( $url ) );
		exit;
	}

	/**
	 * Determine if the WF firewall rules have any of the expired OM rules enabled.
	 *
	 * @since 2.10.0
	 *
	 * @return boolean Whether the WF firewall rules have any of the expired OM rules enabled..
	 */
	protected static function has_expired_rules() {
		if ( ! is_callable( array( wfWAF::getInstance(), 'getStorageEngine' ) ) ) {
			return false;
		}

		$disabled_rules = (array) wfWAF::getInstance()->getStorageEngine()->getConfig( 'disabledRules' );
		$found_rules    = self::$expired_om_rules;

		foreach ( $found_rules as $rule_id => $one ) {
			if ( ! empty( $disabled_rules[ $rule_id ] ) ) {
				unset( $found_rules[ $rule_id ] );
			}
		}

		return ! empty( $found_rules );
	}

	/**
	 * Handles updating the WF firewall disabled rules to include the expired OM rules.
	 *
	 * @since 2.10.0
	 */
	protected function remove_expired_rules() {
		$disabled = (array) wfWAF::getInstance()->getStorageEngine()->getConfig( 'disabledRules' );
		foreach ( self::$expired_om_rules as $rule_id => $one ) {
			$disabled[ $rule_id ] = true;
		}

		wfWAF::getInstance()->getStorageEngine()->setConfig( 'disabledRules', $disabled );
	}

	/**
	 * Checks if given (or current) page is a wordfence admin page.
	 *
	 * @since 2.10.0
	 *
	 * @param  strgin $page Page to check. Falls back to $_REQUEST['page'].
	 *
	 * @return boolean Whether given (or current) page is a wordfence admin page.
	 */
	public static function is_wf_page( $page = null ) {
		if ( empty( $page ) && ! empty( $_REQUEST['page'] ) ) {
			$page = $_REQUEST['page'];
		}

		if ( empty( $page ) ) {
			return false;
		}

		$page = sanitize_key( $page );

		return 'wfls' === $page || preg_match( '/wordfence/', $page );
	}

	/**
	 * Check if the Wordfence plugin is active.
	 *
	 * @since 2.10.0
	 *
	 * @return bool
	 */
	public static function is_active() {
		return is_callable( 'wfWAF::getInstance' );
	}
}
