<?php  
if ( ! function_exists( 'burger_appetizer_service' ) ) :
	function burger_appetizer_service() {
	$hs_service 			= get_theme_mod('hs_service','1');
	$service_title 			= get_theme_mod('service_title','Special Package');
	$service_description	= get_theme_mod('service_description','Find our all best packages'); 
	$service_contents		= get_theme_mod('service_contents',appetizer_get_service_default());
	if($hs_service=='1'):
?>		
<section id="service-section" class="service-section service-home st-py-default shapes-section">
	<div class="container">
		<?php if(!empty($service_title) || !empty($service_description)): ?>
			<div class="row">
				<div class="col-lg-6 col-12 mx-lg-auto mb-5 text-center">
					<div class="heading-default wow fadeInUp">
						<?php if(!empty($service_title)): ?>
							<h2><?php echo wp_kses_post($service_title); ?></h2>
						<?php endif; ?>
						<?php do_action('appetizer_section_seprator'); ?>
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
				$title = ! empty( $service_item->title ) ? apply_filters( 'appetizer_translate_single_string', $service_item->title, 'Service section' ) : '';
				$subtitle = ! empty( $service_item->subtitle ) ? apply_filters( 'appetizer_translate_single_string', $service_item->subtitle, 'Service section' ) : '';
				$button = ! empty( $service_item->text2) ? apply_filters( 'appetizer_translate_single_string', $service_item->text2,'Service section' ) : '';
				$link = ! empty( $service_item->link ) ? apply_filters( 'appetizer_translate_single_string', $service_item->link, 'Service section' ) : '';
				$image = ! empty( $service_item->image_url ) ? apply_filters( 'appetizer_translate_single_string', $service_item->image_url, 'Service section' ) : '';
		?>
			<div class="col-lg-3 col-md-6 col-12">
				<div class="service-item">
					<div class="service-item-overlay">
						<?php if ( ! empty( $image ) ) : ?>
							<img src="<?php echo esc_url( $image ); ?>" />
						<?php endif; ?>
						<?php if ( ! empty( $subtitle ) ) : ?>
							<span class="badge"><?php echo esc_html( $subtitle ); ?></span>
						<?php endif; ?>	
					</div>
					<div class="service-content">
						<?php if ( ! empty( $title ) ) : ?>
							<h5><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a></h5>
						<?php endif; ?>	
						
						<?php if ( ! empty( $button ) ) : ?>
							<a href="<?php echo esc_url( $link ); ?>" class="btn btn-link btn-like-icon"><?php echo esc_html( $button ); ?>  <span class="bticn"><i class="fa fa-chevron-right"></i></span></a>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		<?php } } ?>
		</div>
	</div>
	<div class="lg-shape1 clipartss"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/appetizer/images/clipArt/services/shape1.png" alt="image"></div>
	<div class="lg-shape2 clipartss"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/appetizer/images/clipArt/services/shape2.png" alt="image"></div>
	<div class="lg-shape3 clipartss"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/appetizer/images/clipArt/services/shape3.png" alt="image"></div>
	<div class="lg-shape4 clipartss"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>inc/appetizer/images/clipArt/services/shape4.png" alt="image"></div>
</section>	
<?php
endif;	
	}
endif;
if ( function_exists( 'burger_appetizer_service' ) ) {
$section_priority = apply_filters( 'appetizer_section_priority', 12, 'burger_appetizer_service' );
add_action( 'appetizer_sections', 'burger_appetizer_service', absint( $section_priority ) );
}