<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cli_icon              = ( $script_blocker_status === true ? '<span class="dashicons dashicons-yes cli-enabled ">' : '<span class="dashicons dashicons-no-alt cli-disabled"></span>' );
$action_text           = ( $script_blocker_status === true ? __( 'Disable', 'cookie-law-info' ) : __( 'Enable', 'cookie-law-info' ) );
$action_value          = ( $script_blocker_status === true ? 'disabled' : 'enabled' );
$script_blocker_text   = ( $script_blocker_status === true ? __( 'Script blocker is enabled.', 'cookie-law-info' ) : __( 'Script blocker is currently disabled. Enable the blocker if you want any of the below listed plugins to be auto blocked.', 'cookie-law-info' ) );
$cli_notice_text       = sprintf( __( '<a id="wt-cli-script-blocker-action">%s</a>', 'cookie-law-info' ), $action_text );
$js_blocking_status    = ( $js_blocking === false || $js_blocking === 'no' ) ? false : true;
$script_blocker_class  = ( $js_blocking_status === false || $script_blocker_status === false ) ? 'wt-cli-script-blocker-disabled' : '';
$advanced_settings_url = get_admin_url( null, 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info#cookie-law-info-advanced' );
$js_blocking_notice    = sprintf( wp_kses( __( 'Advanced script rendering is currently disabled. It should be enabled for the automatic script blocker to function. <a href="%s">Enable.</a>', 'cookie-law-info' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $advanced_settings_url ) );
$count                 = 0;
$plugin_help_url       = 'https://www.webtoffee.com/gdpr-cookie-consent-plugin-basic-version-user-guide/#Script_Blo_21';
?>
<style>
	#wt-cli-script-blocker-action {
		cursor: pointer;
	}
	table.cli_script_items td,
	table.cli_script_items th {
		display: table-cell !important;
		padding: 1em !important;
		vertical-align: top;
		line-height: 1.75em;
	}

	.cli-switch {
		display: inline-block;
		position: relative;
		min-height: 20px;
		padding-left: 38px;
		font-size: 14px;

	}

	.cli-switch input[type="checkbox"] {
		display: none;
	}

	.cli-switch .cli-slider {
		background-color: #e3e1e8;
		height: 20px;
		width: 38px;
		bottom: 0;
		cursor: pointer;
		left: 0;
		position: absolute;
		right: 0;
		top: 0;
		transition: .4s;
	}

	.cli-switch .cli-slider:before {
		background-color: #fff;
		bottom: 2px;
		content: "";
		height: 15px;
		left: 3px;
		position: absolute;
		transition: .4s;
		width: 15px;
	}

	.cli-switch input:checked+.cli-slider {
		background-color: #28a745;
	}

	.cli-switch input:checked+.cli-slider:before {
		transform: translateX(18px);
	}

	.cli-switch .cli-slider {
		border-radius: 34px;
		font-size: 0;
	}

	.cli-switch .cli-slider:before {
		border-radius: 50%;
	}

	.dashicons.cli-enabled {
		color: #46b450;
	}

	.dashicons.cli-disabled {
		color: #dc3232;
	}

	.wt-cli-script-blocker-disabled,
	.wt-cli-plugin-inactive .cli-switch {
		opacity: 0.5;
	}

	.cli_script_items [data-wt-cli-tooltip]:before {
		min-width: 220px;
	}

	.wt-cli-notice.wt-cli-info {
		padding: 15px 15px 15px 41px;
		background: #e5f5fa;
		position: relative;
		border-left: 4px solid;
		border-color: #00a0d2;
		margin-bottom: 15px;
	}

	.wt-cli-notice.wt-cli-info:before {
		content: "\f348";
		color: #00a0d2;
		font-family: "dashicons";
		position: absolute;
		left: 15px;
		font-size: 16px;
	}
</style>
<div class="wrap cliscript-container">
	<h3><?php echo esc_html__( 'Manage Script Blocking', 'cookie-law-info' ); ?></h3>
	<?php if ( $js_blocking_status === false ) : ?>
		<div class="notice-warning notice">
			<p><label><span class="dashicons dashicons-no-alt cli-disabled"></span></label>
				<?php echo wp_kses_post( $js_blocking_notice ); ?>
		</div>
	<?php endif; ?>
	<div class="notice-info notice">
		<p><label><?php echo wp_kses_post( $cli_icon ); ?></label>
			<?php echo esc_html( $script_blocker_text ); ?> <?php echo wp_kses_post( $cli_notice_text ); ?></p>
	</div>
	<form method="post" name="script_blocker_form">
		<?php
		if ( function_exists( 'wp_nonce_field' ) ) {
			wp_nonce_field( $this->module_id );
		}
		?>
		<input type="hidden" id="cli_script_blocker_state" name="cli_script_blocker_state" class="styled" value="<?php echo esc_attr( $action_value ); ?>" />
		<input type="hidden" id="cli_update_script_blocker" name="cli_update_script_blocker" />
	</form>
	<div class="wt-cli-notice wt-cli-info">
		<?php
		echo sprintf(
			wp_kses(
				__( 'Below is the list of plugins currently supported for auto blocking. Plugins marked inactive are either not installed or activated on your website. Enabled plugins will be blocked by default on the front-end of your website prior to obtaining user consent and rendered respectively based on consent. <a href="%s" target="_blank">Read more.</a>', 'cookie-law-info' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_url( $plugin_help_url )
		);
		?>
	</div>
	<table class="cli_script_items widefat <?php echo esc_attr( $script_blocker_class ); ?>" cellspacing="0">
		<thead>
			<tr>
				<th><?php echo esc_html__( 'No', 'cookie-law-info' ); ?></th>
				<th><?php echo esc_html__( 'Name', 'cookie-law-info' ); ?></th>
				<th><?php echo esc_html__( 'Enabled', 'cookie-law-info' ); ?><span class="wt-cli-tootip" data-wt-cli-tooltip="<?php echo esc_html__( 'Enabled: Plugins will be blocked by default prior to obtaining user consent.', 'cookie-law-info' ); ?> <?php echo esc_html__( 'Disabled: Plugins will be rendered prior to obtaining consent.', 'cookie-law-info' ); ?>"><span class="wt-cli-tootip-icon"></span></span></th>
				<?php if ( Cookie_Law_Info_Cookies::get_instance()->check_if_old_category_table() === false ) : ?>
					<th><?php echo esc_html__( 'Category', 'cookie-law-info' ); ?></th>
				<?php endif; ?>
				<th><?php echo esc_html__( 'Description', 'cookie-law-info' ); ?></th>
			</tr>
		</thead>
		<tbody>

			<?php
			$disabled_plugins = array();
			$enabled_plugins  = array();
			foreach ( $wt_cli_integration_list as $plugin => $data ) {

				$plugin_data = ( isset( $script_data[ $plugin ] ) ? $script_data[ $plugin ] : '' );
				if ( ! empty( $plugin_data ) ) {
					if ( defined( $data['identifier'] ) || function_exists( $data['identifier'] ) || class_exists( $data['identifier'] ) ) {
						$plugin_data['active']      = true;
						$enabled_plugins[ $plugin ] = $plugin_data;
					} else {
						$plugin_data['active']       = false;
						$disabled_plugins[ $plugin ] = $plugin_data;
					}
				}
			}
			$plugin_list = $enabled_plugins + $disabled_plugins;
			if ( ! empty( $plugin_list ) ) :
				foreach ( $plugin_list as $plugin => $plugin_data ) :

					$count++;
					$script_id            = isset( $plugin_data['id'] ) ? $plugin_data['id'] : '';
					$title                = isset( $plugin_data['title'] ) ? $plugin_data['title'] : '';
					$description          = isset( $plugin_data['description'] ) ? $plugin_data['description'] : '';
					$status               = isset( $plugin_data['status'] ) ? wp_validate_boolean( $plugin_data['status'] ) : false;
					$plugin_status        = isset( $plugin_data['active'] ) ? wp_validate_boolean( $plugin_data['active'] ) : false;
					$category             = __( 'Non-necessary', 'cookie-law-info' );
					$plugins_status_text  = ( $plugin_status === false ? __( 'Inactive', 'cookie-law-info' ) : '' );
					$plugins_status_class = ( $plugin_status === false ? 'wt-cli-plugin-inactive' : 'wt-cli-plugin-active' );
					?>
					<tr class="<?php echo esc_attr( $plugins_status_class ); ?>" data-script-id="<?php echo esc_attr( $script_id ); ?>">
						<td><?php echo esc_html( $count ); ?></td>
						<td><?php echo esc_html( $title ); ?>
							<?php if ( ! empty( $plugins_status_text ) ) : ?>
								<span style="color:#dc3232; margin-left:3px;">( <?php echo esc_html( $plugins_status_text ); ?> )</span>
							<?php endif; ?>
						</td>
						<td>
							<div class="cli-switch">
								<input type="checkbox" id="wt-cli-checkbox-<?php echo esc_attr( $plugin ); ?>" data-script-id="<?php echo esc_attr( $script_id ); ?>" class="wt-cli-plugin-status" <?php checked( wp_validate_boolean( $status ), true ); ?> />
								<label for="wt-cli-checkbox-<?php echo esc_attr( $plugin ); ?>" class="cli-slider"></label>
							</div>
						</td>
						<?php if ( Cookie_Law_Info_Cookies::get_instance()->check_if_old_category_table() === false ) : ?>
						
						<td> 
							<select name="cliscript_category" id="cliscript_category">
								<option value="0">--Select Category--</option>
								<?php foreach ( $terms as $key => $term ) : ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $plugin_data['category'], $key, false ); ?>><?php echo esc_html( $term['title'] ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<?php endif; ?>
						<td><?php echo esc_html( $description ); ?></td>
					</tr>
					<?php
			endforeach;
			endif;
			?>
		</tbody>
	</table>
</div>
<script>
	let item = document.getElementById('wt-cli-script-blocker-action');
	item && item.addEventListener('click', function(event) {
		event.preventDefault();
		document.script_blocker_form.submit();
	});
</script>