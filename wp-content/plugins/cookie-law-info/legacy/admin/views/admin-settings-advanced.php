<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cookie-law-info-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	<h3><?php echo esc_html__( 'Advanced', 'cookie-law-info' ); ?></h3>
	<p><?php echo esc_html__( 'Sometimes themes apply settings that clash with plugins. If that happens, try adjusting these settings.', 'cookie-law-info' ); ?></p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php echo esc_html__( 'Reset settings', 'cookie-law-info' ); ?></th>
			<td>
				<input type="submit" name="delete_all_settings" value="<?php echo esc_html__( 'Delete settings and reset', 'cookie-law-info' ); ?>" class="button-secondary" onclick="cli_store_settings_btn_click(this.name); if(confirm('<?php echo esc_html__( 'Are you sure you want to delete all your settings and switch to the new interface?', 'cookie-law-info' ); ?>')){  }else{ return false;};" />
				<span class="cli_form_help"><?php echo esc_html__( 'Warning: Resets all your current settings to default. This action will switch you to the new and improved user interface and this action is not reversible.', 'cookie-law-info' ); ?></span>
			</td>
		</tr>
	</table>
	<?php do_action( 'wt_cli_after_advanced_settings' ); ?>
	<?php
		require 'admin-settings-save-button.php';
	?>
</div>
