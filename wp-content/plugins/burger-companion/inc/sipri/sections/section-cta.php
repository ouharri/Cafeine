<?php 
if ( ! function_exists( 'burger_cozipress_cta' ) ) :
	function burger_cozipress_cta() {
	$hs_pg_about_cta				= get_theme_mod('hs_pg_about_cta','1');	
	$pg_about_cta_head_icon			= get_theme_mod('pg_about_cta_head_icon','fa-phone');
	$pg_about_cta_head_img			= get_theme_mod('pg_about_cta_head_img',BURGER_COMPANION_PLUGIN_URL .'inc/sipri/images/cta/avatar-1.png');
	$pg_about_cta_ttl				= get_theme_mod('pg_about_cta_ttl',"Let's Talk About Business Solutions");
	$pg_about_cta_desc				= get_theme_mod('pg_about_cta_desc','It is a long established fact that a reader');
	$pg_about_cta_ctinfo				= get_theme_mod('pg_about_cta_ctinfo','<h6 class="title">Help Desk 24/7</h6><p class="text"><a href="tel:+12 345 678 90">(+12 345 678 90)</a></p>');
	$pg_about_cta_btn_icon				= get_theme_mod('pg_about_cta_btn_icon','fa-headphones');
	$pg_about_cta_btn_lbl				= get_theme_mod('pg_about_cta_btn_lbl','Live Chat');
	$pg_about_cta_btn_url			= get_theme_mod('pg_about_cta_btn_url');
	$pg_about_cta_bg_img			= get_theme_mod('pg_about_cta_bg_img',BURGER_COMPANION_PLUGIN_URL . 'inc/sipri/images/cta/dotted_image.png');
	$pg_about_cta_bg_attach			= get_theme_mod('pg_about_cta_bg_attach','scroll');
	if($hs_pg_about_cta =='1'){
?>
<section  id="cta-section" class="cta-section home-cta" style="background:url('<?php echo esc_url($pg_about_cta_bg_img); ?>') no-repeat <?php echo esc_attr($pg_about_cta_bg_attach); ?> center top / cover rgb(33 68 98 / 0.2);background-blend-mode:multiply;">
	<div class="container">
		<div class="row wow fadeInUp">
			<div class="col-12">
				<div class="cta-wrapper text-md-left text-center">
					<div class="cta-content">
						<div class="cta-icon-wrap mb-md-0 mb-4">
							<?php if ( ! empty( $pg_about_cta_head_icon ) ) : ?>
								<div class="cta-icon"><i class="fa <?php echo esc_attr($pg_about_cta_head_icon); ?>"></i></div>
							<?php endif; ?>
							
							<?php if ( ! empty( $pg_about_cta_head_img ) ) : ?>
								<div class="cta-img"><img src="<?php echo esc_url($pg_about_cta_head_img); ?>" alt=""></div>
							<?php endif; ?>
							
						</div>
						<div class="cta-info">
							<?php if ( ! empty( $pg_about_cta_ttl ) ) : ?>
								<h3 class="text-primary"><?php echo wp_kses_post($pg_about_cta_ttl); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty($pg_about_cta_desc) ) : ?>		
								<h5><?php echo wp_kses_post($pg_about_cta_desc); ?></h5>    
							<?php endif; ?>	
						</div>
					</div>
					<div class="cta-btn-wrap text-lg-right text-center mt-lg-0 mt-4">
						<aside class="widget widget-contact">
							<div class="contact-area">
								<div class="contact-info">
								   <?php if ( ! empty($pg_about_cta_ctinfo) ) :
											echo wp_kses_post($pg_about_cta_ctinfo);
								   endif; ?>	
								</div>
							</div>
						</aside>
						<?php if ( ! empty($pg_about_cta_btn_lbl) ) : ?>
						<aside class="cta-btns">
							<a href="<?php echo esc_url($pg_about_cta_btn_url); ?>" class="btn btn-primary btn-like-icon"><?php echo esc_html($pg_about_cta_btn_lbl); ?> <span class="bticn"><i class="fa <?php echo esc_attr($pg_about_cta_btn_icon); ?>"></i></span></a>
						</aside>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	} }
endif;
if ( function_exists( 'burger_cozipress_cta' ) ) {
$section_priority = apply_filters( 'cozipress_section_priority', 12, 'burger_cozipress_cta' );
add_action( 'cozipress_sections', 'burger_cozipress_cta', absint( $section_priority ) );
}