<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cookie-law-info-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	<ul class="cli_sub_tab">
		<li style="border-left:none; padding-left: 0px;" data-target="cookie-message"><a><?php echo esc_html__( 'Cookie bar', 'cookie-law-info' ); ?></a></li>
		<li data-target="show-again-tab"><a><?php echo esc_html__( 'Revisit consent', 'cookie-law-info' ); ?></a></li>
	</ul>
	<div class="cli_sub_tab_container">
		<div class="cli_sub_tab_content" data-id="cookie-message" style="display:block;">
			<div class="wt-cli-section wt-cli-section-general-settings">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="bar_heading_text_field"><?php echo esc_html__( 'Message Heading', 'cookie-law-info' ); ?></label></th>
						<td>
							<input type="text" name="bar_heading_text_field" value="<?php echo esc_attr( stripslashes( $the_options['bar_heading_text'] ) ); ?>" />
							<span class="cli_form_help"><?php echo esc_html__( 'Input text to have a heading for the cookie consent bar. Leave it blank if you do not need one.', 'cookie-law-info' ); ?>
							</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="notify_message_field"><?php echo esc_html__( 'Message', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php
							echo '<textarea name="notify_message_field" class="vvv_textbox">';
							echo wp_kses_post( apply_filters( 'format_to_edit', stripslashes( $the_options['notify_message'] ) ) ) . '</textarea>';
							?>
							<span class="cli_form_help"><?php echo esc_html__( 'Modify/edit the content of the cookie consent bar.', 'cookie-law-info' ); ?> <br /><em><?php echo esc_html__( 'Supports shortcodes.(link shortcodes to help link) e.g. [cookie_accept_all] for accept all button, [cookie_button] for accept button, [cookie_reject] for reject button, [cookie_link] for Read more, [cookie_settings] for cookie settings.', 'cookie-law-info' ); ?></em></span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="background_field"><?php echo esc_html__( 'Cookie Bar Colour', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php
							/** RICHARDASHBY EDIT */
							// echo '<input type="text" name="background_field" id="cli-colour-background" value="' .$the_options['background']. '" />';
							echo '<input type="text" name="background_field" id="cli-colour-background" value="' . esc_attr( $the_options['background'] ) . '" class="my-color-field" data-default-color="#fff" />';
							?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="text_field"><?php echo esc_html__( 'Text Colour', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php
							/** RICHARDASHBY EDIT */
							echo '<input type="text" name="text_field" id="cli-colour-text" value="' . esc_attr( $the_options['text'] ) . '" class="my-color-field" data-default-color="#000" />';
							?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="font_family_field"><?php echo esc_html__( 'Font', 'cookie-law-info' ); ?></label></th>
						<td>
							<select name="font_family_field" class="vvv_combobox">
								<?php $this->print_combobox_options( $this->get_fonts(), $the_options['font_family'] ); ?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="cookie_bar_as_field"><?php echo esc_html__( 'Show cookie bar as', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php
							$cookie_bar_as = $the_options['cookie_bar_as'];
							?>
							<input type="radio" id="cookie_bar_as_field_banner" name="cookie_bar_as_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_bar_type" value="banner" <?php checked( $cookie_bar_as, 'banner' ); ?> /> <?php echo esc_html__( 'Banner', 'cookie-law-info' ); ?>
							<input type="radio" id="cookie_bar_as_field_popup" name="cookie_bar_as_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_bar_type" value="popup" <?php checked( $cookie_bar_as, 'popup' ); ?> /> <?php echo esc_html__( 'Popup', 'cookie-law-info' ); ?>
							<input type="radio" id="cookie_bar_as_field_widget" name="cookie_bar_as_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_bar_type" value="widget" <?php checked( $cookie_bar_as, 'widget' ); ?> /> <?php echo esc_html__( 'Widget', 'cookie-law-info' ); ?>
						</td>
					</tr>
					<tr valign="top" cli_frm_tgl-id="cli_bar_type" cli_frm_tgl-val="widget">
						<th scope="row"><label for="widget_position_field"><?php echo esc_html__( 'Position', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php $widget_position = $the_options['widget_position']; ?>
							<select name="widget_position_field" id="widget_position_field" class="vvv_combobox">
								<option value="left" <?php echo $widget_position == 'left' ? 'selected' : ''; ?>><?php echo esc_html__( 'Left', 'cookie-law-info' ); ?></option>
								<option value="right" <?php echo $widget_position == 'right' ? 'selected' : ''; ?>><?php echo esc_html__( 'Right', 'cookie-law-info' ); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top" cli_frm_tgl-id="cli_bar_type" cli_frm_tgl-val="popup">
						<th scope="row"><label for="popup_overlay_field"><?php echo esc_html__( 'Add overlay?', 'cookie-law-info' ); ?></label></th>
						<td>
							<input type="radio" id="popup_overlay_field_yes" name="popup_overlay_field" class="styled" value="true" <?php echo ( $the_options['popup_overlay'] == true ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Yes', 'cookie-law-info' ); ?>
							<input type="radio" id="popup_overlay_field_no" name="popup_overlay_field" class="styled" value="false" <?php echo ( $the_options['popup_overlay'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'No', 'cookie-law-info' ); ?>
							<span class="cli_form_help"><?php echo esc_html__( 'When the popup is active, an overlay will block the user from browsing the site.', 'cookie-law-info' ); ?></span>
							<span class="cli_form_er cli_scroll_accept_er"><?php echo esc_html__( '`Accept on scroll` will not work along with this option.', 'cookie-law-info' ); ?></span>
						</td>
					</tr>
					<tr valign="top" cli_frm_tgl-id="cli_bar_type" cli_frm_tgl-val="banner" cli_frm_tgl-lvl="1">
						<th scope="row"><label for="notify_position_vertical_field"><?php echo esc_html__( 'Position:', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php
							$notify_positon = ( isset( $the_options['notify_position_vertical'] ) ? $the_options['notify_position_vertical'] : 'bottom' );
							?>
							<input type="radio" id="notify_position_vertical_field_top" name="notify_position_vertical_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_bar_pos" value="top" <?php checked( $notify_positon, 'top' ); ?> /> <?php echo esc_html__( 'Header', 'cookie-law-info' ); ?>
							<input type="radio" id="notify_position_vertical_field_bottom" name="notify_position_vertical_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_bar_pos" value="bottom" <?php checked( $notify_positon, 'bottom' ); ?> /> <?php echo esc_html__( 'Footer', 'cookie-law-info' ); ?>
						</td>
					</tr>
					<!-- header_fix code here -->
					<tr valign="top" cli_frm_tgl-id="cli_bar_type" cli_frm_tgl-val="banner" cli_frm_tgl-lvl="1">
						<td colspan="2" style="padding: 0px;">
							<table>
								<tr valign="top" cli_frm_tgl-id="cli_bar_pos" cli_frm_tgl-val="top" cli_frm_tgl-lvl="2">
								<th></th>
									<td>
										<div style="margin-top:-15px; margin-bottom:15px;">
											<input type="radio" id="header_fix_field_yes" name="header_fix_field" class="styled" value="true" <?php echo ( $the_options['header_fix'] == true ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Fix bar on header', 'cookie-law-info' ); ?>
										</div>
										<div>
											<input type="radio" id="iheader_fix_field_no" name="header_fix_field" class="styled" value="false" <?php echo ( $the_options['header_fix'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Move with the scroll', 'cookie-law-info' ); ?>
										</div>
										
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><label for="notify_animate_show_field"><?php echo esc_html__( 'On load', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php
							$notify_animate_show = ( isset( $the_options['notify_animate_show'] ) ? $the_options['notify_animate_show'] : false );
							?>
							<input type="radio" id="notify_animate_show_field_animate" name="notify_animate_show_field" class="styled" value="true" <?php checked( $notify_animate_show, true ); ?> /> <?php echo esc_html__( 'Animate', 'cookie-law-info' ); ?>
							<input type="radio" id="notify_animate_show_field_sticky" name="notify_animate_show_field" class="styled" value="false" <?php checked( $notify_animate_show, false ); ?> /> <?php echo esc_html__( 'Sticky', 'cookie-law-info' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="notify_animate_hide_field"><?php echo esc_html__( 'On hide', 'cookie-law-info' ); ?></label></th>
						<td>
							<?php
							$notify_animate_hide = ( isset( $the_options['notify_animate_hide'] ) ? $the_options['notify_animate_hide'] : true );
							?>
							<input type="radio" id="notify_animate_hide_field_animate" name="notify_animate_hide_field" class="styled" value="true" <?php checked( $notify_animate_hide, true ); ?> /> <?php echo esc_html__( 'Animate', 'cookie-law-info' ); ?>
							<input type="radio" id="notify_animate_hide_field_sticky" name="notify_animate_hide_field" class="styled" value="false" <?php checked( $notify_animate_hide, false ); ?> /> <?php echo esc_html__( 'Sticky', 'cookie-law-info' ); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="cli_sub_tab_content" data-id="show-again-tab">
			<div class="wt-cli-section wt-cli-section-floating-widget-settings">
				<div class="cli_sub_tab_content" data-id="show-again-tab">
					<div class="wt-cli-notice wt-cli-info">
					<?php
					echo wp_kses(
						__( 'Revisit consent will allow the visitors to view/edit/revoke their prior preferences. Enable to display a sticky/fixed widget widget at the footer of your website. You can also manually insert a widget by adding the shortcode <strong>[wt_cli_manage_consent]</strong> to your website.', 'cookie-law-info' ),
						array(
							'p'      => array(),
							'strong' => array(),
						)
					);
					?>
					</div>
					<div class="wt-cli-revisit-consent-widget">
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><label for="showagain_tab_field"><?php echo esc_html__( 'Enable revisit consent widget', 'cookie-law-info' ); ?><span class="wt-cli-tootip" data-wt-cli-tooltip="<?php echo esc_html__( 'Enable to display a sticky/fixed widget at the footer of your website (remains fixed on page scroll).', 'cookie-law-info' ); ?>"><span class="wt-cli-tootip-icon"></span></span></label></th>
								<td>
									<input type="hidden" name="showagain_tab_field" value="false" id="showagain_tab_field_no">
									<input name="showagain_tab_field" type="checkbox" value="true" id="showagain_tab_field_yes" class="wt-cli-input-toggle-checkbox" data-cli-toggle-target="wt-cli-revisit-consent-widget" <?php checked( $the_options['showagain_tab'], true ); ?>>
								</td>
							</tr>
						</table>
					</div>
					<div class="wt-cli-input-toggle-section" data-cli-toggle-id="wt-cli-revisit-consent-widget">
						<table class="form-table">
							<tr valign="top" cli_frm_tgl-id="cli_bar_type" cli_frm_tgl-val="banner" cli_frm_tgl-lvl="0">
								<th scope="row"><label for="notify_position_horizontal_field"><?php echo esc_html__( 'Widget position', 'cookie-law-info' ); ?></label></th>
								<td>
									<input type="radio" id="notify_position_horizontal_field_right" name="notify_position_horizontal_field" class="styled" value="right" <?php checked( $the_options['notify_position_horizontal'], 'right' ); ?> /> <?php echo esc_html__( 'Right', 'cookie-law-info' ); ?>
									<input type="radio" id="notify_position_horizontal_field_left" name="notify_position_horizontal_field" class="styled"  value="left" <?php checked( $the_options['notify_position_horizontal'], 'left' ); ?> /> <?php echo esc_html__( 'Left', 'cookie-law-info' ); ?>
								</td>
							</tr>

							<tr valign="top" cli_frm_tgl-id="cli_bar_type" cli_frm_tgl-val="popup" cli_frm_tgl-lvl="0">
								<th scope="row"><label for="popup_showagain_position_field"><?php echo esc_html__( 'Tab Position', 'cookie-law-info' ); ?></label></th>
								<td>
									<select name="popup_showagain_position_field" class="vvv_combobox" style="max-width:100%;">
										<?php
										$pp_sa_pos = $the_options['popup_showagain_position'];
										?>
										<option value="bottom-right" <?php echo $pp_sa_pos == 'bottom-right' ? 'selected' : ''; ?>>
											<?php echo esc_html__( 'Bottom Right', 'cookie-law-info' ); ?>
										</option>
										<option value="bottom-left" <?php echo $pp_sa_pos == 'bottom-left' ? 'selected' : ''; ?>>
											<?php echo esc_html__( 'Bottom Left', 'cookie-law-info' ); ?>
										</option>
										<option value="top-right" <?php echo $pp_sa_pos == 'top-right' ? 'selected' : ''; ?>>
											<?php echo esc_html__( 'Top Right', 'cookie-law-info' ); ?>
										</option>
										<option value="top-left" <?php echo $pp_sa_pos == 'top-left' ? 'selected' : ''; ?>>
											<?php echo esc_html__( 'Top Left', 'cookie-law-info' ); ?>
										</option>
									</select>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><label id="wt-cli-revisit-consent-margin-label" for="showagain_x_position_field" data-cli-right-text="<?php echo esc_html__( 'From Right Margin', 'cookie-law-info' ); ?>" data-cli-left-text="<?php echo esc_html__( 'From Left Margin', 'cookie-law-info' ); ?>"><?php echo esc_html__( 'From Left Margin', 'cookie-law-info' ); ?></label></th>
								<td>
									<input type="text" name="showagain_x_position_field" value="<?php echo esc_attr( $the_options['showagain_x_position'] ); ?>" />
									<span class="cli_form_help"><?php echo esc_html__( 'Specify the widget distance from margin in ‘px’ or  ‘%’ . e.g. 100px or 30%', 'cookie-law-info' ); ?></span>
								</td>
							</tr>

						</table>

					</div>
					<table class="form-table" style="margin-top: 0;">
						<tr valign="top">
							<th scope="row"><label for="showagain_text"><?php echo esc_html__( 'Text on the widget', 'cookie-law-info' ); ?></label></th>
							<td>
								<input type="text" name="showagain_text_field" value="<?php echo esc_attr( $the_options['showagain_text'] ); ?>" />
								<span class="cli_form_help"><?php echo esc_html__( 'Input a text to appear on the revisit consent widget.', 'cookie-law-info' ); ?></span>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>


	<?php
	require 'admin-settings-save-button.php';
	?>
</div>
