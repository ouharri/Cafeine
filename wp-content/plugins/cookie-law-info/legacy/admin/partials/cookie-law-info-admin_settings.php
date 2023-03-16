<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$cli_admin_view_path   = CLI_PLUGIN_PATH . 'admin/views/';
$cli_img_path          = CLI_PLUGIN_URL . 'images/';
$plugin_name           = 'wtgdprcookieconsent';
$cli_activation_status = get_option( $plugin_name . '_activation_status' );

// taking pages for privacy policy URL.
$args_for_get_pages = array(
	'sort_order'   => 'ASC',
	'sort_column'  => 'post_title',
	'hierarchical' => 0,
	'child_of'     => 0,
	'parent'       => -1,
	'offset'       => 0,
	'post_type'    => 'page',
	'post_status'  => 'publish',
);
$all_pages          = get_pages( $args_for_get_pages );
?>
<script type="text/javascript">
	var cli_settings_success_message='<?php echo esc_html__( 'Settings updated.', 'cookie-law-info' ); ?>';
	var cli_settings_error_message='<?php echo esc_html__( 'Unable to update Settings.', 'cookie-law-info' ); ?>';
	var cli_reset_settings_success_message='<?php echo esc_html__( 'Settings reset to defaults.', 'cookie-law-info' ); ?>';
	var cli_reset_settings_error_message='<?php echo esc_html__( 'Unable to reset settings.', 'cookie-law-info' ); ?>';
</script>
<div class="wrap">
	<h2 class="wp-heading-inline"><?php echo esc_html__( 'Settings', 'cookie-law-info' ); ?></h2>
	
	<div class="wt-cli-gdpr-plugin-header">
		<div class="wt-cli-gdpr-plugin-status-bar">
			<table class="cli_notify_table cli_bar_state">
				<tr valign="middle" class="cli_bar_on" style="<?php echo $the_options['is_on'] == true ? '' : 'display:none;'; ?>">
					<td style="padding-left: 10px;">
						<div class="wt-cli-gdpr-plugin-status wt-cli-gdpr-plugin-status-active">
							<img id="cli-plugin-status-icon" src="<?php echo esc_url( $cli_img_path ); ?>add.svg" />
							<span><?php echo esc_html__( 'Cookie bar is currently active', 'cookie-law-info' ); ?></span>
						</div>
					</td>
				</tr>
				<tr valign="middle" class="cli_bar_off" style="<?php echo $the_options['is_on'] == true ? 'display:none;' : ''; ?>">
					<td style="padding-left: 10px;">
						<div class="wt-cli-gdpr-plugin-status wt-cli-gdpr-plugin-status-active">
							<img id="cli-plugin-status-icon" src="<?php echo esc_url( $cli_img_path ); ?>cross.png" />
							<span><?php echo esc_html__( 'Cookie bar is currently inactive', 'cookie-law-info' ); ?></span>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="wt-cli-gdpr-plugin-branding">
			<div class="wt-cli-gdpr-plugin-branding-logo">
				<img src="<?php echo esc_url( $cli_img_path ); ?>logo-cookieyes.svg" alt="CookieYes Logo">
			</div>
			<div class="wt-cli-gdpr-plugin-branding-tagline">

			<span><b><?php echo esc_html__( 'Cookie Compliance Made Easy', 'cookie-law-info' ); ?> | 
								<?php
								echo sprintf(
									wp_kses(
										__( 'Plugin Developed By <a href="%s" target="_blank">WebToffee</a>', 'cookie-law-info' ),
										array(
											'a' => array(
												'href'   => array(),
												'target' => array(),
											),
										)
									),
									'https://www.webtoffee.com/'
								);
								?>
			</b></span>
			</div>
		</div>
	</div>

   
	<div class="cli_settings_left" id="cky-container">
		<div class="nav-tab-wrapper wp-clearfix cookie-law-info-tab-head">
			<?php
			$tab_head_arr = array(
				'cookie-law-info-general'     => __( 'General', 'cookie-law-info' ),
				'cookie-law-info-message-bar' => __( 'Customise Cookie Bar', 'cookie-law-info' ),
				'cookie-law-info-buttons'     => __( 'Customise Buttons', 'cookie-law-info' ),
				'cookie-law-info-advanced'    => __( 'Advanced', 'cookie-law-info' ),
				'cookie-law-info-help'        => __( 'Help Guide', 'cookie-law-info' ),
				'cookie-law-info-upgrade-pro' => __( 'Free vs Pro', 'cookie-law-info' ),
			);
			Cookie_Law_Info::generate_settings_tabhead( $tab_head_arr );
			?>
		</div>
		<div id="cky-tab-container" class="cookie-law-info-tab-container">
			<?php
			$setting_views_a = array(
				'cookie-law-info-general'     => 'admin-settings-general.php',
				'cookie-law-info-message-bar' => 'admin-settings-messagebar.php',
				'cookie-law-info-buttons'     => 'admin-settings-buttons.php',
				'cookie-law-info-advanced'    => 'admin-settings-advanced.php',
			);
			$setting_views_b = array(
				'cookie-law-info-help'        => 'admin-settings-help.php',
				'cookie-law-info-upgrade-pro' => 'admin-settings-upgrade-pro.php',
			);
			?>
			<?php $form_action = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; ?>
			<form method="post" action="<?php echo esc_url( $form_action ); ?>" id="cli_settings_form">
				<input type="hidden" name="cli_update_action" value="" id="cli_update_action" />
				<?php
				// Set nonce:
				if ( function_exists( 'wp_nonce_field' ) ) {
					wp_nonce_field( 'cookielawinfo-update-' . CLI_SETTINGS_FIELD );
				}
				foreach ( $setting_views_a as $target_id => $value ) {
					$settings_view = $cli_admin_view_path . $value;
					if ( file_exists( $settings_view ) ) {
						include $settings_view;
					}
				}
				?>
				 
				<?php
				// settings form fields for module
				do_action( 'cli_module_settings_form' );
				?>
							
			</form>
			<?php
			foreach ( $setting_views_b as $target_id => $value ) {
				$settings_view = $cli_admin_view_path . $value;
				if ( file_exists( $settings_view ) ) {
					include $settings_view;
				}
			}
			?>
			<?php do_action( 'cli_module_out_settings_form' ); ?>
		</div>
	</div>
	<div class="cli_settings_right">
	 <?php require $cli_admin_view_path . 'goto-pro-v2.php'; ?>   
	</div>

</div>
