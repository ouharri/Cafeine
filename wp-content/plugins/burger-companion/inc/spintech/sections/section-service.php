<?php 
	if ( ! function_exists( 'burger_spintech_service' ) ) :
	function burger_spintech_service() {
	$hs_service					=	get_theme_mod('hs_service','1');		
	$service_title				= get_theme_mod('service_title','Explore');
	$service_subtitle			= get_theme_mod('service_subtitle','Our <span class="text-primary">Services</span>');
	$service_description		= get_theme_mod('service_description','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
	$service_contents			= get_theme_mod('service_contents',spintech_get_service_default());
	if($hs_service == '1') { 
?>
	    <section id="service-section" class="service-section service-home st-py-default service-home">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default wow fadeInUp">
						<?php if ( ! empty( $service_title ) ) : ?>
							 <span class="badge bg-primary ttl"><?php echo wp_kses_post($service_title); ?></span>
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
            <div class="row hm-serv-content">
                <div class="col-lg-10 col-12 mx-lg-auto">
                    <div class="row row-cols-1 row-cols-md-3 g-4 wow fadeInUp">
						<?php
							if ( ! empty( $service_contents ) ) {
							$service_contents = json_decode( $service_contents );
							foreach ( $service_contents as $service_item ) {
								$spintech_service_title = ! empty( $service_item->title ) ? apply_filters( 'spintech_translate_single_string', $service_item->title, 'service section' ) : '';
								$text = ! empty( $service_item->text ) ? apply_filters( 'spintech_translate_single_string', $service_item->text, 'service section' ) : '';
								$icon = ! empty( $service_item->icon_value) ? apply_filters( 'spintech_translate_single_string', $service_item->icon_value,'service section' ) : '';
								$button = ! empty( $service_item->text2) ? apply_filters( 'spintech_translate_single_string', $service_item->text2,'service section' ) : '';
								$spintech_serv_link = ! empty( $service_item->link ) ? apply_filters( 'spintech_translate_single_string', $service_item->link, 'service section' ) : '';
						?>
							<div class="col">
								<div class="theme-item">
									<div class="theme-icon">
										<span class="theme-circle"></span>
										<div class="theme-corn">
											<?php if ( ! empty( $icon ) ) {?>
												<i class="fa <?php echo esc_html( $icon ); ?>"></i>
											<?php } ?>
											<div class="circles-spin">
												<div class="circle-one"></div>
												<div class="circle-two"></div>
											</div>
										</div>
									</div>
									<div class="theme-content">
										<?php if ( ! empty( $spintech_service_title ) ) : ?>
											<h5 class="theme-title"><a href="<?php echo esc_url( $spintech_serv_link ); ?>"><?php echo esc_html( $spintech_service_title ); ?></a></h5>
										<?php endif; ?>
										<?php if ( ! empty( $text ) ) : ?>
											<p><?php echo esc_html( $text ); ?></p>
										<?php endif; ?>
										<?php if ( ! empty( $button ) ) : ?>	
											<div class="theme-link"><a href="<?php echo esc_url( $spintech_serv_link ); ?>" class="read-link"><?php echo esc_html( $button ); ?></a></div>
										<?php endif; ?>	
									</div>
								</div>
							</div>
						<?php }} ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape2"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/clipArt/shape2.png" alt="image"></div>
        <div class="shape3"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/clipArt/shape3.png" alt="image"></div>
        <div class="shape4"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/clipArt/shape4.png" alt="image"></div>
        <div class="shape5"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/clipArt/shape5.png" alt="image"></div>
    </section>
	
	
<?php	
	}}
endif;
if ( function_exists( 'burger_spintech_service' ) ) {
$section_priority = apply_filters( 'spintech_section_priority', 13, 'burger_spintech_service' );
add_action( 'spintech_sections', 'burger_spintech_service', absint( $section_priority ) );
}