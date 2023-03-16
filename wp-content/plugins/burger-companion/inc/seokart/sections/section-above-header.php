<?php 
	if ( ! function_exists( 'seokart_above_header' ) ) :
	function seokart_above_header() {
		$hs_above_phone 		= get_theme_mod('hs_above_phone','1');
		$abv_hdr_phone_ttl 		= get_theme_mod('abv_hdr_phone_ttl','+01 2345 6789');
		$hide_show_hdr_email	= get_theme_mod('hide_show_hdr_email','1');
		$hdr_email_ttl 			= get_theme_mod('hdr_email_ttl','hello@example.com');
		$hide_show_hdr_info 	= get_theme_mod('hide_show_hdr_info','1');
		$hdr_info_ttl 			= get_theme_mod('hdr_info_ttl','92 Bowery St, New york, NY 10013');
		$hdr_info_link 			= get_theme_mod('hdr_info_link');
		$hide_show_social_icon 	= get_theme_mod('hide_show_social_icon','1');
		$social_icons 			= get_theme_mod('social_icons',seokart_get_social_icon_default());
		if($hs_above_phone=='1' || $hide_show_hdr_email=='1' || $hide_show_hdr_info=='1' || $hide_show_social_icon=='1'): ?>
			<div class="top-header-wrap">
            	<button class="top-header-toggler top-header-close" type="button" data-bs-toggle="collapse" data-bs-target=".top-header"><i class="fa fa-ellipsis-v"></i></button>
            	<div class="top-header collapse">
	                <div class="header-widget">
	                    <ul class="info-widget">
							<?php if($hs_above_phone=='1'): ?>
								<li class="phone">
									<?php if(!empty($abv_hdr_phone_ttl)): ?>
										<a href="tel:<?php echo esc_attr($abv_hdr_phone_ttl)?>"> 
											<span class="icon">
												<svg width="17" height="17"><path d="M16.405 12.119c-.542-.375-1.084-.756-1.658-1.082a29.13 29.13 0 0 0-2.122-1.079.923.923 0 0 0-1.111.24c-.289.3-.571.608-.837.929a.36.36 0 0 1-.48.13 7.7 7.7 0 0 1-4.522-4.5.5.5 0 0 1 .1-.431c.3-.308.642-.572.954-.869a.969.969 0 0 0 .247-1.108 4.213 4.213 0 0 0-.32-.736c-.6-.988-1.193-1.977-1.827-2.941A1.073 1.073 0 0 0 3 .4 12.326 12.326 0 0 0 .13 3.856a1.083 1.083 0 0 0-.12.686 10.4 10.4 0 0 0 .507 1.939 18.132 18.132 0 0 0 9.139 9.536 23.862 23.862 0 0 0 2.895.987.08.08 0 0 1 .047-.02l-.047.02a.4.4 0 0 0 .146-.05 1.428 1.428 0 0 0 .358-.164 12.286 12.286 0 0 0 3.624-2.987 1.035 1.035 0 0 0-.274-1.684ZM14 14.825a1.416 1.416 0 0 1-.16.122c-1.416 1-.91 1-2.773.418a13.685 13.685 0 0 1-4.962-3.039 15.31 15.31 0 0 1-4.521-6.279c-.7-2.011-.695-1.478.5-3.05.491-.648 1.091-1.215 1.69-1.871a18.233 18.233 0 0 1 2.051 3.389.423.423 0 0 1-.123.356c-.347.337-.717.65-1.078.972a.653.653 0 0 0-.194.761 8.724 8.724 0 0 0 5.936 5.887.667.667 0 0 0 .767-.2c.325-.358.639-.727.978-1.071a.425.425 0 0 1 .358-.117 18.417 18.417 0 0 1 3.414 2.03c-.656.587-1.27 1.14-1.883 1.692Zm-3.567-12.7a4.251 4.251 0 0 1 3.782 2.452 4.012 4.012 0 0 1 .408 1.758.587.587 0 0 0 .178.424.5.5 0 0 0 .346.134h.021a.528.528 0 0 0 .5-.561v-.225l-.014-.132c-.011-.1-.024-.224-.04-.344a5.324 5.324 0 0 0-5.145-4.549.559.559 0 0 0-.655.5.551.551 0 0 0 .617.539Zm-1.164 3.15a2.277 2.277 0 0 1 2.184 2.168.552.552 0 0 0 .524.611H12a.548.548 0 0 0 .5-.652 3.337 3.337 0 0 0-3.189-3.166.559.559 0 0 0-.659.5.55.55 0 0 0 .615.535Z"/></svg>
											</span>
											<?php echo esc_html($abv_hdr_phone_ttl)?>
										</a>
									<?php endif; ?>
								</li>
							<?php endif; ?>
							
							<?php if($hide_show_hdr_email=='1'): ?>
								<li class="email"> 
									<?php if(!empty($hdr_email_ttl)): ?>
										<a href="mailto:<?php echo esc_attr($hdr_email_ttl)?>">
											<span class="icon">
												<svg width="19" height="13"><path d="M9.5 0h7.734A1.578 1.578 0 0 1 19 1.665v9.351A2.218 2.218 0 0 1 16.88 13H2.1A2.2 2.2 0 0 1 0 11.01V1.552A1.551 1.551 0 0 1 1.648 0H9.5ZM1.079 2.117V10.9a1.15 1.15 0 0 0 1.149 1.088h14.539a1.16 1.16 0 0 0 1.158-1.084V2.497c0-.1-.014-.2-.024-.349l-.361.282c-2.2 1.8-5.3 2.727-7.507 4.523a.654.654 0 0 1-1.058 0C7.728 5.938 6.484 5.787 5.239 4.772 4.175 3.908 2.21 3.043 1.075 2.117Zm8.423 3.93c2.395-1.955 5.663-3.019 8.1-5.009H1.4c2.435 1.99 5.7 3.05 8.1 5.009Z"/></svg>
											</span>
											<?php echo esc_html($hdr_email_ttl)?>
										</a>
									<?php endif; ?>	
								</li>
							<?php endif; ?>
	                    </ul>
						
	                    <ul class="info-widget">
							<?php if($hide_show_hdr_info=='1'): ?>
								<li class="info">
									<?php if(!empty($hdr_info_ttl)): ?>
										<a href="<?php echo esc_url($hdr_info_link); ?>">
											<span class="icon">
												<svg width="12" height="18"><path d="M5.5 8.621a2.951 2.951 0 1 0-2.88-2.95 2.918 2.918 0 0 0 2.88 2.95Zm0-4.846a1.9 1.9 0 1 1-1.85 1.9 1.876 1.876 0 0 1 1.85-1.9Zm-3.14 7.2c.78 1.082.47.662 2.72 3.948a.5.5 0 0 0 .84 0c2.26-3.3 1.95-2.885 2.72-3.949a13.4 13.4 0 0 0 2.02-3.5 5.542 5.542 0 0 0-.77-5.27 5.512 5.512 0 0 0-9.55 5.27 13.4 13.4 0 0 0 2.02 3.5Zm-.43-8.124a4.449 4.449 0 0 1 7.14 0 4.509 4.509 0 0 1 .62 4.28 12.778 12.778 0 0 1-1.88 3.217c-.57.79-.4.548-2.31 3.352-1.91-2.8-1.74-2.562-2.31-3.352a12.778 12.778 0 0 1-1.88-3.217 4.509 4.509 0 0 1 .62-4.28Zm.85 10.41a.507.507 0 0 0-.71-.164l-1.46.947a.533.533 0 0 0 0 .891l4.61 2.984a.521.521 0 0 0 .56 0l4.61-2.984a.533.533 0 0 0 0-.891l-1.46-.947a.507.507 0 0 0-.71.164.527.527 0 0 0 .16.727l.77.5-3.65 2.36-3.65-2.36.77-.5a.527.527 0 0 0 .16-.727Z"/></svg>
											</span>
											<?php echo wp_kses_post($hdr_info_ttl); ?>
										</a>
									<?php endif; ?>
								</li>
							<?php endif; ?>
							
							
							<?php if($hide_show_social_icon=='1'): ?>
								<li>
									<aside class="widget widget_social_widget">
										<ul>
											<?php
												$social_icons = json_decode($social_icons);
												if( $social_icons!='' )
												{
												foreach($social_icons as $social_item){	
												$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'seokart_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
												$social_link = ! empty( $social_item->link ) ? apply_filters( 'seokart_translate_single_string', $social_item->link, 'Header section' ) : '';
											?>
											<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
											<?php }} ?>
										</ul>
									</aside>
								</li>
							<?php endif; ?>
	                    </ul>
	               </div>
	           </div>
            </div>
		<?php endif; 
} endif;
add_action('seokart_above_header', 'seokart_above_header');