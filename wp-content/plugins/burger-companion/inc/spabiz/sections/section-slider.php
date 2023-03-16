 <!--===// Start: Slider
    =================================--> 
<?php  
if ( ! function_exists( 'burger_spabiz_slider' ) ) :
	function burger_spabiz_slider() {
	$hs_slider 						= get_theme_mod('hs_slider','1');	
	$slider 						= get_theme_mod('slider',spabiz_get_slider_default());
	if($hs_slider=='1'):
?>	
<section id="slider-section" class="slider-section slider-one">
    <div class="home-slider owl-carousel" id="home-slider">
		<?php
			if ( ! empty( $slider ) ) {
			$slider = json_decode( $slider );
			foreach ( $slider as $slide_item ) {
				$title = ! empty( $slide_item->title ) ? apply_filters( 'spabiz_translate_single_string', $slide_item->title, 'slider section' ) : '';
				$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'spabiz_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
				$subtitle2 = ! empty( $slide_item->subtitle2 ) ? apply_filters( 'spabiz_translate_single_string', $slide_item->subtitle2, 'slider section' ) : '';
				$subtitle3 = ! empty( $slide_item->subtitle3 ) ? apply_filters( 'spabiz_translate_single_string', $slide_item->subtitle3, 'slider section' ) : '';
				$text = ! empty( $slide_item->text ) ? apply_filters( 'spabiz_translate_single_string', $slide_item->text, 'slider section' ) : '';
				$button = ! empty( $slide_item->text2) ? apply_filters( 'spabiz_translate_single_string', $slide_item->text2,'slider section' ) : '';
				$link = ! empty( $slide_item->link ) ? apply_filters( 'spabiz_translate_single_string', $slide_item->link, 'slider section' ) : '';
				$image = ! empty( $slide_item->image_url ) ? apply_filters( 'spabiz_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
		?>
    	<div class="item">
            <div class="main-slider">
                <div class="main-table">
                    <div class="main-table-cell">
                        <div class="container">
                        	<div class="row align-items-center"> 
                        		<div class="col-lg-6 col-sm-6">                          
	                                <div class="main-content text-left">
										<?php if ( ! empty( $title ) ) : ?>
											<span class="sub-title"><i class="fa fa-square"></i> <?php echo esc_html($title); ?></span>
										<?php endif; ?>
										
										<?php if ( ! empty( $subtitle ) || ! empty( $subtitle2 )) : ?>
											<h1><?php echo esc_html($subtitle); ?> <span><?php echo esc_html($subtitle2); ?></span></h1>
										<?php endif; ?>
										
										<?php if ( ! empty( $subtitle3 ) ) : ?>
											<h4><?php echo esc_html($subtitle3); ?></h4>
										<?php endif; ?>
										
										<?php if ( ! empty( $text ) ) : ?>
											<p><?php echo esc_html($text); ?></p>
										<?php endif; ?>
										<?php if ( ! empty( $button ) ) : ?>
											<a href="<?php echo esc_url($link); ?>" class="main-btn btn-effect"><span></span><?php echo esc_html($button); ?></a>
										<?php endif; ?>		
	                                </div>
	                            </div>
	                            <div class="col-lg-6 col-sm-6">
	                            	<div class="img-content">
	                            		<div class="elemt1"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/elemt/e1.png" alt=""></div>
	                            		<!--div class="offer_spa">
	                            			<span><i class="fa fa-circle-o"></i></span>
	                            			<span>Smooth Hair Spa</span>
	                            			<span>$25.00</span>
	                            		</div-->
										<?php if ( ! empty( $image ) ) : ?>
											<div class="main-slide-img"><img src="<?php echo esc_url($image); ?>" alt="" class=""></div>
										<?php endif; ?>
	                            	</div>
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
endif;	}
endif;
if ( function_exists( 'burger_spabiz_slider' ) ) {
$section_priority = apply_filters( 'spabiz_section_priority', 11, 'burger_spabiz_slider' );
add_action( 'spabiz_sections', 'burger_spabiz_slider', absint( $section_priority ) );
}