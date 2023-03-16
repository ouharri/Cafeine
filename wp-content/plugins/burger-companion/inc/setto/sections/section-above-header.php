<?php  
	if ( ! function_exists( 'setto_above_header' ) ) :
	function setto_above_header() {
	$hs_abv_hdr				= get_theme_mod('hs_abv_hdr','1');
	$hs_abv_hdr_info1		= get_theme_mod('hs_abv_hdr_info1','1');
	$abv_hdr_info1			= get_theme_mod('abv_hdr_info1','Shop today free shipping on order over $50.00');
	$hs_abv_hdr_info2		= get_theme_mod('hs_abv_hdr_info2','1');
	$social_icons			= get_theme_mod('social_icons',setto_get_social_icon_default());
	$hs_abv_hdr_info3		= get_theme_mod('hs_abv_hdr_info3','1');
	$abv_hdr_info3			= get_theme_mod('abv_hdr_info3','<span class="fa fa-truck">
										</span>
										<span class="track-text">Track your order</span>');
	$abv_hdr_info3_link		= get_theme_mod('abv_hdr_info3_link','#');
if($hs_abv_hdr=='1'):		
?>
	<div id="top-notification" class="top-notification-bg top-notification-one above-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="top-notification-bar">
                        <ul class="notification-entry">
							<?php if($hs_abv_hdr_info1=='1'): ?>	
								<li class="notify-wrap txt-wrap info1">
									<p><?php echo wp_kses_post($abv_hdr_info1); ?></p>
								</li>
							<?php endif; ?>	
							
                            <li class="notify-wrap user-wrap">
								<?php if($hs_abv_hdr_info2=='1'): ?>	
									<div class="top-social">
										<ul class="social-icon">
											<?php
												$social_icons = json_decode($social_icons);
												if( $social_icons!='' )
												{
												foreach($social_icons as $social_item){	
												$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'setto_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
												$social_link = ! empty( $social_item->link ) ? apply_filters( 'setto_translate_single_string', $social_item->link, 'Header section' ) : '';
											?>
												<li class="">
													<a href="<?php echo esc_url( $social_link ); ?>">
														<span class="svg-icon fa <?php echo esc_attr( $social_icon ); ?>"></span>
													</a>
												</li>
											<?php }} ?>
										</ul>
									</div>
								<?php endif; ?>	
								
								<?php if($hs_abv_hdr_info3=='1'): ?>	
									<a href="<?php echo esc_url($abv_hdr_info3_link); ?>" class="order-track info3">
										<?php echo wp_kses_post($abv_hdr_info3); ?>
									</a>
								<?php endif; ?>	
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif;
}
add_action( 'setto_above_header', 'setto_above_header');
endif;