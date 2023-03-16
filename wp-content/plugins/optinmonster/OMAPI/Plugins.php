<?php
/**
 * AM Plugins class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AM Plugins class.
 *
 * @since 1.9.10
 */
class OMAPI_Plugins {

	/**
	 * The Base OMAPI Object
	 *
	 *  @since 1.8.0
	 *
	 * @var OMAPI
	 */
	protected $base;

	/**
	 * Plugins array.
	 *
	 * @since 2.10.0
	 *
	 * @var array
	 */
	protected static $plugins = array();

	/**
	 * Constructor.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		$this->base = OMAPI::get_instance();
	}

	/**
	 * Gets the list of AM plugins.
	 *
	 * @since 2.0.0
	 *
	 * @return array List of AM plugins.
	 */
	public function get_list() {
		if ( empty( self::$plugins ) ) {
			self::$plugins = array(
				'wpforms-lite/wpforms.php'      => array(
					'slug'  => 'wpforms',
					'icon'  => $this->base->url . 'assets/images/about/plugin-wp-forms.png',
					'class' => 'wpforms-litewpformsphp',
					'check' => array( 'function' => 'wpforms' ),
					'name'  => 'WPForms',
					'desc'  => __( 'WPForm’s easy drag & drop WordPress form builder allows you to create contact forms, online surveys, donation forms, order forms and morein just a few minutes without writing any code.', 'optin-monster-api' ),
					'url'   => 'https://downloads.wordpress.org/plugin/wpforms-lite.zip',
					'pro'   => array(
						'plugin' => 'wpforms-premium/wpforms.php',
						'name'   => 'WPForms Pro',
						'url'    => 'https://wpforms.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
					),
				),
				'google-analytics-for-wordpress/googleanalytics.php' => array(
					'slug'  => 'monsterinsights',
					'icon'  => $this->base->url . 'assets/images/about/plugin-mi.png',
					'class' => 'google-analytics-for-wordpressgoogleanalyticsphp',
					'check' => array( 'function' => 'MonsterInsights' ),
					'name'  => 'MonsterInsights',
					/* translators: %s - MonsterInsights Plugin name.*/
					'desc'  => sprintf( __( '%s makes it effortless to properly connect your WordPress site with Google Analytics, so you can start making data-driven decisions to grow your business.', 'optin-monster-api' ), 'MonsterInsights' ),
					'url'   => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.zip',
					'pro'   => array(
						'plugin' => 'google-analytics-premium/googleanalytics-premium.php',
						'name'   => 'MonsterInsights Pro',
						'url'    => 'https://www.monsterinsights.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
					),
				),
				'rafflepress/rafflepress.php'   => array(
					'slug'  => 'rafflepress',
					'icon'  => $this->base->url . 'assets/images/about/plugin-rafflepress.png',
					'class' => 'rafflepressrafflepressphp',
					'check' => array(
						'constant' => array(
							'RAFFLEPRESS_VERSION',
							'RAFFLEPRESS_PRO_VERSION',
						),
					),
					'name'  => 'RafflePress',
					'desc'  => __( 'Turn your visitors into brand ambassadors! Easily grow your email list, website traffic, and social media followers with powerful viral giveaways & contests.', 'optin-monster-api' ),
					'url'   => 'https://downloads.wordpress.org/plugin/rafflepress.zip',
					'pro'   => array(
						'plugin' => '',
						'name'   => 'RafflePress',
						'url'    => 'https://rafflepress.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
					),
				),
				'wp-mail-smtp/wp_mail_smtp.php' => array(
					'slug'  => 'wpmailsmtp',
					'icon'  => $this->base->url . 'assets/images/about/plugin-wp-mail-smtp.png',
					'class' => 'wp-mail-smtpwp-mail-smtpphp',
					'check' => array( 'function' => 'wp_mail_smtp' ),
					'name'  => 'WP Mail SMTP',
					'desc'  => __( 'Make sure your website’s emails reach the inbox. Our goal is to make email deliverability easy and reliable. Trusted by over 1 MILLION websites.', 'optin-monster-api' ),
					'url'   => 'https://downloads.wordpress.org/plugin/wp-mail-smtp.zip',
					'pro'   => array(
						'plugin' => 'wp-mail-smtp-pro/wp_mail_smtp.php',
						'name'   => 'WP Mail SMTP',
						'url'    => 'https://wpmailsmtp.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
					),
				),
				'all-in-one-seo-pack/all_in_one_seo_pack.php' => array(
					'slug'  => 'aioseo',
					'icon'  => $this->base->url . 'assets/images/about/plugin-aioseo.png',
					'class' => 'all-in-one-seo-packall-in-one-seo-packphp',
					'check' => array(
						'constant' => array(
							'AIOSEOP_VERSION',
							'AIOSEO_VERSION',
						),
					),
					'name'  => 'AIOSEO',
					/* translators: %s - AIOSEO Plugin name.*/
					'desc'  => sprintf( __( 'Easily set up proper SEO foundations for your site in less than 10 minutes with %s. It’s the most powerful and user-friendly WordPress SEO plugin, used by over 2 MILLION sites.', 'optin-monster-api' ), 'All-in-One SEO' ),
					'url'   => 'https://downloads.wordpress.org/plugin/all-in-one-seo-pack.zip',
					'pro'   => array(
						'plugin' => '',
						'name'   => 'All-in-One SEO Pack',
						'url'    => 'https://aioseo.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
					),
				),
				'coming-soon/coming-soon.php'   => array(
					'slug'  => 'seedprod',
					'icon'  => $this->base->url . 'assets/images/about/plugin-seedprod.png',
					'class' => 'coming-sooncoming-soonphp',
					'check' => array(
						'constant' => array(
							'SEEDPROD_PRO_VERSION',
							'SEEDPROD_VERSION',
						),
					),
					'name'  => 'SeedProd',
					'desc'  => __( 'Professionally design landing page templates like coming soon pages and sales pages that get you up and going with just a few clicks of a mouse. Used on over 1 MILLION sites! ', 'optin-monster-api' ),
					'url'   => 'https://downloads.wordpress.org/plugin/coming-soon.zip',
					'pro'   => array(
						'plugin' => '~seedprod-coming-soon-pro*~',
						'name'   => 'SeedProd',
						'url'    => 'https://www.seedprod.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
					),
				),
				'trustpulse-api/trustpulse.php' => array(
					'slug'  => 'trustpulse',
					'icon'  => $this->base->url . 'assets/images/about/plugin-trustpulse.png',
					'class' => 'trustpulse-apitrustpulsephp',
					'check' => array( 'class' => 'TPAPI' ),
					'name'  => 'TrustPulse',
					'desc'  => __( 'TrustPulse is the honest marketing platform that leverages and automates the real power of social proof to instantly increase trust, conversions and sales.', 'optin-monster-api' ),
					'url'   => 'https://downloads.wordpress.org/plugin/trustpulse-api.zip',
					'pro'   => array(
						'plugin' => '',
						'name'   => 'TrustPulse',
						'url'    => 'https://trustpulse.com/?utm_source=WordPress&utm_medium=Plugin&utm_campaign=OptinMonsterAboutUs',
					),
				),
				'pushengage/main.php'           => array(
					'slug'  => 'pushengage',
					'icon'  => $this->base->url . 'assets/images/about/plugin-pushengage.png',
					'class' => 'pushengagemainphp',
					'check' => array( 'constant' => 'PUSHENGAGE_VERSION' ),
					'name'  => 'PushEngage',
					'desc'  => __( 'Plugin to push notifications for Chrome, Firefox, Opera, Microsoft Edge, Safari, UC Browser and Samsung Internet browsers.', 'optin-monster-api' ),
					'url'   => 'https://downloads.wordpress.org/plugin/pushengage.zip',
				),
			);
			foreach ( self::$plugins as $plugin_id => $plugin ) {
				self::$plugins[ $plugin_id ]['id'] = $plugin_id;
			}
		}

		return self::$plugins;
	}

