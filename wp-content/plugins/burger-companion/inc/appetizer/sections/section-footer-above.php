<?php  
	if ( ! function_exists( 'appetizer_footer_above' ) ) :
	function appetizer_footer_above() {
	$footer_abv_hs	= get_theme_mod('footer_abv_hs','1');
	$footer_abv_info	= get_theme_mod('footer_abv_info',appetizer_get_footer_info_default());
	if($footer_abv_hs=='1'){	
	?>
		<div class="footer-above">
			<div class="container">
				<div class="row g-4 info-wrp">
					<?php
						if ( ! empty( $footer_abv_info ) ) {
						$footer_abv_info = json_decode( $footer_abv_info );
						foreach ( $footer_abv_info as $info_item ) {
							$title = ! empty( $info_item->title ) ? apply_filters( 'appetizer_translate_single_string', $info_item->title, 'Footer Above  section' ) : '';
							$text = ! empty( $info_item->text ) ? apply_filters( 'appetizer_translate_single_string', $info_item->text, 'Footer Above section' ) : '';
							$link = ! empty( $info_item->link ) ? apply_filters( 'appetizer_translate_single_string', $info_item->link, 'Footer Above section' ) : '';
							$image = ! empty( $info_item->image_url ) ? apply_filters( 'appetizer_translate_single_string', $info_item->image_url, 'Footer Above section' ) : '';
							$icon = ! empty( $info_item->icon_value ) ? apply_filters( 'appetizer_translate_single_string', $info_item->icon_value, 'Footer Above section' ) : '';
					?>
						<div class="col-lg-3 col-md-6 col-12 wow fadeIn">
							<aside class="widget widget-contact">
								<div class="contact-area">
									<?php if(!empty($image)){ ?>
										<div class="contact-icon">
											<div class="contact-corn"><img src="<?php echo esc_url($image); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" <?php endif; ?>></div>
										</div>
									<?php }else{ ?>	
										<div class="contact-icon">
											<div class="contact-corn"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
										</div>
									<?php } ?>
									
									<?php if(!empty($title) || !empty($text)){ ?>
										<div class="contact-info">
											<?php if(!empty($link)){ ?>
												<h6 class="title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html( $title ); ?></a></h6>
											<?php }else{ ?>	
												<h6 class="title"><a href="javascript:void(0);"><?php echo esc_html( $title ); ?></a></h6>
											<?php } ?>
									
											<p class="text"><?php echo esc_html( $text ); ?></p>
										</div>
									<?php } ?>	
								</div>
							</aside>
						</div>
					<?php }} ?>
				</div>
			</div>
		</div>
	<?php }
	}
add_action( 'appetizer_footer_above', 'appetizer_footer_above');
endif;