<?php  
if ( ! function_exists( 'burger_decorme_info' ) ) :
	function burger_decorme_info() {
	$info_hs 					= get_theme_mod('info_hs','1');
	$info_contents 				= get_theme_mod('info2_contents',decorme_get_info2_default());
	if($info_hs=='1'):
?>	
<section id="info-section" class="info-section info-three">
	<div class="container">
		<div class="row">
			<div class="col-12 wow fadeInUp">
				<div class="row g-4 info-wrapper">
					<?php
						if ( ! empty( $info_contents ) ) {
						$info_contents = json_decode( $info_contents );
						foreach ( $info_contents as $info_item ) {
							$title = ! empty( $info_item->title ) ? apply_filters( 'decorme_translate_single_string', $info_item->title, 'Info 2 section' ) : '';
							$image = ! empty( $info_item->image_url) ? apply_filters( 'decorme_translate_single_string', $info_item->image_url,'Info 2 section' ) : '';
							$link = ! empty( $info_item->link ) ? apply_filters( 'decorme_translate_single_string', $info_item->link, 'Info section' ) : '';
					?>
						<div class="col-lg-2 col-sm-4 col-12">
							<aside class="widget widget-contact">
								<div class="contact-area">
									<div class="contact-icon">
										<div class="contact-corn">
											<?php if ( ! empty( $image ) ) : ?>
												<img src="<?php echo esc_url($image); ?>">
											<?php endif; ?>
											<?php if ( ! empty( $icon ) ) : ?>
												<i class="fa <?php echo esc_attr($icon); ?>"></i>
											<?php endif; ?>
											<?php if ( ! empty( $link ) ) : ?>
												</a>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</aside>
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
if ( function_exists( 'burger_decorme_info' ) ) {
$section_priority = apply_filters( 'decorme_section_priority', 12, 'burger_decorme_info' );
add_action( 'decorme_sections', 'burger_decorme_info', absint( $section_priority ) );
}