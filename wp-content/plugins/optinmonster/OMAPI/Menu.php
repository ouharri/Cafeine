<?php
/**
 * Menu class.
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
 * Menu class.
 *
 * @since 1.0.0
 */
class OMAPI_Menu {

	/**
	 * The admin page slug.
	 *
	 * @since 2.0.0
	 */
	const SLUG = 'optin-monster-dashboard';

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
	 * @var OMAPI
	 */
	public $base;

	/**
	 * The OMAPI_Pages object.
	 *
	 * @since 1.9.10
	 *
	 * @var OMAPI_Pages
	 */
	public $pages = null;

	/**
	 * Panel slugs/names.
	 *
	 * @since 1.9.0
	 *
	 * @var array
	 */
	public $panels = array();

	/**
	 * Registered page hooks.
	 *
	 * @since 1.9.10
	 *
	 * @var array
	 */
	public $hooks = array();

	/**
	 * The OM landing page url.
	 *
	 * @since 1.8.4
	 */
	const LANDING_URL = 'https://optinmonster.com/wp/?utm_source=orgplugin&utm_medium=link&utm_campaign=wpdashboard';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_testing Whether we are doing integration testing.
	 */
	public function __construct( $is_testing = false ) {
		if ( ! $is_testing ) {
			// Set our object.
			$this->set();

			// Load actions and filters.
			add_action( 'admin_menu', array( $this, 'menu' ) );
			add_action( 'admin_menu', array( $this, 'after_menu_registration' ), 999 );

			// Load custom admin bar menu items.
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 999 );

			// Load helper body classes.
			add_filter( 'admin_body_class', array( $this, 'admin_body_classes' ) );

			add_filter( 'plugin_action_links_' . plugin_basename( OMAPI_FILE ), array( $this, 'output_plugin_links' ) );

			// Add upgrade link to plugin page.
			add_filter( 'plugin_row_meta', array( $this, 'maybe_add_upgrade_link' ), 10, 2 );
		}
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
	 * Loads the OptinMonster admin menu.
	 *
	 * @since 1.0.0
	 */
	public function menu() {
		$this->pages = new OMAPI_Pages();
		$this->pages->setup();

		// Filter to change the menu position if there is any conflict with another menu on the same position.
		$menu_position = apply_filters( 'optin_monster_api_menu_position', 26 );

		$this->hooks[] = add_menu_page(
			'OptinMonster',
			'OptinMonster' . $this->notifications_count(),
			$this->base->access_capability( self::SLUG ),
			self::SLUG,
			array( $this->pages, 'render_app_loading_page' ),
			$this->icon_svg(),
			$menu_position
		);

		// Just add a placeholder secondary page.
		$this->hooks[] = add_submenu_page(
			self::SLUG, // parent slug.
			__( 'Dashboard', 'optin-monster-api' ), // page title.
			__( 'Dashboard', 'optin-monster-api' ), // menu title.
			$this->base->access_capability( self::SLUG ),
			self::SLUG,
			array( $this->pages, 'render_app_loading_page' )
		);

		$this->hooks = array_merge( $this->hooks, $this->pages->register_submenu_pages( self::SLUG ) );

		// Register our old api page and redirect to the new dashboard.
		$hook = add_submenu_page(
			self::SLUG . '-hidden',
			'OptinMonster',
			'OptinMonster',
			$this->base->access_capability( self::SLUG ),
			'optin-monster-api-settings',
			'__return_null'
		);
		add_action( 'load-' . $hook, array( $this, 'redirect_to_dashboard' ) );

		// Register link under the appearance menu for "Popup Builder".
		global $submenu;
		if ( current_user_can( $this->base->access_capability( self::SLUG ) ) && $submenu ) {
			$submenu['themes.php'][] = array(
				esc_html__( 'Popup Builder', 'optin-monster-api' ),
				$this->base->access_capability( self::SLUG ),
				esc_url_raw( OMAPI_Urls::templates() ),
			);
		}

		// Maybe add custom CSS for our menu upgrade link.
		if ( $this->base->can_show_upgrade() ) {
			add_action( 'admin_footer', array( $this, 'add_upgrade_link_css' ) );
		}
	}

