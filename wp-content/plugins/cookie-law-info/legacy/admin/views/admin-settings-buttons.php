<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cookie-law-info-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	
	<ul class="cli_sub_tab">
		<li style="border-left:none; padding-left: 0px;" data-target="accept-all-button"><a><?php echo esc_html__( 'Accept All Button', 'cookie-law-info' ); ?></a></li>
		<li data-target="accept-button"><a><?php echo esc_html__( 'Accept Button', 'cookie-law-info' ); ?></a></li>
		<li data-target="reject-button"><a><?php echo esc_html__( 'Reject Button', 'cookie-law-info' ); ?></a></li>
		<li data-target="settings-button"><a><?php echo esc_html__( 'Settings Button', 'cookie-law-info' ); ?></a></li>
		<li data-target="read-more-button"><a><?php echo esc_html__( 'Read more', 'cookie-law-info' ); ?></a></li>
		<li data-target="do-not-sell-button" class="wt-cli-ccpa-element"><a><?php echo esc_html__( 'Do not sell', 'cookie-law-info' ); ?></a></li>
	</ul>

	<div class="cli_sub_tab_container">

		<div class="cli_sub_tab_content" data-id="accept-button" style="display:block;">
			<h3><?php echo esc_html__( 'Accept Button', 'cookie-law-info' ); ?> <code>[cookie_button]</code></h3>
			<p><?php echo esc_html__( 'Customize the Accept button to match the theme of your site. Insert the shortcode [cookie_button] in Customise Cookie Bar > Cookie bar > Message to include accept button in cookie consent bar.', 'cookie-law-info' ); ?></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_1_text_field"><?php echo esc_html__( 'Text', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_1_text_field" value="<?php echo esc_attr( stripslashes( $the_options['button_1_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_1_link_colour_field"><?php echo esc_html__( 'Text colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
							echo '<input type="text" name="button_1_link_colour_field" id="cli-colour-link-button-1" value="' . esc_attr( $the_options['button_1_link_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_1_as_button_field"><?php echo esc_html__( 'Show as', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" cli_frm_tgl-target="cli_accept_type" id="button_1_as_button_field_yes" name="button_1_as_button_field" class="styled cli_form_toggle" value="true" <?php echo ( $the_options['button_1_as_button'] == true ) ? ' checked="checked"' : ' '; ?> /> <?php echo esc_html__( 'Button', 'cookie-law-info' ); ?>
						
						<input type="radio" cli_frm_tgl-target="cli_accept_type" id="button_1_as_button_field_no" name="button_1_as_button_field" class="styled cli_form_toggle" value="false" <?php echo ( $the_options['button_1_as_button'] == false ) ? ' checked="checked"' : ''; ?>  /> <?php echo esc_html__( 'Link', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top" class="cli-indent-15" cli_frm_tgl-id="cli_accept_type" cli_frm_tgl-val="true">
					<th scope="row"><label for="button_1_button_colour_field"><?php echo esc_html__( 'Background colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
						echo '<input type="text" name="button_1_button_colour_field" id="cli-colour-btn-button-1" value="' . esc_attr( $the_options['button_1_button_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="button_1_action_field"><?php echo esc_html__( 'Action', 'cookie-law-info' ); ?></label></th>
					<td>
						<select name="button_1_action_field" id="cli-plugin-button-1-action" class="vvv_combobox cli_form_toggle" cli_frm_tgl-target="cli_accept_action">
							<?php $this->print_combobox_options( $this->get_js_actions(), $the_options['button_1_action'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="cli-plugin-row cli-indent-15" cli_frm_tgl-id="cli_accept_action" cli_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_1_url_field"><?php echo esc_html__( 'URL', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_1_url_field" id="button_1_url_field" value="<?php echo esc_attr( $the_options['button_1_url'] ); ?>" />
						<span class="cli_form_help"><?php echo esc_html__( 'Specify the URL to redirect users on accept button click. e.g. Entering the cookie policy page URL will redirect users to the cookie policy page after giving consent.', 'cookie-law-info' ); ?></span>
					</td>
				</tr>

				<tr valign="top" class="cli-plugin-row cli-indent-15" cli_frm_tgl-id="cli_accept_action" cli_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_1_new_win_field"><?php echo esc_html__( 'Open URL in new window', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_1_new_win_field_yes" name="button_1_new_win_field" class="styled" value="true" <?php echo ( $the_options['button_1_new_win'] == true ) ? ' checked="checked"' : ''; ?> /><?php echo esc_html__( 'Yes', 'cookie-law-info' ); ?>
						
						<input type="radio" id="button_1_new_win_field_no" name="button_1_new_win_field" class="styled" value="false" <?php echo ( $the_options['button_1_new_win'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'No', 'cookie-law-info' ); ?>
					</td>
				</tr>
				
				
				
				<tr valign="top">
					<th scope="row"><label for="button_1_button_size_field"><?php echo esc_html__( 'Button Size', 'cookie-law-info' ); ?></label></th>
					<td>
						<select name="button_1_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_1_button_size'] ); ?>
						</select>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>

		<div class="cli_sub_tab_content" data-id="reject-button">
			<h3><?php echo esc_html__( 'Reject Button', 'cookie-law-info' ); ?> <code>[cookie_reject]</code></h3>
			
			<p>
			<?php
			echo wp_kses(
				__( 'Customize the Reject button to match the theme of your site. Insert the shortcode <strong>[cookie_reject]</strong> in <strong>Customise Cookie Bar > Cookie bar > Message</strong> to include reject button in cookie consent bar.', 'cookie-law-info' ),
				array(
					'p'      => array(),
					'strong' => array(),
				)
			);
			?>
			</p>
			<table class="form-table" >
				<tr valign="top">
					<th scope="row"><label for="button_3_text_field"><?php echo esc_html__( 'Text', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_3_text_field" value="<?php echo esc_attr( stripslashes( $the_options['button_3_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_3_link_colour_field"><?php echo esc_html__( 'Text colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
							echo '<input type="text" name="button_3_link_colour_field" id="cli-colour-link-button-3" value="' . esc_attr( $the_options['button_3_link_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>               
				<tr valign="top">
					<th scope="row"><label for="button_3_as_button_field"><?php echo esc_html__( 'Show as', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_3_as_button_field_yes" name="button_3_as_button_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_reject_type" value="true" <?php echo ( $the_options['button_3_as_button'] == true ) ? ' checked="checked"' : ' '; ?>  /> <?php echo esc_html__( 'Button', 'cookie-law-info' ); ?>
						
						<input type="radio" id="button_3_as_button_field_no" name="button_3_as_button_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_reject_type" value="false" <?php echo ( $the_options['button_3_as_button'] == false ) ? ' checked="checked"' : ''; ?> /><?php echo esc_html__( 'Link', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top" cli_frm_tgl-id="cli_reject_type" cli_frm_tgl-val="true">
					<th scope="row"><label for="button_3_button_colour_field"><?php echo esc_html__( 'Background colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
							echo '<input type="text" name="button_3_button_colour_field" id="cli-colour-btn-button-3" value="' . esc_attr( $the_options['button_3_button_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_3_action_field"><?php echo esc_html__( 'Action', 'cookie-law-info' ); ?></label></th>
					<td>
						<select name="button_3_action_field" id="cli-plugin-button-3-action" class="vvv_combobox cli_form_toggle" cli_frm_tgl-target="cli_reject_action">
							<?php
								$action_list                          = $this->get_js_actions();
								$action_list['close_header']['value'] = '#cookie_action_close_header_reject';
								$this->print_combobox_options( $action_list, $the_options['button_3_action'] );
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="cli-plugin-row" cli_frm_tgl-id="cli_reject_action" cli_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_3_url_field"><?php echo esc_html__( 'URL', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_3_url_field" id="button_3_url_field" value="<?php echo esc_url( $the_options['button_3_url'] ); ?>" />
						<span class="cli_form_help"><?php echo esc_html__( 'Specify the URL to redirect users on reject button click. e.g. Entering the cookie policy page URL will redirect users to the cookie policy page after rejecting cookies.', 'cookie-law-info' ); ?></span>
					</td>
				</tr>

				<tr valign="top" class="cli-plugin-row" cli_frm_tgl-id="cli_reject_action" cli_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_3_new_win_field"><?php echo esc_html__( 'Open URL in new window', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_3_new_win_field_yes" name="button_3_new_win_field" class="styled" value="true" <?php echo ( $the_options['button_3_new_win'] == true ) ? ' checked="checked"' : ''; ?>  /><?php echo esc_html__( 'Yes', 'cookie-law-info' ); ?>
						<input type="radio" id="button_3_new_win_field_no" name="button_3_new_win_field" class="styled" value="false" <?php echo ( $the_options['button_3_new_win'] == false ) ? ' checked="checked"' : ''; ?> /><?php echo esc_html__( 'No', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_3_button_size_field"><?php echo esc_html__( 'Button Size', 'cookie-law-info' ); ?></label></th>
					<td>
						<select name="button_3_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_3_button_size'] ); ?>
						</select>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="cli_sub_tab_content" data-id="settings-button">
			<h3><?php echo esc_html__( 'Settings Button', 'cookie-law-info' ); ?> <code>[cookie_settings]</code></h3>
			<p>
			<?php
			echo wp_kses(
				__( 'Customize the cookie settings to match the theme of your site. Insert the shortcode <strong>[cookie_settings]</strong> in <strong>Customise Cookie Bar > Cookie bar > Message</strong> to include cookie settings within the cookie consent bar. Clicking ‘Cookie settings’ opens up a pop up window with provisions to enable/disable cookie categories.', 'cookie-law-info' ),
				array(
					'p'      => array(),
					'strong' => array(),
				)
			);
			?>
			</p>
			<table class="form-table" >
				<tr valign="top">
					<th scope="row"><label for="button_4_text_field"><?php echo esc_html__( 'Text', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_4_text_field" value="<?php echo esc_attr( stripslashes( $the_options['button_4_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_4_link_colour_field"><?php echo esc_html__( 'Text colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
							echo '<input type="text" name="button_4_link_colour_field" id="cli-colour-link-button-4" value="' . esc_attr( $the_options['button_4_link_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_4_as_button_field"><?php echo esc_html__( 'Show as', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_4_as_button_field_yes" name="button_4_as_button_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_settings_type" value="true" <?php echo ( $the_options['button_4_as_button'] == true ) ? ' checked="checked"' : ' '; ?> /><?php echo esc_html__( 'Button', 'cookie-law-info' ); ?>
							   
						<input type="radio" id="button_4_as_button_field_no" name="button_4_as_button_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_settings_type" value="false" <?php echo ( $the_options['button_4_as_button'] == false ) ? ' checked="checked"' : ''; ?> /><?php echo esc_html__( 'Link', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top" cli_frm_tgl-id="cli_settings_type" cli_frm_tgl-val="true">
					<th scope="row"><label for="button_4_button_colour_field"><?php echo esc_html__( 'Background colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
							echo '<input type="text" name="button_4_button_colour_field" id="cli-colour-btn-button-4" value="' . esc_attr( $the_options['button_4_button_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="cli_sub_tab_content" data-id="read-more-button">
			<h3><?php echo esc_html__( 'Read more', 'cookie-law-info' ); ?> <code>[cookie_link]</code></h3>
			<p>
				<?php echo esc_html__( '‘Read more’ redirects users to the ‘Privacy & Cookie Policy’ page. Create a ‘Privacy & Cookie Policy’ page for your site from here.', 'cookie-law-info' ); ?>
				<?php
				echo wp_kses(
					__( 'Insert the shortcode <strong>[cookie_link]</strong> in <strong>Customise Cookie Bar > Cookie bar > Message</strong> to include ‘Read more’ within the cookie consent bar.', 'cookie-law-info' ),
					array(
						'p'      => array(),
						'strong' => array(),
					)
				);
				?>
			</p>
			<?php
			if ( Cookie_Law_Info_Admin::module_exists( 'cli-policy-generator' ) ) {
				?>
			<p><?php echo esc_html__( 'Click', 'cookie-law-info' ); ?> <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info-policy-generator' ) ); ?>"><?php echo esc_html__( 'here', 'cookie-law-info' ); ?></a> <?php echo esc_html__( ' to generate content for Cookie Policy page.', 'cookie-law-info' ); ?>
			</p>
				<?php
			}
			?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_2_text_field"><?php echo esc_html__( 'Text', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_2_text_field" value="<?php echo esc_attr( stripslashes( $the_options['button_2_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_2_link_colour_field"><?php echo esc_html__( 'Text colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
							echo '<input type="text" name="button_2_link_colour_field" id="cli-colour-link-button-1" value="' . esc_attr( $the_options['button_2_link_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_2_as_button_field"><?php echo esc_html__( 'Show as', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_2_as_button_field_yes" name="button_2_as_button_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_readmore_type" value="true" <?php echo ( $the_options['button_2_as_button'] == true ) ? ' checked="checked"' : ''; ?>  /> <?php echo esc_html__( 'Button', 'cookie-law-info' ); ?>
							   
						<input type="radio" id="button_2_as_button_field_no" name="button_2_as_button_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_readmore_type" value="false" <?php echo ( $the_options['button_2_as_button'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Link', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top" cli_frm_tgl-id="cli_readmore_type" cli_frm_tgl-val="true">
					<th scope="row"><label for="button_2_button_colour_field"><?php echo esc_html__( 'Background colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
							echo '<input type="text" name="button_2_button_colour_field" id="cli-colour-btn-button-1" value="' . esc_attr( $the_options['button_2_button_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><label for="button_2_url_type_field"><?php echo esc_html__( 'URL or Page?', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_2_url_type_field_url" name="button_2_url_type_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_readmore_url_type" value="url" <?php echo ( $the_options['button_2_url_type'] == 'url' ) ? ' checked="checked"' : ''; ?>  /> <?php echo esc_html__( 'URL', 'cookie-law-info' ); ?>
							   
						<input type="radio" id="button_2_url_type_field_page" name="button_2_url_type_field" class="styled cli_form_toggle" cli_frm_tgl-target="cli_readmore_url_type" value="page" <?php echo ( $the_options['button_2_url_type'] == 'page' ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Page', 'cookie-law-info' ); ?>
					</td>
				</tr>

				<tr valign="top" cli_frm_tgl-id="cli_readmore_url_type" cli_frm_tgl-val="url">
					<th scope="row"><label for="button_2_url_field"><?php echo esc_html__( 'URL', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_2_url_field" id="button_2_url_field" value="<?php echo esc_url( $the_options['button_2_url'] ); ?>" />
					</td>
				</tr>
				<tr valign="top" cli_frm_tgl-id="cli_readmore_url_type" cli_frm_tgl-val="page">
					<th scope="row"><label for="button_2_page_field"><?php echo esc_html__( 'Page', 'cookie-law-info' ); ?></label></th>
					<td>
						<select name="button_2_page_field" class="vvv_combobox" id="button_2_page_field">
							<option value="0">--<?php echo esc_html__( 'Select One', 'cookie-law-info' ); ?>--</option>
							<?php
							foreach ( $all_pages as $page ) {
								?>
								<option value="<?php echo esc_attr( $page->ID ); ?>" <?php echo ( $the_options['button_2_page'] == $page->ID ? 'selected' : '' ); ?>> <?php echo esc_html( $page->post_title ); ?> </option>;
								<?php
							}
							?>
						</select>
						<?php
						if ( $the_options['button_2_page'] > 0 ) {
							$privacy_policy_page = get_post( $the_options['button_2_page'] );
							$privacy_page_exists = 0;
							if ( $privacy_policy_page instanceof WP_Post ) {
								if ( $privacy_policy_page->post_status === 'publish' ) {
									$privacy_page_exists = 1;
								}
							}
							if ( $privacy_page_exists == 0 ) {
								?>
								<span class="cli_form_er cli_privacy_page_not_exists_er" style="display: inline;"><?php echo esc_html__( 'The currently selected page does not exist. Please select a new page.' ); ?></span>
								<?php
							}
						}
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_2_new_win_field"><?php echo esc_html__( 'Hide cookie bar in this page/URL', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_2_hidebar_field_yes" name="button_2_hidebar_field" class="styled" value="true" <?php echo ( $the_options['button_2_hidebar'] == true ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Yes', 'cookie-law-info' ); ?>
							   <input type="radio" id="button_2_hidebar_field_no" name="button_2_hidebar_field" class="styled" value="false" <?php echo ( $the_options['button_2_hidebar'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'No', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_2_new_win_field"><?php echo esc_html__( 'Open in a new window', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_2_new_win_field_yes" name="button_2_new_win_field" class="styled" value="true" <?php echo ( $the_options['button_2_new_win'] == true ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Yes', 'cookie-law-info' ); ?>
							   <input type="radio" id="button_2_new_win_field_no" name="button_2_new_win_field" class="styled" value="false" <?php echo ( $the_options['button_2_new_win'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'No', 'cookie-law-info' ); ?>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="cli_sub_tab_content" data-id="do-not-sell-button">
			<h3><?php echo esc_html__( 'Do not sell', 'cookie-law-info' ); ?> <code>[wt_cli_ccpa_optout]</code></h3>
			<p><?php echo esc_html__( 'Customise the appearance of CCPA notice. Enabling ‘Show CCPA notice’ displays the notice on the consent bar and records prior consent from the user. Alternatively, insert CCPA shortcode [wt_cli_ccpa_optout] to render CCPA notice in a specific page of your site, preferably, cookie policy page.', 'cookie-law-info' ); ?></p>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_6_text_field"><?php echo esc_html__( 'CCPA Text', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_6_text_field" value="<?php echo esc_attr( stripslashes( $the_options['button_6_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_6_as_link_field"><?php echo esc_html__( 'Show as', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" cli_frm_tgl-target="wt-cli-ccpa-advanced" id="button_6_as_link_field_yes" name="button_6_as_link_field" class="styled cli_form_toggle" value="true" <?php echo ( wp_validate_boolean( $the_options['button_6_as_link'] ) == true ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Link', 'cookie-law-info' ); ?>
						<input type="radio" cli_frm_tgl-target="wt-cli-ccpa-advanced" id="button_6_as_link_field_no" name="button_6_as_link_field" class="styled cli_form_toggle" value="false" <?php echo ( wp_validate_boolean( $the_options['button_6_as_link'] ) == false ) ? ' checked="checked"' : ' '; ?> /> <?php echo esc_html__( 'Checkbox', 'cookie-law-info' ); ?>
						<span class="cli_form_help" cli_frm_tgl-id="wt-cli-ccpa-advanced" cli_frm_tgl-val="true" style="margin-top:8px;"><?php echo esc_html__( 'The shortcode will be represented as a link whereever used.', 'cookie-law-info' ); ?></span>
						<span class="cli_form_help" cli_frm_tgl-id="wt-cli-ccpa-advanced" cli_frm_tgl-val="false" style="margin-top:8px;"><?php echo esc_html__( 'The shortcode will be represented as a checkbox with select option to record consent.', 'cookie-law-info' ); ?></span>

					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_6_link_colour_field"><?php echo esc_html__( 'Text colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
						echo '<input type="text" name="button_6_link_colour_field" id="cli-colour-link-button-6" value="' . esc_attr( $the_options['button_6_link_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="cli_sub_tab_content" data-id="accept-all-button" style="display:block;">
			<h3><?php echo esc_html__( 'Accept All Button', 'cookie-law-info' ); ?> <code>[cookie_accept_all]</code></h3>
			<p><?php echo esc_html__( 'This button/link can be customised to either simply close the cookie bar, or follow a link. You can also customise the colours and styles, and show it as a link or a button.', 'cookie-law-info' ); ?></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_7_text_field"><?php echo esc_html__( 'Text', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_7_text_field" value="<?php echo esc_attr( stripslashes( $the_options['button_7_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_7_link_colour_field"><?php echo esc_html__( 'Text colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
						echo '<input type="text" name="button_7_link_colour_field" id="cli-colour-link-button-7" value="' . esc_attr( $the_options['button_7_link_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_7_as_button_field"><?php echo esc_html__( 'Show as', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" cli_frm_tgl-target="cli_accept_all_type" id="button_7_as_button_field_yes" name="button_7_as_button_field" class="styled cli_form_toggle" value="true" <?php echo ( $the_options['button_7_as_button'] == true ) ? ' checked="checked"' : ' '; ?> /> <?php echo esc_html__( 'Button', 'cookie-law-info' ); ?>

						<input type="radio" cli_frm_tgl-target="cli_accept_all_type" id="button_7_as_button_field_no" name="button_7_as_button_field" class="styled cli_form_toggle" value="false" <?php echo ( $the_options['button_7_as_button'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'Link', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top" class="cli-indent-15" cli_frm_tgl-id="cli_accept_all_type" cli_frm_tgl-val="true">
					<th scope="row"><label for="button_7_button_colour_field"><?php echo esc_html__( 'Background colour', 'cookie-law-info' ); ?></label></th>
					<td>
						<?php
						echo '<input type="text" name="button_7_button_colour_field" id="cli-colour-btn-button-7" value="' . esc_attr( $the_options['button_7_button_colour'] ) . '" class="my-color-field" />';
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_7_action_field"><?php echo esc_html__( 'Action', 'cookie-law-info' ); ?></label></th>
					<td>
						<select name="button_7_action_field" id="cli-plugin-button-7-action" class="vvv_combobox cli_form_toggle" cli_frm_tgl-target="cli_accept_all_action">
							<?php $this->print_combobox_options( $this->get_js_actions(), $the_options['button_7_action'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="cli-plugin-row cli-indent-15" cli_frm_tgl-id="cli_accept_all_action" cli_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_7_url_field"><?php echo esc_html__( 'URL', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="text" name="button_7_url_field" id="button_7_url_field" value="<?php echo esc_url( $the_options['button_7_url'] ); ?>" />
						<span class="cli_form_help"><?php echo esc_html__( 'Button will only link to URL if Action = Open URL', 'cookie-law-info' ); ?></span>
					</td>
				</tr>

				<tr valign="top" class="cli-plugin-row cli-indent-15" cli_frm_tgl-id="cli_accept_all_action" cli_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_7_new_win_field"><?php echo esc_html__( 'Open URL in new window?', 'cookie-law-info' ); ?></label></th>
					<td>
						<input type="radio" id="button_7_new_win_field_yes" name="button_7_new_win_field" class="styled" value="true" <?php echo ( $the_options['button_7_new_win'] == true ) ? ' checked="checked"' : ''; ?> /><?php echo esc_html__( 'Yes', 'cookie-law-info' ); ?>

						<input type="radio" id="button_7_new_win_field_no" name="button_7_new_win_field" class="styled" value="false" <?php echo ( $the_options['button_7_new_win'] == false ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html__( 'No', 'cookie-law-info' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_7_button_size_field"><?php echo esc_html__( 'Size', 'cookie-law-info' ); ?></label></th>
					<td>
						<select name="button_7_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_7_button_size'] ); ?>
						</select>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>

	</div>
	<?php
	require 'admin-settings-save-button.php';
	?>
</div>
