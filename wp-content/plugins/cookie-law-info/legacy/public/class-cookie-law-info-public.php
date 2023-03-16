<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://cookielawinfo.com/
 * @since      1.6.6
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/public
 * @author     WebToffee <info@webtoffee.com>
 */
class Cookie_Law_Info_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.6.6
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	public $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.6.6
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	public $version;

	public $plugin_obj;

	/*
	 * module list, Module folder and main file must be same as that of module name
	 * Please check the `register_modules` method for more details
	 */
	private $modules                = array(
		'script-blocker',
		'shortcode',
	);
	public static $existing_modules = array();
	public $cookie_categories;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.6.6
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */

	public function __construct( $plugin_name, $version, $plugin_obj ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->plugin_obj  = $plugin_obj;
		register_activation_hook( CLI_PLUGIN_FILENAME, array( $this, 'activator' ) );

	}
	public function activator() {

		$activation_transient = wp_validate_boolean( get_transient( '_wt_cli_first_time_activation' ) );

		if ( Cookie_Law_Info::maybe_first_time_install() === true ) {
			$js_blocking = wp_validate_boolean( Cookie_Law_Info::get_js_option() );
			if ( $js_blocking === false ) {
				update_option( 'cookielawinfo_js_blocking', 'yes' );
			}
		}

	}

	/**
	 * Set Category Cookies If Empty
	 *
	 * @since 1.7.7
	 */
	public function cli_set_category_cookies() {
		$js_blocking_enabled = Cookie_Law_Info::wt_cli_is_js_blocking_active();

		if ( $js_blocking_enabled === false ) {

			$cookie_category_data = apply_filters( 'wt_cli_cookie_categories', array() );
			$the_options          = Cookie_Law_Info::get_settings();

			if ( $the_options['is_on'] == true ) {

				foreach ( $cookie_category_data as $key => $data ) {
					if ( empty( $_COOKIE[ "cookielawinfo-checkbox-$key" ] ) ) {
						$category_enabled = isset( $data['enabled'] ) ? $data['enabled'] : false;
						$cookie_value     = ( isset( $data['default_state'] ) && $data['default_state'] === true ) ? 'yes' : 'no';
						if ( $category_enabled === false ) {
							return false;
						} else {
							if ( true === apply_filters( 'wt_cli_set_secure_cookies', false ) ) {
								@setcookie( "cookielawinfo-checkbox-$key", $cookie_value, time() + 3600, '/', '', true );
							} else {
								@setcookie( "cookielawinfo-checkbox-$key", $cookie_value, time() + 3600, '/' );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.6.6
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cookie_Law_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cookie_Law_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$the_options = Cookie_Law_Info::get_settings();
		if ( $the_options['is_on'] == true ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cookie-law-info-public.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-gdpr', plugin_dir_url( __FILE__ ) . 'css/cookie-law-info-gdpr.css', array(), $this->version, 'all' );
			// this style will include only when shortcode is called
			wp_register_style( $this->plugin_name . '-table', plugin_dir_url( __FILE__ ) . 'css/cookie-law-info-table.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.6.6
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cookie_Law_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cookie_Law_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$the_options               = Cookie_Law_Info::get_settings();
		$ccpa_enabled              = ( isset( $the_options['ccpa_enabled'] ) ? $the_options['ccpa_enabled'] : false );
		$ccpa_region_based         = ( isset( $the_options['ccpa_region_based'] ) ? $the_options['ccpa_region_based'] : false );
		$ccpa_enable_bar           = ( isset( $the_options['ccpa_enable_bar'] ) ? $the_options['ccpa_enable_bar'] : false );
		$ccpa_type                 = ( isset( $the_options['consent_type'] ) ? $the_options['consent_type'] : 'gdpr' );
		$js_blocking_enabled       = Cookie_Law_Info::wt_cli_is_js_blocking_active();
		$enable_custom_integration = apply_filters( 'wt_cli_enable_plugin_integration', false );
		$trigger_dom_reload        = apply_filters( 'wt_cli_script_blocker_trigger_dom_refresh', false );

		if ( $the_options['is_on'] == true ) {
			$non_necessary_cookie_ids = Cookie_Law_Info::get_non_necessary_cookie_ids();
			$cli_cookie_datas         = array(
				'nn_cookie_ids'         => ! empty( $non_necessary_cookie_ids ) ? $non_necessary_cookie_ids : array(),
				'cookielist'            => array(),
				'non_necessary_cookies' => $this->get_cookies_by_category(),
				'ccpaEnabled'           => $ccpa_enabled,
				'ccpaRegionBased'       => $ccpa_region_based,
				'ccpaBarEnabled'        => $ccpa_enable_bar,
				'strictlyEnabled'       => Cookie_Law_Info_Cookies::get_instance()->get_strictly_necessory_categories(),
				'ccpaType'              => $ccpa_type,
				'js_blocking'           => $js_blocking_enabled,
				'custom_integration'    => $enable_custom_integration,
				'triggerDomRefresh'     => $trigger_dom_reload,
				'secure_cookies'        => apply_filters( 'wt_cli_set_secure_cookies', false ),
			);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cookie-law-info-public.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'Cli_Data', $cli_cookie_datas );
			wp_localize_script( $this->plugin_name, 'cli_cookiebar_settings', Cookie_Law_Info::get_json_settings() );
			wp_localize_script( $this->plugin_name, 'log_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	/**
	 Registers modules: public+admin
	 */
	public function common_modules() {
		foreach ( $this->modules as $module ) {
			$module_file = plugin_dir_path( __FILE__ ) . "modules/$module/$module.php";
			if ( file_exists( $module_file ) ) {
				self::$existing_modules[] = $module; // this is for module_exits checking
				require_once $module_file;
			}
		}
	}
	public static function module_exists( $module ) {
		return in_array( $module, self::$existing_modules );
	}



	/** Removes leading # characters from a string */
	public static function cookielawinfo_remove_hash( $str ) {
		if ( $str[0] == '#' ) {
			$str = substr( $str, 1, strlen( $str ) );
		} else {
			return $str;
		}
		return self::cookielawinfo_remove_hash( $str );
	}

	/*
	/* Outputs the cookie control script in the footer
	/*  N.B. This script MUST be output in the footer.
	/* This function should be attached to the wp_footer action hook.
	 */
	public function cookielawinfo_inject_cli_script() {
		$the_options     = Cookie_Law_Info::get_settings();
		$show_cookie_bar = true;
		if ( apply_filters( 'wt_cli_hide_bar_on_page_editor', true ) && $this->is_page_editor_active() ) {
			$show_cookie_bar = false;
		}
		if ( $the_options['is_on'] == true && $show_cookie_bar ) {
			// Output the HTML in the footer:
			$message = nl2br( $the_options['notify_message'] );
			$str     = do_shortcode( stripslashes( $message ) );
			$head    = trim( stripslashes( $the_options['bar_heading_text'] ) );

			$notify_html = '<div id="' . $this->cookielawinfo_remove_hash( $the_options['notify_div_id'] ) . '" data-nosnippet="true">' .
				( $head != '' ? '<h5 class="cli_messagebar_head">' . wp_kses_post( $head ) . '</h5>' : '' )
				. '<span>' . $str . '</span></div>';

			$show_again   = stripslashes( $the_options['showagain_text'] );
			$notify_html .= '<div id="' . $this->cookielawinfo_remove_hash( $the_options['showagain_div_id'] ) . '" style="display:none;" data-nosnippet="true"><span id="cookie_hdr_showagain">' . esc_html( $show_again ) . '</span></div>';

			global $wp_query;
			$current_obj = get_queried_object();
			$post_slug   = '';
			if ( is_object( $current_obj ) ) {
				if ( is_category() || is_tag() ) {
					$post_slug = isset( $current_obj->slug ) ? $current_obj->slug : '';
				} elseif ( is_archive() ) {
					$post_slug = isset( $current_obj->rewrite ) && isset( $current_obj->rewrite['slug'] ) ? $current_obj->rewrite['slug'] : '';
				} else {
					if ( isset( $current_obj->post_name ) ) {
						$post_slug = $current_obj->post_name;
					}
				}
			}
			$notify_html = apply_filters( 'cli_show_cookie_bar_only_on_selected_pages', $notify_html, $post_slug );
			require_once plugin_dir_path( __FILE__ ) . 'views/cookie-law-info_bar.php';
		}
	}

	/* Print scripts or data in the head tag on the front end. */
	public function include_user_accepted_cookielawinfo() {
		$this->wt_cli_print_scripts( true );
	}
	public function wt_cli_head_scripts() {

	}
	public function wt_cli_print_scripts( $head = false ) {

		$the_options               = Cookie_Law_Info::get_settings();
		$advanced_script_rendering = Cookie_Law_Info::wt_cli_is_js_blocking_active();

		if ( $the_options['is_on'] == true && ! is_admin() ) {
			$cookie_categories = apply_filters( 'wt_cli_cookie_categories', array() );

			if ( ! empty( $cookie_categories ) ) {
				foreach ( $cookie_categories as $slug => $data ) {
					if ( isset( $data['status'] ) && $data['status'] === true ) {
						if ( $head === false ) {
							$scripts = $data['body_scripts'];
						} else {
							$scripts = $data['head_scripts'];
						}
						if ( ! empty( $scripts ) ) {
							$this->process_scripts( $scripts, $slug, $advanced_script_rendering, $head );
						}
					}
				}
			}
		}
	}
	public function process_scripts( $script, $slug, $advanced_script_rendering, $head ) {
		if ( $advanced_script_rendering === false ) {
			if ( $this->check_consent( $slug ) === true ) {
				echo $script;
			}
		} else {
			echo $this->pre_process_scripts( $slug, $script, $head );
		}
	}
	public function check_consent( $slug ) {

		$preference_cookie = isset( $_COOKIE[ 'cookielawinfo-checkbox-' . $slug ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ 'cookielawinfo-checkbox-' . $slug ] ) ) : 'no';
		$main_cookie       = isset( $_COOKIE['viewed_cookie_policy'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['viewed_cookie_policy'] ) ) : 'no';
		if ( $main_cookie === 'yes' && $preference_cookie === 'yes' || isset( $_GET['cli_bypass'] ) && get_option( 'CLI_BYPASS' ) == 1 ) {
			return true;
		}
		return false;
	}
	public function pre_process_scripts( $slug, $script, $head ) {
		$position = 'body';
		if ( $head === true ) {
			$position = 'head';
		}
		$replace = 'data-cli-class="cli-blocker-script"  data-cli-script-type="' . $slug . '" data-cli-block="true"  data-cli-element-position="' . $position . '"';
		$scripts = $this->replace_script_attribute_type( $script, $replace );
		return $scripts;
	}
	/* Print scripts or data in the body tag on the front end. */
	public function include_user_accepted_cookielawinfo_in_body() {
		$this->wt_cli_print_scripts();
	}
	/**
	 * Desceiption
	 *
	 * @since  1.8.9
	 * @param  string script
	 * @param  string replace
	 * @return string
	 */
	public function replace_script_attribute_type( $script, $replace ) {
		$textarr        = wp_html_split( $script );
		$replace_script = $script;
		$script_array   = ( isset( $textarr ) && is_array( $textarr ) ) ? $textarr : array();
		$changed        = false;
		$script_type    = 'text/plain';
		foreach ( $script_array as $i => $html ) {
			if ( preg_match( '/<script[^\>]*?\>/m', $script_array[ $i ] ) ) {
				$changed = true;
				if ( preg_match( '/<script.*(type=(?:"|\')(.*?)(?:"|\')).*?>/', $script_array[ $i ] ) && preg_match( '/<script.*(type=(?:"|\')text\/javascript(.*?)(?:"|\')).*?>/', $script_array[ $i ] ) ) {
					preg_match( '/<script.*(type=(?:"|\')text\/javascript(.*?)(?:"|\')).*?>/', $script_array[ $i ], $output_array );
					$re = preg_quote( $output_array[1], '/' );
					if ( ! empty( $output_array ) ) {

						$script_array[ $i ] = preg_replace( '/' . $re . '/', 'type="' . $script_type . '"' . ' ' . $replace, $script_array[ $i ], 1 );

					}
				} else {

					$script_array[ $i ] = str_replace( '<script', '<script type="' . $script_type . '"' . ' ' . $replace, $script_array[ $i ] );

				}
			}
		}
		if ( $changed === true ) {
			$replace_script = implode( $script_array );
		}
		return $replace_script;
	}
	public function wt_cli_bypass_script_blocking() {
		$bypass_blocking  = false;
		$the_options      = Cookie_Law_Info::get_settings();
		$ccpa_enabled     = Cookie_Law_Info::sanitise_settings( 'ccpa_enabled', ( isset( $the_options['ccpa_enabled'] ) ? $the_options['ccpa_enabled'] : false ) );
		$ccpa_bar_enabled = Cookie_Law_Info::sanitise_settings( 'ccpa_enable_bar', ( isset( $the_options['ccpa_enable_bar'] ) ? $the_options['ccpa_enable_bar'] : false ) );
		$consent_type     = Cookie_Law_Info::sanitise_settings( 'consent_type', ( isset( $the_options['consent_type'] ) ? $the_options['consent_type'] : 'gdpr' ) );
		if ( $ccpa_enabled === true && $consent_type === 'ccpa' ) {
			if ( $ccpa_bar_enabled === false ) {
				if ( ! isset( $_COOKIE['viewed_cookie_policy'] ) && self::do_not_sell_optout() === false ) {
					$bypass_blocking = true;
				}
			}
		}
		return $bypass_blocking;
	}
	/**
	 * Check whether opted in CCPA or not
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public static function do_not_sell_optout() {
		$preference_cookie = 'CookieLawInfoConsent';
		$ccpa_optout       = false;
		if ( isset( $_COOKIE[ $preference_cookie ] ) ) {
			$json_cookie = json_decode( base64_decode( sanitize_text_field( wp_unslash( $_COOKIE[ $preference_cookie ] ) ) ) );
			$ccpa_optout = ( isset( $json_cookie->ccpaOptout ) ? $json_cookie->ccpaOptout : false );
		}
		return $ccpa_optout;
	}
	public function get_cookies_by_category() {

		$cookie_categories = apply_filters( 'wt_cli_cookie_categories', array() );

		$categories = array();

		if ( ! empty( $cookie_categories ) ) {

			foreach ( $cookie_categories as $slug => $data ) {

				if ( isset( $data['status'] ) && $data['status'] === true ) {

					$cookies     = ( isset( $data['cookies'] ) && is_array( $data['cookies'] ) ) ? $data['cookies'] : array();
					$cookie_list = array();
					$strict      = isset( $data['strict'] ) ? $data['strict'] : false;

					if ( ! empty( $cookies ) ) {
						foreach ( $cookies as $key => $cookie ) {

							$sensitivity  = get_post_meta( $cookie->ID, '_cli_cookie_sensitivity', true );
							$cookie_title = get_post_meta( $cookie->ID, '_cli_cookie_slugid', true );
							$cookie_id    = ( ' ' !== $cookie_title ) ? $cookie_title : $cookie->post_title;

							if ( 'non-necessary' === $sensitivity || false === $strict ) {
								if ( false === $this->maybe_plugin_cookie( $cookie_id ) ) {
									$cookie_list[] = $cookie_id;
								}
							}
						}
					}
				}
				if ( ! empty( $cookie_list ) ) {
					$categories[ $slug ] = $cookie_list;
				}
			}
		}
		return $categories;
	}
	public function maybe_plugin_cookie( $cookie ) {
		if ( 'viewed_cookie_policy' === $cookie || false !== strpos( $cookie, 'cookielawinfo-checkbox' ) ) {
			return true;
		}
		return false;
	}
	/**
	 * Check whether any page editor is active or not
	 *
	 * @since  2.0.5
	 * @return bool
	 */
	public function is_page_editor_active() {
		global $wp_customize;
		if ( isset( $_GET['et_fb'] )
			|| ( defined( 'ET_FB_ENABLED' ) && ET_FB_ENABLED )
			|| isset( $_GET['elementor-preview'] )
			|| isset( $_POST['cs_preview_state'] )
			|| isset( $wp_customize )
		) {
			return true;
		}

		return false;
	}
}
