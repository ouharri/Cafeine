 <!--===// Start: Slider
    =================================--> 
<?php  
	if ( ! function_exists( 'burger_cozipress_slider' ) ) :
	function burger_cozipress_slider() {
	$slider 						= get_theme_mod('slider',cozipress_get_slider_default());	
?>	
	<section id="slider-section" class="slider-section">
        <div class="home-slider owl-carousel owl-theme">
			<?php
				if ( ! empty( $slider ) ) {
				$slider = json_decode( $slider );
				foreach ( $slider as $slide_item ) {
					$cozipress_slide_title = ! empty( $slide_item->title ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->title, 'slider section' ) : '';
					$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
					$text = ! empty( $slide_item->text ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->text, 'slider section' ) : '';
					$button = ! empty( $slide_item->text2) ? apply_filters( 'cozipress_translate_single_string', $slide_item->text2,'slider section' ) : '';
					$cozipress_slide_link = ! empty( $slide_item->link ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->link, 'slider section' ) : '';
					$icon = ! empty( $slide_item->icon_value ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->icon_value, 'slider section' ) : '';
					$image = ! empty( $slide_item->image_url ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
					$open_new_tab = ! empty( $slide_item->open_new_tab ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->open_new_tab, 'slider section' ) : '';
					$align = ! empty( $slide_item->slide_align ) ? apply_filters( 'cozipress_translate_single_string', $slide_item->slide_align, 'slider section' ) : '';
			?>
        	<div class="item">
                <?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $cozipress_slide_title ) ) : ?> alt="<?php echo esc_attr( $cozipress_slide_title ); ?>" title="<?php echo esc_attr( $cozipress_slide_title ); ?>" <?php endif; ?> />
				<?php endif; ?>
                <div class="main-slider">
                    <div class="main-table">
                        <div class="main-table-cell">
                            <div class="container">                                
                                <div class="main-content text-<?php echo esc_attr($align); ?>">
									<?php if ( ! empty( $cozipress_slide_title ) ) : ?>
										<h4 data-animation="fadeInUp" data-delay="150ms"><i class="fa fa-pencil"></i> <?php echo esc_html($cozipress_slide_title); ?></h4>
									<?php endif; ?>	
									
									<?php if ( ! empty( $subtitle ) ) : ?>
										<h1 data-animation="fadeInUp" data-delay="200ms"><?php echo wp_kses_post($subtitle); ?></h1>
									<?php endif; ?>	
									
									<?php if ( ! empty( $text ) ) : ?>
										<p data-animation="fadeInUp" data-delay="500ms"><?php echo wp_kses_post($text); ?></p>
									<?php endif; ?>	
									
									<?php if ( ! empty( $button ) ) : ?>
										<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url($cozipress_slide_link); ?>" <?php if($open_new_tab== 'yes' || $open_new_tab== '1') { echo "target='_blank'"; } ?> class="btn btn-primary btn-like-icon"><?php echo wp_kses_post($button); ?> <span class="bticn"><i class="fa <?php echo esc_attr($icon); ?>"></i></span></a>
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
if ( function_exists( 'burger_cozipress_slider' ) ) {
$section_priority = apply_filters( 'cozipress_section_priority', 11, 'burger_cozipress_slider' );
add_action( 'cozipress_sections', 'burger_cozipress_slider', absint( $section_priority ) );
}
	