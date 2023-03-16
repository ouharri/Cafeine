<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<style>
	.vvv_textbox{
		height: 150px;
		width: 80%;
	}
	.cli-textbox{
		width: 100%;
		height: 35px;
		margin-bottom: 5px;
	}
	.notice, div.updated, div.error{
		margin: 5px 20px 15px 0;
	}
</style>
<div class="wrap">

	<div class="cookie-law-info-form-container">
		<div class="cli-plugin-toolbar top">
			<h3><?php echo esc_html__( 'Privacy Overview', 'cookie-law-info' ); ?></h3>
		</div>
		<?php $form_action = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; ?>
		<form method="post" action="<?php echo esc_url( $form_action ); ?>">
		<?php wp_nonce_field( 'cookielawinfo-update-privacy-overview-content' ); ?>
			<table class="form-table cli_privacy_overview_form" >
				<tr valign="top">
					<td>
						<label for="privacy_overview_title"><?php echo esc_html__( 'Title', 'cookie-law-info' ); ?></label>
						<input type="text" name="privacy_overview_title" value="<?php echo esc_attr( sanitize_text_field( stripslashes( $privacy_title ) ) ); ?>" class="cli-textbox" />
					</td>
				 </tr>
				<tr valign="top">
					<td>
					<label for="privacy_overview_content"><?php echo esc_html__( 'Privacy overview is displayed when the user clicks on ‘cookie settings’ from the cookie consent bar. Edit/ modify the title and content of ‘privacy overview’ from here.', 'cookie-law-info' ); ?></label>
						<?php
						$cli_use_editor = apply_filters( 'cli_use_editor_in_po', true );
						if ( $cli_use_editor ) {
							wp_editor(
								stripslashes( $privacy_content ),
								'cli_privacy_overview_content',
								$wpe_settings = array(
									'textarea_name' => 'privacy_overview_content',
									'textarea_rows' => 10,
								)
							);
						} else {
							?>
							<textarea style="width:100%; height:250px;" name="privacy_overview_content"><?php echo wp_kses_post( stripslashes( $privacy_content ) ); ?></textarea>
							<?php
						}
						?>
							 
						<div class="clearfix"></div>
					</td>
				</tr>
			</table>
			<div class="cli-plugin-toolbar bottom">
				<div class="left">
				</div>
				<div class="right">
					<input type="submit" name="update_privacy_overview_content_settings_form" value="<?php echo esc_html__( 'Save Settings', 'cookie-law-info' ); ?>" style="float: right;" class="button-primary" />
					<span class="spinner" style="margin-top:9px"></span>
				</div>
			</div>
		</form>
	</div>
</div>
