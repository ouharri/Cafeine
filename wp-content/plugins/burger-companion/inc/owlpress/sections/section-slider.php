<?php  
	if ( ! function_exists( 'burger_owlpress_slider' ) ) :
	function burger_owlpress_slider() {
	$slider 						= get_theme_mod('slider',owlpress_get_slider_default());
?>		
<section id="slider-section" class="slider-section">
	<div id="home-slider" class="home-slider owl-carousel owl-theme">
		<?php
			if ( ! empty( $slider ) ) {
			$slider = json_decode( $slider );
			foreach ( $slider as $slide_item ) {
				$title = ! empty( $slide_item->title ) ? apply_filters( 'owlpress_translate_single_string', $slide_item->title, 'slider section' ) : '';
				$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'owlpress_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
				$subtitle2 = ! empty( $slide_item->designation ) ? apply_filters( 'owlpress_translate_single_string', $slide_item->designation, 'slider section' ) : '';
				$text = ! empty( $slide_item->text ) ? apply_filters( 'owlpress_translate_single_string', $slide_item->text, 'slider section' ) : '';
				$button = ! empty( $slide_item->text2) ? apply_filters( 'owlpress_translate_single_string', $slide_item->text2,'slider section' ) : '';
				$link = ! empty( $slide_item->link ) ? apply_filters( 'owlpress_translate_single_string', $slide_item->link, 'slider section' ) : '';
				$image = ! empty( $slide_item->image_url ) ? apply_filters( 'owlpress_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
		?>
			<div class="item">
				<?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
				<?php endif; ?>
				<div class="main-slider">
					<div class="main-table">
						<div class="main-table-cell">
							<div class="container">                                
								<div class="main-content text-left">
									<?php if ( ! empty( $title ) ) : ?>
										<h6 data-animation="fadeInUp" data-delay="150ms"><?php echo esc_html($title); ?></h6>
									<?php endif; ?>
									<?php if ( ! empty( $subtitle ) || ! empty( $subtitle2 ) ) : ?>
										<h1 data-animation="fadeInUp" data-delay="200ms"><?php echo esc_html($subtitle); ?> <span class="text-primary"><?php echo esc_html($subtitle2); ?></span></h1>
									<?php endif; ?>
									
									<?php if ( ! empty( $text ) ) : ?>
										<p data-animation="fadeInUp" data-delay="500ms"><?php echo esc_html($text); ?></p>
									<?php endif; ?>
									
									<?php if ( ! empty( $button ) ) : ?>
										<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url($link); ?>" class="btn btn-border-primary btn-shape"><?php echo esc_html($button); ?></a>
									<?php endif; ?>	
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
	}
endif;
if ( function_exists( 'burger_owlpress_slider' ) ) {
$section_priority = apply_filters( 'owlpress_section_priority', 11, 'burger_owlpress_slider' );
add_action( 'owlpress_sections', 'burger_owlpress_slider', absint( $section_priority ) );
}