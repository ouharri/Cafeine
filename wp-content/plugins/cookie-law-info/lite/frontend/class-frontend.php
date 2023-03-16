<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.webtoffee.com/
 * @since      3.0.0
 *
 * @package    CookieYes
 * @subpackage CookieYes/Frontend
 */

namespace CookieYes\Lite\Frontend;

use CookieYes\Lite\Admin\Modules\Banners\Includes\Controller;
use CookieYes\Lite\Admin\Modules\Settings\Includes\Settings;
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CookieYes
 * @subpackage CookieYes\Lite\Frontend
 * @author     WebToffee <info@webtoffee.com>
 */
class Frontend {

	/**
	 * The ID of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $plugin_name  The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Admin modules of the plugin
	 *
	 * @var array
	 */
	private static $modules;

	/**
	 * Currently active modules
	 *
	 * @var array
	 */
	private static $active_modules;

	/**
	 * Existing modules
	 *
	 * @var array
	 */
	public static $existing_modules;

	/**
	 * Banner object
	 *
	 * @var object
	 */
	protected $banner;

	/**
	 * Plugin settings
	 *
	 * @var object
	 */
	protected $settings;
	/**
	 * Banner template
	 *
	 * @var object
	 */
	protected $template;

	/**
	 * Providers list
	 *
	 * @var array
	 */
	protected $providers = array();
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->load_modules();
		$this->settings = new Settings();
		add_action( 'init', array( $this, 'load_banner' ) );
		add_action( 'wp_footer', array( $this, 'banner_html' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
		add_action( 'wp_head', array( $this, 'insert_script' ), 1 );
		add_action( 'wp_head', array( $this, 'insert_styles' ) );
	}

	/**
	 * Get the default modules array
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public function get_default_modules() {
		$modules = array();
		return $modules;
	}

	/**
	 * Load all the modules
	 *
	 * @return void
	 */
	public function load_modules() {
		if ( true === cky_disable_banner() ) {
			return;
		}
		foreach ( $this->get_default_modules() as $module ) {
			$parts      = explode( '_', $module );
			$class      = implode( '_', $parts );
			$class      = str_ireplace( '-', '_', $class );
			$module     = str_ireplace( '-', '_', $module );
			$class_name = 'CookieYes\Lite\\Frontend\\Modules\\' . ucwords( $module, '_' ) . '\\' . ucwords( $class, '_' );

			if ( class_exists( $class_name ) ) {
				$module_obj = new $class_name( $module );
				if ( $module_obj instanceof $class_name ) {
					if ( $module_obj->is_active() ) {
						$module_obj->init();
						self::$active_modules[ $module ] = true;
					}
				}
			}
		}
	}

	/**
	 * Enqeue front end scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( true === cky_disable_banner() ) {
			return;
		}
		if ( false === $this->settings->is_connected() ) {
			if ( ! $this->template ) {
				return;
			}
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$css    = isset( $this->template['styles'] ) ? $this->template['styles'] : '';
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/script' . $suffix . '.js', array(), $this->version, false );
			wp_localize_script( $this->plugin_name, '_ckyConfig', $this->get_store_data() );
			wp_localize_script( $this->plugin_name, '_ckyStyles', array( 'css' => $css ) );
		}
	}

	/**
	 * Add inline styles to the head
	 *
	 * @return void
	 */
	public function insert_styles() {
		if ( true === $this->settings->is_connected() || true === cky_disable_banner() || is_admin() ) {
			return;
		}
		echo '<style id="cky-style-inline">[data-cky-tag]{visibility:hidden;}</style>';
	}
	/**
	 * Add CookieYes web app script on the header.
	 *
	 * @return void
	 */
	public function insert_script() {
		if ( false === $this->settings->is_connected() || true === cky_disable_banner() ) {
			return;
		}
		echo '<script id="cookieyes" type="text/javascript" src="' . esc_url( $this->settings->get_script_url() ) . '"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
	}
	/**
	 * Load active banner.
	 *
	 * @return void
	 */
	public function load_banner() {
		if ( true === $this->settings->is_connected() || true === cky_disable_banner() ) {
			return;
		}
		if ( ! cky_is_front_end_request() ) {
			return;
		}
		$this->banner = Controller::get_instance()->get_active_banner();
		if ( false === $this->banner ) {
			return;
		}
		$this->template = $this->banner->get_template();
	}
	/**
	 * Print banner HTML as script template using
	 * type="text/template" attribute
	 *
	 * @return void
	 */
	public function banner_html() {
		if ( ! $this->template || true === cky_disable_banner() ) {
			return;
		}
		$html = isset( $this->template['html'] ) ? $this->template['html'] : '';
		echo '<script id="ckyBannerTemplate" type="text/template">';
		echo wp_kses( $html, cky_allowed_html() );
		echo '</script>';
	}
	/**
	 * Modify tags to now show any `id` attribute.
	 *
	 * @param string $tag The `<script>` tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 */
	public function script_loader_tag( $tag, $handle ) {
		if ( false === $this->settings->is_connected() || true === cky_disable_banner() ) {
			return $tag;
		}
		if ( $handle === $this->plugin_name ) {
			$tag = '<script id="cookieyes" type="text/javascript" src="' . esc_attr( $this->settings->get_script_url() ) . '"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		}
		return $tag;
	}
	/**
	 * Get store data
	 *
	 * @return array
	 */
	public function get_store_data() {
		if ( ! $this->banner ) {
			return;
		}
		$settings        = get_option( 'cky_settings' );
		$banner          = $this->banner;
		$banner_settings = $banner->get_settings();

		$providers = array();
		$store     = array(
			'_ipData'       => array(),
			'_assetsURL'    => CKY_PLUGIN_URL . 'frontend/images/',
			'_publicURL'    => get_site_url(),
			'_expiry'       => isset( $banner_settings['settings']['consentExpiry']['value'] ) ? absint( $banner_settings['settings']['consentExpiry']['value'] ) : 365,
			'_categories'   => $this->get_cookie_groups(),
			'_activeLaw'    => 'gdpr',
			'_rootDomain'   => $this->get_cookie_domain(),
			'_block'        => true,
			'_showBanner'   => true,
			'_bannerConfig' => $this->prepare_config(),
			'_version'      => $this->version,
			'_logConsent'   => isset( $settings['consent_logs']['status'] ) && true === $settings['consent_logs']['status'] ? true : false,
			'_tags'         => $this->prepare_tags(),
			'_shortCodes'   => $this->prepare_shortcodes( $banner->get_settings() ),
			'_rtl'          => $this->is_rtl(),
		);
		foreach ( $this->providers as $key => $value ) {
			$providers[] = array(
				're'         => $key,
				'categories' => $value,
			);
		}
		$store['_providersToBlock'] = $providers;
		return $store;
	}
	/**
	 * Return cookie domain
	 *
	 * @return string
	 */
	public function get_cookie_domain() {
		return apply_filters( 'cky_cookie_domain', '' );
	}
	/**
	 * Get cookie groups
	 *
	 * @return array
	 */
	public function get_cookie_groups() {
		$cookie_groups = array();
		$categories    = \CookieYes\Lite\Admin\Modules\Cookies\Includes\Category_Controller::get_instance()->get_items();

		foreach ( $categories as $category ) {
			$category        = new \CookieYes\Lite\Admin\Modules\Cookies\Includes\Cookie_Categories( $category );
			$cookie_groups[] = array(
				'name'           => $category->get_name( cky_current_language() ),
				'slug'           => $category->get_slug(),
				'isNecessary'    => 'necessary' === $category->get_slug() ? true : false,
				'ccpaDoNotSell'  => $category->get_sell_personal_data(),
				'cookies'        => $this->get_cookies( $category ),
				'active'         => true,
				'defaultConsent' => array(
					'gdpr' => $category->get_prior_consent(),
					'ccpa' => 'necessary' === $category->get_slug() || $category->get_sell_personal_data() === false ? true : false,
				),
			);
		}
		return $cookie_groups;
	}
	/**
	 * Get cookies by category
	 *
	 * @param object $category Category object.
	 * @return array
	 */
	public function get_cookies( $category = '' ) {
		$cookies  = array();
		$cat_slug = $category->get_slug();
		$items    = \CookieYes\Lite\Admin\Modules\Cookies\Includes\Cookie_Controller::get_instance()->get_items_by_category( $category->get_id() );
		foreach ( $items as $item ) {
			$cookie    = new \CookieYes\Lite\Admin\Modules\Cookies\Includes\Cookie( $item->cookie_id );
			$cookies[] = array(
				'cookieID' => $cookie->get_name(),
				'domain'   => $cookie->get_domain(),
				'provider' => $cookie->get_url_pattern(),
			);
			$provider  = $cookie->get_url_pattern();
			if ( '' !== $provider && 'necessary' !== $cat_slug ) {
				if ( ! isset( $this->providers[ $provider ] ) ) {
					$this->providers[ $provider ] = array();
				}
				if ( isset( $this->providers[ $provider ] ) && ! in_array( $cat_slug, $this->providers[ $provider ], true ) ) {
					$this->providers[ $provider ][] = $cat_slug;
				}
			}
		}
		return $cookies;
	}

	/**
	 * Prepare the HTML elements tags for front-end script.
	 *
	 * @return array
	 */
	public function prepare_tags() {
		$data = array();
		if ( ! $this->banner ) {
			return;
		}
		$settings  = $this->banner->get_settings();
		$configs   = isset( $settings['config'] ) ? $settings['config'] : array();
		$supported = array(
			'accept-button',
			'reject-button',
			'settings-button',
			'readmore-button',
			'donotsell-button',
			'accept-button',
			'revisit-consent',
		);
		foreach ( $supported as $tag ) {
			$config = cky_array_search( $configs, 'tag', $tag );
			$data[] = array(
				'tag'    => $tag,
				'styles' => isset( $config['styles'] ) ? $config['styles'] : array(),
			);
		}
		return $data;
	}

	/**
	 * Prepare config for the front-end processing
	 *
	 * @return array
	 */
	public function prepare_config() {
		$data   = array();
		$banner = $this->banner;

		if ( ! $banner ) {
			return $data;
		}

		$properties                                   = $banner->get_settings();
		$data['settings']['type']                     = $properties['settings']['type'];
		$data['settings']['position']                 = $properties['settings']['position'];
		$data['settings']['applicableLaw']            = $properties['settings']['applicableLaw'];
		$data['behaviours']['reloadBannerOnAccept']   = $properties['behaviours']['reloadBannerOnAccept']['status'];
		$data['behaviours']['loadAnalyticsByDefault'] = $properties['behaviours']['loadAnalyticsByDefault']['status'];
		$data['behaviours']['animations']             = $properties['behaviours']['animations'];
		$data['config']['revisitConsent']             = $properties['config']['revisitConsent'];
		$data['config']['preferenceCenter']['toggle'] = $properties['config']['preferenceCenter']['elements']['categories']['elements']['toggle'];
		$data['config']['categoryPreview']['status']  = $properties['config']['categoryPreview']['status'];
		$data['config']['categoryPreview']['toggle']  = $properties['config']['categoryPreview']['elements']['toggle'];
		$data['config']['videoPlaceholder']['status'] = $properties['config']['videoPlaceholder']['status'];
		$data['config']['videoPlaceholder']['styles'] = array_merge( $properties['config']['videoPlaceholder']['styles'], $properties['config']['videoPlaceholder']['elements']['title']['styles'] );
		$data['config']['readMore']                   = $properties['config']['notice']['elements']['buttons']['elements']['readMore'];
		$data['config']['auditTable']['status']       = $properties['config']['auditTable']['status'];
		$data['config']['optOption']['status']        = $properties['config']['optoutPopup']['elements']['optOption']['status'];
		$data['config']['optOption']['toggle']        = $properties['config']['optoutPopup']['elements']['optOption']['elements']['toggle'];
		return $data;
	}

	/**
	 * Prepare shortcodes to be used on visitor side.
	 *
	 * @param array $properties Banner properties.
	 * @return array
	 */
	public function prepare_shortcodes( $properties = array() ) {

		$settings   = isset( $properties['settings'] ) ? $properties['settings'] : array();
		$version_id = isset( $settings['versionID'] ) ? $settings['versionID'] : 'default';
		$shortcodes = new \CookieYes\Lite\Frontend\Modules\Shortcodes\Shortcodes( $this->banner, $version_id );
		$data       = array();
		$configs    = ( isset( $properties['config'] ) && is_array( $properties['config'] ) ) ? $properties['config'] : array();
		$config     = cky_array_search( $configs, 'tag', 'readmore-button' );
		$attributes = array();
		if ( isset( $config['meta']['noFollow'] ) && true === $config['meta']['noFollow'] ) {
			$attributes['rel'] = 'nofollow';
		}
		if ( isset( $config['meta']['newTab'] ) && true === $config['meta']['newTab'] ) {
			$attributes['target'] = '_blank';
		}
		$data[] = array(
			'key'        => 'cky_readmore',
			'content'    => do_shortcode( '[cky_readmore]' ),
			'tag'        => 'readmore-button',
			'status'     => isset( $config['status'] ) && true === $config['status'] ? true : false,
			'attributes' => $attributes,
		);
		$data[] = array(
			'key'        => 'cky_show_desc',
			'content'    => do_shortcode( '[cky_show_desc]' ),
			'tag'        => 'show-desc-button',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_hide_desc',
			'content'    => do_shortcode( '[cky_hide_desc]' ),
			'tag'        => 'hide-desc-button',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_category_toggle_label',
			'content'    => do_shortcode( '[cky_category_toggle_label]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_enable_category_label',
			'content'    => do_shortcode( '[cky_enable_category_label]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_disable_category_label',
			'content'    => do_shortcode( '[cky_disable_category_label]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);

		$data[] = array(
			'key'        => 'cky_video_placeholder',
			'content'    => do_shortcode( '[cky_video_placeholder]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_enable_optout_label',
			'content'    => do_shortcode( '[cky_enable_optout_label]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_disable_optout_label',
			'content'    => do_shortcode( '[cky_disable_optout_label]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_optout_toggle_label',
			'content'    => do_shortcode( '[cky_optout_toggle_label]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_optout_option_title',
			'content'    => do_shortcode( '[cky_optout_option_title]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		$data[] = array(
			'key'        => 'cky_optout_close_label',
			'content'    => do_shortcode( '[cky_optout_close_label]' ),
			'tag'        => '',
			'status'     => true,
			'attributes' => array(),
		);
		return $data;
	}

	/**
	 * Determines whether the current/given language code is right-to-left (RTL)
	 *
	 * @param string $language Current language.
	 * @return boolean
	 */
	public function is_rtl( $language = '' ) {
		if ( ! $language ) {
			$language = cky_current_language();
		}

		return in_array( $language, array( 'ar', 'az', 'dv', 'he', 'ku', 'fa', 'ur' ), true );
	}

}
