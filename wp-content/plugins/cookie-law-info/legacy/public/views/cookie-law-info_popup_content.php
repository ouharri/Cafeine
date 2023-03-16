<?php
$overview                 = get_option( 'cookielawinfo_privacy_overview_content_settings' );
$default_privacy_settings = Cookie_Law_Info_Admin::get_privacy_defaults();

$cli_always_enable_text   = __( 'Always Enabled', 'cookie-law-info' );
$cli_enable_text          = __( 'Enabled', 'cookie-law-info' );
$cli_disable_text         = __( 'Disabled', 'cookie-law-info' );
$cli_privacy_readmore     = '<a class="cli-privacy-readmore"  aria-label="' . __( 'Show more', 'cookie-law-info' ) . '" tabindex="0" role="button" data-readmore-text="' . __( 'Show more', 'cookie-law-info' ) . '" data-readless-text="' . __( 'Show less', 'cookie-law-info' ) . '"></a>';
$overview_title           = sanitize_text_field( stripslashes( isset( $overview['privacy_overview_title'] ) ? $overview['privacy_overview_title'] : $default_privacy_settings['privacy_overview_title'] ) );
$privacy_overview_content = wp_kses_post( isset( $overview['privacy_overview_content'] ) ? $overview['privacy_overview_content'] : $default_privacy_settings['privacy_overview_content'] );
$privacy_overview_content = nl2br( $privacy_overview_content );
$privacy_overview_content = do_shortcode( stripslashes( $privacy_overview_content ) );
$content_length           = strlen( strip_tags( $privacy_overview_content ) );
$overview_title           = trim( $overview_title );

// $cookie_categories = $this->get_cookie_categories_data();
// $cookie_filter_categories = '';
$cookie_categories = apply_filters( 'wt_cli_cookie_categories', array() );

$js_blocking_enabled = Cookie_Law_Info::wt_cli_is_js_blocking_active();

?>
<div class="cli-container-fluid cli-tab-container">
	<div class="cli-row">
		<div class="cli-col-12 cli-align-items-stretch cli-px-0">
			<div class="cli-privacy-overview">
				<?php

				if ( isset( $overview_title ) === true && $overview_title !== '' ) {
					if ( has_filter( 'wt_cli_change_privacy_overview_title_tag' ) ) {
						echo wp_kses_post( apply_filters( 'wt_cli_change_privacy_overview_title_tag', esc_html( $overview_title ), '<h4>', '</h4>' ) );
					} else {
						echo '<h4>' . esc_html( $overview_title ) . '</h4>';
					}
				}
				?>
				<div class="cli-privacy-content">
					<div class="cli-privacy-content-text"><?php echo wp_kses_post( $privacy_overview_content ); ?></div>
				</div>
				<?php echo wp_kses_post( $cli_privacy_readmore ); ?>
			</div>
		</div>
		<div class="cli-col-12 cli-align-items-stretch cli-px-0 cli-tab-section-container">
			<?php
			foreach ( $cookie_categories as $key => $value ) {

				$category_enabled       = isset( $value['status'] ) ? $value['status'] : false;
				$cookie_title           = ( isset( $value['title'] ) ? $value['title'] : '' );
				$category_description   = ( isset( $value['description'] ) ? $value['description'] : '' );
				$category_default_state = ( isset( $value['default_state'] ) ? $value['default_state'] : false );
				$cookie_title           = isset( $cookie_filter_categories[ $key ] ) ? $cookie_filter_categories[ $key ] : $cookie_title;
				$checked                = false;
				if ( $js_blocking_enabled === true ) {
					if ( isset( $category_default_state ) && $category_default_state === true ) {
						$checked = true;
					}
				} else {
					if ( isset( $_COOKIE[ "cookielawinfo-checkbox-$key" ] ) && $_COOKIE[ "cookielawinfo-checkbox-$key" ] == 'yes' ) {
						$checked = true;
					} elseif ( ! isset( $_COOKIE[ "cookielawinfo-checkbox-$key" ] ) ) {

						$checked = true;
						if ( $category_default_state === false ) {
							$checked = false;
						}
					}
				}
				?>
				<?php if ( $category_enabled === true ) : ?>
					<div class="cli-tab-section">
						<div class="cli-tab-header">
							<a role="button" tabindex="0" class="cli-nav-link cli-settings-mobile" data-target="<?php echo esc_attr( $key ); ?>" data-toggle="cli-toggle-tab">
								<?php echo esc_html( $cookie_title ); ?>
							</a>
							<?php if ( 'necessary' === $key ) : ?>
								<div class="wt-cli-necessary-checkbox">
									<input type="checkbox" class="cli-user-preference-checkbox"  id="wt-cli-checkbox-<?php echo esc_attr( $key ); ?>" data-id="checkbox-<?php echo esc_attr( $key ); ?>" checked="checked"  />
									<label class="form-check-label" for="wt-cli-checkbox-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $cookie_title ); ?></label>
								</div>
								<span class="cli-necessary-caption"><?php echo esc_html( $cli_always_enable_text ); ?></span>
							<?php else : ?>
								<div class="cli-switch">
									<input type="checkbox" id="wt-cli-checkbox-<?php echo esc_attr( $key ); ?>" class="cli-user-preference-checkbox"  data-id="checkbox-<?php echo esc_attr( $key ); ?>"<?php echo checked( $checked, true, false ); ?> />
									<label for="wt-cli-checkbox-<?php echo esc_attr( $key ); ?>" class="cli-slider" data-cli-enable="<?php echo esc_attr( $cli_enable_text ); ?>" data-cli-disable="<?php echo esc_attr( $cli_disable_text ); ?>"><span class="wt-cli-sr-only"><?php echo esc_html( $cookie_title ); ?></span></label>
								</div>
							<?php endif; ?>
						</div>
						<div class="cli-tab-content">
							<div class="cli-tab-pane cli-fade" data-id="<?php echo esc_attr( $key ); ?>">
								<div class="wt-cli-cookie-description">
									<?php echo do_shortcode( $category_description, 'cookielawinfo-category' ); ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php
			}
			?>
		</div>
	</div>
</div>
<?php
