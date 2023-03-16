<?php 
	if ( ! function_exists( 'burger_spintech_design' ) ) :
	function burger_spintech_design() {
	$hs_design					=	get_theme_mod('hs_design','1');		
	$design_title				= get_theme_mod('design_title','Explore');
	$design_subtitle			= get_theme_mod('design_subtitle','Design & Development');
	$design_description		= get_theme_mod('design_description','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
	$design_contents			= get_theme_mod('design_contents',spintech_get_design_default());
	$design_left_img			= get_theme_mod('design_left_img',BURGER_COMPANION_PLUGIN_URL .'inc/spintech/images/about/design-img.png');
	if($hs_design == '1') { 
?>
	  <section id="design-section" class="design-section st-py-default bg-primary-light design-home">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default wow fadeInUp">
                        <?php if ( ! empty( $design_title ) ) : ?>
							 <span class="badge bg-primary ttl"><?php echo wp_kses_post($design_title); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $design_subtitle ) ) : ?>		
							<h2><?php echo wp_kses_post($design_subtitle); ?></h2>   							
						<?php endif; ?>	
						<?php if ( ! empty( $design_description ) ) : ?>		
							<p><?php echo wp_kses_post($design_description); ?></p>    
						<?php endif; ?>	
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-lg-6 col-12 mb-lg-0 mb-5 wow fadeInLeft">
                    <div class="design-img justify-content-lg-start justify-content-center">
						 <?php if ( ! empty( $design_left_img ) ) : ?>
							<img src="<?php echo esc_url($design_left_img); ?>" class="img-fluid" alt="Spintech">
						<?php endif; ?>		
                    </div>
                </div>
                <div class="col-lg-6 col-12 wow fadeInRight">
                    <div class="row row-cols-1 row-cols-md-2 g-4 design-wrp">
                       <?php
							if ( ! empty( $design_contents ) ) {
							$design_contents = json_decode( $design_contents );
							foreach ( $design_contents as $design_item ) {
								$spintech_design_title = ! empty( $design_item->title ) ? apply_filters( 'spintech_translate_single_string', $design_item->title, 'design section' ) : '';
								$icon = ! empty( $design_item->icon_value) ? apply_filters( 'spintech_translate_single_string', $design_item->icon_value,'design section' ) : '';
						?>
							<div class="col">
								<div class="design-item">
									<div class="design-icon">
										<div class="design-corn">
											<?php if ( ! empty( $icon ) ) {?>
												<i class="fa <?php echo esc_html( $icon ); ?>"></i>
											<?php } ?>
										</div>
									</div>
									<div class="design-content">
										<?php if ( ! empty( $spintech_design_title ) ) : ?>
											<strong class="design-title"><a href="javascript:void(0);"><?php echo esc_html( $spintech_design_title ); ?></a></strong>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php } } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape1"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/clipArt/shape1.png" alt="image"></div>
    </section>
<?php	
	}}
endif;
if ( function_exists( 'burger_spintech_design' ) ) {
$section_priority = apply_filters( 'spintech_section_priority', 14, 'burger_spintech_design' );
add_action( 'spintech_sections', 'burger_spintech_design', absint( $section_priority ) );
}
	