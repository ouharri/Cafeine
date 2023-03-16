<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $wt_cli_integration_list;

$wt_cli_integration_list = apply_filters(
	'wt_cli_plugin_integrations',
	array(

		'facebook-for-wordpress'         => array(
			'identifier'  => 'FacebookPixelPlugin\\FacebookForWordpress',
			'label'       => 'Official Facebook Pixel',
			'status'      => 'yes',
			'description' => 'Official Facebook Pixel',
			'category'    => 'analytics',
			'type'        => 1,
		),
		'twitter-feed'                   => array(
			'identifier'  => 'CTF_VERSION',
			'label'       => 'Smash Balloon Twitter Feed',
			'status'      => 'yes',
			'description' => 'Twitter Feed By Smash Baloon',
			'category'    => 'analytics',
			'type'        => 1,
		),
		'instagram-feed'                 => array(
			'identifier'  => 'SBIVER',
			'label'       => 'Smash Balloon Instagram Feed',
			'status'      => 'yes',
			'description' => 'Instagram Feed By Smash Baloon',
			'category'    => 'advertisement',
			'type'        => 1,
		),
		'google-analytics-for-wordpress' => array(
			'identifier'  => 'MonsterInsights',
			'label'       => 'Google Analytics for WordPress by MonsterInsights',
			'status'      => 'yes',
			'description' => 'Google Analytics Dashboard Plugin for WordPress by MonsterInsights',
			'category'    => 'analytics',
			'type'        => 1,
		),
	)
);


