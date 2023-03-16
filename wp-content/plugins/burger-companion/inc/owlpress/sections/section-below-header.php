<?php 
	if ( ! function_exists( 'owlpress_below_header' ) ) :
	function owlpress_below_header() {
		 $hs_hdr_info  =	get_theme_mod('hs_hdr_info','1');
		 $hdr_info     =	get_theme_mod('hdr_info',owlpress_get_hdr_info_default());
		 if($hs_hdr_info=='1'):
	?>
		<div id="above-header" class="above-header d-lg-block d-none wow fadeInDown">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="header-widget d-flex align-items-center">
							<div class="row">
								<div class="col-lg-12 col-12 mb-lg-0 mb-4">
									<div class="widget-center text-lg-left text-center">
										<?php
											if ( ! empty( $hdr_info ) ) {
											$hdr_info = json_decode( $hdr_info );
											foreach ( $hdr_info as $info_item ) {
												$title = ! empty( $info_item->title ) ? apply_filters( 'owlpress_translate_single_string', $info_item->title, 'Info section' ) : '';
												$text = ! empty( $info_item->text ) ? apply_filters( 'owlpress_translate_single_string', $info_item->text, 'Info section' ) : '';
												$link = ! empty( $info_item->link ) ? apply_filters( 'owlpress_translate_single_string', $info_item->link, 'Info section' ) : '';
												$icon = ! empty( $info_item->icon_value ) ? apply_filters( 'owlpress_translate_single_string', $info_item->icon_value, 'Info section' ) : '';
										?>
											<aside class="widget widget-contact">
												<div class="contact-area">
													<?php if(!empty($icon)): ?>
														<div class="contact-icon"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
													<?php endif; ?>
													
													<?php if(!empty($title) || !empty($text)): ?>
														<div class="contact-info">
															<h6 class="title"><?php echo esc_html($title); ?></h6>
															
															<?php if(!empty($link)): ?>
																<p class="text"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($text); ?></a></p>
															<?php else: ?>	
																<p class="text"><?php echo esc_html($text); ?></p>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												</div>
											</aside>
										<?php } } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; 
} endif;
add_action('owlpress_below_header', 'owlpress_below_header');