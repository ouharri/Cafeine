<?php 
	if ( ! function_exists( 'burger_cozipress_testimonial' ) ) :
	function burger_cozipress_testimonial() {
	$hs_testimonial				= get_theme_mod('hs_testimonial','1');		
	$testimonial_title			= get_theme_mod('testimonial_title','Explore');
	$testimonial_subtitle		= get_theme_mod('testimonial_subtitle','Our <span class="text-primary">Satisfied Clients</span>');
	$testimonial_description	= get_theme_mod('testimonial_description','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
	$testimonials				= get_theme_mod('testimonials',cozipress_get_testimonial_default());
	$testimonial_column			= get_theme_mod('testimonial_column','3');
	$testimonial_bg_img			= get_theme_mod('testimonial_bg_img',BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/testimonials/testimonial_bg.jpg');
	$testimonial_back_attach			= get_theme_mod('testimonial_back_attach','fixed');
if($hs_testimonial == '1'){	
?>
	<section id="testimonials-section" class="home-testimonial testimonials-section st-py-default shapes-section" style="background:url('<?php echo esc_url($testimonial_bg_img); ?>') no-repeat <?php echo esc_attr($testimonial_back_attach); ?> center / cover rgba(0,0,0,0.85);background-blend-mode:multiply;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default text-white wow fadeInUp">
                        <?php if ( ! empty( $testimonial_title ) ) : ?>
							 <span class="badge ttl"><?php echo wp_kses_post($testimonial_title); ?></span>
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
							$cozipresss_test_title = ! empty( $test_item->title ) ? apply_filters( 'cozipresss_translate_single_string', $test_item->title, 'Testimonial section' ) : '';
							$subtitle = ! empty( $test_item->subtitle ) ? apply_filters( 'cozipresss_translate_single_string', $test_item->subtitle, 'Testimonial section' ) : '';
							$text = ! empty( $test_item->text ) ? apply_filters( 'cozipresss_translate_single_string', $test_item->text, 'Testimonial section' ) : '';
							$image = ! empty( $test_item->image_url ) ? apply_filters( 'cozipresss_translate_single_string', $test_item->image_url, 'Testimonial section' ) : '';
					?>
                    <div class="testimonials-item shapes-section">
                        <div class="testimonials-content">
                            <?php if ( ! empty( $text ) ) : ?>
								<p><?php echo esc_html( $text ); ?></p>
							<?php endif; ?>	
                            <div class="testimonials-title">
								<?php if ( ! empty( $cozipresss_test_title ) ) : ?>
									<h4><?php echo esc_html( $cozipresss_test_title ); ?></h4>
								<?php endif; ?>
								
								<?php if ( ! empty( $subtitle ) ) : ?>
									<span class="text-primary"><?php echo esc_html( $subtitle ); ?></span>
								<?php endif; ?>
                            </div>
                        </div>
                        <div class="testimonials-client">
                            <?php if ( ! empty( $image ) ) : ?>
								<div class="img-fluid">
									<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
								</div>
							<?php endif; ?>
                        </div>
                        <div class="lg-shape32"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/testimonials/shape3.png" alt="image"></div>
                        <div class="lg-shape33"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/testimonials/shape4.png" alt="image"></div>
                        <div class="lg-shape34"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/testimonials/shape5.png" alt="image"></div>
                        <div class="lg-shape35"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/testimonials/shape6.png" alt="image"></div>
                    </div>
					<?php } } ?>
                </div>
            </div>
        </div>
        <div class="lg-shape21"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/testimonials/shape1.png" alt="image"></div>
        <div class="lg-shape21bottom"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/testimonials/shape2.png" alt="image"></div>
    </section>
<?php	
	}}
endif;
if ( function_exists( 'burger_cozipress_testimonial' ) ) {
$section_priority = apply_filters( 'cozipress_section_priority', 14, 'burger_cozipress_testimonial' );
add_action( 'cozipress_sections', 'burger_cozipress_testimonial', absint( $section_priority ) );
}	
	