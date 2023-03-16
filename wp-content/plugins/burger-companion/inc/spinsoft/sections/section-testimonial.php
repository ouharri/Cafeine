<?php 
	if ( ! function_exists( 'burger_spintech_testimonial' ) ) :
	function burger_spintech_testimonial() {
	$hs_testimonial				=	get_theme_mod('hs_testimonial','1');
	$testimonial_title			= get_theme_mod('testimonial_title','Explore');
	$testimonial_subtitle		= get_theme_mod('testimonial_subtitle','Our <span class="text-primary">Testimonials</span>');
	$testimonial_description	= get_theme_mod('testimonial_description','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
	$testimonials				= get_theme_mod('testimonials',spintech_get_testimonial_default());	
	if($hs_testimonial == '1') { 
?>
	 <section id="testimonials-section" class="testimonials-section st-py-default home-testimonial">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default wow fadeInUp">
                       <?php if ( ! empty( $testimonial_title ) ) : ?>
							 <span class="badge bg-primary ttl"><?php echo wp_kses_post($testimonial_title); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $testimonial_subtitle ) ) : ?>		
							<h2><?php echo wp_kses_post($testimonial_subtitle); ?></h2>   							
						<?php endif; ?>	
						<?php if ( ! empty( $testimonial_description ) ) : ?>		
							<p><?php echo wp_kses_post($testimonial_description); ?></p>    
						<?php endif; ?>	
                    </div>
                </div>
            </div>
            <div class="row wow fadeInUp">
                <div class="col-12 testimonials-slider owl-carousel owl-theme">
					<?php
						if ( ! empty( $testimonials ) ) {
						$testimonials = json_decode( $testimonials );
						foreach ( $testimonials as $test_item ) {
							$spintech_test_title = ! empty( $test_item->title ) ? apply_filters( 'spintech_translate_single_string', $test_item->title, 'Testimonial section' ) : '';
							$subtitle = ! empty( $test_item->subtitle ) ? apply_filters( 'spintech_translate_single_string', $test_item->subtitle, 'Testimonial section' ) : '';
							$text = ! empty( $test_item->text ) ? apply_filters( 'spintech_translate_single_string', $test_item->text, 'Testimonial section' ) : '';
							$image = ! empty( $test_item->image_url ) ? apply_filters( 'spintech_translate_single_string', $test_item->image_url, 'Testimonial section' ) : '';
					?>
						<div class="testimonials-item">
							<div class="testimonials-content">
								<div class="testimonials-icon"><span>”</span></div>
								<?php if ( ! empty( $text ) ) : ?>
									<p><?php echo esc_html( $text ); ?></p>
								<?php endif; ?>	
							</div>
							<div class="testimonials-client">
								<?php if ( ! empty( $image ) ) : ?>
									<div class="img-fluid">
										<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
									</div>
								<?php endif; ?>
								<div class="testimonials-title">
									<?php if ( ! empty( $spintech_test_title ) ) : ?>
										<h5><?php echo esc_html( $spintech_test_title ); ?></h5>
									<?php endif; ?>
									
									<?php if ( ! empty( $subtitle ) ) : ?>
										<p><?php echo esc_html( $subtitle ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php } } ?>
                </div>
            </div>
        </div>
        <div class="shape19"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape19.png" alt="image"></div>
        <div class="shape20"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape20.png" alt="image"></div>
        <div class="shape21"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape21.png" alt="image"></div>
        <div class="shape22"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape22.png" alt="image"></div>
        <div class="shape23"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape23.png" alt="image"></div>
        <div class="shape24"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape24.png" alt="image"></div>
        <div class="shape25"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape25.png" alt="image"></div>
        <div class="shape26"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/spinsoft/images/clipArt/shape26.png" alt="image"></div>
    </section>
	
<?php	
	}}
endif;
if ( function_exists( 'burger_spintech_testimonial' ) ) {
$section_priority = apply_filters( 'spintech_section_priority', 13, 'burger_spintech_testimonial' );
add_action( 'spintech_sections', 'burger_spintech_testimonial', absint( $section_priority ) );
}		