<?php 
	if ( ! function_exists( 'owlpress_above_footer' ) ) :
	function owlpress_above_footer() {
		$footer_above_hs 		= get_theme_mod('footer_above_hs','1');
		$footer_above_contact 	= get_theme_mod('footer_above_contact',owlpress_get_footer_above_contact_default());
		 if($footer_above_hs=='1'): ?>
        <div class="footer-above">
            <div class="container">
                <div class="row gx-4 gy-0">
					<?php
						if ( ! empty( $footer_above_contact ) ) {
						$footer_above_contact = json_decode( $footer_above_contact );
						foreach ( $footer_above_contact as $contact_item ) {
							$title = ! empty( $contact_item->title ) ? apply_filters( 'owlpress_translate_single_string', $contact_item->title, 'Footer Above section' ) : '';
							$link = ! empty( $contact_item->link ) ? apply_filters( 'owlpress_translate_single_string', $contact_item->link, 'Footer Above section' ) : '';
							$icon = ! empty( $contact_item->icon_value ) ? apply_filters( 'owlpress_translate_single_string', $contact_item->icon_value, 'Footer Above section' ) : '';
					?>
						<div class="col-lg-6 col-md-12 col-12 wow fadeIn">
							<aside class="widget widget-contact">
								<div class="contact-area">
									<?php if(!empty($icon)): ?>
										<div class="contact-icon"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
									<?php endif; ?>
									
									<?php if(!empty($title)): ?>
										<div class="contact-info">
											<?php if(!empty($link)): ?>
												<h6 class="title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h6>
												<a href="<?php echo esc_url($link); ?>" class="contact-link-icon"><i class="fa fa-angle-right"></i></a>
											<?php else: ?>	
												<h6 class="title"><a href="javascript:void(0);"><?php echo esc_html($title); ?></a></h6>
												<a href="javascript:void(0);" class="contact-link-icon"><i class="fa fa-angle-right"></i></a>
											<?php endif; ?>
										</div>
									<?php endif; ?>	
								</div>
							</aside>
						</div>
					<?php } } ?>
                </div>
            </div>
        </div>
	<?php endif;
} endif;
add_action('owlpress_above_footer', 'owlpress_above_footer');