<?php 
	if ( ! function_exists( 'burger_spintech_cta' ) ) :
	function burger_spintech_cta() {
	$hs_cta						=	get_theme_mod('hs_cta','1');		
	$cta_title					= get_theme_mod('cta_title','DO YOU HAVE ANY PROJECT ?');
	$cta_description			= get_theme_mod('cta_description','Let’s Talk About Business Soluations With Us');
	$cta_btn_lbl1				= get_theme_mod('cta_btn_lbl1','Join With Us');
	$cta_btn_link1				= get_theme_mod('cta_btn_link1');
	if($hs_cta == '1') { 
?>
  <section  id="cta-section" class="cta-section home-cta">
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-12">
                    <div class="cta-wrapper text-md-left text-center">
                        <div class="cta-content">
							<?php if ( ! empty( $cta_title ) ) : ?>
								<p><?php echo wp_kses_post($cta_title); ?></p>
							<?php endif; ?>
							<?php if ( ! empty($cta_description) ) : ?>		
								<h3><?php echo wp_kses_post($cta_description); ?></h3>    
							<?php endif; ?>	
                        </div>
                        <div class="cta-btn-wrap text-lg-right text-center">
							<?php if ( ! empty( $cta_btn_lbl1 ) ) : ?>
								<a href="<?php echo esc_url($cta_btn_link1); ?>" class="btn btn-white" data-text="Join With Us"><?php echo esc_html($cta_btn_lbl1); ?></a>
							<?php endif;?>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape-star01"></div>
        <div class="shape-star02"></div>
        <div class="shape-star03"></div>
        <div class="shape-star04"></div>
        <div class="shape-cta01"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL ); ?>inc/spintech/images/clipArt/ctaClipart/shape1.png" alt="image"></div>
        <div class="shape-cta02"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape2.png" alt="image"></div>
        <div class="shape-cta03"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape3.png" alt="image"></div>
        <div class="shape-cta04"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape4.png" alt="image"></div>
        <div class="shape-cta05"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape5.png" alt="image"></div>
        <div class="shape-cta06"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape6.png" alt="image"></div>
        <div class="shape-cta07"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape3.png" alt="image"></div>
        <div class="shape-cta08"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape6.png" alt="image"></div>
        <div class="shape-cta09"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spintech/images/clipArt/ctaClipart/shape9.png" alt="image"></div>
  </section>
  
  <?php	
	}}
endif;
if ( function_exists( 'burger_spintech_cta' ) ) {
$section_priority = apply_filters( 'spintech_section_priority', 16, 'burger_spintech_cta' );
add_action( 'spintech_sections', 'burger_spintech_cta', absint( $section_priority ) );
}