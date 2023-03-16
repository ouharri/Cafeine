<?php 
	if ( ! function_exists( 'spintech_above_header' ) ) :
	function spintech_above_header() {
	$hs_above_hiring		=	get_theme_mod('hs_above_hiring','1');
	$abv_hdr_hiring_ttl		=	get_theme_mod('abv_hdr_hiring_ttl','Now Hiring:');
	$abv_hdr_hiring_content	=	get_theme_mod('abv_hdr_hiring_content','"Are you a driven 1st Line IT Support?","Are you a driven 1st Line IT Support?","Are you a driven 1st Line IT Support?"');
	
	$hide_show_cntct_info		=	get_theme_mod('hide_show_cntct_info','1');
	$th_contct_icon				=	get_theme_mod('th_contct_icon','fa-clock-o');
	$th_contact_text				=	get_theme_mod('th_contact_text','Office Hours 8:00AM - 6:00PM');
	
	$hide_show_social_icon		=	get_theme_mod('hide_show_social_icon','1');
	$social_icons				=	get_theme_mod('social_icons',spintech_get_social_icon_default());	
?>
    <!--===// Start: Main Header
    =================================-->
   
        <!--===// Start: Header Above
        =================================-->
		<?php if($hs_above_hiring == '1' || $hide_show_cntct_info == '1' || $hide_show_social_icon == '1') { ?>
			<div id="above-header" class="above-header d-lg-block d-none wow fadeInDown">
				<div class="header-widget d-flex align-items-center">
					<div class="container">
						<div class="row">
							<div class="col-lg-6 col-12 mb-lg-0 mb-4">
								<div class="widget-left text-lg-left text-center">
									<aside class="widget widget-text-slide">
										<?php if($hs_above_hiring == '1') { ?>
											<div class="text-animation hiring">
												<div class="text-heading"><strong><?php echo esc_html( $abv_hdr_hiring_ttl ); ?></strong>
													<div class="text-sliding">            
														<span class="typewrite" data-period="2000" data-type='[ <?php echo wp_kses_post( $abv_hdr_hiring_content ); ?>]'></span><span class="wrap"></span>
													</div>
												</div>
											</div>
										<?php } ?>	
									</aside>                                
								</div>
							</div>
							<div class="col-lg-6 col-12 mb-lg-0 mb-4">                            
								<div class="widget-right justify-content-lg-end justify-content-center text-lg-right text-center">
									<?php if($hide_show_cntct_info == '1') { ?>
										<aside class="widget widget-contact">
											<div class="contact-area">
												<div class="contact-icon">
													<div class="contact-corn"><i class="fa <?php echo esc_attr( $th_contct_icon ); ?>"></i></div>
												</div>
												<div class="contact-info">
													<p class="text"><a href="javascript:void(0);"><?php echo esc_html( $th_contact_text ); ?></a></p>
												</div>
											</div>
										</aside>
									<?php } ?>	
									<?php if($hide_show_social_icon == '1') { ?>
										<aside class="widget widget_social_widget">
											<ul>
												<?php
													$social_icons = json_decode($social_icons);
													if( $social_icons!='' )
													{
													foreach($social_icons as $social_item){	
													$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'spintech_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
													$social_link = ! empty( $social_item->link ) ? apply_filters( 'spintech_translate_single_string', $social_item->link, 'Header section' ) : '';
												?>
												<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
												<?php }} ?>
											</ul>
										</aside>
									<?php } ?>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		
	<?php 
} endif;
add_action('spintech_above_header', 'spintech_above_header');