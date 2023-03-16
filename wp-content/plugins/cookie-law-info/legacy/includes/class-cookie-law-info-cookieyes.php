<?php
/**
 * Cookieyes Integration
 *
 * @version 1.9.6
 * @package CookieLawInfo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Cookie_Law_Info_Cookieyes' ) ) {
	/**
	 * Cookieyes library
	 */
	class Cookie_Law_Info_Cookieyes {
		/**
		 * CookieYes options
		 *
		 * @var array
		 */
		protected $cookieyes_options;
		/**
		 * Current webstite URL
		 *
		 * @var string
		 */
		protected $website_url;
		/**
		 * CookieYes token
		 *
		 * @var string
		 */
		protected $token;
		/**
		 * CookieYes connection status
		 *
		 * @var bool
		 */
		protected $ckyes_status;
		/**
		 * Allowed CookieYes ajax actions
		 *
		 * @var array
		 */
		protected $ckyes_actions;
		/**
		 * Email of the user
		 *
		 * @var string
		 */
		public $ckyes_scan_data;

		public $user_email;
		/**
		 * Current module name
		 *
		 * @var [type]
		 */
		public $module_id;

		const API_BASE_PATH = 'https://app.cookieyes.com/public/api/wp-basic/v1/';

		const EC_WT_CKYES_CONNECTION_FAILED      = 100;
		const EC_WT_CKYES_INVALID_CREDENTIALS    = 101;
		const EC_WT_CKYES_ALREADY_EXIST          = 102;
		const EC_WT_CKYES_LICENSE_NOT_ACTIVATED  = 103;
		const EC_WT_CKYES_SCAN_LIMIT_REACHED     = 104;
		const EC_WT_CKYES_DISCONNECTED           = 105;
		const EC_WT_CKYES_ACTIVE_SCAN            = 106;
		const EC_WT_CKYES_PENDING_VERIFICATION   = 107;
		const EC_WT_CKYES_NOT_REGISTERED         = 108;
		const EC_WT_CKYES_EMAIL_ALREADY_VERIFIED = 109;

		const WT_CKYES_CONNECTION_SUCCESS      = 200;
		const WT_CKYES_SCAN_INITIATED          = 201;
		const WT_CKYES_PWD_RESET_SENT          = 202;
		const WT_CKYES_EMAIL_VERIFICATION_SENT = 203;
		const WT_CKYES_ABORT_SUCCESSFULL       = 204;

		/**
		 * Initialize CookieYes scanner library
		 */
		public function __construct() {
			$this->ckyes_actions = $this->get_ckyes_actions();
			$this->module_id     = 'cookieyes';

			add_action( 'init', array( $this, 'init' ) );
			add_action( 'wp_ajax_cookieyes_ajax_main_controller', array( $this, 'ajax_main_controller' ), 10, 0 );
			add_action( 'wt_cli_after_advanced_settings', array( $this, 'ckyes_settings' ), 11 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
		/**
		 * Initialize CookieYes actions
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'admin_footer', array( $this, 'ckyes_forms' ) );
			add_filter( 'wt_cli_enable_ckyes_branding', array( $this, 'show_ckyes_branding' ) );
			add_filter( 'wt_cli_ckyes_account_widget', array( $this, 'add_ckyes_account_widget' ) );
		}
		/**
		 * Return supported ajax actions
		 *
		 * @return array
		 */
		public function get_ckyes_actions() {
			return array(
				'register',
				'login',
				'reset_password',
				'connect_disconnect',
				'resend_email',
				'delete_account',
			);
		}
		/**
		 * AJAX main controller
		 *
		 * @return void
		 */
		public function ajax_main_controller() {
			check_ajax_referer( $this->module_id, '_wpnonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
			}
			if ( isset( $_POST['sub_action'] ) ) {

				$sub_action = sanitize_text_field( wp_unslash( $_POST['sub_action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				if ( in_array( $sub_action, $this->ckyes_actions, true ) && method_exists( $this, $sub_action ) ) {

					$response       = $this->{$sub_action}();
					$data           = array();
					$status         = ( isset( $response['status'] ) ? $response['status'] : false );
					$status_code    = ( isset( $response['code'] ) ? $response['code'] : '' );
					$message        = ( isset( $response['message'] ) ? $response['message'] : false );
					$html           = ( isset( $response['html'] ) ? $response['html'] : false );
					$data['status'] = $status;
					if ( ! empty( $status_code ) ) {
						$data['code'] = $status_code;
						$data['html'] = $html;
						if ( false === $message ) {
							$data['message'] = $this->get_ckyes_message( $status_code );
						} else {
							$data['message'] = $message;
						}
					}
					if ( true === $status ) {
						wp_send_json_success( $data );
					}
					wp_send_json_error( $data );
				}
			}
			$data['message'] = __( 'Invalid request', 'cookie-law-info' );
			wp_send_json_error( $data );
			exit();
		}
		/**
		 * CookieYes account status widget
		 *
		 * @return string
		 */
		public function add_ckyes_account_widget() {
			if ( $this->get_cookieyes_status() === false || $this->get_cookieyes_status() === 0 ) {
				return;
			}
			if ( $this->get_cookieyes_status() === false ) {
				return;
			}
			$ckyes_account_status_text = __( 'Connected to CookieYes', 'cookie-law-info' );
			$ckyes_account_action      = 'disconnect';
			$ckyes_account_action_text = __( 'Disconnect', 'cookie-law-info' );
			$image_directory           = CLI_PLUGIN_URL . 'admin/images/';
			$ckyes_account_status_icon = $image_directory . 'add.svg';

			if ( $this->get_cookieyes_status() === 0 ) {
				$ckyes_account_action      = 'connect';
				$ckyes_account_action_text = '';
				$ckyes_account_status_icon = $image_directory . 'remove.svg';
				$ckyes_account_status_text = __( 'Disconnected from CookieYes', 'cookie-law-info' );
			}
			$html  = '<span class="wt-cli-ckyes-account-widget-container">';
			$html .= '<span class="wt-cli-ckyes-status-icon"><img src="' . $ckyes_account_status_icon . '" style="max-width:100%;   " alt=""></span>';
			$html .= '<span class="wt-cli-ckyes-status-text">' . $ckyes_account_status_text . '</span>';
			$html .= '<span><a href="#" class="wt-cli-ckyes-account-action" data-action="' . $ckyes_account_action . '">' . $ckyes_account_action_text . '</a></span>';
			$html .= '</span>';
			return $html;
		}
		/**
		 * Enqueue the javascript file for CookieYes API
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			$allowed_pages = apply_filters( 'wt_cli_ckyes_allowed_pages', array( 'cookie-law-info-cookie-scaner', 'cookie-law-info' ) );
			if ( isset( $_GET['post_type'] ) && CLI_POST_TYPE === $_GET['post_type'] && isset( $_GET['page'] ) && in_array( $_GET['page'], $allowed_pages, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$params = array(
					'nonce'    => wp_create_nonce( esc_html( $this->module_id ) ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'messages' => array(
						'error'          => __( 'Invalid request', 'cookie-law-info' ),
						'delete_success' => __( 'Successfully deleted!', 'cookie-law-info' ),
						'delete_failed'  => __( 'Delete failed, please try again later', 'cookie-law-info' ),
					),
				);
				wp_enqueue_script( 'cookie-law-info-ckyes-admin', CLI_PLUGIN_URL . 'admin/js/cookie-law-info-ckyes.js', array( 'cookie-law-info' ), CLI_VERSION, true );
				wp_localize_script( 'cookie-law-info-ckyes-admin', 'ckyes_admin', $params );
			}
		}
		/**
		 * Login and password reset HTML for the scanner
		 *
		 * @return void
		 */
		public function ckyes_forms() {
			$allowed_pages = apply_filters( 'wt_cli_ckyes_allowed_pages', array( 'cookie-law-info-cookie-scaner' ) );

			if ( isset( $_GET['post_type'] ) && CLI_POST_TYPE === $_GET['post_type'] && isset( $_GET['page'] ) && in_array( $_GET['page'], $allowed_pages, true ) ) : // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				?>
				<style>
					.wt-cli-ckyes-login-icon>.dashicons {
						font-size: 50px;
						width: initial;
						height: initial;
					}

					.wt-cli-ckyes-login-icon {
						width: 80px;
						height: 80px;
						margin: 0 auto;
						display: flex;
						align-items: center;
						justify-content: center;
						border-radius: 50%;
						background: #f2f2f2;
					}

					.wt-cli-form-input {
						display: block;
						width: 100%;
						height: 45px;
						border: 1px solid #4041424a !important;
						margin-top: 10px;
					}

					.wt-cli-action-container {
						display: flex;
						align-items: center;
						justify-content: space-between;
						margin-top: 10px;
					}

					button.wt-cli-action.button {
						padding: 2px 24px;
						min-width: 100px;
						font-weight: 500;
					}

					#wt-cli-ckyes-modal-login {
						padding: 45px 25px;
						width: 430px;
					}
									</style>
				<div class='wt-cli-modal' id='wt-cli-ckyes-modal-password-reset'>
					<div class="wt-cli-modal-header">
						<h4><?php echo esc_html__( 'Reset Password', 'cookie-law-info' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h4>
					</div>
					<div class="wt-cli-modal-body">
						<form id="wt-cli-ckyes-form-password-reset">
							<input type="email" name="ckyes-reset-email" class="wt-cli-form-input" placeholder="<?php echo esc_attr__( 'Email', 'cookie-law-info' ); ?>" value="<?php echo esc_attr( $this->get_user_email() ); ?>" />
							<div class="wt-cli-action-container">
								<button id="wt-cli-ckyes-password-reset-btn" class="wt-cli-action button button-primary"><?php echo esc_html__( 'Send password reset email', 'cookie-law-info' ); ?></button>
							</div>

						</form>
					</div>
				</div>
				<div class='wt-cli-modal' id='wt-cli-ckyes-modal-register'>
					<span class="wt-cli-modal-js-close">×</span>
					<div class="wt-cli-modal-header"><h4><?php echo esc_html__( 'Welcome to CookieYes', 'cookie-law-info' ); ?></h4></div>
					<div class="wt-cli-modal-body">
						<p><?php echo esc_html__( 'Enter your email to create an account with CookieYes. By clicking “Connect”, your CookieYes account will be created automatically and you can start scanning your website for cookies right away!', 'cookie-law-info' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
						<form id="wt-cli-ckyes-form-register">
							<input type="email" name="ckyes-email" class="wt-cli-form-input" placeholder="<?php echo esc_attr__( 'Email', 'cookie-law-info' ); ?>" value = "<?php echo esc_attr( $this->get_user_email() ); ?>" />
							<div class="wt-cli-action-container">
								<div class="wt-cli-action-group">
									<button id="wt-cli-ckyes-register-btn" class="wt-cli-action button button-primary"><?php echo esc_html__( 'Connect', 'cookie-law-info' ); ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<?php
			endif;
		}
		/**
		 * CookieYes message handler
		 *
		 * @param int $msg_code Message code.
		 * @return string
		 */
		public function get_ckyes_message( $msg_code ) {
			switch ( $msg_code ) {
				case self::EC_WT_CKYES_CONNECTION_FAILED:
					$msg = __( 'Could not establish connection with scanner! please try again later', 'cookie-law-info' );
					break;
				case self::EC_WT_CKYES_INVALID_CREDENTIALS:
					$msg = __( 'Invalid credentials', 'cookie-law-info' );
					break;
				case self::EC_WT_CKYES_ALREADY_EXIST:
					$msg = __( 'You already have an account with CookieYes.', 'cookie-law-info' );
					break;
				case self::EC_WT_CKYES_LICENSE_NOT_ACTIVATED:
					$msg = __( 'License is not activated, please activate your license and try again', 'cookie-law-info' );
					break;
				case self::EC_WT_CKYES_DISCONNECTED:
					$msg = __( 'Disconnected with cookieyes, please connect and scan again', 'cookie-law-info' );
					break;
				case self::EC_WT_CKYES_SCAN_LIMIT_REACHED:
					$msg = __( 'Your monthly scan limit is reached please try again later', 'cookie-law-info' );
					break;
				case self::EC_WT_CKYES_ACTIVE_SCAN:
					$msg = __( 'A scanning is already in progress please try again after some time', 'cookie-law-info' );
					break;
				case self::WT_CKYES_CONNECTION_SUCCESS:
					$msg = __( 'Successfully connected with CookieYes', 'cookie-law-info' );
					break;
				case self::WT_CKYES_PWD_RESET_SENT:
					$msg = __( 'A password reset message has been sent to your email address. Click the link in the email to reset your password', 'cookie-law-info' );
					break;
				case self::WT_CKYES_EMAIL_VERIFICATION_SENT:
					$msg = __( 'A email verfication link has been sent to your email address. Click the link in the email to verify your account', 'cookie-law-info' );
					break;
				case self::EC_WT_CKYES_EMAIL_ALREADY_VERIFIED:
					$msg = __( 'Email has already verified', 'cookie-law-info' );
					break;
				default:
					$msg = '';
					break;
			}
			return $msg;
		}
		/**
		 * Return the current user email
		 *
		 * @return string
		 */
		public function get_user_email() {
			if ( ! $this->user_email ) {
				$cookieyes_options = $this->get_cookieyes_options();
				$this->user_email  = ( isset( $cookieyes_options['email'] ) ? $cookieyes_options['email'] : '' );
			}
			return sanitize_email( $this->user_email );
		}
		/**
		 * Get CookieYes access token
		 *
		 * @return string
		 */
		public function get_access_token() {
			if ( ! $this->token ) {
				$cookieyes_options = $this->get_cookieyes_options();
				$this->token       = ( isset( $cookieyes_options['token'] ) ? $cookieyes_options['token'] : '' );
			}
			return $this->token;
		}
		/**
		 * Save access token
		 *
		 * @param string $token Token received from the CookieYes.
		 * @return void
		 * @throws Exception Error message.
		 */
		public function set_access_token( $token ) {
			if ( is_string( $token ) ) {
				$json = json_decode( $token, true );
				if ( $json ) {
					$token = $json;
				} else {
					// assume $token is just the token string.
					$token = array(
						'access_token' => $token,
					);
				}
			}
			if ( null === $token ) {
				throw new Exception( __( 'Invalid json token', 'cookie-law-info' ) );
			}
			if ( ! isset( $token['access_token'] ) ) {
				throw new Exception( __( 'Invalid token format', 'cookie-law-info' ) );
			}
			$this->token = $token;
		}
		/**
		 * Reset the token values
		 *
		 * @return void
		 */
		public function reset_token() {
			delete_option( 'wt_cli_cookieyes_options' );
		}
		/**
		 * Cookieyes options like status, access token etc
		 *
		 * @return array
		 */
		public function get_cookieyes_options() {
			if ( ! $this->cookieyes_options ) {
				$cky_license       = array(
					'status' => 0,
					'token'  => '',
					'email'  => '',
				);
				$cookieyes_options = get_option( 'wt_cli_cookieyes_options', false );
				if ( false !== $cookieyes_options && is_array( $cookieyes_options ) ) {

					$cky_license['status'] = intval( isset( $cookieyes_options['status'] ) ? $cookieyes_options['status'] : 0 );
					$cky_license['token']  = isset( $cookieyes_options['token'] ) ? $cookieyes_options['token'] : '';
					$cky_license['email']  = isset( $cookieyes_options['email'] ) ? $cookieyes_options['email'] : '';
				} else {
					return false;
				}
				$this->cookieyes_options = $cky_license;
			}

			return $this->cookieyes_options;
		}
		/**
		 * Return current status of the CookieYes
		 *
		 * @return bool
		 */
		public function get_cookieyes_status() {

			if ( ! $this->ckyes_status ) {
				$cookieyes_options  = $this->get_cookieyes_options();
				$this->ckyes_status = ( isset( $cookieyes_options['status'] ) ? intval( $cookieyes_options['status'] ) : false );
			}
			return $this->ckyes_status;
		}
		/**
		 * Save CookieYes options
		 *
		 * @param array $options options.
		 * @return void
		 */
		public function set_cookieyes_options( $options ) {
			$cky_license        = array(
				'status' => 0,
				'token'  => '',
				'email'  => '',
			);
			$this->ckyes_status = $cky_license['status']  = ( isset( $options['status'] ) ? intval( $options['status'] ) : 0 );
			$this->token        = $cky_license['token']   = isset( $options['token'] ) ? sanitize_text_field( $options['token'] ) : '';
			$this->user_email   = $cky_license['email']   = isset( $options['email'] ) ? sanitize_email( $options['email'] ) : '';

			update_option( 'wt_cli_cookieyes_options', $cky_license );
		}
		/**
		 * Returns API base path
		 *
		 * @return string
		 */
		public function get_base_path() {
			return self::API_BASE_PATH;
		}
		/**
		 * Returns current website URL
		 *
		 * @return string
		 */
		public function get_website_url() {
			if ( ! $this->website_url ) {
				$this->website_url = home_url();
			}
			return $this->website_url;
		}
		/**
		 * Parse data from a remote request response.
		 *
		 * @param array $raw_response raw response from a remote request.
		 * @return array
		 */
		public function parse_raw_response( $raw_response ) {

			$response_code = wp_remote_retrieve_response_code( $raw_response );
			if ( 200 !== $response_code ) {
				if ( 401 === $response_code ) {
					$this->reset_token();
				}
				return false;
			}
			$response = json_decode( wp_remote_retrieve_body( $raw_response ), true );
			return $response;
		}
		/**
		 * Returns default response code and message
		 *
		 * @return array
		 */
		public function get_default_response() {
			$api_response = array(
				'status' => false,
				'code'   => 100,
			);
			return $api_response;
		}
		/**
		 * Do a remote a request
		 *
		 * @param string  $request_type Request type POST. GET, PUT etc.
		 * @param string  $endpoint API end point.
		 * @param boolean $body Request body.
		 * @param boolean $auth_token Bearer token.
		 * @return array
		 */
		public function wt_remote_request( $request_type = 'GET', $endpoint = '', $body = false, $auth_token = false ) {

			$request_args                            = array(
				'timeout' => 60,
				'headers' => array(),
			);
			$request_args['headers']['Content-Type'] = 'application/json';
			$request_args['headers']['Accept']       = 'application/json';

			if ( false !== $body ) {
				$request_args['body'] = json_encode( $body );
			}
			if ( false !== $auth_token ) {
				$request_args['headers']['Authorization'] = 'Bearer ' . $auth_token;
			}
			// Request types.
			switch ( $request_type ) {
				case 'GET':
					$raw_response = wp_remote_get(
						$endpoint,
						$request_args
					);
					break;

				case 'PUT':
				case 'POST':
					$raw_response = wp_remote_post(
						$endpoint,
						$request_args
					);
					break;
				default:
					break;
			}
			if ( $raw_response ) {
				$response = $this->parse_raw_response( $raw_response );
				return $response;
			}
			return false;
		}
		/**
		 * Register API , create an account with CookieYes
		 *
		 * @return array
		 */
		public function register() {
			check_ajax_referer( $this->module_id, '_wpnonce' );

			$api_response = $this->get_default_response();
			$endpoint     = $this->get_base_path() . 'users/register';

			$url              = $this->get_website_url();
			$email            = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
			$this->user_email = $email;
			if ( empty( $email ) || empty( $url ) ) {
				$api_response['code'] = 101;
				return $api_response;
			}
			$request_body = array(
				'email' => $email,
				'url'   => $url,
			);
			$response     = $this->wt_remote_request( 'POST', $endpoint, $request_body );
			if ( isset( $response ) && is_array( $response ) ) {
				if ( isset( $response['token'] ) ) {
					$cky_options = array(
						'status' => 2, // Waiting for email verification.
						'token'  => $response['token'],
						'email'  => $this->get_user_email(),
					);
					$this->set_cookieyes_options( $cky_options );
					$api_response['status'] = true;
					$api_response['code']   = self::WT_CKYES_EMAIL_VERIFICATION_SENT;
					$api_response['html']   = $this->get_email_verification_html();

				} else {
					if ( isset( $response['status'] ) && $response['status'] == 'error' ) {
						if ( isset( $response['error_code'] ) && $response['error_code'] == 1002 ) {
							$api_response['status'] = false;
							$api_response['code']   = self::EC_WT_CKYES_ALREADY_EXIST;
							$api_response['html']   = $this->get_login_html();
						}
					}
				}
			}
			return $api_response;
		}
		/**
		 * Login API
		 *
		 * @return array
		 */
		public function login() {

			check_ajax_referer( $this->module_id, '_wpnonce' );
			$api_response = $this->get_default_response();
			$endpoint     = $this->get_base_path() . 'users/login';

			$url   = $this->get_website_url();
			$email = $this->get_user_email();

			$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : $email;
			$password = isset( $_POST['password'] ) ? $_POST['password'] : ''; // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( empty( $email ) || empty( $url ) || empty( $password ) ) {
				$api_response['code'] = 101;
				return $api_response;
			}
			$request_body = array(
				'email'    => $email,
				'url'      => $url,
				'password' => $password,
			);
			$response     = $this->wt_remote_request( 'POST', $endpoint, $request_body );

			if ( isset( $response ) && is_array( $response ) ) {

				if ( isset( $response['status'] ) && 'error' === $response['status'] ) {

					if ( isset( $response['error_code'] ) && 1003 == $response['error_code'] ) {
						$api_response['code'] = 101;
					}
				} else {
					if ( isset( $response['token'] ) ) {
						$cky_options = array(
							'status' => true,
							'token'  => $response['token'],
							'email'  => $this->get_user_email(),
						);
						$this->set_cookieyes_options( $cky_options );
						$this->set_ckyes_branding_default();
						$api_response['status'] = true;
						$api_response['code']   = 200;
					}
				}
			}
			return $api_response;
		}
		/**
		 * Retreive next scan ID from CookieYes.
		 *
		 * @param int $total_urls Total URLs to be scanned.
		 * @return array
		 */
		public function get_next_scan_id( $total_urls ) {

			$api_response = array(
				'status'     => false,
				'code'       => 100,
				'scan_id'    => '',
				'scan_token' => '',
			);
			if ( $this->get_cookieyes_status() === 1 || $this->get_cookieyes_status() === 2 ) {

				$token = $this->get_access_token();
				if ( empty( $token ) ) {
					return $api_response;
				}
				$endpoint     = $this->get_base_path() . 'scan/create';
				$request_body = array(
					'page_limit'        => $total_urls,
					'scan_result_token' => $this->set_ckyes_scan_instance(),
				);

				$response = $this->wt_remote_request( 'POST', $endpoint, $request_body, $token );
				if ( isset( $response ) && is_array( $response ) ) {

					if ( isset( $response['status'] ) && $response['status'] === 'error' ) {
						if ( isset( $response['error_code'] ) ) {
							if ( $response['error_code'] == 1005 ) {
								$response = $this->refresh_scan_token();
							} elseif ( $response['error_code'] == 1007 ) {
								$api_response['code'] = self::EC_WT_CKYES_PENDING_VERIFICATION;
							}
						}
					}
					if ( isset( $response['scan_id'] ) && $response['scan_token'] ) {
						if ( $this->get_cookieyes_status() === 2 ) { // If email verified then set to activate state.
							$this->change_status( true );
							$this->set_ckyes_branding_default();

						}
						$api_response['status']     = true;
						$api_response['scan_id']    = $response['scan_id'];
						$api_response['scan_token'] = $response['scan_token'];
						$api_response['code']       = self::WT_CKYES_SCAN_INITIATED;
					}
				} else {
					return $api_response;
				}
			} else {
				$api_response['code'] = self::EC_WT_CKYES_DISCONNECTED;
			}
			return $api_response;
		}
		/**
		 * Reset password API
		 *
		 * @return array
		 */
		public function reset_password() {
			check_ajax_referer( $this->module_id, '_wpnonce' );
			$api_response = $this->get_default_response();

			$endpoint = $this->get_base_path() . 'password/reset';

			$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( empty( $email ) ) {
				$api_response['code'] = 101;
				return $api_response;
			}
			$request_body = array(
				'email' => $email,
			);
			$response     = $this->wt_remote_request( 'POST', $endpoint, $request_body );
			if ( isset( $response ) && is_array( $response ) ) {
				if ( isset( $response['status'] ) && 'success' === $response['status'] ) {

					$api_response['status'] = true;
					$api_response['code']   = 202;
				}
			}

			return $api_response;
		}
		/**
		 * Resend email verification
		 *
		 * @return array
		 */
		public function resend_email() {
			$api_response = $this->get_default_response();
			$token        = $this->get_access_token();
			if ( empty( $token ) ) {
				return $api_response;
			}
			$endpoint = $this->get_base_path() . 'users/resend-verification-email';
			$response = $this->wt_remote_request( 'POST', $endpoint, false, $token );

			if ( isset( $response ) && is_array( $response ) ) {
				if ( isset( $response['status'] ) && 'resend_email_verification' === $response['status'] ) {
					$api_response['status'] = true;
					$api_response['code']   = self::WT_CKYES_EMAIL_VERIFICATION_SENT;
					$api_response['html']   = $this->get_email_verification_html( false, false );
				} elseif ( isset( $response['status'] ) && 'already_verified' === $response['status'] ) {
					$api_response['status'] = false;
					$api_response['code']   = self::EC_WT_CKYES_EMAIL_ALREADY_VERIFIED;
				}
			}
			return $api_response;
		}
		/**
		 * Change connection status with CookieYes
		 *
		 * @return array
		 */
		public function connect_disconnect() {
			check_ajax_referer( $this->module_id, '_wpnonce' );
			$api_response = array(
				'status'  => false,
				'code'    => 100,
				'message' => '',
			);
			$message      = __( 'Successfully disconnected with Cookieyes', 'cookie-law-info' );
			$action       = isset( $_POST['account_action'] ) ? sanitize_text_field( wp_unslash( $_POST['account_action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( empty( $action ) ) {
				$api_response['message'] = __( 'Could not identify the action', 'cookie-law-info' );
				return $api_response;
			}
			if ( 'connect' === $action ) {
				$this->change_status( true );
				$message = __( 'Successfully connected with Cookieyes', 'cookie-law-info' );
			} else {
				$this->change_status( false );
			}
			$api_response['status']  = true;
			$api_response['message'] = $message;
			return $api_response;
		}
		/**
		 * Connect with CookieYes
		 *
		 * @return array
		 */
		public function ckyes_connect() {
			$api_response = array(
				'status' => false,
			);
			$this->change_status( true );
			$api_response['status'] = true;
			return $api_response;
		}
		/**
		 * Chane status
		 *
		 * @param boolean $status current status.
		 * @return void
		 */
		public function change_status( $status = false ) {
			$ckye_status = 0;
			if ( true === $status ) {
				$ckye_status = 1;
			}
			$ckyes_options           = $this->get_cookieyes_options();
			$ckyes_options['status'] = $ckye_status;
			$this->set_cookieyes_options( $ckyes_options );
		}
		/**
		 * Refresh the current scan token
		 *
		 * @return array
		 */
		protected function refresh_scan_token() {

			$token = $this->get_access_token();

			if ( empty( $token ) ) {
				return false;
			}
			$endpoint = $this->get_base_path() . 'scan/token';
			$response = $this->wt_remote_request( 'GET', $endpoint, false, $token );
			return $response;
		}
		/**
		 * Return the current scanning status
		 *
		 * @param int $scan_id CookieYes scan ID.
		 * @return array
		 */
		protected function get_scan_status( $scan_id ) {

			$token = $this->get_access_token();

			if ( empty( $token ) ) {
				return false;
			}
			$endpoint = $this->get_base_path() . 'scan/' . $scan_id . '/status';
			$response = $this->wt_remote_request( 'GET', $endpoint, false, $token );
			return $response;
		}
		/**
		 * Return the final san results
		 *
		 * @param int $scan_id CookieYes scan ID.
		 * @return array
		 */
		protected function get_scan_results( $scan_id ) {

			$token = $this->get_access_token();

			if ( empty( $token ) ) {
				return false;
			}
			$endpoint = $this->get_base_path() . 'scan/' . $scan_id . '/result';

			$response = $this->wt_remote_request( 'GET', $endpoint, false, $token );
			return $response;
		}
		/**
		 * Add option to enable / disable cookeiyes branding on settings popup
		 *
		 * @since  1.9.6
		 * @access public
		 */
		public function ckyes_settings() {

			if ( $this->get_cookieyes_status() !== false ) : // wt_cli_temp_fix.

				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"></th>
						<td>
							<button class="wt-cli-ckyes-delete-btn button" data-action="show-prompt"><?php echo esc_html( __( 'Delete site data from CookieYes', 'cookie-law-info' ) ); ?></button>
						</td>
					</tr>
				</table>
				<div class='wt-cli-modal' id='wt-cli-ckyes-modal-delete-account'>
					<span class="wt-cli-modal-js-close">×</span>
					<div class="wt-cli-modal-header"><h4><?php echo esc_html__( 'Do you really want to delete your website from CookieYes', 'cookie-law-info' ); ?></h4></div>
					<div class="wt-cli-modal-body">
						<p><?php echo esc_html__( 'This action will clear all your website data from CookieYes. If you have multiple websites added to your CookieYes account, then only the data associated with this website get deleted. Otherwise, your entire account will be deleted.', 'cookie-law-info' ); ?></p>
						<button class="wt-cli-action wt-cli-ckyes-delete-btn button button-primary" data-action="delete-account" ><?php echo esc_html__( 'Delete this website', 'cookie-law-info' ); ?></button>
					</div>
				</div>
				<?php
			endif;
		}
		/**
		 * CookieYes branding settings update
		 *
		 * @return void
		 */
		public function ckyes_save_settings() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			check_admin_referer( 'cookielawinfo-update-' . CLI_SETTINGS_FIELD );
			if ( isset( $_POST['wt-cli-ckyes-branding'] ) && 'yes' === $_POST['wt-cli-ckyes-branding'] ) {
				$this->set_ckyes_branding( true );
			} else {
				$this->set_ckyes_branding( false );
			}
		}
		/**
		 * CookieYes branding status
		 *
		 * @return bool
		 */
		public function get_ckyes_branding() {
			$ckyes_branding = get_option( 'wt_cli_ckyes_branding', false );
			if ( false !== $ckyes_branding ) {
				return sanitize_text_field( $ckyes_branding );
			}
			return false;
		}
		/**
		 * Save CookieYes branding options
		 *
		 * @param string $value status value.
		 * @return void
		 */
		public function set_ckyes_branding( $value ) {
			if ( true === $value ) {
				update_option( 'wt_cli_ckyes_branding', 'yes' );
			} else {
				update_option( 'wt_cli_ckyes_branding', 'no' );
			}
		}
		/**
		 * Get CookieYes branding default options
		 *
		 * @return void
		 */
		public function set_ckyes_branding_default() {
			if ( $this->get_ckyes_branding() === false ) {
				$this->set_ckyes_branding( true );
			}
		}
		/**
		 * Show CookieYes branding logo on the settings page
		 *
		 * @return bool
		 */
		public function show_ckyes_branding() {
			if ( $this->get_ckyes_branding() === 'yes' && $this->get_cookieyes_status() === 1 ) {
				return true;
			}
			return false;
		}
		/**
		 * Check if email verified or not
		 *
		 * @return array
		 */
		public function check_email_verified() {

			$response = $this->get_default_response();

			if ( 2 === $this->get_cookieyes_status() ) {
				$response['code']   = self::EC_WT_CKYES_PENDING_VERIFICATION;
				$response['status'] = false;
				$response['html']   = $this->get_email_verification_html( true );
			} else {
				$response = $this->ckyes_connect();
			}
			return $response;
		}
		/**
		 * Return email verification modal HTML
		 *
		 * @param boolean $pending Whether already email verification send or not.
		 * @param boolean $resend Disable resend.
		 * @return string
		 */
		public function get_email_verification_html( $pending = false, $resend = true ) {

			$html           = '';
			$resend_message = '';
			/* translators: %s: user email. */
			$message = sprintf( __( "We've sent an account verification link to the email address %s. Please click on the link given in email to verify your account with CookieYes.", 'cookie-law-info' ), esc_html( $this->get_user_email() ) );

			if ( true === $resend ) {
				/* translators: %s: Resent link. */
				$resend_message = wp_kses(
					__( "If you didn't receive the email, click <a id='wt-cli-ckyes-email-resend-link' role='button'>here</a> to resend the verification email.", 'cookie-law-info' ),
					array(
						'a' => array(
							'href'  => array(),
							'class' => array(),
							'id'    => array(),
							'role'  => array(),
						),
					)
				);
			}
			$heading = __( 'Verification link sent', 'cookie-law-info' );
			if ( true === $pending ) {
				$heading = __( 'Pending email verification!', 'cookie-law-info' );
			}
			$html .= '<div class="wt-cli-ckyes-form-email-verify">';
			$html .= '<h4>' . $heading . '</h4>';
			$html .= '<div>' . $message . '</div>';
			$html .= '<div>' . $resend_message . '</div>';
			$html .= '</div>';

			return $html;
		}
		/**
		 * API request to abort the CookieYes scan.
		 *
		 * @param int $scan_id scan ID.
		 * @return array
		 */
		public function ckyes_abort_scan( $scan_id ) {
			$api_response = $this->get_default_response();
			$token        = $this->get_access_token();

			if ( empty( $token ) ) {
				return false;
			}
			$endpoint = $this->get_base_path() . 'scan/' . $scan_id . '/abort';
			$response = $this->wt_remote_request( 'POST', $endpoint, false, $token );
			if ( isset( $response['scan_result'] ) && 'cancelled' === $response['scan_result'] ) {
				$api_response['status'] = true;
				$api_response['code']   = self::WT_CKYES_ABORT_SUCCESSFULL;
			}
			return $api_response;
		}
		/**
		 * Return login form HTML
		 *
		 * @return string
		 */
		public function get_login_html() {
			$html  = '';
			$html .= '<div class="wt-cli-modal-body">';
			$html .= '<div class="wt-cli-ckyes-login-icon">';
			$html .= '<span class="dashicons dashicons-admin-users"></span>';
			$html .= '</div>';
			$html .= '<h4>' . sprintf( __( 'Looks like you already have an account with CookieYes for email id %s, please login to continue.', 'cookie-law-info' ), esc_html( $this->get_user_email() ) ) . '</h4>';
			$html .= '<form id="wt-cli-ckyes-form-login">';
			$html .= '<div class="wt-cli-form-row">';
			$html .= '<input type="email" name="ckyes-email" class="wt-cli-form-input" placeholder="' . __( 'Email', 'cookie-law-info' ) . '" value="' . esc_attr( $this->get_user_email() ) . '"/>';
			$html .= '<input type="password" name="ckyes-password" class="wt-cli-form-input" placeholder="' . __( 'Password', 'cookie-law-info' ) . '" />';
			$html .= '</div>';
			$html .= '<p style="color: #757575">' . __( 'Please check if you have received an email with your password from CookieYes.', 'cookie-law-info' ) . '</p>';
			$html .= '<p style="color: #757575;">' . __( 'If you did not get the email, click “Reset password” to create a new password.', 'cookie-law-info' ) . '</p>';
			$html .= '<div class="wt-cli-action-container">';
			$html .= '<div class="wt-cli-action-group">';
			$html .= '<a href="#" id="wt-cli-ckyes-pwd-reset-link" class="wt-cli-action-link">' . __( 'Reset password', 'cookie-law-info' ) . '</a>';
			$html .= '</div>';
			$html .= '<div class="wt-cli-action-group">';
			$html .= '<button id="wt-cli-ckyes-login-btn" class="wt-cli-action button button-primary">' . __( 'Login', 'cookie-law-info' ) . '</button>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</form>';
			$html .= '</div>';
			return $html;

		}
		public function delete_account() {

			$api_response = $this->get_default_response();
			if ( 1 === apply_filters( 'wt_cli_cookie_scan_status', 0 ) ) {
				$ckyes_scan_id = $this->get_ckyes_scan_id();
				if ( $ckyes_scan_id ) {
					$response = $this->ckyes_abort_scan( $ckyes_scan_id );
					$status   = isset( $response['status'] ) ? $response['status'] : false;
					if ( false === $status ) {
						wp_send_json_error();
					}
					do_action( 'wt_cli_ckyes_abort_scan' );
				}
			}
			$this->delete_ckyes_account();

		}
		public function delete_ckyes_account() {
			$api_response = $this->get_default_response();
			$token        = $this->get_access_token();

			if ( empty( $token ) ) {
				return $api_response;
			}

			$endpoint = $this->get_base_path() . 'users/delete';
			$response = $this->wt_remote_request( 'POST', $endpoint, false, $token );

			if ( isset( $response['status'] ) && 'deleted_successfully' === $response['status'] ) {
				$api_response['status'] = true;
				$this->reset_token();
				wp_send_json_success();
			}
			wp_send_json_error();
		}
		public function get_ckyes_scan_data() {

			if ( ! $this->ckyes_scan_data ) {
				$scan_data       = array(
					'scan_id'       => 0,
					'scan_status'   => '',
					'scan_token'    => '',
					'scan_estimate' => '',
				);
				$ckyes_scan_data = get_option( 'wt_cli_ckyes_scan_options', false );

				if ( $ckyes_scan_data !== false && is_array( $ckyes_scan_data ) ) {

					$scan_data['scan_id']       = intval( isset( $ckyes_scan_data['scan_id'] ) ? $ckyes_scan_data['scan_id'] : 0 );
					$scan_data['scan_status']   = isset( $ckyes_scan_data['scan_status'] ) ? $ckyes_scan_data['scan_status'] : 0;
					$scan_data['scan_token']    = isset( $ckyes_scan_data['scan_token'] ) ? $ckyes_scan_data['scan_token'] : '';
					$scan_data['scan_estimate'] = isset( $ckyes_scan_data['scan_estimate'] ) ? $ckyes_scan_data['scan_estimate'] : 0;
					$scan_data['scan_instance'] = isset( $ckyes_scan_data['scan_instance'] ) ? $ckyes_scan_data['scan_instance'] : 0;

				} else {
					return false;
				}
				$this->ckyes_scan_data = $scan_data;
			}
			return $this->ckyes_scan_data;
		}
		public function get_ckyes_scan_id() {
			$ckyes_scan_data = $this->get_ckyes_scan_data();
			return ( isset( $ckyes_scan_data['scan_id'] ) ? $ckyes_scan_data['scan_id'] : 0 );
		}

		public function get_ckyes_scan_status() {
			$ckyes_scan_data = $this->get_ckyes_scan_data();
			return ( isset( $ckyes_scan_data['scan_status'] ) ? intval( $ckyes_scan_data['scan_status'] ) : 0 );
		}

		public function get_ckyes_scan_token() {
			$ckyes_scan_data = $this->get_ckyes_scan_data();
			return ( isset( $ckyes_scan_data['scan_token'] ) ? $ckyes_scan_data['scan_token'] : '' );
		}

		public function get_ckyes_scan_estimate() {
			$ckyes_scan_data = $this->get_ckyes_scan_data();
			return ( isset( $ckyes_scan_data['scan_estimate'] ) ? $ckyes_scan_data['scan_estimate'] : 0 );
		}

		public function set_ckyes_scan_id( $value = 0 ) {
			$this->set_ckyes_scan_data( 'scan_id', $value );
		}

		public function set_ckyes_scan_status( $value = 0 ) {
			$this->set_ckyes_scan_data( 'scan_status', $value );
		}

		public function set_ckyes_scan_token( $value = '' ) {
			$this->set_ckyes_scan_data( 'scan_token', $value );
		}

		public function set_ckyes_scan_estimate( $value = 0 ) {
			$this->set_ckyes_scan_data( 'scan_estimate', $value );
		}

		public function set_ckyes_scan_data( $option_name, $value ) {
			$options                 = $this->get_ckyes_scan_data();
			$options[ $option_name ] = $value;
			update_option( 'wt_cli_ckyes_scan_options', $options );
			$this->ckyes_scan_data = $options;
		}
		public function reset_scan_token() {
			delete_option( 'wt_cli_ckyes_scan_options' );
		}

		public function set_ckyes_scan_instance() {
			$instance_id = 'wt-cli-scan-' . wp_create_nonce( $this->module_id );
			$instance_id = base64_encode( $instance_id );
			$this->set_ckyes_scan_data( 'scan_instance', $instance_id );
			return $instance_id;
		}
		public function get_ckyes_scan_instance() {
			$ckyes_scan_data = $this->get_ckyes_scan_data();
			return ( isset( $ckyes_scan_data['scan_instance'] ) ? $ckyes_scan_data['scan_instance'] : 0 );
		}
	}
	$settings_popup = new Cookie_Law_Info_Cookieyes();
}