	/**
	 * Gets the list of AM plugins which include plugin status (installed/activated).
	 *
	 * @since 2.10.0
	 *
	 * @return array List of AM plugins.
	 */
	public function get_list_with_status() {
		$this->get_list();
		foreach ( self::$plugins as $plugin_id => $data ) {
			if ( ! isset( $data['status'] ) ) {
				self::$plugins[ $plugin_id ] = $this->get( $plugin_id )->get_data();
			}
		}

		return self::$plugins;
	}

	/**
	 * Get given Plugin instance.
	 *
	 * @since 2.10.0
	 *
	 * @param  string $plugin_id The plugin ID.
	 *
	 * @return OMAPI_Plugins_Plugin
	 */
	public function get( $plugin_id ) {
		return OMAPI_Plugins_Plugin::get( $plugin_id );
	}

	/**
	 * Installs and activates a plugin for a given url (if user is allowed).
	 *
	 * @since 2.0.0
	 *
	 * @param OMAPI_Plugins_Plugin $plugin The Plugin instance.
	 * @return array On success.
	 * @throws Exception On error.
	 */
	public function install_plugin( OMAPI_Plugins_Plugin $plugin ) {

		$not_allowed_exception = new Exception( esc_html__( 'Sorry, not allowed!', 'optin-monster-api' ), rest_authorization_required_code() );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			throw $not_allowed_exception;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		$creds = request_filesystem_credentials( admin_url( 'admin.php' ), '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			throw $not_allowed_exception;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$skin = version_compare( $GLOBALS['wp_version'], '5.3', '>=' )
			? new OMAPI_InstallSkin()
			: new OMAPI_InstallSkinCompat();

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( $skin );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) ) {
			throw new Exception( esc_html__( 'Missing required installer!', 'optin-monster-api' ), 500 );
		}

