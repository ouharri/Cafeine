<?php 
	if ( ! function_exists( 'burger_spintech_info' ) ) :
	function burger_spintech_info() {
	$hs_info		=	get_theme_mod('hs_info','1');	
	$info_contents	= get_theme_mod('info_contents',spintech_get_info_default());
	if($hs_info == '1') { 
?>	
	 <section id="info-section" class="info-section">
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="row mx-md-0 info-wrapper">
						<?php
							if ( ! empty( $info_contents ) ) {
							$info_contents = json_decode( $info_contents );
							foreach ( $info_contents as $info_item ) {
								$spintech_info_title = ! empty( $info_item->title ) ? apply_filters( 'spintech_translate_single_string', $info_item->title, 'info section' ) : '';
								$text = ! empty( $info_item->text ) ? apply_filters( 'spintech_translate_single_string', $info_item->text, 'info section' ) : '';
								$icon = ! empty( $info_item->icon_value) ? apply_filters( 'spintech_translate_single_string', $info_item->icon_value,'info section' ) : '';
								$image = ! empty( $info_item->image_url ) ? apply_filters( 'spintech_translate_single_string', $info_item->image_url, 'info section' ) : '';
						?>
							<div class="col-lg-3 col-md-6 col-12 mb-lg-0 mb-4">
								<?php if ( ! empty( $image ) ) {?>
								<aside class="widget widget-contact bg-primary-light">
								<?php } else { ?>
								<aside class="widget widget-contact bg-primary-light-not">
								<?php } ?>
									<?php if ( ! empty( $image ) ) {?>
									<div class="item--overlay bg-image" style="background-image: url(<?php echo esc_url( $image ); ?>);"></div>
									<?php } ?>
									<div class="contact-area">
										<div class="contact-icon">
										   <div class="contact-corn">
											<?php if ( ! empty( $icon ) ) {?>
												<i class="fa <?php echo esc_html( $icon ); ?> "></i>
											<?php } ?>
										   </div>
										</div>
										<div class="contact-info">
											<?php if ( ! empty( $spintech_info_title ) ) : ?>
												<h6 class="title"><a href="javascript:void(0);"><?php echo esc_html( $spintech_info_title ); ?></a></h6>
											<?php endif; ?>
											<?php if ( ! empty( $text ) ) : ?>
												<p class="text"><?php echo esc_html( $text ); ?></p>
											<?php endif; ?>
										</div>
									</div>
								</aside>
							</div>
						<?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </section>
	
	  <?php	
	}}
endif;
if ( function_exists( 'burger_spintech_info' ) ) {
$section_priority = apply_filters( 'spintech_section_priority', 12, 'burger_spintech_info' );
add_action( 'spintech_sections', 'burger_spintech_info', absint( $section_priority ) );
}