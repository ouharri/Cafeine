<?php 
	if ( ! function_exists( 'seokart_footer_above' ) ) :
	function seokart_footer_above() {
		$footer_above_hs		= get_theme_mod('footer_above_hs','1'); 
		$footer_above_info		= get_theme_mod('footer_above_info',seokart_get_footer_info_default());
		if($footer_above_hs=='1'): ?>
			<div class="footer-info row">
				<?php
					if ( ! empty( $footer_above_info ) ) {
					$footer_above_info = json_decode( $footer_above_info );
					foreach ( $footer_above_info as $footer_info_item ) {
						$title = ! empty( $footer_info_item->title ) ? apply_filters( 'seokart_translate_single_string', $footer_info_item->title, 'Footer Above section' ) : '';
						$subtitle = ! empty( $footer_info_item->subtitle ) ? apply_filters( 'seokart_translate_single_string', $footer_info_item->subtitle, 'Footer Above section' ) : '';
						$icon = ! empty( $footer_info_item->icon_value ) ? apply_filters( 'seokart_translate_single_string', $footer_info_item->icon_value, 'Footer Above section' ) : '';
				?>
					<div class="col-lg-4 col-md-6 info-item mb-4"> 
						<div class="media">
							<?php if ( ! empty( $icon ) ) : ?>	
								<i class="fa <?php echo esc_attr($icon); ?>"></i>
							<?php endif; ?>	
							<div class="media-body"> 
								<?php if ( ! empty( $title ) ) : ?>	
									<h4>
										<?php echo esc_html($title); ?>
									</h4>
								<?php endif; ?>	
								
								<?php if ( ! empty( $subtitle ) ) : ?>	
									<h6><?php echo esc_html($subtitle); ?></h6>
								<?php endif; ?>	
							</div>
						</div>
					</div>
				<?php } } ?>
			</div>
		<?php else: ?>
			<div style="padding: 50px 55px;"></div>
		<?php endif; 
} endif;
add_action('seokart_footer_above', 'seokart_footer_above');