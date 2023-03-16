<?php
/**
 * Class Review_Feedback file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Review_Feedback;

use CookieYes\Lite\Includes\Modules;
use CookieYes\Lite\Includes\Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Uninstall feedback Operation
 *
 * @class       Review_Feedback
 * @version     3.0.0
 * @package     CookieYes
 */
class Review_Feedback extends Modules {

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
	protected $rest_base = '/settings/notices/review_notice';

	/**
	 * WordPress.org review link
	 *
	 * @var string
	 */
	protected $review_url = 'https://wordpress.org/support/plugin/cookie-law-info/reviews/#new-post';

	/**
	 * Constructor.
	 */
	public function init() {
		add_action( 'admin_notices', array( $this, 'add_notice' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'add_script' ) );
	}

	/**
	 * Display review notice
	 *
	 * @return void
	 */
	public function add_notice() {
		$plugin_dir_url = defined( 'CKY_PLUGIN_URL' ) ? CKY_PLUGIN_URL : trailingslashit( site_url() );
		$assets_path    = $plugin_dir_url . 'admin/dist/img/';
		$screen         = get_current_screen();

		if ( $screen && 'edit' === $screen->parent_base || ! current_user_can( 'manage_options' ) || true === cky_is_admin_page() ) {
			return;
		}
		$notices = Notice::get_instance()->get();
		if ( ! isset( $notices['review_notice'] ) ) {
			return;
		}
		?>
		<div class="cky-notice-review cky-admin-notice cky-admin-notice-default is-dismissible">
			<div class="cky-admin-notice-content">
				<div class="cky-admin-notice-message">
					<div class="cky-row cky-align-center">
						<div class="cky-col-12">
							<h4 class="cky-admin-notice-header"><img width="100" src="<?php echo esc_attr( $assets_path ) . 'logo.svg'; ?>" alt=""></h4>
							<p style="margin-top: 15px; margin-bottom:5px;"><?php echo wp_kses_post( sprintf( __( 'Hey, we at %1$s CookieYes %2$s would like to thank you for using our plugin. We would really appreciate if you could take a moment to drop a quick review that will inspire us to keep going.', 'cookie-law-info' ), '<b>', '</b>' ) ); ?></p>
						</div>
						<div class="cky-col-12">
							<div class="cky-flex" style="margin-top: 10px;">
								<button class="cky-button cky-button-review"><?php echo esc_html__( 'Review now', 'cookie-law-info' ); ?></button>
								<button class="cky-button-outline-secondary cky-button cky-button-cancel" style="margin-left: 10px;"><?php echo esc_html__( 'Remind me later', 'cookie-law-info' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="cky-admin-notice-close"><button type="button" aria-label="Close" class="cky-close cky-button-cancel"><span aria-hidden="true">×</span></button></div>
		</div>
		<style>
			.cky-admin-notice {
				display: flex;
				justify-content: space-between;
				position: relative;
				margin: 0 0 10px;
				border-radius: 5px;
				background: #ffffff;
				margin: 15px 20px 10px 2px;
				width: calc(100% - 20px);
				float: left;
				position: relative;
				border: 1px solid #d7e1f2;
			}
			.cky-admin-notice-content {
				display: flex;
				align-items: center;
				margin: 0;
				padding: 10px 15px 10px 15px;
				border: 0;
			}
			.cky-admin-notice-header {
				margin-top: 5px;
			}
			.cky-admin-notice .cky-admin-notice-content .cky-admin-notice-header {
				display: flex;
				align-items: center;
				margin: 0 0 5px;
				padding: 0;
				border: 0;
				color: #23282d;
				font-size: 16px;
				line-height: 18px;
			}
			.cky-button {
				width: auto;
				min-width: 80px;
				padding: 8px 14px;
				background-color: #1863dc;
				color: #ffffff;
				border: 1px solid #1863dc;
				font-weight: 500;
				font-size: 14px;
				border-radius: 3px;
				cursor: pointer;
				line-height: 16px;
			}
			.cky-button-outline-secondary {
				background-color: transparent;
				color: #555d66;
				border-color: #c9d0d6;
			}
			.cky-admin-notice .cky-close {
				font-size: 20px;
				font-weight: 300;
				padding: 0;
				background: transparent;
				border: none;
				display: inline-block;
				color: #7e7e7e;
				cursor: pointer;
			}
			.cky-admin-notice .cky-admin-notice-close {
				display: flex;
				align-items: center;
				margin-right: 15px;
				position: absolute;
				right: 0;
				top: 10px;
			}
			.cky-admin-notice .cky-admin-notice-content p {
				font-size: 14px;
			}
			.cky-admin-notice-footer {
				position: absolute;
				right: 45px;
				bottom: 15px;
			}
			.cky-admin-notice-message {
				flex: 1;
				position: relative;
				padding: 5px 20px 3px 0px;
			}
		</style>
		<?php

	}

	/**
	 * Review feedback scripts.
	 *
	 * @return void
	 */
	public function add_script() {
		$expiry = 15 * DAY_IN_SECONDS;
		?>
			<script type="text/javascript">
				(function($) {
					const expiration = '<?php echo esc_js( $expiry ); ?>';
					function ckyUpdateNotice( expiry = expiration ) {
						$.ajax({
							url: "<?php echo esc_url_raw( rest_url() . $this->namespace . $this->rest_base ); ?>",
							headers: {
								'X-WP-Nonce': '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>',
								contentType: 'application/json'
							},
							type: 'POST',
							dataType: 'json',
							data: {
								expiry: expiry
							},
							complete: function( response ) {
								$('.cky-notice-review').hide();
							}
						});
					}
					$(document).on('click', '.cky-button-cancel', function(e) {
						e.preventDefault();
						ckyUpdateNotice();
					});
					$(document).on('click', '.cky-button-review', function(e) {
						e.preventDefault();
						ckyUpdateNotice(0);
						window.open('<?php echo esc_js( $this->review_url ); ?>');
					});
				})(jQuery)
			</script>
			<?php
	}
}
