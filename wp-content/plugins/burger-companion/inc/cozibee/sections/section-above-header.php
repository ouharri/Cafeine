<?php 
	if ( ! function_exists( 'cozipress_above_header' ) ) :
	function cozipress_above_header() {
		$hs_above_opening		=	get_theme_mod('hs_above_opening','1');
		$hide_show_hdr_support	=	get_theme_mod('hide_show_hdr_support','1');	
		$hide_show_social_icon	=	get_theme_mod('hide_show_social_icon','1');
		$hide_show_hdr_btn		=	get_theme_mod('hide_show_hdr_btn','1');

	 if($hs_above_opening == '1' || $hide_show_hdr_support == '1' || $hide_show_social_icon == '1'): ?>
			<div id="above-header" class="above-header d-lg-block d-none wow fadeInDown">
				<div class="header-widget d-flex align-items-center">
					<div class="container">
						<div class="row">
							<div class="col-lg-6 col-12 mb-lg-0 mb-4">
								<div class="widget-left text-lg-left text-center">
									<?php 
										cozipress_header_opening_hour(); 
										cozipress_header_support(); 
									?>	
								</div>
							</div>
							<div class="col-lg-6 col-12 mb-lg-0 mb-4">                            
								<div class="widget-right justify-content-lg-end justify-content-center text-lg-right text-center">
									<?php 
										cozipress_header_social_icon();
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; 
} endif;
add_action('cozipress_above_header', 'cozipress_above_header');