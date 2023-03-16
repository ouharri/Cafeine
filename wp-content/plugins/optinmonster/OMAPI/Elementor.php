<?php
/**
 * Elementor class.
 *
 * @since 2.2.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Elementor class.
 *
 * @since 2.2.0
 */
class OMAPI_Elementor {

	/**
	 * Holds the class object.
	 *
	 * @since 1.7.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.7.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.7.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * The minimum Elementor version required.
	 *
	 * @since 2.11.2
	 *
	 * @var string
	 */
	const MINIMUM_VERSION = '3.1.0';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Skip if Elementor is not available.
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		// Check if Elementor is the minimum version.
		if ( ! self::is_minimum_version() ) {
			return;
		}

		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_assets' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widget' ), 999 );
		add_action( 'optin_monster_should_set_campaigns_as_preview', array( $this, 'maybe_set_campaigns_as_preview' ) );
		add_action( 'optin_monster_display_media_button', array( $this, 'maybe_show_campaign_button' ), 10, 2 );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.7.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Load an integration css in the elementor document.
	 *
	 * @since 2.2.0
	 */
	public function editor_assets() {
		 // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['action'] ) || 'elementor' !== $_GET['action'] ) {
			return;
		}

		$css_handle = $this->base->plugin_slug . '-elementor-admin';
		wp_enqueue_style(
			$css_handle,
			$this->base->url . 'assets/dist/css/elementor-admin.min.css',
			array(),
			$this->base->asset_version()
		);

		$this->maybe_enqueue_dark_mode( $css_handle );
	}

	/**
	 * Handle enqueueing the dark-mode css. Will be conditionally displayed based on the UI setting.
	 *
	 * We have to do this until Elementor has better handling for dark-mode via a body class
	 *
	 * @see https://github.com/elementor/elementor/issues/13419
	 *
	 * @since 2.2.0
	 *
	 * @param  string $css_handle Non-dark mode handle.
	 *
	 * @return bool|string
	 */
	protected function maybe_enqueue_dark_mode( $css_handle ) {

		$ui_theme = \Elementor\Core\Settings\Manager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

		if ( 'light' === $ui_theme ) {
			return false;
		}

		$ui_theme_media_queries = 'auto' === $ui_theme
			? '(prefers-color-scheme: dark)'
			: 'all';

		wp_enqueue_style(
			$css_handle . '-dark-mode',
			$this->base->url . 'assets/dist/css/elementor-admin-dark.min.css',
			array( $css_handle ),
			$this->base->asset_version(),
			$ui_theme_media_queries
		);
	}

	/**
	 * Return the Elementor versions string.
	 *
	 * @since 2.11.2
	 *
	 * @return string
	 */
	public static function version() {
		return defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '0.0.0';
	}

	/**
	 * Determines if the passed version string passes the operator compare
	 * against the currently installed version of Elementor.
	 *
	 * Defaults to checking if the current Elementor version is greater than
	 * the passed version.
	 *
	 * @since 2.11.2
	 *
	 * @param string $version  The version to check.
	 * @param string $operator The operator to use for comparison.
	 *
	 * @return string
	 */
	public static function version_compare( $version = '', $operator = '>=' ) {
		return version_compare( self::version(), $version, $operator );
	}

	/**
	 * Determines if the current Elementor version meets the minimum version
	 * requirement.
	 *
	 * @since 2.11.2
	 *
	 * @return boolean
	 */
	public static function is_minimum_version() {
		return self::version_compare( self::MINIMUM_VERSION );
	}

	/**
	 * Register WPForms Widget.
	 *
	 * @since 2.2.0
	 *
	 * @param \Elementor\Widgets_Manager $widget_manager Elementor widget manager object.
	 */
	public function register_widget( $widget_manager ) {
		$widget_manager->register_widget_type( new OMAPI_Elementor_Widget() );

		// We need to override the button widget with our extended version.
		$widget_manager->register_widget_type( new OMAPI_Elementor_ButtonWidget() );
	}

	/**
	 * Set the preview flag if in the elementor preview mode.
	 *
	 * @since 2.2.0
	 *
	 * @param  bool $is_preview Whether we're currently in preview mode.
	 *
	 * @return bool              Whether we're in preview mode.
	 */
	public function maybe_set_campaigns_as_preview( $is_preview ) {
		if ( ! $is_preview ) {
			$is_preview = \Elementor\Plugin::instance()->preview->is_preview_mode();
		}

		return $is_preview;
	}

	/**
	 * Show the editor campaign media button if in the elementor editor.
	 *
	 * @since 2.3.0
	 *
	 * @param  bool $show Whether button will show.
	 *
	 * @return bool       Whether button will show.
	 */
	public function maybe_show_campaign_button( $show, $editor_id ) {
		$edit_mode = \Elementor\Plugin::instance()->editor->is_edit_mode();
		if ( $edit_mode ) {
			$show = true;
			add_action( 'elementor/editor/footer', array( $this->base->classicEditor, 'shortcode_modal' ) );
		}

		return $show;
	}
}
