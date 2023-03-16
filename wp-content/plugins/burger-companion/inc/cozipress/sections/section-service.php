<?php 
	if ( ! function_exists( 'burger_cozipress_service' ) ) :
	function burger_cozipress_service() {
	$hs_service					= get_theme_mod('hs_service','1');	
	$service_title				= get_theme_mod('service_title','What We Do');
	$service_subtitle			= get_theme_mod('service_subtitle','Our <span class="text-primary">Services</span>');
	$service_description		= get_theme_mod('service_description','This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.');
	$service_contents			= get_theme_mod('service_contents',cozipress_get_service_default());
if($hs_service == '1'){	
?>
	<section id="service-section" class="service-section service-home st-py-default shapes-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default wow fadeInUp">
						<?php if ( ! empty( $service_title ) ) : ?>
							 <span class="badge ttl"><?php echo wp_kses_post($service_title); ?></span>
						<?php endif; ?>
						
						<?php if ( ! empty( $service_subtitle ) ) : ?>		
							<h2><?php echo wp_kses_post($service_subtitle); ?></h2>					
						<?php endif; ?>	
						
						<?php if ( ! empty( $service_description ) ) : ?>		
							<p><?php echo wp_kses_post($service_description); ?></p>    
						<?php endif; ?>	
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-12 mx-lg-auto">
                    <div class="row row-cols-1 row-cols-lg-3 row-cols-md-2 g-4 wow fadeInUp hm-serv-content">
						<?php
							if ( ! empty( $service_contents ) ) {
							$service_contents = json_decode( $service_contents );
							foreach ( $service_contents as $service_item ) {
								$cozipress_service_title = ! empty( $service_item->title ) ? apply_filters( 'cozipress_translate_single_string', $service_item->title, 'service section' ) : '';
								$text = ! empty( $service_item->text ) ? apply_filters( 'cozipress_translate_single_string', $service_item->text, 'service section' ) : '';
								$icon = ! empty( $service_item->icon_value) ? apply_filters( 'cozipress_translate_single_string', $service_item->icon_value,'service section' ) : '';
								$button = ! empty( $service_item->text2) ? apply_filters( 'cozipress_translate_single_string', $service_item->text2,'service section' ) : '';
								$cozipress_serv_link = ! empty( $service_item->link ) ? apply_filters( 'cozipress_translate_single_string', $service_item->link, 'service section' ) : '';
								$image = ! empty( $service_item->image_url ) ? apply_filters( 'cozipress_translate_single_string', $service_item->image_url, 'service section' ) : '';
						?>
							<div class="col">
								<div class="theme-item">
									<div class="theme-item-overlay">
										<?php if ( ! empty( $image ) ) : ?>
											<img src="<?php echo esc_url( $image ); ?>" />
										<?php endif; ?>	
									</div>
									
									<?php if ( ! empty( $icon ) ) : ?>
										<div class="theme-icon">
											<i class="fa <?php echo esc_attr( $icon ); ?>"></i>
										</div>
									<?php endif; ?>	
									<div class="theme-content">
										<?php if ( ! empty( $cozipress_service_title ) ) : ?>
											<h4 class="theme-title"><a href="<?php echo esc_url( $cozipress_serv_link ); ?>"><?php echo esc_html( $cozipress_service_title ); ?></a></h4>
										<?php endif; ?>	
										
										<?php if ( ! empty( $text ) ) : ?>
											<p><?php echo esc_html( $text ); ?></p>
										<?php endif; ?>		
											
										<?php if ( ! empty( $button ) ) : ?>	
											<a href="<?php echo esc_url( $cozipress_serv_link ); ?>" class="btn btn-primary btn-like-icon"><?php echo esc_html( $button ); ?> <span class="bticn"><i class="fa fa-arrow-right"></i></span></a>
										<?php endif; ?>	
										
									</div>
								</div>
							</div>
						<?php } } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg-shape1"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape1.png" alt="image"></div>
        <div class="lg-shape2"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape2.png" alt="image"></div>
        <div class="lg-shape3"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape3.png" alt="image"></div>
        <div class="lg-shape4"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape4.png" alt="image"></div>
    </section>
<?php	
	}}
endif;
if ( function_exists( 'burger_cozipress_service' ) ) {
$section_priority = apply_filters( 'cozipress_section_priority', 13, 'burger_cozipress_service' );
add_action( 'cozipress_sections', 'burger_cozipress_service', absint( $section_priority ) );
}	