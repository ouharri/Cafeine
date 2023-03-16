<?php  
if ( ! function_exists( 'burger_spabiz_funfact' ) ) :
	function burger_spabiz_funfact() {
	$hs_funfact 		= get_theme_mod('hs_funfact','1');	
	$funfact_title		= get_theme_mod('funfact_title','<i class="fa fa-square"></i> What we do'); 
	$funfact_subtitle	= get_theme_mod('funfact_subtitle','our funfact'); 
	$funfact_description= get_theme_mod('funfact_description','We are experienced professionals who understand that It funfacts is charging, and are true partners who care about your success experienced professionals'); 
	$funfact_btn_lbl	= get_theme_mod('funfact_btn_lbl','Learn More');
	$funfact_btn_link	= get_theme_mod('funfact_btn_link','#');
	$funfact_contents	= get_theme_mod('funfact_contents',spabiz_get_funfact_default());
	$funfact_bg_img		= get_theme_mod('funfact_bg_img',esc_url(BURGER_COMPANION_PLUGIN_URL .'inc/spabiz/images/funfact/fbg.png'));	
	if($hs_funfact=='1'):
?>	
<section id="funfact-home" class="funfact-section ptb-80 funfact-one funfact-home wow fadeInUp funfact-home" style="background:url(<?php echo esc_url($funfact_bg_img); ?>) ;">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 col-sm-6">
				<div class="funfact-content">
					<?php if ( ! empty( $funfact_title )  || ! empty( $funfact_subtitle ) || ! empty( $funfact_description )) : ?>
						<div class="section-title">
							<?php if ( ! empty( $funfact_title ) ) : ?>
								<h6 class="subtitle"><?php echo wp_kses_post($funfact_title); ?></h6>
							<?php endif; ?>
							
							<?php if ( ! empty( $funfact_subtitle ) ) : ?>
								<h3 class="title"><?php echo wp_kses_post($funfact_subtitle); ?></h3>
							<?php endif; ?>
							
							<?php if ( ! empty( $funfact_description ) ) : ?>
								<p class="text"><?php echo wp_kses_post($funfact_description); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $funfact_btn_lbl ) ) : ?>
						<a href="<?php echo esc_url($funfact_btn_link); ?>" class="main-btn btn-effect"><?php echo wp_kses_post($funfact_btn_lbl); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-6 col-sm-6">
				<div class="counter-area">
					<?php
					if ( ! empty( $funfact_contents ) ) {
						$funfact_contents = json_decode( $funfact_contents );
						foreach ( $funfact_contents as $funfact_item ) {
							$title = ! empty( $funfact_item->title ) ? apply_filters( 'spabiz_translate_single_string', $funfact_item->title, 'Funfact section' ) : '';
							$subtitle = ! empty( $funfact_item->subtitle ) ? apply_filters( 'spabiz_translate_single_string', $funfact_item->subtitle, 'Funfact section' ) : '';
							$text = ! empty( $funfact_item->text ) ? apply_filters( 'spabiz_translate_single_string', $funfact_item->text, 'Funfact section' ) : '';
							$icon = ! empty( $funfact_item->icon_value ) ? apply_filters( 'spabiz_translate_single_string', $funfact_item->icon_value, 'Funfact section' ) : '';
					?>
						<div class="funfact-item">
							<?php if ( ! empty( $icon ) ) : ?>
								<div class="funfact-icon">
									<i class="fa <?php echo esc_attr($icon); ?>"></i>
								</div>
							<?php endif; ?>
							<div class="funfact-count">
								<?php if ( ! empty( $title )  || ! empty( $subtitle )) : ?>
									<h3><span class="counter"><?php echo esc_html($title); ?></span><span><?php echo esc_html($subtitle); ?></span></h3>
								<?php endif; ?>
								
								<?php if ( ! empty( $text ) ) : ?>	
									<p><?php echo esc_html($text); ?></p>
								<?php endif; ?>	
							</div>
						</div>
					<?php } } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php	
endif;	}
endif;
if ( function_exists( 'burger_spabiz_funfact' ) ) {
$section_priority = apply_filters( 'spabiz_section_priority', 14, 'burger_spabiz_funfact' );
add_action( 'spabiz_sections', 'burger_spabiz_funfact', absint( $section_priority ) );
}