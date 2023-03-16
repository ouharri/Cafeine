<?php  
if ( ! function_exists( 'burger_decorme_service' ) ) :
	function burger_decorme_service() {
	$service_hs 			= get_theme_mod('service_hs','1');	
	$service_title			= get_theme_mod('service_title','Our Services'); 
	$service_subtitle		= get_theme_mod('service_subtitle','Our Services'); 
	$service_description	= get_theme_mod('service_description','<span class="font-weight-normal">We Provide Best</span> Services'); 
	$service_contents 		= get_theme_mod('service_contents',decorme_get_service_default());
	if($service_hs=='1'):
?>		
<section id="service-section" class="service-section service-home st-py-default">
	<div class="container">
		<?php if(!empty($service_title) || !empty($service_subtitle) || !empty($service_description)): ?>
			<div class="row">
				<div class="col-lg-10 col-12 mx-lg-auto mb-5 text-center">
					<div class="theme-heading wow fadeInUp">
						<?php if(!empty($service_title)): ?>
							<span class="placeholder"><?php echo wp_kses_post($service_title); ?></span>
						<?php endif; ?>
						
						<?php if(!empty($service_subtitle)): ?>
							<h5 class="text-primary"><?php echo wp_kses_post($service_subtitle); ?></h5>
						<?php endif; ?>
						
						<?php if(!empty($service_description)): ?>
							<h2 class="mb-0"><span class="font-weight-normal"><?php echo wp_kses_post($service_description); ?></h2>
						<?php endif; ?>
						
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-12 wow fadeInUp">
				<div class="row g-4 service-wrapper">
					<?php
						if ( ! empty( $service_contents ) ) {
						$service_contents = json_decode( $service_contents );
						foreach ( $service_contents as $service_item ) {
							$title = ! empty( $service_item->title ) ? apply_filters( 'decorme_translate_single_string', $service_item->title, 'Service section' ) : '';
							$subtitle = ! empty( $service_item->subtitle ) ? apply_filters( 'decorme_translate_single_string', $service_item->subtitle, 'Service section' ) : '';
							$text = ! empty( $service_item->text ) ? apply_filters( 'decorme_translate_single_string', $service_item->text, 'Service section' ) : '';
							$button = ! empty( $service_item->text2 ) ? apply_filters( 'decorme_translate_single_string', $service_item->text2, 'Service section' ) : '';
							$icon = ! empty( $service_item->icon_value) ? apply_filters( 'decorme_translate_single_string', $service_item->icon_value,'Service section' ) : '';
							$link = ! empty( $service_item->link ) ? apply_filters( 'decorme_translate_single_string', $service_item->link, 'Service section' ) : '';
					?>
						<div class="col-lg-4 col-md-6 col-12">
							<aside class="widget widget-contact">
								<div class="contact-area">
									<div class="contact-icon">
										<div class="contact-corn">
											<?php if ( ! empty( $icon ) ) : ?>
												<i class="fa <?php echo esc_attr($icon); ?>"></i>
											<?php endif; ?>
										</div>
									</div>
									<div class="contact-info">
										<?php if ( ! empty( $title ) || ! empty( $subtitle )) : ?>
											<h6 class="title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?> <span class="text-primary"><?php echo esc_html($subtitle); ?></span></a></h6>
										<?php endif; ?>
										<?php if ( ! empty( $text ) ) : ?>
											<p class="text"><?php echo esc_html($text); ?></p>
										<?php endif; ?>	
										<a href="<?php echo esc_url($link); ?>" class="readmore"><?php echo esc_html($button); ?><i class="fa fa-angle-right ml-1"></i></a>
									</div>
								</div>
								<div class="overlay-content contact-area">
									<div class="contact-icon">
										<div class="contact-corn">
											<?php if ( ! empty( $image ) ) : ?>
												<img src="<?php echo esc_url($image); ?>">
											<?php else: ?>
												<i class="fa <?php echo esc_attr($icon); ?>"></i>
											<?php endif; ?>
										</div>
									</div>
									<div class="contact-info">
										<?php if ( ! empty( $title ) || ! empty( $subtitle )) : ?>
											<h6 class="title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?> <span class="text-primary"><?php echo esc_html($subtitle); ?></span></a></h6>
										<?php endif; ?>
										<?php if ( ! empty( $text ) ) : ?>
											<p class="text"><?php echo esc_html($text); ?></p>
										<?php endif; ?>	
										<a href="<?php echo esc_url($link); ?>" class="readmore"><?php echo esc_html($button); ?><i class="fa fa-angle-right ml-1"></i></a>
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
if ( function_exists( 'burger_decorme_service' ) ) {
$section_priority = apply_filters( 'decorme_section_priority', 13, 'burger_decorme_service' );
add_action( 'decorme_sections', 'burger_decorme_service', absint( $section_priority ) );
}