 <!--===// Start: Slider
    =================================--> 
<?php  
if ( ! function_exists( 'burger_appetizer_slider' ) ) :
	function burger_appetizer_slider() {	
	$slider 						= get_theme_mod('slider',appetizer_get_slider_default());
	$slider_arrow_left				='<img src="'.esc_url(get_template_directory_uri() .'/assets/images/icon_gif/arrow-left.gif').'">';
	$slider_arrow_right				='<img src="'.esc_url(get_template_directory_uri() .'/assets/images/icon_gif/arrow-right.gif').'">';
	$settings=array('arrowLeft'=>$slider_arrow_left,'arrowRight'=>$slider_arrow_right);
	
	wp_register_script('appetizer-slider',get_template_directory_uri().'/assets/js/homepage/slider.js',array('jquery'));
	wp_localize_script('appetizer-slider','slider_settings',$settings);
	wp_enqueue_script('appetizer-slider');	
?>	
<section id="slider-section" class="slider-section">
	<?php
		if ( ! empty( $slider ) ) {
		$slider = json_decode( $slider );
	?>
	<div id="home-slider" class="home-slider owl-carousel owl-theme">
		<?php
			foreach ( $slider as $slide_item ) {
				$appetizer_slide_title = ! empty( $slide_item->title ) ? apply_filters( 'appetizer_translate_single_string', $slide_item->title, 'slider section' ) : '';
				$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'appetizer_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
				$text = ! empty( $slide_item->text ) ? apply_filters( 'appetizer_translate_single_string', $slide_item->text, 'slider section' ) : '';
				$button = ! empty( $slide_item->text2) ? apply_filters( 'appetizer_translate_single_string', $slide_item->text2,'slider section' ) : '';
				$link = ! empty( $slide_item->link ) ? apply_filters( 'appetizer_translate_single_string', $slide_item->link, 'slider section' ) : '';
				$image = ! empty( $slide_item->image_url ) ? apply_filters( 'appetizer_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
				$align = ! empty( $slide_item->slide_align ) ? apply_filters( 'appetizer_translate_single_string', $slide_item->slide_align, 'slider section' ) : '';
		?>
			<div class="item">
				<?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $appetizer_slide_title ) ) : ?> alt="<?php echo esc_attr( $appetizer_slide_title ); ?>" title="<?php echo esc_attr( $appetizer_slide_title ); ?>" <?php endif; ?> />
				<?php endif; ?>
				<div class="main-slider">
					<div class="main-table">
						<div class="main-table-cell">
							<div class="container">                                
								<div class="main-content text-<?php echo esc_attr($align); ?>">
									<?php if ( ! empty( $appetizer_slide_title ) ) : ?>
										<h4 data-animation="fadeInUp" data-delay="150ms"><?php echo esc_html($appetizer_slide_title); ?></h4>
									<?php endif; ?>
									
									<?php if ( ! empty( $subtitle ) ) : ?>
										<span data-animation="fadeInUp" data-delay="180ms" class="hr-line"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hr-line-white.png"></span>
										<h1 data-animation="fadeInUp" data-delay="200ms"><?php echo esc_html($subtitle); ?></h1>
									<?php endif; ?>
									
									<?php if ( ! empty( $text ) ) : ?>
										<p data-animation="fadeInUp" data-delay="500ms"><?php echo esc_html($text); ?> </p>
									<?php endif; ?>	
									
									<?php if ( ! empty( $button ) ) : ?>
										<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url($link); ?>" class="btn btn-primary" data-text="<?php echo esc_attr($button); ?>"><span><?php echo esc_html($button); ?></span></a>
									<?php endif; ?>	
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div id="home-slider-thumbs" class="home-slider-thumbs owl-carousel owl-theme">
		<?php
			foreach ( $slider as $slide_item ) {
				$price = ! empty( $slide_item->designation) ? apply_filters( 'appetizer_translate_single_string', $slide_item->designation,'slider section' ) : '';
				$image = ! empty( $slide_item->image_url ) ? apply_filters( 'appetizer_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
		?>
			<div class="item">
				<div class="thumb-content">
					<?php if ( ! empty( $price ) ) : ?>
						<div class="price"><?php echo esc_html( $price ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $image ) ) : ?>
						<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $appetizer_slide_title ) ) : ?> alt="<?php echo esc_attr( $appetizer_slide_title ); ?>" title="<?php echo esc_attr( $appetizer_slide_title ); ?>" <?php endif; ?> />
					<?php endif; ?>
				</div>
			</div>
		<?php }  ?>
	</div>
	<?php } ?>
</section>
<?php
	}
endif;
if ( function_exists( 'burger_appetizer_slider' ) ) {
$section_priority = apply_filters( 'appetizer_section_priority', 11, 'burger_appetizer_slider' );
add_action( 'appetizer_sections', 'burger_appetizer_slider', absint( $section_priority ) );
}
	