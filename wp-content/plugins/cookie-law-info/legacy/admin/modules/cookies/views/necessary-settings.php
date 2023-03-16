<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<style>
	.vvv_textbox{
		height: 150px;
		width:100%;
	}
	#wpbody-content .notice {
		margin: 5px 20px 15px 0;
	}
	.notice, div.updated, div.error{
		margin: 5px 20px 15px 0;
	}
</style>
<script type="text/javascript">
	var cli_success_message='<?php echo esc_html__( 'Settings updated.', 'cookie-law-info' ); ?>';
	var cli_error_message='<?php echo esc_html__( 'Unable to update Settings.', 'cookie-law-info' ); ?>';
</script>   
<div class="wrap">
	<div class="cookie-law-info-form-container">
		<div class="cli-plugin-toolbar top">
			<h3><?php echo esc_html__( 'Necessary Cookie Settings', 'cookie-law-info' ); ?></h3>
		</div>
		<?php $form_action = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; ?>

		<form method="post" action="<?php echo esc_url( $form_action ); ?>" id="cli_ncessary_form" class="cookie-sensitivity-form">
			<?php wp_nonce_field( 'cookielawinfo-update-necessary' ); ?> 
			<table class="form-table cli_necessary_form cli-admin-table">
				<tr>
					<td>
						<label for="wt_cli_necessary_title"><?php echo esc_html__( 'Title', 'cookie-law-info' ); ?></label>
						<input type="text" id="wt_cli_necessary_title" name="wt_cli_necessary_title" value="<?php echo esc_attr( stripslashes( $settings['title'] ) ); ?>" class="cli-textbox" />
					</td>
				</tr>
				<tr>
					<td>
					   <label for="necessary_description"><?php echo esc_html__( 'Description', 'cookie-law-info' ); ?></label>
						<textarea name="necessary_description" class="vvv_textbox"><?php echo wp_kses_post( apply_filters( 'format_to_edit', stripslashes( $settings['description'] ) ) ); ?>
						</textarea>
					</td>
				</tr>
				
			</table>
			<div class="cli-plugin-toolbar bottom">
				<div class="left">
				</div>
				<div class="right">
					<input type="hidden" name="cli_necessary_ajax_update" value="1">
					<input type="submit" name="update_admin_settings_form" value="<?php echo esc_html__( 'Update Settings', 'cookie-law-info' ); ?>" class="button-primary" style="float:right;" onClick="return cli_store_settings_btn_click(this.name)" />
					<span class="spinner" style="margin-top:9px"></span>
				</div>
			</div>
		</form>
	</div>
</div>