		$result = $installer->install( esc_url_raw( $plugin['url'] ) );

		if ( ! $installer->plugin_info() ) {
			throw new Exception( esc_html__( 'Plugin failed to install!', 'optin-monster-api' ), 500 );
		}

		update_option(
			sanitize_text_field( $plugin['slug'] . '_referred_by' ),
			'optinmonster'
		);

		$plugin_basename = $installer->plugin_info();

		// Activate the plugin silently.
		try {
			$this->activate_plugin( $plugin_basename );

			return array(
				'message'      => esc_html__( 'Plugin installed & activated.', 'optin-monster-api' ),
				'is_activated' => true,
				'basename'     => $plugin_basename,
			);

		} catch ( \Exception $e ) {

			return array(
				'message'      => esc_html__( 'Plugin installed.', 'optin-monster-api' ),
				'is_activated' => false,
				'basename'     => $plugin_basename,
			);
		}
	}

	/**
	 * Activates a plugin with a given plugin name (if user is allowed).
	 *
	 * @since 2.0.0
	 *
	 * @param string $plugin_id
	 * @return array On success.
	 * @throws Exception On error.
	 */
	public function activate_plugin( $plugin_id ) {

		// Check for permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			throw new Exception( esc_html__( 'Sorry, not allowed!', 'optin-monster-api' ), rest_authorization_required_code() );
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$activate = activate_plugin( sanitize_text_field( $plugin_id ), '', false, true );

		if ( is_wp_error( $activate ) ) {
			$e = new OMAPI_WpErrorException();
			throw $e->setWpError( $activate );
		}

		// Prevent the various welcome/onboarding redirects that may occur when activating plugins.
		switch ( $plugin_id ) {
			case 'google-analytics-for-wordpress/googleanalytics.php':
				delete_transient( '_monsterinsights_activation_redirect' );
				break;
			case 'wpforms-lite/wpforms.php':
				update_option( 'wpforms_activation_redirect', true );
				break;
			case 'all-in-one-seo-pack/all_in_one_seo_pack.php':
				update_option( 'aioseo_activation_redirect', true );
				break;
			case 'trustpulse-api/trustpulse.php':
				delete_option( 'trustpulse_api_plugin_do_activation_redirect' );
				break;
		}

		return array(
			'message'  => esc_html__( 'Plugin activated.', 'optin-monster-api' ),
			'basename' => $plugin_id,
		);
	}

	/**
	 * Get a active plugins header value for OM app requests.
	 *
	 * @since 2.9.0
	 *
	 * @return string The plugins header value.
	 */
	public function get_active_plugins_header_value() {
		$wpf_active = $this->base->wpforms->is_active();

		// Set initial values.
		$plugins = array(
			// We want to know information about WPForms regardless
			// of if it's active. We'll use this to "disconnect" WPForms if it's not active.
			'wpf' => array(
				'a' => $wpf_active,
				'v' => $wpf_active ? $this->base->wpforms->version() : 0,
			),
		);

		return http_build_query( $plugins );
	}
}
