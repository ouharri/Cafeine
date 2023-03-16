<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div style="clear: both;"></div>
<div class="wt-cli-footer">
	<div class="wt-cli-row">
		<div class="wt-cli-col-6"></div>
		<div class="wt-cli-col-6"><input type="submit" name="update_admin_settings_form" value="<?php echo esc_html__( 'Update Settings', 'cookie-law-info' ); ?>" class="button-primary" style="float:right;" onClick="return cli_store_settings_btn_click(this.name)" />
			<span class="spinner" style="margin-top:10px"></span></div>
	</div>
</div>
