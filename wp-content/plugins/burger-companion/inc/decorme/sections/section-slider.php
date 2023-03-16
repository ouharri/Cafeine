 <!--===// Start: Slider
    =================================--> 
<?php  
if ( ! function_exists( 'burger_decorme_slider' ) ) :
	function burger_decorme_slider() {
	$slider_hs 						= get_theme_mod('slider_hs','1');
	$slider 						= get_theme_mod('slider5',decorme_get_slider5_default());
	if($slider_hs=='1'):
?>	
<section id="slider-section" class="slider-section home-slider-five">
	<div class="home-slider owl-carousel owl-theme">
		<?php
			if ( ! empty( $slider ) ) {
			$slider = json_decode( $slider );
			foreach ( $slider as $slide_item ) {
				$title = ! empty( $slide_item->title ) ? apply_filters( 'decorme_translate_single_string', $slide_item->title, 'slider 5 section' ) : '';
				$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'decorme_translate_single_string', $slide_item->subtitle, 'slider 5 section' ) : '';
				$subtitle2 = ! empty( $slide_item->subtitle2 ) ? apply_filters( 'decorme_translate_single_string', $slide_item->subtitle2, 'slider 5 section' ) : '';
				$text = ! empty( $slide_item->text ) ? apply_filters( 'decorme_translate_single_string', $slide_item->text, 'slider 5 section' ) : '';
				$button = ! empty( $slide_item->text2) ? apply_filters( 'decorme_translate_single_string', $slide_item->text2,'slider 5 section' ) : '';
				$link = ! empty( $slide_item->link ) ? apply_filters( 'decorme_translate_single_string', $slide_item->link, 'slider 5 section' ) : '';
				$image = ! empty( $slide_item->image_url ) ? apply_filters( 'decorme_translate_single_string', $slide_item->image_url, 'slider 5 section' ) : '';
				$image2 = ! empty( $slide_item->image_url2 ) ? apply_filters( 'decorme_translate_single_string', $slide_item->image_url2, 'slider 5 section' ) : '';
		?>
			<div class="item">
				<?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
				<?php endif; ?>
				<div class="main-slider">
					<div class="main-table">
						<div class="main-table-cell">
							<div class="container">
								<div class="row g-5 align-items-center">
									<div class="col-xxl-7 col-lg-6 col-12">
										<div class="main-content text-left">
											<?php if ( ! empty( $title )) : ?>
												<h5 data-animation="fadeInUp" data-delay="150ms"><?php echo esc_html($title); ?></h5>
											<?php endif; ?>
											
											<?php if ( ! empty( $subtitle )  || ! empty( $subtitle2 )) : ?>	
												<h3 data-animation="fadeInUp" data-delay="200ms"><?php echo esc_html($subtitle); ?> <span class="text-primary"><?php echo esc_html($subtitle2); ?></span></h3>
											<?php endif; ?>
											
											<?php if ( ! empty( $text )) : ?>
												<p data-animation="fadeInUp" data-delay="500ms"><?php echo esc_html($text); ?></p>
											<?php endif; ?>
											
											<?php if ( ! empty( $button )) : ?>
												<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url( $link ); ?>" class="btn btn-primary"><?php echo esc_html($button); ?></a>
											<?php endif; ?>
										</div>
									</div>
									<div class="col-xxl-5 col-lg-6 col-12 d-lg-inline-block d-none">
										<?php if ( ! empty( $image2 ) ) : ?>
											<div class="main-img">
												<img src="<?php echo esc_url( $image2 ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
												<div class="circles-spin">
													<div class="circle-one"></div>
													<div class="circle-two"></div>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } } ?>
	</div>
</section>
<?php	
	endif; }
endif;
if ( function_exists( 'burger_decorme_slider' ) ) {
$section_priority = apply_filters( 'decorme_section_priority', 11, 'burger_decorme_slider' );
add_action( 'decorme_sections', 'burger_decorme_slider', absint( $section_priority ) );
}
	