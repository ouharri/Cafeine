<?php  
	if ( ! function_exists( 'appetizer_above_header' ) ) :
	function appetizer_above_header() {
	$hs_above_opening		=	get_theme_mod('hs_above_opening','1');
	$abv_hdr_opening_icon	=	get_theme_mod('abv_hdr_opening_icon','fa-clock-o');
	$abv_hdr_opening_ttl	=	get_theme_mod('abv_hdr_opening_ttl','Opening Hours - 10 Am to 6 PM');
	$hide_show_hdr_phone	=	get_theme_mod('hide_show_hdr_phone','1');
	$hdr_phone_icon			=	get_theme_mod('hdr_phone_icon','fa-mobile');
	$hdr_phone_ttl			=	get_theme_mod('hdr_phone_ttl','<a href="tel:+91 123 456 7890">+91 123 456 7890</a>');
	$hide_show_social_icon	=	get_theme_mod('hide_show_social_icon','1');
	$social_icons			=	get_theme_mod('social_icons',appetizer_get_social_icon_default());
if($hs_above_opening=='1' || $hide_show_hdr_phone=='1' || $hide_show_social_icon=='1'):		
?>
	<div id="above-header" class="above-header d-lg-block d-none wow fadeInDown">
		<div class="header-widget d-flex align-items-center">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-12 mb-lg-0 mb-4">
						<div class="widget-left text-lg-left text-center">
							<?php if($hs_above_opening=='1'): ?>
								<aside class="widget widget-contact first">
									<div class="contact-area">
										<?php if(!empty($abv_hdr_opening_icon)): ?>
											<div class="contact-icon">
												<div class="contact-corn"><i class="fa <?php echo esc_attr($abv_hdr_opening_icon); ?>"></i></div>
											</div>
										<?php endif; ?>	
										<?php if(!empty($abv_hdr_opening_ttl)): ?>
											<div class="contact-info">
												<p class="text"><?php echo wp_kses_post($abv_hdr_opening_ttl); ?></p>
											</div>
										<?php endif; ?>
									</div>
								</aside>
							<?php endif; ?>	
							
							<?php if($hide_show_hdr_phone=='1'): ?>
								<aside class="widget widget-contact second">
									<div class="contact-area">
										<?php if(!empty($hdr_phone_icon)): ?>
											<div class="contact-icon">
												<div class="contact-corn"><i class="fa <?php echo esc_attr($hdr_phone_icon); ?>"></i></div>
											</div>
										<?php endif; ?>	
										<?php if(!empty($hdr_phone_ttl)): ?>
											<div class="contact-info">
												<p class="text"><?php echo wp_kses_post($hdr_phone_ttl); ?></p>
											</div>
										<?php endif; ?>
									</div>
								</aside>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-lg-6 col-12 mb-lg-0 mb-4">                            
						<div class="widget-right justify-content-lg-end justify-content-center text-lg-right text-center">
							<?php if($hide_show_social_icon=='1'): ?>
								<aside class="widget widget_social_widget">
									<ul>
										<?php
											$social_icons = json_decode($social_icons);
											if( $social_icons!='' )
											{
											foreach($social_icons as $social_item){	
											$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'appetizer_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
											$social_link = ! empty( $social_item->link ) ? apply_filters( 'appetizer_translate_single_string', $social_item->link, 'Header section' ) : '';
										?>
										<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
										<?php }} ?>
									</ul>
								</aside>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif;
}
add_action( 'appetizer_above_header', 'appetizer_above_header');
endif;