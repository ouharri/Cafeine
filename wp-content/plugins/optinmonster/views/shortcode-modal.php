<div id="optin-monster-modal-backdrop" class="optin-monster-modal-inline" style="display: none"></div>
<div id="optin-monster-modal-wrap" class="optin-monster-modal-inline" style="display: none">
	<form id="optin-monster-modal" tabindex="-1">
		<div id="optin-monster-modal-title">
			<span class="optin-monster-modal-inline-item"><?php esc_html_e( 'Insert OptinMonster Campaign', 'optin-monster-api' ); ?></span>
			<span class="optin-monster-modal-monsterlink-item"><?php esc_html_e( 'Insert/Edit Link to an OptinMonster Campaign', 'optin-monster-api' ); ?></span>
			<button type="button" id="optin-monster-modal-close"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'optin-monster-api' ); ?></span></button>
		</div>
		<div id="optin-monster-modal-inner">
			<div id="optin-monster-modal-options">
				<div class="optin-monster-modal-inline-item">
					<?php $this->output_view( 'inline-campaign-shortcode-modal.php', $data ); ?>
				</div>
				<div class="optin-monster-modal-monsterlink-item">
					<?php $this->output_view( 'monsterlink-campaign-shortcode-modal.php', $data ); ?>
				</div>
			</div>
		</div>
		<div class="submitbox">
			<div id="optin-monster-modal-cancel">
				<a class="submitdelete deletion" href="#"><?php esc_html_e( 'Cancel', 'optin-monster-api' ); ?></a>
			</div>
			<?php if ( ! empty( $data['campaigns']['inline'] ) || ! empty( $data['campaigns']['other'] ) ) : ?>
				<div id="optin-monster-modal-update">
					<?php if ( ! empty( $data['canMonsterlink'] ) ) : ?>
						<button class="button button-primary optin-monster-modal-monsterlink-item" id="optin-monster-modal-submit"><?php esc_html_e( 'Link Campaign', 'optin-monster-api' ); ?></button>
					<?php else : ?>
						<a class="button button-primary optin-monster-modal-monsterlink-item" href="<?php echo esc_url( $data['upgradeUri'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Upgrade Now', 'optin-monster-api' ); ?></a>
					<?php endif; ?>
					<button class="button button-primary optin-monster-modal-inline-item" id="optin-monster-modal-submit-inline"><?php esc_html_e( 'Add Campaign', 'optin-monster-api' ); ?></button>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>