	/**
	 * Loads custom items in the WP admin bar menu.
	 *
	 * @since 2.6.12
	 *
	 * @param object $admin_bar The WP admin bar object.
	 */
	public function admin_bar_menu( $admin_bar ) {
		if ( ! current_user_can( $this->base->access_capability( self::SLUG ) ) ) {
			return;
		}

		$admin_bar->add_node(
			array(
				'id'     => 'om-new-campaign',
				'title'  => esc_html__( 'Popup', 'optin-monster-api' ),
				'href'   => esc_url_raw( OMAPI_Urls::templates() ),
				'parent' => 'new-content',
			)
		);
	}

	/**
	 * Get the Archie SVG, and maybe encode it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $fill Color of Archie.
	 * @param bool   $return_encoded Whether the svg shoud be base_64 encoded.
	 *
	 * @return string Archie SVG.
	 */
	public function icon_svg( $fill = '#a0a5aa', $return_encoded = true ) {
		$icon = file_get_contents( plugin_dir_path( OMAPI_FILE ) . '/assets/css/images/icons/archie-icon.svg' );
		$icon = str_replace( 'fill="currentColor"', 'fill="' . $fill . '"', $icon );

		if ( $return_encoded ) {
			$icon = 'data:image/svg+xml;base64,' . base64_encode( $icon ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		}

		return $icon;
	}

	/**
	 * Handles enqueueing assets for registered pages, and ensuring about page is at bottom.
	 *
	 * @since  1.9.10
	 *
	 * @return void
	 */
	public function after_menu_registration() {
		global $submenu;

		// Make sure the about page is still the last page.
		if ( isset( $submenu[ self::SLUG ] ) ) {
			$after  = array();
			$at_end = array( 'optin-monster-about', 'optin-monster-upgrade', 'optin-monster-bfcm' );
			foreach ( $submenu[ self::SLUG ] as $key => $menu ) {
				if ( isset( $menu[2] ) && in_array( $menu[2], $at_end ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$after[] = $menu;
					unset( $submenu[ self::SLUG ][ $key ] );
				}
			}
			$submenu[ self::SLUG ] = array_values( $submenu[ self::SLUG ] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			foreach ( $after as $menu ) {
				$submenu[ self::SLUG ][] = $menu; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		// Load settings page assets.
		foreach ( $this->hooks as $hook ) {
			if ( ! empty( $hook ) ) {
				add_action( 'load-' . $hook, array( $this, 'assets' ) );
			}
		}
	}

	/**
	 * Add pages to plugin action links in the Plugins table.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $links Default plugin action links.
	 *
	 * @return array $links Amended plugin action links.
	 */
	public function output_plugin_links( $links ) {

		// Maybe add an upgrade link to the plugin links.
		$upgrade_links = array();
		if ( $this->base->can_show_upgrade() ) {
			$upgrade_links[] = sprintf( '<a class="om-plugin-upgrade-link" href="%s">%s</a>', OMAPI_Urls::upgrade( 'plugin_action_link' ), 'vbp_pro' === $this->base->get_level() ? __( 'Upgrade to Growth', 'optin-monster-api' ) : __( 'Upgrade to Pro', 'optin-monster-api' ) );
		}

		$new_links = $this->base->get_api_credentials()
			? array(
				sprintf( '<a href="%s">%s</a>', OMAPI_Urls::campaigns(), __( 'Campaigns', 'optin-monster-api' ) ),
				sprintf( '<a href="%s">%s</a>', OMAPI_Urls::settings(), __( 'Settings', 'optin-monster-api' ) ),
			)
			: array(
				sprintf( '<a href="%s">%s</a>', OMAPI_Urls::onboarding(), __( 'Get Started', 'optin-monster-api' ) ),
			);

		$links = array_merge( $upgrade_links, $new_links, $links );

		return $links;
	}

	/**
	 * Add upgrade link to the plugin row.
	 *
	 * @since 2.4.0
	 *
	 * @param  array  $links Default plugin row links.
	 * @param string $file  The plugin file.
	 *
	 * @return array The links array.
	 */
	public function maybe_add_upgrade_link( $links, $file ) {
		if ( $file === plugin_basename( OMAPI_FILE ) ) {

			// If user upgradeable or not registered yet, let's put an upgrade link.
			if ( $this->base->can_show_upgrade() ) {
				$label = 'vbp_pro' === $this->base->get_level()
					? __( 'Upgrade to Growth', 'optin-monster-api' )
					: __( 'Upgrade to Pro', 'optin-monster-api' );

				$upgradeLink = sprintf(
					'<a class="om-plugin-upgrade-link" href="%s" aria-label="%s" target="_blank" rel="noopener">%s</a>',
					esc_url_raw( OMAPI_Urls::upgrade( 'plugin_row_meta' ) ),
					$label,
					$label
				);

				array_splice( $links, 1, 0, array( $upgradeLink ) );
			}
		}

		return $links;
	}

	/**
	 * Adds om admin body classes
	 *
	 * @since  1.3.4
	 *
	 * @param  array $classes Body classes.
	 *
	 * @return array
	 */
	public function admin_body_classes( $classes ) {

		$classes .= ' omapi-screen ';

		if ( $this->base->get_api_key_errors() ) {
			$classes .= ' omapi-has-api-errors ';
		}

		return $classes;

	}

	/**
	 * Check if we're on one of the OM menu/sub-menu pages.
	 *
	 * @since  1.9.0
	 *
	 * @return boolean
	 */
	public function is_om_page() {
		if ( ! is_admin() ) {
			return false;
		}

		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			$page   = $screen->id;
			if ( false !== strpos( $page, 'toplevel_page_optin-monster-' ) ) {
				return true;
			}

			if ( ! empty( $screen->parent_base ) && false !== strpos( $screen->parent_base, 'optin-monster-' ) ) {
				return true;
			}
		} else {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		}

		return false !== strpos( $page, 'optin-monster' );
	}

	/**
	 * Loads assets for the settings page.
	 *
	 * @since 1.0.0
	 */
	public function assets() {
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_filter( 'admin_footer_text', array( $this, 'footer' ) );
		add_action( 'in_admin_header', array( $this, 'output_plugin_screen_banner' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'fix_plugin_js_conflicts' ), 100 );
		add_action( 'admin_print_footer_scripts', array( $this, 'fix_plugin_js_conflicts' ), 100 );
	}

	/**
	 * Register and enqueue settings page specific CSS.
	 *
	 * @since 1.0.0
	 */
	public function styles() {
		$version = $this->base->asset_version();
		$prefix  = $this->base->plugin_slug . '-';

		wp_enqueue_style( $prefix . 'font-awesome', $this->base->url . 'assets/css/font-awesome.min.css', array(), $version );

		wp_enqueue_style( $prefix . 'common', $this->base->url . 'assets/dist/css/common.min.css', array( $prefix . 'font-awesome' ), $version );

		// Run a hook to load in custom styles.
		do_action( 'optin_monster_api_admin_styles' );

	}

	/**
	 * Register and enqueue settings page specific JS.
	 *
	 * @since 1.0.0
	 */
	public function scripts() {
		$version = $this->base->asset_version();

		wp_register_script(
			$this->base->plugin_slug . '-admin',
			$this->base->url . 'assets/dist/js/admin.min.js',
			array( 'jquery' ),
			$version,
			true
		);

		wp_enqueue_script( $this->base->plugin_slug . '-admin' );

		// Run a hook to load in custom styles.
		do_action( 'optin_monster_api_admin_scripts' );
	}

	/**
	 * Deque specific scripts that cause conflicts on settings page. E.g.
	 * - optimizely
	 * - bigcommerce
	 * - learnpress
	 *
	 * @since 1.1.5.9
	 */
	public function fix_plugin_js_conflicts() {
		if ( $this->is_om_page() ) {
			global $wp_scripts;

			$remove = array(
				'lp-',
				'optimizely',
				'bigcommerce-',
			);
			foreach ( $wp_scripts->queue as $script ) {
				foreach ( $remove as $search ) {
					if ( 0 === strpos( $script, $search ) ) {

						// Dequeue scripts that might cause our settings not to work properly.
						wp_dequeue_script( $script );
					}
				}
			}
		}
	}

	/**
	 * Customizes the footer text on the OptinMonster settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text  The default admin footer text.
	 * @return string $text Amended admin footer text.
	 */
	public function footer( $text ) {
		$url = 'https://wordpress.org/support/plugin/optinmonster/reviews?filter=5#new-post';
		/* translators: %1$s - OptinMonster plugin support url */
		$text = sprintf( __( 'Please rate <strong>OptinMonster</strong> <a href="%1$s" target="_blank" rel="noopener">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%1$s" target="_blank" rel="noopener noreferrer">WordPress.org</a> to help us spread the word. Thank you from the OptinMonster team!', 'optin-monster-api' ), $url );

		return $text;
	}

	/**
	 * Echo out plugin header banner
	 *
	 * @since 1.1.5.2
	 */
	public function output_plugin_screen_banner() {
		$path   = 'vue/dist';
		$dir    = trailingslashit( dirname( $this->base->file ) ) . $path;
		$loader = new OMAPI_AssetLoader( $dir );
		$logo   = '#';
		$help   = '#';

		$list = $loader->getAssetsList( $dir );
		foreach ( $list as $item ) {
			if (
				false !== strpos( $item, 'logo-om' )
				&& preg_match( '/\.svg$/', $item )
			) {
				$logo = $loader->getAssetUri( trim( $item, '/' ), $this->base->url . $path );
			}

			if ( false !== strpos( $item, '/help-circle.' ) ) {
				$help = $loader->getAssetUri( trim( $item, '/' ), $this->base->url . $path );
			}
		}

		$this->base->output_view( 'plugin-banner.php', compact( 'logo', 'help' ) );
	}

	/**
	 * Get the parent slug (contextual based on beta being enabled).
	 *
	 * @since  1.9.10
	 *
	 * @return string
	 */
	public function parent_slug() {
		return self::SLUG;
	}

	/**
	 * Redirects to main OM page.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $args Array of query args.
	 *
	 * @return void
	 */
	public function redirect_to_dashboard( $args = array() ) {
		$url = OMAPI_Urls::dashboard( $args );
		wp_safe_redirect( esc_url_raw( $url ) );
		exit;
	}

	/**
	 * Add the notifications bubble to the menu.
	 *
	 * @since  2.0.0
	 *
	 * @return string Notifications bubble markup.
	 */
	public function notifications_count() {
		$count = apply_filters( 'optin_monster_api_notifications_count', 0, $this );
		$count = absint( $count );
		$html  = '';

		if ( $count ) {
			$html .= sprintf(
				' <span class="om-notifications-count update-plugins count-%1$d"><span class="plugin-count">%2$s</span></span>',
				$count,
				esc_html( number_format_i18n( $count ) )
			);

		}

		add_action( 'admin_footer', array( $this, 'add_jiggle_css' ) );

		return $html;
	}

	/**
	 * Output the css that jiggles the OM notification count bubble.
	 *
	 * @since 2.0.0
	 */
	public function add_jiggle_css() {
		$this->base->output_min_css( 'jiggle-css.php' );
	}

	/**
	 * Output the css that highlights the OM upgrade menu link.
	 *
	 * @since 2.6.12
	 */
	public function add_upgrade_link_css() {
		$this->base->output_min_css( 'upgrade-link-css.php' );
	}
}
