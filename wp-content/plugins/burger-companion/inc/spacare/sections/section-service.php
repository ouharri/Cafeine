<?php  
if ( ! function_exists( 'burger_spabiz_service' ) ) :
	function burger_spabiz_service() {
	$hs_service 		= get_theme_mod('hs_service','1');
	$service_title		= get_theme_mod('service_title','<i class="fa fa-square"></i> What we do'); 
	$service_subtitle	= get_theme_mod('service_subtitle','our service'); 
	$service_description= get_theme_mod('service_description','We are experienced professionals who understand that It services is charging, and are true partners who care about your success experienced professionals'); 
	$service_contents	= get_theme_mod('service_contents',spabiz_get_service_default());
	if($hs_service=='1'):
?>	
<section id="service-home" class="service-section service-two ptb-80 wow fadeInUp service-home">
	<div class="container">
		<?php if ( ! empty( $service_title )  || ! empty( $service_subtitle ) || ! empty( $service_description )) : ?>
			<div class="section-title">
				<?php if ( ! empty( $service_title ) ) : ?>
					<h6 class="subtitle"><?php echo wp_kses_post($service_title); ?></h6>
				<?php endif; ?>
				
				<?php if ( ! empty( $service_subtitle ) ) : ?>
					<h3 class="title"><?php echo wp_kses_post($service_subtitle); ?></h3>
				<?php endif; ?>
				
				<?php if ( ! empty( $service_description ) ) : ?>
					<p class="text"><?php echo wp_kses_post($service_description); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="row hm-serv-content">
			<?php
			if ( ! empty( $service_contents ) ) {
				$service_contents = json_decode( $service_contents );
				foreach ( $service_contents as $service_item ) {
					$title = ! empty( $service_item->title ) ? apply_filters( 'spabiz_translate_single_string', $service_item->title, 'Service section' ) : '';
					$text = ! empty( $service_item->text ) ? apply_filters( 'spabiz_translate_single_string', $service_item->text, 'Service section' ) : '';
					$button = ! empty( $service_item->text2 ) ? apply_filters( 'spabiz_translate_single_string', $service_item->text2, 'Service section' ) : '';
					$link = ! empty( $service_item->link ) ? apply_filters( 'spabiz_translate_single_string', $service_item->link, 'Service section' ) : '';
					$icon = ! empty( $service_item->icon_value ) ? apply_filters( 'spabiz_translate_single_string', $service_item->icon_value, 'Service section' ) : '';
			?>
				<div class="col-lg-3 col-sm-6 mb-4">
					<div class="main-service">
						<div class="service">
							<?php if ( ! empty( $icon ) ) : ?>
								<div class="service-icon">
									<i class="fa <?php echo esc_attr($icon); ?>"></i>
									<i class="fa <?php echo esc_attr($icon); ?>"></i>
								</div>
							<?php endif; ?>
							<div class="service-content">
								<?php if ( ! empty( $title ) ) : ?>
									<h4><?php echo esc_html($title); ?></h4>
								<?php endif; ?>
							</div>
						</div>
						<div class="effect-box">
							<div class="effect-inner">
								<div class="service">
									<?php if ( ! empty( $icon ) ) : ?>
										<div class="service-icon">
											<i class="fa <?php echo esc_attr($icon); ?>"></i>
											<i class="fa <?php echo esc_attr($icon); ?>"></i>
										</div>
									<?php endif; ?>
									<div class="service-content">
										<?php if ( ! empty( $title ) ) : ?>
											<h4><?php echo esc_html($title); ?></h4>
										<?php endif; ?>
										
										<?php if ( ! empty( $text ) ) : ?>
											<p><?php echo esc_html($text); ?></p>
										<?php endif; ?>
										
										<?php if ( ! empty( $button ) ) : ?>
											<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($button); ?></a>
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
</section>
<?php	
endif;	}
endif;
if ( function_exists( 'burger_spabiz_service' ) ) {
$section_priority = apply_filters( 'spabiz_section_priority', 13, 'burger_spabiz_service' );
add_action( 'spabiz_sections', 'burger_spabiz_service', absint( $section_priority ) );
}