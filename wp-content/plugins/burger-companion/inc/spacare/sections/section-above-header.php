<?php 
	if ( ! function_exists( 'spabiz_above_header' ) ) :
	function spabiz_above_header() {
		$hs_hdr_info		=	get_theme_mod('hs_hdr_info','1'); 
		$hs_social_icon		=	get_theme_mod('hs_social_icon','1');
		if($hs_hdr_info == '1' || $hs_social_icon == '1'): 	 ?>
			<div id="above-header" class="above-header d-lg-block d-none wow fadeInDown">
			<div class="header-widget d-flex align-items-center">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-auto mb-lg-0 mb-4">
							<?php if($hs_hdr_info == '1'){ ?>
								<div class="widget-left text-lg-left">
									<?php spabiz_header_info(); ?>
								</div>
							<?php } ?>
						</div>
						<div class="col mb-lg-0 mb-4">                            
							<?php if($hs_social_icon == '1'){ ?>
								<div class="widget-right justify-content-lg-end justify-content-center text-lg-right text-center">
									<?php spabiz_header_social_icon(); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; 
} endif;
add_action('spabiz_above_header', 'spabiz_above_header');