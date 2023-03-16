 <!--===// Start: Slider
    =================================--> 
<?php  
if ( ! function_exists( 'burger_seokart_slider' ) ) :
	function burger_seokart_slider() {
	$slider = get_theme_mod('slider',seokart_get_slider_default());	
?>	
	<section id="slider-section" class="slider-area">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/slider-shap-1.png" alt="" class="shape-1">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/ball-shap.png" alt="" class="shape-2">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/slider-shap-3.png" alt="" class="shape-3">
        <div class="container">
            <div class="owl-carousel slider-inner">
				<?php
					if ( ! empty( $slider ) ) {
					$slider = json_decode( $slider );
					foreach ( $slider as $slide_item ) {
						$seokart_slide_title = ! empty( $slide_item->title ) ? apply_filters( 'seokart_translate_single_string', $slide_item->title, 'slider section' ) : '';
						$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'seokart_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
						$text = ! empty( $slide_item->text ) ? apply_filters( 'seokart_translate_single_string', $slide_item->text, 'slider section' ) : '';
						$button = ! empty( $slide_item->text2) ? apply_filters( 'seokart_translate_single_string', $slide_item->text2,'slider section' ) : '';
						$seokart_slide_link = ! empty( $slide_item->link ) ? apply_filters( 'seokart_translate_single_string', $slide_item->link, 'slider section' ) : '';
						$image = ! empty( $slide_item->image_url ) ? apply_filters( 'seokart_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
						$align = ! empty( $slide_item->slide_align ) ? apply_filters( 'seokart_translate_single_string', $slide_item->slide_align, 'slider section' ) : '';
				?>
					<div class="item"> 
						<div class="row text-<?php echo esc_attr($align); ?>">
							<div class="col-lg-7">
							
								<?php if ( ! empty( $seokart_slide_title ) ) : ?>
									<span class="badge-count" data-animation="fadeInUp" data-wow-delay="0.2s"><?php echo esc_html($seokart_slide_title);?></span>
								<?php endif; ?>
								
								<?php if ( ! empty( $subtitle ) ) : ?>
									<h2 data-animation="fadeInUp" data-delay="0.4s"><?php echo esc_html($subtitle);?> </h2>
								<?php endif; ?>	
								
								<?php if ( ! empty( $text ) ) : ?>
									<p data-animation="fadeInUp" data-delay="0.6s"><?php echo esc_html($text);?> </p>
								<?php endif; ?>	
								
								
								<div class="button-group" data-animation="fadeInUp" data-wow-delay="0.8s">
									<?php if ( ! empty( $button ) ) : ?>
										<a href="<?php echo esc_url($seokart_slide_link);?>" class="theme-button"><?php echo esc_html($button);?></a>
									<?php endif; ?>	
								</div>
							</div>
							<?php if ( ! empty( $image ) ) : ?>
								<div class="col-lg-5" data-animation="fadeInRight">
									<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $seokart_slide_title ) ) : ?> alt="<?php echo esc_attr( $seokart_slide_title ); ?>" title="<?php echo esc_attr( $seokart_slide_title ); ?>" <?php endif; ?> />
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php } } ?>
            </div>
        </div> 
    </section> 
<?php	
	}
endif;
if ( function_exists( 'burger_seokart_slider' ) ) {
$section_priority = apply_filters( 'seokart_section_priority', 11, 'burger_seokart_slider' );
add_action( 'seokart_sections', 'burger_seokart_slider', absint( $section_priority ) );
}	