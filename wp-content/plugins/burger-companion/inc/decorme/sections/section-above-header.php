<?php  
	if ( ! function_exists( 'decorme_hdr_contact_info' ) ) :
	function decorme_hdr_contact_info() {
	$hs_hdr_ct_info		=	get_theme_mod('hs_hdr_ct_info','1');
	$hdr_ct_info_icon	=	get_theme_mod('hdr_ct_info_icon','fa-phone');
	$hdr_ct_info_subttl	=	get_theme_mod('hdr_ct_info_subttl','+1-202-555-0170 ');
	if($hs_hdr_ct_info == '1'){
?>
	<ul class="menu-right-list">
		<li class="content-list">
			<aside class="widget widget-contact">
				<div class="contact-area">
					<?php if(!empty($hdr_ct_info_icon)): ?>
						<div class="contact-icon"><i class="fa <?php echo esc_attr($hdr_ct_info_icon); ?>"></i></div>
					<?php endif; ?>
					<div class="contact-info">
						<?php if(!empty($hdr_ct_info_subttl)): ?>
							<h4 class="title"><?php echo wp_kses_post($hdr_ct_info_subttl); ?></h4>
					<?php endif; ?>	
					</div>
				</div>
			</aside>
		</li>
	</ul>
<?php } 
}
add_action( 'decorme_hdr_contact_info', 'decorme_hdr_contact_info');
endif;



if ( ! function_exists( 'decorme_hdr_social' ) ) :
	function decorme_hdr_social() {
	$hs_social_icon =	get_theme_mod('hs_social_icon','1');
	$social_icons =	get_theme_mod('social_icons',decorme_get_social_icon_default());
	if($hs_social_icon=='1'):
?>
	<ul class="menu-right-list">
		<li class="social-list">
			<aside class="widget widget_social">
				<?php
					$social_icons = json_decode($social_icons);
					if( $social_icons!='' )
					{
					foreach($social_icons as $social_item){	
					$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'decorme_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
					$social_link = ! empty( $social_item->link ) ? apply_filters( 'decorme_translate_single_string', $social_item->link, 'Header section' ) : '';
				?>
				<div class="circle"><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></div>
				<?php }} ?>
			</aside>
		</li>
	</ul>
<?php endif;
}
add_action( 'decorme_hdr_social', 'decorme_hdr_social');
endif;