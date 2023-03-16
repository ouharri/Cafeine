<?php
/**
 * Class Uninstall_Feedback file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Uninstall_Feedback;

use WP_Error;
use CookieYes\Lite\Includes\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Uninstall feedback Operation
 *
 * @class       Uninstall_Feedback
 * @version     3.0.0
 * @package     CookieYes
 */
class Uninstall_Feedback extends Modules {

	/**
	 * API url.
	 *
	 * @var string
	 */
	protected $api_url = 'https://feedback.cookieyes.com/api/v1/feedbacks';

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $current_version = CLI_VERSION;

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	protected $plugin_file = CLI_PLUGIN_BASENAME; // plugin main file.

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'cky/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = '/uninstall-feedback';

	/**
	 * Constructor.
	 */
	public function init() {

		add_action( 'admin_footer', array( $this, 'attach_feedback_modal' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( $this->plugin_file ), array( $this, 'plugin_action_links' ) );
		add_action( 'rest_api_init', array( $this, 'cky_register_routes' ) );
	}

	/**
	 * Register the routes for uninstall feedback.
	 */
	public function cky_register_routes() {

		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'send_uninstall_reason' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
			)
		);
	}

	/**
	 * Check if a given request has access to create an item.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'cookieyes_rest_cannot_create', __( 'Sorry, you are not allowed to create resources.', 'cookie-law-info' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Edit action links
	 *
	 * @param array $links action links.
	 * @return array
	 */
	public function plugin_action_links( $links ) {

		if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="cky-deactivate-link"', $links['deactivate'] );
		}

		return $links;
	}

	/**
	 * Get the uninstall reasons
	 *
	 * @return array
	 */
	private function get_uninstall_reasons() {

		$reasons = array(
			array(
				'id'     => 'setup-difficult',
				'text'   => __( 'Setup is too difficult/ Lack of documentation', 'cookie-law-info' ),
				'fields' => array(
					array(
						'type'        => 'textarea',
						'placeholder' => __(
							'Describe the challenges that you faced while using our plugin',
							'cookie-law-info'
						),
					),
				),
			),
			array(
				'id'     => 'not-have-that-feature',
				'text'   => __( 'The plugin is great, but I need specific feature that you don\'t support', 'cookie-law-info' ),
				'fields' => array(
					array(
						'type'        => 'textarea',
						'placeholder' => __( 'Could you tell us more about that feature?', 'cookie-law-info' ),
					),
				),
			),
			array(
				'id'   => 'affecting-performance',
				'text' => __( 'The plugin is affecting website speed', 'cookie-law-info' ),
			),
			array(
				'id'     => 'found-better-plugin',
				'text'   => __( 'I found a better plugin', 'cookie-law-info' ),
				'fields' => array(
					array(
						'type'        => 'text',
						'placeholder' => __( 'Please share which plugin', 'cookie-law-info' ),
					),
				),
			),
			array(
				'id'     => 'cookieyes-connection-issues',
				'text'   => __( 'I have issues while connecting to the CookieYes web app', 'cookie-law-info' ),
				'fields' => array(
					array(
						'type'        => 'textarea',
						'placeholder' => __( 'Please describe the issues', 'cookie-law-info' ),
					),
				),
			),
			array(
				'id'   => 'use-cookieyes-webapp',
				'text' => __( 'I would like to use the CookieYes web app instead of the plugin', 'cookie-law-info' ),
			),
			array(
				'id'   => 'temporary-deactivation',
				'text' => __( 'It’s a temporary deactivation', 'cookie-law-info' ),
			),
			array(
				'id'     => 'other',
				'text'   => __( 'Other', 'cookie-law-info' ),
				'fields' => array(
					array(
						'type'        => 'textarea',
						'placeholder' => __( 'Please share the reason', 'cookie-law-info' ),
					),
				),
			),
		);

		return $reasons;
	}

	/**
	 * Attach modal for feedback and uninstall
	 *
	 * @return void
	 */
	public function attach_feedback_modal() {
		global $pagenow;
		if ( 'plugins.php' !== $pagenow ) {
			return;
		}
		$reasons = $this->get_uninstall_reasons();
		?>
		<div class="cky-modal" id="cky-modal">
			<div class="cky-modal-wrap">
				<div class="cky-modal-header">
					<h3><?php echo esc_html__( 'Quick Feedback', 'cookie-law-info' ); ?></h3>
					<button type="button" class="cky-modal-close"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0.572899 0.00327209C0.459691 0.00320032 0.349006 0.036716 0.254854 0.0995771C0.160701 0.162438 0.0873146 0.251818 0.0439819 0.356405C0.000649228 0.460992 -0.0106814 0.576084 0.0114242 0.687113C0.0335299 0.798142 0.0880779 0.900118 0.168164 0.980132L4.18928 5L0.168164 9.01987C0.0604905 9.12754 0 9.27358 0 9.42585C0 9.57812 0.0604905 9.72416 0.168164 9.83184C0.275838 9.93951 0.421875 10 0.574148 10C0.726422 10 0.872459 9.93951 0.980133 9.83184L5.00125 5.81197L9.02237 9.83184C9.13023 9.93836 9.2755 9.99844 9.4271 9.99923C9.5023 9.99958 9.57681 9.98497 9.6463 9.95623C9.71579 9.92749 9.77886 9.8852 9.83184 9.83184C9.93924 9.72402 9.99955 9.57804 9.99955 9.42585C9.99955 9.27367 9.93924 9.12768 9.83184 9.01987L5.81072 5L9.83184 0.980132C9.88515 0.926818 9.92744 0.863524 9.9563 0.793865C9.98515 0.724206 10 0.649547 10 0.574148C10 0.49875 9.98515 0.42409 9.9563 0.354431C9.92744 0.284772 9.88515 0.221479 9.83184 0.168164C9.77852 0.114849 9.71523 0.072558 9.64557 0.0437044C9.57591 0.0148507 9.50125 0 9.42585 0C9.35045 0 9.27579 0.0148507 9.20614 0.0437044C9.13648 0.072558 9.07318 0.114849 9.01987 0.168164L4.99813 4.19053L0.976385 0.170662C0.868901 0.0635642 0.723383 0.00338113 0.57165 0.00327209H0.572899Z" fill="#ffffff"/> </svg></button>
				</div>
				<div class="cky-modal-body">
					<h4 class="cky-feedback-caption"><?php echo esc_html__( 'If you have a moment, please let us know why you are deactivating the CookieYes plugin.', 'cookie-law-info' ); ?></h4>
					<ul class="cky-feedback-reasons-list">
						<?php
						foreach ( $reasons as $reason ) :
							?>
							<li>
								<div class="cky-feedback-form-group">
									<label class="cky-feedback-label"><input type="radio" name="selected-reason" value="<?php echo esc_attr( $reason['id'] ); ?>" class="cky-feedback-input-radio"><?php echo esc_html( $reason['text'] ); ?></label>
									<?php
										$fields = ( isset( $reason['fields'] ) && is_array( $reason['fields'] ) ) ? $reason['fields'] : array();
									if ( empty( $fields ) ) {
										continue;
									}
									?>
									<div class="cky-feedback-form-fields">
										<?php

										foreach ( $fields as $field ) :
											$field_type        = isset( $field['type'] ) ? $field['type'] : 'text';
											$field_placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
											$field_key         = isset( $reason['id'] ) ? $reason['id'] : '';
											$field_name        = $field_key . '-' . $field_type;
											if ( 'textarea' === $field_type ) :
												?>
												<textarea rows="3" cols="45" class="cky-feedback-input-field" name="<?php echo esc_attr( $field_name ); ?>" placeholder="<?php echo esc_attr( $field_placeholder ); ?>"></textarea>
												<?php
											else :
												?>
												<input class="cky-feedback-input-field" type="text" name="<?php echo esc_attr( $field_name ); ?>" placeholder="<?php echo esc_attr( $field_placeholder ); ?>" >
												<?php
											endif;
											endforeach;
										?>
									</div>
								</div>
							</li>

						<?php endforeach; ?>
					</ul>
					<div class="cky-uninstall-feedback-privacy-policy">
						<?php esc_html__( "We do not collect any personal data when you submit this form. It's your feedback that we value.", 'cookie-law-info' ); ?>
						<a href="https://www.cookieyes.com/privacy-policy/" target="_blank"><?php echo esc_html__( 'Privacy Policy', 'cookie-law-info' ); ?></a>
					</div>
				</div>
				<div class="cky-modal-footer">
					<button class="button-primary cky-modal-submit">
						<?php echo esc_html__( 'Submit & Deactivate', 'cookie-law-info' ); ?>
					</button>
					<a class="cky-goto-support" href="https://www.cookieyes.com/support/" target="_blank">
						<span class="dashicons dashicons-external"></span>
						<?php echo esc_html__( 'Go to support', 'cookie-law-info' ); ?>
					</a>
					<button class="button-secondary cky-modal-skip">
						<?php echo esc_html__( 'Skip & Deactivate', 'cookie-law-info' ); ?>
					</button>
				</div>
			</div>
		</div>

		<style type="text/css">
			.cky-modal {
				position: fixed;
				z-index: 99999;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				background: rgba(0, 0, 0, 0.5);
				display: none;
			}

			.cky-modal.modal-active {
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.cky-modal-wrap {
				width: 600px;
				position: relative;
				background: #fff;
			}

			.cky-modal-header {
				background: linear-gradient(336.94deg,#1A7FBB -111.69%,#26238D 196.34%);
				padding: 12px 20px;
			}

			.cky-modal-header h3 {
				display: inline-block;
				color: #fff;
				line-height: 150%;
				margin: 0;
			}

			.cky-modal-body {
				font-size: 14px;
				line-height: 2.4em;
				padding: 5px 30px 20px 30px;
				box-sizing: border-box;
			}

			.cky-modal-body h3 {
				font-size: 15px;
			}

			.cky-modal-body .input-text,
			.cky-modal-body {
				width: 100%;
			}

			.cky-modal-body .cky-feedback-input {
				margin-top: 5px;
				margin-left: 20px;
			}

			.cky-modal-footer {
				padding: 0px 20px 15px 20px;
				display: flex;
			}

			.cky-button-left {
				float: left;
			}

			.cky-button-right {
				float: right;
			}

			.cky-sub-reasons {
				display: none;
				padding-left: 20px;
				padding-top: 10px;
				padding-bottom: 4px;
			}

			.cky-uninstall-feedback-privacy-policy {
				text-align: left;
				font-size: 12px;
				line-height: 14px;
				margin-top: 20px;
				font-style: italic;
			}

			.cky-uninstall-feedback-privacy-policy a {
				font-size: 11px;
				color: #1863DC;
				text-decoration-color: #99c3d7;
			}

			.cky-goto-support {
				color: #1863DC;
				text-decoration: none;
				display: flex;
				align-items: center;
				margin-left: 15px;
			}

			.cky-modal-footer .cky-modal-submit {
				background-color: #1863DC;
				border-color: #1863DC;
				color: #FFFFFF;
			}

			.cky-modal-footer .cky-modal-skip {
				font-size: 12px;
				color: #a4afb7;
				background: none;
				border: none;
				margin-left: auto;
			}

			.cky-modal-close {
				background: transparent;
				border: none;
				color: #fff;
				float: right;
				font-size: 18px;
				font-weight: lighter;
				cursor: pointer;
			}

			.cky-feedback-caption {
				font-weight: bold;
				font-size: 15px;
				color: #27283C;
				line-height: 1.5;
			}

			input[type="radio"].cky-feedback-input-radio {
				margin: 0 10px 0 0;
				box-shadow: none;
			}
			.cky-feedback-reasons-list li {
				line-height: 1.9;
			}
			.cky-feedback-label {
				font-size: 13px;
			}
			.cky-modal .cky-feedback-input-field {
				width: 98%;
				display: flex;
				padding: 5px;
				-webkit-box-shadow: none;
				box-shadow: none;
				font-size: 13px;
			}
			.cky-modal input[type="text"].cky-feedback-input-field:focus {
				-webkit-box-shadow: none;
				box-shadow: none;
			}
			.cky-feedback-form-fields {
				margin: 10px 0 0 25px;
				display: none;
			}
		</style>

		<script type="text/javascript">
			(function($) {
				$(function() {
					const modal = $('#cky-modal');
					let deactivateLink = '';
					$('a.cky-deactivate-link').click(function(e) {
						e.preventDefault();
						modal.addClass('modal-active');
						deactivateLink = $(this).attr('href');
						modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'right');
					});

					modal.on('click', '.cky-modal-skip', function(e) {
						e.preventDefault();
						modal.removeClass('modal-active');
						window.location.href = deactivateLink;
					});

					modal.on('click', '.cky-modal-close', function(e) {
						e.preventDefault();
						modal.removeClass('modal-active');
					});
					modal.on('click', 'input[type="radio"]', function() {
						$('.cky-feedback-form-fields').hide();
						const $parent =   $(this).closest('.cky-feedback-form-group');
						if( !$parent ) return;
						const $fields = $parent.find('.cky-feedback-form-fields');
						if( !$fields ) return;
						const $input = $fields.find('.cky-feedback-input-field');
						$input && $fields.show(),$input.focus();
					});
					modal.on('click', '.cky-modal-submit', function(e) {
						e.preventDefault();
						const button = $(this);
						if (button.hasClass('disabled')) {
							return;
						}
						const $radio = $('input[type="radio"]:checked', modal);
						const $parent =   $radio && $radio.closest('.cky-feedback-form-group');
						if( !$parent ) {
							window.location.href = deactivateLink;
							return;
						}
						const $input = $parent.find('.cky-feedback-input-field');
						$.ajax({
							url: "<?php echo esc_url_raw( rest_url() . $this->namespace . $this->rest_base ); ?>",
							type: 'POST',
							data: {
								reason_id: (0 === $radio.length) ? 'none' : $radio.val(),
								reason_text: (0 === $radio.length) ? 'none' : $radio.closest('label').text(),
								reason_info: (0 !== $input.length) ? $input.val().trim() : ''
							},
							beforeSend: function(xhr) {
								button.addClass('disabled');
								button.text('Processing...');
								xhr.setRequestHeader( 'X-WP-Nonce', '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>');
							},
							complete: function() {
								window.location.href = deactivateLink;
							}
						});
					});
				});
			}(jQuery));
		</script>
		<?php
	}

	/**
	 * Send uninstall reason to server
	 *
	 * @param array $request request data.
	 * @return void
	 */
	public function send_uninstall_reason( $request ) {
		global $wpdb;
		if ( ! isset( $request['reason_id'] ) ) {
			wp_send_json_error();
		}
		$data = array(
			'reason_slug'    => sanitize_text_field( wp_unslash( $request['reason_id'] ) ),
			'reason_detail'  => ! empty( $request['reason_text'] ) ? sanitize_text_field( wp_unslash( $request['reason_text'] ) ) : null,
			'date'           => gmdate( 'M d, Y h:i:s A' ),
			'comments'       => ! empty( $request['reason_info'] ) ? sanitize_text_field( wp_unslash( $request['reason_info'] ) ) : null,
			'server'         => ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : null,
			'php_version'    => phpversion(),
			'mysql_version'  => $wpdb->db_version(),
			'wp_version'     => get_bloginfo( 'version' ),
			'wc_version'     => defined( 'WC_VERSION' ) ? WC_VERSION : null,
			'locale'         => get_locale(),
			'plugin_version' => $this->current_version,
			'is_multisite'   => is_multisite(),
		);

		$response = wp_remote_post(
			$this->api_url,
			array(
				'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'body'        => wp_json_encode( $data ),
				'cookies'     => array(),
			)
		);
		wp_send_json_success();
	}
}