if ( ! class_exists( 'Cookie_Law_Info_Script_Blocker' ) ) {
	class Cookie_Law_Info_Script_Blocker {


		protected $script_data;
		public $script_blocker_status;
		public $js_blocking;
		public $third_party_enabled;

		protected $script_table = 'cli_scripts';
		protected $module_id    = 'script-blocker';


		function __construct() {
			add_action( 'init', array( $this, 'init_scripts' ), 10 );
			$this->init_script_blocker();
			register_activation_hook( CLI_PLUGIN_FILENAME, array( $this, 'activator' ) );
			add_action( 'activated_plugin', array( $this, 'update_integration_data' ) );
			add_action( 'admin_menu', array( $this, 'register_settings_page' ), 10 );
			add_action( 'wp_ajax_wt_cli_change_plugin_status', array( $this, 'change_plugin_status' ) );
			add_action( 'admin_init', array( $this, 'update_script_blocker_status' ) );
			add_action( 'wt_cli_after_advanced_settings', array( $this, 'add_blocking_control' ) );
			add_action( 'wt_cli_ajax_settings_update', array( $this, 'update_js_blocking_status' ), 10, 1 );

			// @since 1.9.6 for changing the category of each script blocker
			add_action( 'wp_ajax_cli_change_script_category', array( $this, 'cli_change_script_category' ) );
			add_action( 'wt_cli_after_cookie_category_migration', array( $this, 'reset_scripts_category' ) );

		}
		public function init_script_blocker() {
			if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || is_admin() ) {
				return;
			}
			if ( $this->get_blocking_status() === true && $this->advanced_rendering_enabled() === true && $this->third_party_scripts() === true ) {
				add_action( 'template_redirect', array( $this, 'start_buffer' ) );
				add_action( 'shutdown', array( $this, 'end_buffer' ), 999 );
			}
		}
		public function init_scripts() {
			$this->load_integrations();
		}
		/**
		 * Get the current status of the integrations
		 *
		 * @since  1.9.2
		 * @access public
		 * @return array
		 */
		public function get_script_data() {
			global $wpdb;
			$script_table = $wpdb->prefix . $this->script_table;
			$scripts      = array();
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $script_table ) ) == $script_table ) {

				$script_data = $wpdb->get_results( "select * from {$wpdb->prefix}cli_scripts", ARRAY_A );
				foreach ( $script_data as $key => $data ) {

					$id            = sanitize_text_field( ( isset( $data['id'] ) ? $data['id'] : '' ) );
					$slug          = sanitize_text_field( ( isset( $data['cliscript_key'] ) ? $data['cliscript_key'] : '' ) );
					$title         = sanitize_text_field( ( isset( $data['cliscript_title'] ) ? $data['cliscript_title'] : '' ) );
					$description   = sanitize_text_field( ( isset( $data['cliscript_description'] ) ? $data['cliscript_description'] : '' ) );
					$category_id   = isset( $data['cliscript_category'] ) ? $data['cliscript_category'] : '';
					$status        = ( isset( $data['cliscript_status'] ) && ( $data['cliscript_status'] === 'yes' || $data['cliscript_status'] === '1' ) ? true : false );
					$term          = get_term_by( 'id', $category_id, 'cookielawinfo-category' );
					$category_slug = '';
					if ( '' !== $category_id ) {
						if ( is_numeric( $category_id ) ) {
							$category_slug = $this->get_script_category_slug_by_id( $category_id );

						} else {
							$category_slug = $category_id;
						}
					}
					if ( false === Cookie_Law_Info_Cookies::get_instance()->check_if_old_category_table() && ! is_admin() && is_null( term_exists( $category_slug, 'cookielawinfo-category' ) ) ) {
						$status = false;
					}
					if ( ! empty( $id ) ) {
						$scripts[ $slug ] = array(
							'id'          => $id,
							'title'       => $title,
							'description' => $description,
							'category'    => $category_slug,
							'status'      => $status,
						);
					}
				}
			}
			return $scripts;
		}
		public function get_scripts() {
			if ( ! $this->script_data ) {
				$this->script_data = $this->get_script_data();
			}
			return $this->script_data;
		}
		/**
		 * Register admin menu for the plugn
		 *
		 * @since  1.9.2
		 * @access public
		 * @return bool
		 */
		public function get_script_blocker_status() {
			$status = get_option( 'cli_script_blocker_status' );
			if ( isset( $status ) && $status === 'enabled' ) {
				return true;
			}
			return false;
		}
		public function get_third_party_status() {

			if ( Cookie_Law_Info_Cookies::get_instance()->check_if_old_category_table() === true ) {
				$cookies = Cookie_Law_Info_Cookies::get_instance()->get_cookies();
				if ( isset( $cookies['non-necessary'] ) && ! empty( $cookies['non-necessary'] ) ) {
					$status = isset( $cookies['non-necessary']['status'] ) ? $cookies['non-necessary']['status'] : false;
					return $status;
				}
			} else {
				return true;
			}
			return false;
		}
		/**
		 * Update script blocker status
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function update_script_blocker_status() {

			if ( isset( $_POST['cli_update_script_blocker'] ) ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
				}
				check_admin_referer( $this->module_id );
				$cli_sb_status = ( isset( $_POST['cli_script_blocker_state'] ) ? sanitize_text_field( wp_unslash( $_POST['cli_script_blocker_state'] ) ) : '' );
				if ( $cli_sb_status === 'enabled' ) {
					$this->script_blocker_status = true;
				} else {
					$this->script_blocker_status = false;
				}
				update_option( 'cli_script_blocker_status', $cli_sb_status );
			}
		}
		/**
		 * Register admin menu for the plugn
		 *
		 * @since  1.9.2
		 * @access public
		 * @return array
		 */
		public function register_settings_page() {

			add_submenu_page(
				'edit.php?post_type=' . CLI_POST_TYPE,
				__( 'Script Blocker', 'cookie-law-info' ),
				__( 'Script Blocker', 'cookie-law-info' ),
				'manage_options',
				'cli-script-settings',
				array( $this, 'integrations_settings_page' )
			);
		}

		/**
		 * Admin menu settings page
		 *
		 * @since  1.9.2
		 * @access public
		 * @return array
		 */
		public function integrations_settings_page() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
			}
			if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == CLI_POST_TYPE && isset( $_GET['page'] ) && $_GET['page'] == 'cli-script-settings' ) {

				global $wt_cli_integration_list;
				$script_data           = $this->get_scripts();
				$script_blocker_status = $this->get_blocking_status();
				$js_blocking           = $this->advanced_rendering_enabled();
				$messages              = array(
					'success' => __( 'Status updated', 'cookie-law-info' ),
				);
				$settings              = array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( $this->module_id ),
					'messages' => $messages,
				);
				wp_enqueue_style( 'cookie-law-info' );
				wp_enqueue_script( 'cookie-law-info-script-blocker', plugin_dir_url( __FILE__ ) . 'assets/js/script-blocker.js', array( 'jquery', 'cookie-law-info' ), CLI_VERSION, false );
				wp_localize_script( 'cookie-law-info-script-blocker', 'wt_cli_script_blocker_obj', $settings );

			}
			$terms = Cookie_Law_Info_Cookies::get_instance()->get_cookie_category_options();
			$terms = ( isset( $terms ) ? $terms : array() );

			include plugin_dir_path( __FILE__ ) . 'views/settings.php';
		}
		/**
		 * Add option to enable or disable javascript blocking in advanced settings tab
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function add_blocking_control() {

			$js_blocking = $this->advanced_rendering_enabled();
			echo '<table class="form-table">
                    <tr valign="top">
                        <th scope="row">' . esc_html__( 'Advanced script rendering', 'cookie-law-info' ) . '</th>
                        <td>
                            <input type="radio" id="wt_cli_js_blocking_enable_field" name="wt_cli_js_blocking_field" class="styled" value="yes" ' . checked( $js_blocking, true, false ) . ' /><label for="wt_cli_js_blocking_enable_field" >' . esc_html__( 'Enable', 'cookie-law-info' ) . '</label>
                            <input type="radio" id="wt_cli_js_blocking_disable_field" name="wt_cli_js_blocking_field" class="styled" value="no" ' . checked( $js_blocking, false, false ) . ' /><label for="wt_cli_js_blocking_disable_field" >' . esc_html__( 'Disable', 'cookie-law-info' ) . '</label>
                            <span class="cli_form_help" style="margin-top:10px;">' . esc_html__( 'Advanced script rendering will render the blocked scripts using javascript thus eliminating the need for a page refresh. It is also optimized for caching since there is no server-side processing after obtaining the consent.', 'cookie-law-info' ) . '</span>
                        </td>
                        </tr>
                 </table>';
		}

		/**
		 * Enabe or disable javascript blocking
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function update_js_blocking_status( $data ) {

			$js_blocking = 'no';
			if ( isset( $data['wt_cli_js_blocking_field'] ) && $data['wt_cli_js_blocking_field'] === 'yes' ) {
				$js_blocking = 'yes';
			}
			$this->js_blocking = $js_blocking;
			update_option( 'cookielawinfo_js_blocking', $js_blocking );

		}
		/**
		 * Fire during plugin activation or deactivaion
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function activator() {
			global $wpdb;
			$activation_transient = wp_validate_boolean( get_transient( '_wt_cli_first_time_activation' ) );
			$plugin_settings      = get_option( CLI_SETTINGS_FIELD );

			if ( $activation_transient === true ) {
				set_transient( 'wt_cli_script_blocker_notice', true, DAY_IN_SECONDS );
				$script_blocking = $this->get_script_blocker_status();
				if ( $script_blocking === false ) {
					update_option( 'cli_script_blocker_status', 'enabled' );
				}
			}
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			if ( is_multisite() ) {
				// Get all blogs in the network and activate plugin on each one
				$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::install_tables();
					restore_current_blog();
				}
			} else {
				self::install_tables();
			}
		}

		/**
		 * Install necessary tables for storing integrations data
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public static function install_tables() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$like            = '%' . $wpdb->prefix . 'cli_scripts%';
			$table_name      = $wpdb->prefix . 'cli_scripts';
			if ( ! $wpdb->get_results( $wpdb->prepare( 'SHOW TABLES LIKE %s', $like ), ARRAY_N ) ) {

				$sql = "CREATE TABLE $table_name(
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `cliscript_title` TEXT NOT NULL,
                    `cliscript_category` VARCHAR(100) NOT NULL,
                    `cliscript_type` INT DEFAULT 0,
                    `cliscript_status` VARCHAR(100) NOT NULL,
                    `cliscript_description` LONGTEXT NOT NULL,
                    `cliscript_key` VARCHAR(100) NOT NULL,
                    `type` INT NOT NULL DEFAULT '0',
                    PRIMARY KEY(`id`)
                ) $charset_collate;";
				dbDelta( $sql );
			}
			self::update_table_columns();
			self::insert_scripts( $table_name );
		}
		/**
		 * Update the status of the plugin based on user option
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function change_plugin_status() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
			}
			check_ajax_referer( $this->module_id );
			$script_id = (int) ( isset( $_POST['script_id'] ) ? absint( $_POST['script_id'] ) : -1 );
			$status    = wp_validate_boolean( ( isset( $_POST['status'] ) && true === wp_validate_boolean( sanitize_text_field( wp_unslash( $_POST['status'] ) ) ) ? true : false ) );
			if ( $script_id !== -1 ) {
				$this->update_script_status( $script_id, $status );
				wp_send_json_success();
			}
			wp_send_json_error();

		}
		public function update_script_status( $id, $status ) {

			global $wpdb;
			$script_table = $wpdb->prefix . $this->script_table;
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}cli_scripts SET cliscript_status = %d WHERE id = %d", $status, $id ) );

		}
		/**
		 * Load integration if it is currently activated
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public static function insert_scripts( $table_name ) {

			global $wpdb;
			global $wt_cli_integration_list;
			foreach ( $wt_cli_integration_list as $key => $value ) {
				$data        = array(
					'cliscript_key'         => isset( $key ) ? $key : '',
					'cliscript_title'       => isset( $value['label'] ) ? $value['label'] : '',
					'cliscript_category'    => isset( $value['category'] ) ? $value['category'] : '',
					'cliscript_type'        => isset( $value['type'] ) ? $value['type'] : 0,
					'cliscript_status'      => isset( $value['status'] ) ? $value['status'] : true,
					'cliscript_description' => isset( $value['description'] ) ? $value['description'] : '',
				);
				$data_exists = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}cli_scripts WHERE cliscript_key= %s", $key ), ARRAY_A );
				if ( ! $data_exists ) {
					if ( Cookie_Law_Info::maybe_first_time_install() === false ) {
						$data['cliscript_status'] = false;
					}
					$wpdb->insert( $table_name, $data );
				}
			}
		}
		/**
		 *
		 * @access private
		 * @return void
		 * @since  1.9.2
		 */
		private static function update_table_columns() {
			global $wpdb;
			if ( ! $wpdb->get_results( "SHOW COLUMNS FROM {$wpdb->prefix}cli_scripts LIKE 'cliscript_type'", ARRAY_N ) ) {
				$wpdb->query( "ALTER TABLE {$wpdb->prefix}cli_scripts ADD `cliscript_type` INT DEFAULT 0 AFTER `cliscript_category`" );
			}
		}
		/**
		 * Load integration if it is currently activated
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function load_integrations() {
			global $wt_cli_integration_list;
			foreach ( $wt_cli_integration_list as $plugin => $details ) {
				if ( $this->wt_cli_plugin_is_active( $plugin ) ) {

					$file = plugin_dir_path( __FILE__ ) . "integrations/$plugin.php";
					if ( file_exists( $file ) ) {
						require_once $file;
					} else {
						error_log( "searched for $plugin integration at $file, but did not find it" );
					}
				}
			}
		}

		/**
		 * Check and load necessary plugin data if it is in disabled state
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function update_integration_data() {

		}

		/**
		 * Check if the listed integration is active on the website
		 *
		 * @since  1.9.2
		 * @access public
		 */
		public function wt_cli_plugin_is_active( $plugin ) {
			global $wt_cli_integration_list;
			$script_data = $this->get_scripts();

			if ( empty( $script_data ) ) {
				return false;
			}
			if ( ! isset( $wt_cli_integration_list[ $plugin ] ) ) {
				return false;
			}
			$details = $wt_cli_integration_list[ $plugin ];

			$enabled = isset( $script_data[ $plugin ]['status'] ) ? wp_validate_boolean( $script_data[ $plugin ]['status'] ) : false;
			if ( ( defined( $details['identifier'] )
					|| function_exists( $details['identifier'] )
					|| class_exists( $details['identifier'] ) ) && $enabled === true ) {
				return true;
			}
			return false;
		}

		/**
		 * Start buffering the output for blocking scripts
		 *
		 * @since  1.9.2
		 * @access public
		 */

		public function start_buffer() {
			ob_start( array( $this, 'init' ) );
		}
		/**
		 * Flush the buffer
		 *
		 * @access public
		 */

		public function end_buffer() {
			if ( ob_get_length() ) {
				ob_end_flush();
			}
		}

		/**
		 * Starts replacing the tags that should be blocked
		 *
		 * @since  1.9.2
		 * @access public
		 * @param  string
		 * @return string
		 */

		public function init( $buffer ) {
			$buffer = $this->replace_scripts( $buffer );
			return $buffer;
		}

		/**
		 * check if there is a partial match between a key of the array and the haystack
		 * We cannot use array_search, as this would not allow partial matches.
		 *
		 * @param string $haystack
		 * @param array  $needle
		 *
		 * @return bool|string
		 */

		private function strpos_arr( $haystack, $needle ) {
			if ( empty( $haystack ) ) {
				return false;
			}

			if ( ! is_array( $needle ) ) {
				$needle = array( $needle );
			}
			foreach ( $needle as $key => $value ) {

				if ( is_array( $value ) ) {

					foreach ( $value as $data ) {

						if ( strlen( $data ) === 0 ) {
							continue;
						}
						if ( ( $pos = strpos( $haystack, $data ) ) !== false ) {
							return ( is_numeric( $key ) ) ? $data : $key;
						}
					}
				} else {

					if ( strlen( $value ) === 0 ) {
						continue;
					}
					if ( ( $pos = strpos( $haystack, $value ) ) !== false ) {
						return ( is_numeric( $key ) ) ? $value : $key;
					}
				}
			}

			return false;
		}
		/**
		 * Perform a series of regular expression operation to find and replace the unwanted tags from the output
		 *
		 * @since  1.9.2
		 * @access public
		 * @param  string
		 * @return string
		 */

		public function replace_scripts( $buffer ) {
			$third_party_script_tags = array();

			$third_party_script_tags = apply_filters( 'wt_cli_third_party_scripts', $third_party_script_tags );

			$script_pattern = '/(<script.*?>)(\X*?)<\/script>/i';
			$index          = 0;
			if ( preg_match_all(
				$script_pattern,
				$buffer,
				$matches,
				PREG_PATTERN_ORDER
			) ) {

				foreach ( $matches[1] as $key => $script_open ) {
					// exclude ld+json
					if (
						strpos( $script_open, 'application/ld+json' )
						!== false
					) {
						continue;
					}
					$total_match = $matches[0][ $key ];
					$content     = $matches[2][ $key ];

					// if there is inline script here, it has some content
					if ( ! empty( $content ) ) {
						$found = $this->strpos_arr(
							$content,
							$third_party_script_tags
						);

						if ( $found !== false ) {
							$category = $this->get_category_by_script_slug( $found );
							$new      = $total_match;
							$new      = $this->replace_script_type_attribute( $new, $category );
							$buffer   = str_replace( $total_match, $new, $buffer );
						}
					}
					$script_src_pattern
						= '/<script [^>]*?src=[\'"](http:\/\/|https:\/\/|\/\/)([\w.,;@?^=%&:()\/~+#!\-*]*?)[\'"].*?>/i';
					if ( preg_match_all(
						$script_src_pattern,
						$total_match,
						$src_matches,
						PREG_PATTERN_ORDER
					)
					) {
						$script_src_matches = ( isset( $src_matches[2] ) && is_array( $src_matches[2] ) ) ? $src_matches[2] : array();
						if ( ! empty( $script_src_matches ) ) {
							foreach ( $src_matches[2] as $src_key => $script_src ) {
								$script_src = $src_matches[1][ $src_key ] . $src_matches[2][ $src_key ];
								$found      = $this->strpos_arr(
									$script_src,
									$third_party_script_tags
								);

								if ( $found !== false ) {
									$category = $this->get_category_by_script_slug( $found );
									$new      = $total_match;
									$new      = $this->replace_script_type_attribute( $new, $category );
									$buffer   = str_replace( $total_match, $new, $buffer );
								}
							}
						}
					}
				}
			}
			return $buffer;
		}

		public function replace_script_type_attribute( $script, $category ) {

			$replace     = 'data-cli-class="cli-blocker-script"  data-cli-script-type="' . sanitize_text_field( $category ) . '" data-cli-block="true"  data-cli-element-position="head"';
			$script_type = 'text/plain';

			if ( preg_match( '/<script[^\>]*?\>/m', $script ) ) {
				$changed = true;
				if ( preg_match( '/<script.*(type=(?:"|\')(.*?)(?:"|\')).*?>/', $script ) && preg_match( '/<script.*(type=(?:"|\')text\/javascript(.*?)(?:"|\')).*?>/', $script ) ) {
					preg_match( '/<script.*(type=(?:"|\')text\/javascript(.*?)(?:"|\')).*?>/', $script, $output_array );
					$re = preg_quote( $output_array[1], '/' );
					if ( ! empty( $output_array ) ) {

						$script = preg_replace( '/' . $re . '/', 'type="' . $script_type . '"' . ' ' . $replace, $script, 1 );

					}
				} else {

					$script = str_replace( '<script', '<script type="' . $script_type . '"' . ' ' . $replace, $script );

				}
			}
			return $script;
		}

		/* change category of item on list page (ajax) */
		public function cli_change_script_category() {

			if ( current_user_can( 'manage_options' ) && check_ajax_referer( $this->module_id ) ) {

				$script_id = (int) ( isset( $_POST['script_id'] ) ? sanitize_text_field( wp_unslash( $_POST['script_id'] ) ) : -1 );
				$category  = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

				if ( $script_id !== -1 ) {
					self::cli_script_update_category( $script_id, $category );
					wp_send_json_success();
				}
				wp_send_json_error( __( 'Invalid script id', 'cookie-law-info' ) );
			}
			wp_send_json_error( __( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
		}
		public static function cli_script_update_category( $id = 0, $cat = 3 ) {

			global $wpdb;

			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}cli_scripts SET cliscript_category = %s WHERE id = %s", $cat, $id ) );
		}
		public function get_category_by_script_slug( $slug ) {
			$category    = 'non-necessary';
			$scripts     = $this->get_scripts();
			$script_data = isset( $scripts[ $slug ] ) ? $scripts[ $slug ] : array();
			if ( ! empty( $script_data ) ) {
				$category = isset( $script_data['category'] ) ? $script_data['category'] : '';
			}
			return $category;
		}
		/**
		 * Set script category to necessary after cookie category migration
		 *
		 * @since  1.9.5
		 * @access public
		 */
		public function reset_scripts_category() {

			global $wt_cli_integration_list;
			$script_data = $this->get_scripts();
			if ( isset( $script_data ) ) {
				foreach ( $script_data as $key => $data ) {
					$script_id = $data['id'];
					$category  = $data['category'];

					if ( 'non-necessary' === $category ) {
						if ( -1 !== self::get_non_necessary_category_id() ) {
							$this->cli_script_update_category( $script_id, 'non-necessary' );
						} else {
							$default_category = isset( $wt_cli_integration_list[ $key ]['category'] ) ? $wt_cli_integration_list[ $key ]['category'] : '';

							if ( '' !== $default_category ) {
								$this->cli_script_update_category( $script_id, $default_category );
							}
						}
					}
				}
			}
		}
		/**
		 * Get the id of non-necessary category ( Default category )
		 *
		 * @since  1.9.5
		 * @access public
		 */
		public static function get_non_necessary_category_id() {

			$id_obj = get_term_by( 'slug', 'non-necessary', 'cookielawinfo-category' );
			$id     = -1; // for non-necessary default - this may change
			if ( $id_obj ) {
				$id = $id_obj->term_id;
			}
			return $id;
		}

		public function get_blocking_status() {

			if ( ! $this->script_blocker_status ) {
				$this->script_blocker_status = $this->get_script_blocker_status();
			}
			return $this->script_blocker_status;
		}
		public function advanced_rendering_enabled() {

			if ( ! $this->js_blocking ) {
				$this->js_blocking = Cookie_Law_Info::get_js_option();
			}
			return $this->js_blocking;
		}
		public function third_party_scripts() {

			if ( ! $this->third_party_enabled ) {
				$this->third_party_enabled = $this->get_third_party_status();
			}
			return $this->third_party_enabled;
		}
		// Returns the category slug by category id or slug
		public function get_script_category_slug_by_id( $category_id ) {

			$category_slug = '';
			$category_id   = intval( $category_id );
			if ( $category_id === -1 ) { // Existing cusomters.
				$category_slug = 'non-necessary';
			} else {
				if ( Cookie_Law_Info_Languages::get_instance()->is_multilanguage_plugin_active() === true ) {

					$default_language = Cookie_Law_Info_Languages::get_instance()->get_default_language_code();
					$current_language = Cookie_Law_Info_Languages::get_instance()->get_current_language_code();

					if ( $current_language !== $default_language ) {
						$default_term = Cookie_Law_Info_Languages::get_instance()->get_term_by_language( $category_id, $default_language );

						if ( $default_term && $default_term->term_id ) {
							$category_slug = $default_term->slug;
						}
					} else {
						$term = get_term_by( 'id', $category_id, 'cookielawinfo-category' );
						if ( isset( $term ) && is_object( $term ) ) {
							$category_slug = $term->slug;
						}
					}
				} else {
					$term = get_term_by( 'id', $category_id, 'cookielawinfo-category' );
					if ( isset( $term ) && is_object( $term ) ) {
						$category_slug = $term->slug;
					}
				}
			}
			return $category_slug;
		}
	}

}
new Cookie_Law_Info_Script_Blocker();
