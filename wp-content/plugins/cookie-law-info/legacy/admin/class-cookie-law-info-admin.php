<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://cookielawinfo.com/
 * @since      1.6.6
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/admin
 * @author     WebToffee <info@webtoffee.com>
 */
class Cookie_Law_Info_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.6.6
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.6.6
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $plugin_obj;

	/*
	 * admin module list, Module folder and main file must be same as that of module name
	 * Please check the `admin_modules` method for more details
	 */
	private $modules = array(
		'cookies',
		'cli-policy-generator',
		'ccpa',
		'cookie-scaner',
		'uninstall-feedback',
	);

	public static $existing_modules = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.6.6
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_obj ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->plugin_obj  = $plugin_obj;
		add_action( 'admin_init', array( $this, 'load_plugin' ) );
		register_activation_hook( CLI_PLUGIN_FILENAME, array( $this, 'activator' ) );
		// since 1.9.5 Initialize plugin settings
		add_action( 'wt_cli_initialize_plugin', array( $this, 'initialize_plugin_settings' ) );
	}

	/**
	 * Store default datas to the database if a first time user
	 *
	 * @since  2.3.1
	 * @access public
	 */
	public function activator() {

		if ( Cookie_Law_Info::maybe_first_time_install() === true ) {
			add_option( 'wt_cli_first_time_activated_plugin', 'true' );
		}
	}

	public function set_default_settings() {
		$options = get_option( CLI_SETTINGS_FIELD );
		if ( $options === false ) {
			$default = Cookie_Law_Info::get_settings();
			update_option( CLI_SETTINGS_FIELD, $default );
		}
	}
	public function set_privacy_overview_options() {
		$options = get_option( 'cookielawinfo_privacy_overview_content_settings' );
		if ( $options === false ) {
			$default = self::get_privacy_defaults();
			update_option( 'cookielawinfo_privacy_overview_content_settings', $default );
		}
	}
	public function initialize_plugin_settings() {
		$this->set_default_settings();
		$this->set_privacy_overview_options();
	}
	/**
	 * Register the stylesheets for the admin area.
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
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == CLI_POST_TYPE || isset( $_GET['page'] ) && $_GET['page'] == 'cookie-law-info' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cookie-law-info-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
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
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == CLI_POST_TYPE ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cookie-law-info-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name,
				'ckyConfigs',
				array(
					'redirectUrl' => esc_url( admin_url( 'admin.php?page=cookie-law-info' ) )
				)
			);
		}

	}

	/**
	 Registers admin modules
	 */
	public function admin_modules() {
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

	/**
	 Registers menu options
	 Hooked into admin_menu
	 */
	public function admin_menu() {
		global $submenu;
		add_submenu_page(
			'edit.php?post_type=' . CLI_POST_TYPE,
			__( 'Settings', 'cookie-law-info' ),
			__( 'Settings', 'cookie-law-info' ),
			'manage_options',
			'cookie-law-info',
			array( $this, 'admin_settings_page' )
		);
		add_submenu_page(
			'edit.php?post_type=' . CLI_POST_TYPE,
			__( 'Privacy Overview', 'cookie-law-info' ),
			__( 'Privacy Overview', 'cookie-law-info' ),
			'manage_options',
			'cookie-law-info-poverview',
			array( $this, 'privacy_overview_page' )
		);
		// rearrange settings menu
		if ( isset( $submenu ) && ! empty( $submenu ) && is_array( $submenu ) ) {
			$out                   = array();
			$back_up_settings_menu = array();
			if ( isset( $submenu[ 'edit.php?post_type=' . CLI_POST_TYPE ] ) && is_array( $submenu[ 'edit.php?post_type=' . CLI_POST_TYPE ] ) ) {
				foreach ( $submenu[ 'edit.php?post_type=' . CLI_POST_TYPE ] as $key => $value ) {
					if ( $value[2] == 'cookie-law-info' ) {
						$back_up_settings_menu = $value;
					} else {
						$out[ $key ] = $value;
					}
				}
				array_unshift( $out, $back_up_settings_menu );
				$submenu[ 'edit.php?post_type=' . CLI_POST_TYPE ] = $out;
			}
		}
	}
	/**
	 * Return the default privacy overview contents
	 *
	 * @since  1.9.2
	 * @return array
	 */
	public static function get_privacy_defaults() {

		$settings = array(
			'privacy_overview_content' => 'This website uses cookies to improve your experience while you navigate through the website. Out of these, the cookies that are categorized as necessary are stored on your browser as they are essential for the working of basic functionalities of the website. We also use third-party cookies that help us analyze and understand how you use this website. These cookies will be stored in your browser only with your consent. You also have the option to opt-out of these cookies. But opting out of some of these cookies may affect your browsing experience.',
			'privacy_overview_title'   => 'Privacy Overview',
		);
		return $settings;
	}
	/*
	* Privacy overview CMS page
	* @since 1.7.7
	*/
	public function privacy_overview_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
		}

		$stored_options   = get_option( 'cookielawinfo_privacy_overview_content_settings' );
		$stored_options   = ( isset( $stored_options ) && is_array( $stored_options ) ) ? $stored_options : array();
		$default_settings = self::get_privacy_defaults();

		$privacy_title   = isset( $stored_options['privacy_overview_title'] ) ? $stored_options['privacy_overview_title'] : $default_settings['privacy_overview_title'];
		$privacy_content = isset( $stored_options['privacy_overview_content'] ) ? $stored_options['privacy_overview_content'] : $default_settings['privacy_overview_content'];

		if ( isset( $_POST['update_privacy_overview_content_settings_form'] ) ) {

			// Check nonce:
			check_admin_referer( 'cookielawinfo-update-privacy-overview-content' );

			$privacy_title   = $stored_options['privacy_overview_title'] = sanitize_text_field( isset( $_POST['privacy_overview_title'] ) ? wp_unslash( $_POST['privacy_overview_title'] ) : '' );
			$privacy_content = $stored_options['privacy_overview_content'] = wp_kses_post( isset( $_POST['privacy_overview_content'] ) && $_POST['privacy_overview_content'] !== '' ? wp_unslash( $_POST['privacy_overview_content'] ) : '' );

			update_option( 'cookielawinfo_privacy_overview_content_settings', $stored_options );
			echo '<div class="updated"><p><strong>' . esc_html__( 'Settings Updated.', 'cookie-law-info' ) . '</strong></p></div>';
		}

		require_once plugin_dir_path( __FILE__ ) . 'partials/cookie-law-info-privacy_overview.php';
	}
	public function plugin_action_links( $links ) {
		$links[] = '<a href="' . get_admin_url( null, 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info' ) . '">' . __( 'Settings', 'cookie-law-info' ) . '</a>';
		$links[] = '<a href="https://www.webtoffee.com/product/gdpr-cookie-consent/" target="_blank">' . esc_html__( 'Support', 'cookie-law-info' ) . '</a>';
		$links[] = '<a href="https://www.webtoffee.com/product/gdpr-cookie-consent/?utm_source=free_plugin_listing&utm_medium=gdpr_basic&utm_campaign=GDPR&utm_content=' . CLI_VERSION . '" target="_blank" style="color: #3db634; font-weight: 500;">' . __( 'Premium Upgrade', 'cookie-law-info' ) . '</a>';
		return $links;
	}

	/*
	* admin settings page
	*/
	public function admin_settings_page() {
		 // Lock out non-admins:
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
		}
		// Get options:
		$the_options = Cookie_Law_Info::get_settings();
		// Check if form has been set:
		if ( isset( $_POST['update_admin_settings_form'] ) || // normal php submit
		( isset( $_POST['cli_settings_ajax_update'] ) && $_POST['cli_settings_ajax_update'] == 'update_admin_settings_form' ) ) {
			// Check nonce:
			check_admin_referer( 'cookielawinfo-update-' . CLI_SETTINGS_FIELD );

			// module settings saving hook
			do_action( 'cli_module_save_settings' );

			foreach ( $the_options as $key => $value ) {
				if ( isset( $_POST[ $key . '_field' ] ) ) {
					// Store sanitised values only:
					$the_options[ $key ] = Cookie_Law_Info::sanitise_settings( $key, wp_unslash( $_POST[ $key . '_field' ] ) );
				}
			}
			$the_options = apply_filters( 'wt_cli_before_save_settings', $the_options, $_POST );
			update_option( CLI_SETTINGS_FIELD, $the_options );
			do_action( 'wt_cli_ajax_settings_update', $_POST );
			echo '<div class="updated"><p><strong>' . esc_html__( 'Settings Updated.', 'cookie-law-info' ) . '</strong></p></div>';
		} elseif ( isset( $_POST['delete_all_settings'] ) || // normal php submit
		( isset( $_POST['cli_settings_ajax_update'] ) && $_POST['cli_settings_ajax_update'] == 'delete_all_settings' ) ) {
			// Check nonce:
			check_admin_referer( 'cookielawinfo-update-' . CLI_SETTINGS_FIELD );
			$this->delete_settings();
			// $the_options = Cookie_Law_Info::get_settings();
			// exit();
		}
		if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) == 'xmlhttprequest' ) {
			exit();
		}
		require_once plugin_dir_path( __FILE__ ) . 'partials/cookie-law-info-admin_settings.php';
	}

	/**
	 Add custom meta boxes to Cookie Audit custom post type.
		- Cookie Type (e.g. session, permanent)
		- Cookie Duration (e.g. 2 hours, days, years, etc)
	 */




	/** Apply column names to the custom post type table */


	function remove_cli_addnew_link() {
		 global $submenu;
		if ( isset( $submenu ) && ! empty( $submenu ) && is_array( $submenu ) ) {
			unset( $submenu[ 'edit.php?post_type=' . CLI_POST_TYPE ][10] );
		}
	}

	/**
	 Delete the values in all fields
	 WARNING - this has a predictable result i.e. will delete saved settings! Once deleted,
	 the get_admin_options() function will not find saved settings so will return default values
	 */
	public function delete_settings() {
		if ( defined( 'CLI_ADMIN_OPTIONS_NAME' ) ) {
			delete_option( CLI_ADMIN_OPTIONS_NAME );
		}
		if ( defined( 'CLI_SETTINGS_FIELD' ) ) {
			delete_option( CLI_SETTINGS_FIELD );
		}
	}

	/**
	 Prints a combobox based on options and selected=match value

	 Parameters:
		$options = array of options (suggest using helper functions)
		$selected = which of those options should be selected (allows just one; is case sensitive)

	 Outputs (based on array ( $key => $value ):
		<option value=$value>$key</option>
		<option value=$value selected="selected">$key</option>
	 */
	public function print_combobox_options( $options, $selected ) {
		foreach ( $options as $option ) {
			echo '<option value="' . esc_attr( $option['value'] ) . '"';
			if ( $option['value'] == $selected ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html( $option['text'] ) . '</option>';
		}
	}

	/**
	 Returns list of available jQuery actions
	 Used by buttons/links in header
	 */
	public function get_js_actions() {
		$js_actions = array(
			'close_header' => array(
				'text'  => __( 'Close consent bar', 'cookie-law-info' ),
				'value' => '#cookie_action_close_header',
			),
			'open_url'     => array(
				'text'  => __( 'Redirect to URL on click', 'cookie-law-info' ),
				'value' => 'CONSTANT_OPEN_URL',
			),   // Don't change this value, is used by jQuery
		);
		return $js_actions;
	}

	/**
	 Returns button sizes (dependent upon CSS implemented - careful if editing)
	 Used when printing admin form (for combo boxes)
	 */
	public function get_button_sizes() {
		$sizes = array(
			'super'  => array(
				'text'  => __( 'Extra Large', 'cookie-law-info' ),
				'value' => 'super',
			),
			'large'  => array(
				'text'  => __( 'Large', 'cookie-law-info' ),
				'value' => 'large',
			),
			'medium' => array(
				'text'  => __( 'Medium', 'cookie-law-info' ),
				'value' => 'medium',
			),
			'small'  => array(
				'text'  => __( 'Small', 'cookie-law-info' ),
				'value' => 'small',
			),
		);
		return $sizes;
	}

	/**
	 Function returns list of supported fonts
	 Used when printing admin form (for combo box)
	 */
	public function get_fonts() {
		$fonts = array(
			'default'         => array(
				'text'  => __( 'Default theme font', 'cookie-law-info' ),
				'value' => 'inherit',
			),
			'sans_serif'      => array(
				'text'  => __( 'Sans Serif', 'cookie-law-info' ),
				'value' => 'Helvetica, Arial, sans-serif',
			),
			'serif'           => array(
				'text'  => __( 'Serif', 'cookie-law-info' ),
				'value' => 'Georgia, Times New Roman, Times, serif',
			),
			'arial'           => array(
				'text'  => __( 'Arial', 'cookie-law-info' ),
				'value' => 'Arial, Helvetica, sans-serif',
			),
			'arial_black'     => array(
				'text'  => __( 'Arial Black', 'cookie-law-info' ),
				'value' => 'Arial Black,Gadget,sans-serif',
			),
			'georgia'         => array(
				'text'  => __( 'Georgia, serif', 'cookie-law-info' ),
				'value' => 'Georgia, serif',
			),
			'helvetica'       => array(
				'text'  => __( 'Helvetica', 'cookie-law-info' ),
				'value' => 'Helvetica, sans-serif',
			),
			'lucida'          => array(
				'text'  => __( 'Lucida', 'cookie-law-info' ),
				'value' => 'Lucida Sans Unicode, Lucida Grande, sans-serif',
			),
			'tahoma'          => array(
				'text'  => __( 'Tahoma', 'cookie-law-info' ),
				'value' => 'Tahoma, Geneva, sans-serif',
			),
			'times_new_roman' => array(
				'text'  => __( 'Times New Roman', 'cookie-law-info' ),
				'value' => 'Times New Roman, Times, serif',
			),
			'trebuchet'       => array(
				'text'  => __( 'Trebuchet', 'cookie-law-info' ),
				'value' => 'Trebuchet MS, sans-serif',
			),
			'verdana'         => array(
				'text'  => __( 'Verdana', 'cookie-law-info' ),
				'value' => 'Verdana, Geneva',
			),
		);
		return $fonts;
	}

	/**
	 * Set plugin default plugin on activation
	 *
	 * @since  1.9.5
	 * @access public
	 */

	public function load_plugin() {

		if ( is_admin() && get_option( 'wt_cli_first_time_activated_plugin' ) == 'true' ) {
			do_action( 'wt_cli_initialize_plugin' );
			delete_option( 'wt_cli_first_time_activated_plugin' );
		}
		$this->redirect_to_settings_page();
	}
	public static function wt_cli_admin_notice( $type = 'info', $message = '', $icon = false ) {
		$icon_class = ( true === $icon ) ? 'wt-cli-callout-icon' : '';
		$html       = '<div class="wt-cli-callout wt-cli-callout-' . $type . ' ' . $icon_class . ' ">' . $message . '</div>';
		return $html;
	}
	public function redirect_to_settings_page() {
		if ( ! isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && $_GET['page'] == 'cookie-law-info' ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info' ) );
			exit();
		}
	}

}
