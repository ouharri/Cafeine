<?php  
if ( ! function_exists( 'burger_setto_slider' ) ) :
	function burger_setto_slider() {
	$slider_hs 						= get_theme_mod('slider_hs','1');	
	$slider 		= get_theme_mod('slider5',setto_get_slider5_default());
	if($slider_hs=='1'):
?>		
<div id="setto-slider-section" class="setto-slider-section slider-four">
	<div class="slider-area">
		<div class="owl-carousel owl-theme" id="home-slider_five">
			<?php
				if ( ! empty( $slider ) ) {
				$slider = json_decode( $slider );
				foreach ( $slider as $slide_item ) {
					$title = ! empty( $slide_item->title ) ? apply_filters( 'setto_translate_single_string', $slide_item->title, 'slider section' ) : '';
					$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'setto_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
					$subtitle2 = ! empty( $slide_item->subtitle2 ) ? apply_filters( 'setto_translate_single_string', $slide_item->subtitle2, 'slider section' ) : '';
					$button = ! empty( $slide_item->text2 ) ? apply_filters( 'setto_translate_single_string', $slide_item->text2, 'slider section' ) : '';
					$link = ! empty( $slide_item->link ) ? apply_filters( 'setto_translate_single_string', $slide_item->link, 'slider section' ) : '';
					$image = ! empty( $slide_item->image_url ) ? apply_filters( 'setto_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
			?>
				<div class="item">
					<div class="slider-item  slider-content-left" style="background-image: url('<?php echo esc_url($image); ?>');">
						<div class="slider-text">
							<?php if ( ! empty( $title )) : ?>
								<span class="sub-title" style=""><?php echo esc_html($title); ?></span>
							<?php endif; ?>	
							
							<?php if ( ! empty( $subtitle )  || ! empty( $subtitle2 )) : ?>
								<h2 class="title" style="color:#333333;">
									<span><?php echo esc_html($subtitle); ?></span>
									<span><?php echo esc_html($subtitle2); ?></span>
								</h2>
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
</div>
<?php
endif;	}
endif;
if ( function_exists( 'burger_setto_slider' ) ) {
$section_priority = apply_filters( 'setto_section_priority', 11, 'burger_setto_slider' );
add_action( 'setto_sections', 'burger_setto_slider', absint( $section_priority ) );
}