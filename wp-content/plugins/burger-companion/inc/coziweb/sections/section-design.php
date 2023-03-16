<?php 
	if ( ! function_exists( 'burger_cozipress_design' ) ) :
	function burger_cozipress_design() {
	$hs_design					= get_theme_mod('hs_design','1');		
	$design_title				= get_theme_mod('design_title','We Are Here');
	$design_subtitle			= get_theme_mod('design_subtitle','About <span class="text-primary">Us</span>');
	$design_description			= get_theme_mod('design_description','This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.');
	$design_contents			= get_theme_mod('design_contents',cozipress_get_design_default());
	$design_left_img			= get_theme_mod('design_left_img',BURGER_COMPANION_PLUGIN_URL . 'inc/coziweb/images/design-img.jpg');
	if($hs_design=='1'){
?>
	<section id="design-section" class="design-home design-section st-py-default bg-primary-light shapes-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default wow fadeInUp">
						<?php if ( ! empty( $design_title ) ) : ?>
							 <span class="badge ttl"><?php echo wp_kses_post($design_title); ?></span>
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
			<?php if ( empty( $design_left_img ) ) : ?>
            <div class="row row-cols-lg-1 row-cols-1 g-lg-0 g-5 mt-1">
			<?php else: ?>
			<div class="row row-cols-lg-2 row-cols-1 g-lg-0 g-5 mt-1">
			<?php endif; ?>
                <div class="col wow fadeInLeft">
                    <div class="tilter">
                        <div class="design-img tilter__figure">
							<?php if ( ! empty( $design_left_img ) ) : ?>
								<img src="<?php echo esc_url($design_left_img); ?>" class="img-fluid" alt="Cozipress">
							<?php endif; ?>		
                            <div class="tilter__deco--lines"></div>
                        </div>
                    </div>
                </div>
                <div class="col wow fadeInRight">
                    <div class="row row-cols-1 g-4 p-lg-5 p-0 design-wrp">
						<?php
							if ( ! empty( $design_contents ) ) {
							$design_contents = json_decode( $design_contents );
							foreach ( $design_contents as $design_item ) {
								$cozipress_design_title = ! empty( $design_item->title ) ? apply_filters( 'cozipress_translate_single_string', $design_item->title, 'design section' ) : '';
								$text = ! empty( $design_item->text ) ? apply_filters( 'cozipress_translate_single_string', $design_item->text, 'design section' ) : '';
								$icon = ! empty( $design_item->icon_value) ? apply_filters( 'cozipress_translate_single_string', $design_item->icon_value,'design section' ) : '';
								$cozipress_design_link = ! empty( $design_item->link ) ? apply_filters( 'cozipress_translate_single_string', $design_item->link, 'design section' ) : '';
						?>
							<div class="col">
								<div class="design-item">
									<div class="design-icon">
										<div class="design-corn">
											<?php if ( ! empty( $icon ) ) {?>
												<i class="fa <?php echo esc_attr( $icon ); ?> txt-pink"></i>
											<?php } ?>
										</div>
									</div>
									<div class="design-content">
										<?php if ( ! empty( $cozipress_design_title ) ) : ?>
											<h4 class="design-title"><a href="<?php echo esc_url( $cozipress_design_link ); ?>"><?php echo esc_html( $cozipress_design_title ); ?></a></h4>
										<?php endif; ?>
										
										<?php if ( ! empty( $text ) ) : ?>
											<p><?php echo esc_html( $text ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php } } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg-shape5"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape5.png" alt="image"></div>
        <div class="lg-shape6"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape6.png" alt="image"></div>
        <div class="lg-shape7"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape7.png" alt="image"></div>
        <div class="lg-shape8"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape8.png" alt="image"></div>
        <div class="lg-shape9"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape9.png" alt="image"></div>
    </section>
<?php	
	}}
endif;
if ( function_exists( 'burger_cozipress_design' ) ) {
$section_priority = apply_filters( 'cozipress_section_priority', 13, 'burger_cozipress_design' );
add_action( 'cozipress_sections', 'burger_cozipress_design', absint( $section_priority ) );
}		