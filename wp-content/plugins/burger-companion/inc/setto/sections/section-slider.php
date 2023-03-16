<?php  
if ( ! function_exists( 'burger_setto_slider' ) ) :
	function burger_setto_slider() {
	$slider_hs 						= get_theme_mod('slider_hs','1');
	$slider 						= get_theme_mod('slider',setto_get_slider_default());
	if($slider_hs=='1'):
?>		
<div id="setto-slider-section" class="setto-slider-section">
	<div class="slider-content">
		<div class="home-slider swiper-container" id="home-slider_one">
			<div class="swiper-wrapper">
				<?php
					if ( ! empty( $slider ) ) {
					$slider = json_decode( $slider );
					foreach ( $slider as $slide_item ) {
						$title = ! empty( $slide_item->title ) ? apply_filters( 'setto_translate_single_string', $slide_item->title, 'slider section' ) : '';
						$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'setto_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
						$subtitle2 = ! empty( $slide_item->designation ) ? apply_filters( 'setto_translate_single_string', $slide_item->designation, 'slider section' ) : '';
						$text = ! empty( $slide_item->text ) ? apply_filters( 'setto_translate_single_string', $slide_item->text, 'slider section' ) : '';
						$button = ! empty( $slide_item->text2) ? apply_filters( 'setto_translate_single_string', $slide_item->text2,'slider section' ) : '';
						$link = ! empty( $slide_item->link ) ? apply_filters( 'setto_translate_single_string', $slide_item->link, 'slider section' ) : '';
						$image = ! empty( $slide_item->image_url ) ? apply_filters( 'setto_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
				?>
					<div class="swiper-slide">
						<div class="slide-image" style="background-image: url('<?php echo esc_url($image); ?>');">
							<div class="slider-text-info" style="background-color: #ffffff; ">
								<?php if ( ! empty( $title ) || ! empty( $subtitle )) : ?>
									<h2 style="color:#333333;">
										<span><?php echo esc_html($title); ?></span>
										<span><?php echo esc_html($subtitle); ?></span>
									</h2>
								<?php endif; ?>
								<?php if ( ! empty( $subtitle2 ) || ! empty( $text )) : ?>
									<span class="sub-title" style="color:#333333;">
										<span class="sub-title1"><?php echo esc_html($subtitle2); ?></span>
										<span class="sub-title2"><?php echo esc_html($text); ?></span>
									</span>
								<?php endif; ?>
								
								<?php if ( ! empty( $button )) : ?>
									<div class="slider-button">
										<a class="btn btn-style3" href="<?php echo esc_url($link); ?>"><?php echo esc_html($button); ?></a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php } } ?>
			</div>
		</div>
		<div class="swiper-buttons">
			<button class="single-prev-slider"><i class="fa fa-angle-left"></i></button>
			<button class="single-next-slider"><i class="fa fa-angle-right"></i></button>
		</div>
	</div>
</div>
<?php
endif;	}
endif;
if ( function_exists( 'burger_setto_slider' ) ) {
$section_priority = apply_filters( 'setto_section_priority', 11, 'burger_setto_slider' );
add_action( 'setto_sections', 'burger_setto_slider', absint( $section_priority ) );
}