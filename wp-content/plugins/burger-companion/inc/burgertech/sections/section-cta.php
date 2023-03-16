<?php 
	if ( ! function_exists( 'burger_spintech_cta' ) ) :
	function burger_spintech_cta() {
	$hs_cta						=	get_theme_mod('hs_cta','1');		
	$cta_title					= get_theme_mod('cta_title','DO YOU HAVE ANY PROJECT ?');
	$cta_description			= get_theme_mod('cta_description','Let’s Talk About Business Soluations With Us');
	$cta_btn_lbl1				= get_theme_mod('cta_btn_lbl1','Join With Us');
	$cta_btn_link1				= get_theme_mod('cta_btn_link1');
	$cta_btn_lbl2				= get_theme_mod('cta_btn_lbl2','70 975 975 70');
	$cta_btn_link2				= get_theme_mod('cta_btn_link2');
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
							<?php if ( ! empty( $cta_btn_lbl2 ) ) : ?>
								<a class="read-link" href="<?php echo esc_url($cta_btn_link2); ?>"><span class="cta-call-icon"><svg class="call-icon" viewBox="0 0 32 32"><path class="path1" d="M18.317 23.228c-.276-.497-.497-.993-.772-1.49a3.452 3.452 0 00-3.09-1.876H12.8l-1.048-1.931-1.545-3.2.993-1.324c.497-.607 1.159-1.986.166-3.917l-1.324-2.593a2.705 2.705 0 00-1.986-1.545c-.828-.166-1.655.055-2.372.607a10.398 10.398 0 00-3.31 4.91c-.441 1.379-.276 2.924.386 4.303L8.001 25.6a5.707 5.707 0 003.145 2.869c1.103.386 2.262.607 3.476.607.883 0 1.766-.11 2.648-.331.772-.221 1.434-.772 1.821-1.545.331-.772.331-1.655-.055-2.372-.166-.552-.441-1.048-.717-1.6zm-1.158 3.034c-.055.11-.11.221-.331.276-1.6.441-3.31.331-4.8-.221-.828-.276-1.49-.938-1.931-1.766L4.8 14.179c-.441-.883-.552-1.821-.276-2.648.441-1.545 1.379-2.869 2.593-3.862a.598.598 0 01.386-.166h.11a.736.736 0 01.441.386l1.324 2.593c.497.993.166 1.49.055 1.6l-1.379 1.876c-.276.331-.276.772-.11 1.159l1.821 3.862s0 .055.055.055l1.379 2.538c.221.331.552.607.993.552l2.317-.055c.497 0 .883.276 1.103.662.276.497.497.993.772 1.49s.552 1.048.772 1.545a.77.77 0 010 .497z"/><path class="wave wave-sm" d="M16.607 10.152c-.552-.276-1.214-.055-1.49.441-.276.552-.055 1.214.441 1.49 1.379.717 2.593 3.034 2.538 4.855 0 .607.497 1.214 1.103 1.214.607 0 1.103-.552 1.103-1.159.055-2.648-1.6-5.738-3.697-6.841z"/><path class="wave wave-md" d="M19.531 6.676c-.552-.276-1.214-.055-1.49.441-.276.552-.055 1.214.441 1.49 2.703 1.434 4.303 4.359 4.248 7.834 0 .607.497 1.159 1.103 1.159.607 0 1.103-.497 1.103-1.103.055-4.303-1.986-8-5.407-9.821z"/><path class="wave wave-lg" d="M22.952 3.09c-.552-.276-1.214-.11-1.49.441s-.11 1.214.441 1.49C25.6 7.118 27.751 11.2 27.586 16c0 .607.441 1.103 1.048 1.103h.055c.607 0 1.103-.441 1.103-1.048.166-5.628-2.372-10.428-6.841-12.966z"/></svg></span> <span class="cta-label"><?php echo esc_html($cta_btn_lbl2); ?></span></a>
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
$section_priority = apply_filters( 'spintech_section_priority', 12, 'burger_spintech_cta' );
add_action( 'spintech_sections', 'burger_spintech_cta', absint( $section_priority ) );
}