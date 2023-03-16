<?php 
	if ( ! function_exists( 'burger_storebiz_testimonial' ) ) :
	function burger_storebiz_testimonial() {
	$hs_testimonial			= get_theme_mod('hs_testimonial','1');
	$testimonial_title		= get_theme_mod('testimonial_title','Satisfy Clients');
	$testimonials			= get_theme_mod('testimonial_content',storebiz_get_testimonial_default());
	if($hs_testimonial=='1'){
?>
	<section id="testimonials-section" class="front-testimonial testimonials-section st-py-default shapes-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default text-white wow fadeInUp">
                        <div class="title">
							<?php if ( ! empty( $testimonial_title ) ) : ?>		
								<h4><?php echo wp_kses_post($testimonial_title); ?></h4>	
							<?php endif; ?>	
						</div>
						<div class="heading-right">
							<div class="testimonial-nav owl-nav">
								<button type="button" role="presentation" class="owl-prev"><span aria-label="Previous">‹</span></button>
								<button type="button" role="presentation" class="owl-next"><span aria-label="Next">›</span></button>
							</div>
						</div>
                    </div>
                </div>
            </div>
            <div class="row wow fadeInUp">
                <div class="col-12 testimonials-slider owl-carousel owl-theme">
					<?php
						if ( ! empty( $testimonials ) ) {
						$testimonials = json_decode( $testimonials );
						foreach ( $testimonials as $test_item ) {
							$storebiz_test_title = ! empty( $test_item->title ) ? apply_filters( 'storebiz_translate_single_string', $test_item->title, 'Testimonial section' ) : '';
							$subtitle = ! empty( $test_item->subtitle ) ? apply_filters( 'storebiz_translate_single_string', $test_item->subtitle, 'Testimonial section' ) : '';
							$text = ! empty( $test_item->text ) ? apply_filters( 'storebiz_translate_single_string', $test_item->text, 'Testimonial section' ) : '';
							$image = ! empty( $test_item->image_url ) ? apply_filters( 'storebiz_translate_single_string', $test_item->image_url, 'Testimonial section' ) : '';
					?>
                    <div class="testimonials-item shapes-section default-carousel">
						
						 <div class="testimonials-client">
                            <?php if ( ! empty( $image ) ) : ?>
								<div class="img-fluid">
									<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
								</div>
							<?php endif; ?>
                        </div>
                        <div class="testimonials-content">
							
							<div class="testimonials-title">
								<?php if ( ! empty( $storebiz_test_title ) ) : ?>
									<h4><?php echo esc_html( $storebiz_test_title ); ?></h4>
								<?php endif; ?>
								
								<?php if ( ! empty( $subtitle ) ) : ?>
									<span class="text-primary"><?php echo esc_html( $subtitle ); ?></span>
								<?php endif; ?>
                            </div>
							
                            <?php if ( ! empty( $text ) ) : ?>
								<p><?php echo esc_html( $text ); ?></p>
							<?php endif; ?>	
                        </div>
                       
                    </div>
					<?php } } ?>
                </div>
            </div>
        </div>
    </section>
<?php
	}}
endif;
if ( function_exists( 'burger_storebiz_testimonial' ) ) {
$section_priority = apply_filters( 'stortebiz_section_priority', 13, 'burger_storebiz_testimonial' );
add_action( 'storebiz_sections', 'burger_storebiz_testimonial', absint( $section_priority ) );
}	