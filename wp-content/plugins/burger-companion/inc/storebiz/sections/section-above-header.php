<?php 
	if ( ! function_exists( 'storebiz_above_header' ) ) :
	function storebiz_above_header() {
		$hs_above_first_info		=	get_theme_mod('hs_above_first_info','1');
		$hide_show_hdr_info2		=	get_theme_mod('hide_show_hdr_info2','1');	
		$hide_show_abv_hdr_menus	=	get_theme_mod('hide_show_abv_hdr_menus','1');
		if($hs_above_first_info == '1' || $hide_show_hdr_info2 == '1' || $hide_show_abv_hdr_menus == '1' ): ?>
			<div id="above-header" class="above-header d-lg-block d-none wow fadeInDown">
				<div class="header-widget d-flex align-items-center">
					<div class="container">
						<div class="row">
							<div class="col-lg-6 col-12 mb-lg-0 mb-4">
								<div class="widget-left text-lg-left text-center">
									<?php 
										$abv_hdr_first_info_icon	=	get_theme_mod('abv_hdr_first_info_icon','fa-truck');
										$abv_hdr_first_info_ttl		=	get_theme_mod('abv_hdr_first_info_ttl','Free Delivery');
										if($hs_above_first_info == '1'){
									?>
									<aside class="widget widget-contact first">
										<div class="contact-area">
											<div class="contact-icon">
												<div class="contact-corn"><i class="fa <?php echo esc_attr( $abv_hdr_first_info_icon ); ?>"></i></div>
											</div>
											<div class="contact-info">
												<p class="text"><?php echo wp_kses_post( $abv_hdr_first_info_ttl ); ?></p>
											</div>
										</div>
									</aside>
									<?php  }
										$hdr_info2_icon =	get_theme_mod('hdr_info2_icon',' fa-dollar');
										$hdr_info2_ttl  =	get_theme_mod('hdr_info2_ttl','Return Policy');
										if($hide_show_hdr_info2 == '1'){
									?>
										<aside class="widget widget-contact second">
											<div class="contact-area">
												<div class="contact-icon">
													<div class="contact-corn"><i class="fa <?php echo esc_attr( $hdr_info2_icon ); ?>"></i></div>
												</div>
												<div class="contact-info">
													<p class="text"><?php echo wp_kses_post( $hdr_info2_ttl ); ?></p>
												</div>
											</div>
										</aside>
									<?php  } ?>
								</div>
							</div>
							<div class="col-lg-6 col-12 mb-lg-0 mb-4">                            
								<div class="widget-right justify-content-lg-end justify-content-center text-lg-right text-center">
									<!--div class="select-currency">
										 <?php //echo do_shortcode( '[woocommerce_currency_switcher_drop_down_box]' ); ?>
									</div-->
									<?php if($hide_show_abv_hdr_menus == '1') : ?>
										<div class="main-navbar menu-bar">
											<?php if ( is_active_sidebar( 'storebiz-header-right' ) ) : dynamic_sidebar( 'storebiz-header-right');  endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif;
} endif;
add_action('storebiz_above_header', 'storebiz_above_header');