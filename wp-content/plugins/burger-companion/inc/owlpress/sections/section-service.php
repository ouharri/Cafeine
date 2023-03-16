<?php  
if ( ! function_exists( 'burger_owlpress_service' ) ) :
	function burger_owlpress_service() {
	$hs_service 			= get_theme_mod('hs_service','1');	
	$service_title 			= get_theme_mod('service_title','What We Do');
	$service_subtitle		= get_theme_mod('service_subtitle','Our <span class="text-primary">Services</span>'); 
	$service_description	= get_theme_mod('service_description','Lorem Ipsum. Proin Gravida Nibh Vel Velit Auctor Aliquet');
	$service_contents		= get_theme_mod('service_contents',owlpress_get_service_default());
	$service_sec_column		= get_theme_mod('service_sec_column','3');	
	if($hs_service=='1'):
?>	
<section id="service-section" class="service-section service-home st-py-default shapes-section bg-primary-light">
	<div class="container">
		<?php if(!empty($service_title) || !empty($service_subtitle) || !empty($service_description)): ?>
			<div class="row">
				<div class="col-lg-6 col-12 mx-lg-auto text-center">
					<div class="heading-default wow fadeInUp">
						<?php if(!empty($service_title)): ?>
							<h6><?php echo wp_kses_post($service_title); ?></h6>
						<?php endif; ?>	
						
						<?php if(!empty($service_subtitle)): ?>
							<h4><?php echo wp_kses_post($service_subtitle); ?></h4>
							<?php do_action('owlpress_section_seprator'); ?>
						<?php endif; ?>	
						
						<?php if(!empty($service_description)): ?>
							<p><?php echo wp_kses_post($service_description); ?></p>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row g-4 wow fadeInUp hm-serv-content">
			<?php
			if ( ! empty( $service_contents ) ) {
			$service_contents = json_decode( $service_contents );
			foreach ( $service_contents as $service_item ) {
				$title = ! empty( $service_item->title ) ? apply_filters( 'owlpress_translate_single_string', $service_item->title, 'Service section' ) : '';
				$text = ! empty( $service_item->text ) ? apply_filters( 'owlpress_translate_single_string', $service_item->text, 'Service section' ) : '';
				$link = ! empty( $service_item->link ) ? apply_filters( 'owlpress_translate_single_string', $service_item->link, 'Service section' ) : '';
				$icon = ! empty( $service_item->icon_value ) ? apply_filters( 'owlpress_translate_single_string', $service_item->icon_value, 'Service section' ) : '';
		?>
				<div class="col-lg-3 col-md-6 col-12">
					<div class="service-item">
						<?php if(!empty($icon)): ?>
							<div class="service-icon">
								<i class="fa <?php echo esc_attr($icon); ?>"></i>
							</div>
						<?php endif; ?>		
						<div class="service-content">
							<?php if(!empty($title)): ?>
								<h5><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h5>
							<?php endif; ?>	
							
							<?php if(!empty($text)): ?>	
								<p><?php echo esc_html($text); ?></p>
							<?php endif; ?>	
							<div class="service-btn"><a href="<?php echo esc_url($link); ?>" class="btn btn-link btn-like-icon"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/services/arrow.png" /></a></div>
						</div>
					</div>
				</div>
			<?php } }?>
		</div>
	</div>
	<div class="lg-shape1 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/services/shape1.png" alt="image"></div>
	<div class="lg-shape2 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/services/shape2.png" alt="image"></div>
	<div class="lg-shape3 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/services/shape3.png" alt="image"></div>
	<div class="lg-shape4 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/services/shape4.png" alt="image"></div>
	<div class="lg-shape5 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/services/shape5.png" alt="image"></div>
</section>
<?php
endif;	
	}
endif;
if ( function_exists( 'burger_owlpress_service' ) ) {
$section_priority = apply_filters( 'owlpress_section_priority', 12, 'burger_owlpress_service' );
add_action( 'owlpress_sections', 'burger_owlpress_service', absint( $section_priority ) );
}