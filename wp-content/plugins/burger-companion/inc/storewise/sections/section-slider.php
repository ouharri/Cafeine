<?php  
	if ( ! function_exists( 'storewise_slider' ) ) :
	function storewise_slider() {
	$slider 						= get_theme_mod('slider',storebiz_get_slider_default());
?>
<div id="first-section" class="first-section mt-lg-2-9 mt-0 slider-three">
	<div class="container">
		<div class="row g-3">
			<div class="col-lg-12 col-12">
				<div class="slider-area">
					<div id="slider-section" class="slider-section">
				        <div class="home-slider owl-carousel owl-theme">
							<?php
								if ( ! empty( $slider ) ) {
								$slider = json_decode( $slider );
								foreach ( $slider as $slide_item ) {
									$storebiz_slide_title = ! empty( $slide_item->title ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->title, 'slider section' ) : '';
									$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
									$text = ! empty( $slide_item->text ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->text, 'slider section' ) : '';
									$button = ! empty( $slide_item->text2) ? apply_filters( 'storebiz_translate_single_string', $slide_item->text2,'slider section' ) : '';
									$storebiz_slide_link = ! empty( $slide_item->link ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->link, 'slider section' ) : '';
									$icon = ! empty( $slide_item->icon_value ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->icon_value, 'slider section' ) : '';
									$image = ! empty( $slide_item->image_url ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
									$open_new_tab = ! empty( $slide_item->open_new_tab ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->open_new_tab, 'slider section' ) : '';
									$align = ! empty( $slide_item->slide_align ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->slide_align, 'slider section' ) : '';
							?>
				        	<div class="item">
				                <?php if ( ! empty( $image ) ) : ?>
									<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $storebiz_slide_title ) ) : ?> alt="<?php echo esc_attr( $storebiz_slide_title ); ?>" title="<?php echo esc_attr( $storebiz_slide_title ); ?>" <?php endif; ?> />
								<?php endif; ?>
				                <div class="main-slider">
				                    <div class="main-table">
				                        <div class="main-table-cell">
				                            <div class="container">                                
				                                <div class="main-content text-<?php echo esc_attr($align); ?>">
													<?php if ( ! empty( $storebiz_slide_title ) ) : ?>
														<h6 data-animation="fadeInUp" data-delay="150ms"> <?php echo esc_html($storebiz_slide_title); ?></h6>
													<?php endif; ?>	
													
													<?php if ( ! empty( $subtitle ) ) : ?>
														<h2 data-animation="fadeInUp" data-delay="200ms"><?php echo wp_kses_post($subtitle); ?></h2>
													<?php endif; ?>	
													
													<?php if ( ! empty( $text ) ) : ?>
														<h2 data-animation="fadeInUp" data-delay="500ms"><?php echo wp_kses_post($text); ?></h2>
													<?php endif; ?>	
													
													<?php if ( ! empty( $button ) ) : ?>
														<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url($storebiz_slide_link); ?>" <?php if($open_new_tab== 'yes' || $open_new_tab== '1') { echo "target='_blank'"; } ?> class="btn btn-primary btn-like-icon"><?php echo wp_kses_post($button); ?></a>
													<?php endif; ?>		
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                </div>
				            </div>
							<?php } } ?>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}
endif;
if ( function_exists( 'storewise_slider' ) ) {
add_action( 'storewise_slider', 'storewise_slider');
}
