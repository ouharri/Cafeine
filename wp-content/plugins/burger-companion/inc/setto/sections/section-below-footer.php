<?php  
	if ( ! function_exists( 'setto_below_footers' ) ) :
	function setto_below_footers() {
	$footer_bottom_hs = get_theme_mod('footer_bottom_hs','1');
	$footer_bottom_contact = get_theme_mod('footer_bottom_contact',setto_get_footer_bottom_contact_default());
if($footer_bottom_hs=='1'):		
?>
	<div class="row">
		<div class="company-details-area">
			<ul class="company-ul">
				<?php
					if ( ! empty( $footer_bottom_contact ) ) {
					$footer_bottom_contact = json_decode( $footer_bottom_contact );
					foreach ( $footer_bottom_contact as $contact_item ) {
						$title = ! empty( $contact_item->title ) ? apply_filters( 'setto_translate_single_string', $contact_item->title, 'Footer Bottom section' ) : '';
						$link = ! empty( $contact_item->link ) ? apply_filters( 'setto_translate_single_string', $contact_item->link, 'Footer Bottom section' ) : '';
						$icon = ! empty( $contact_item->icon_value ) ? apply_filters( 'setto_translate_single_string', $contact_item->icon_value, 'Footer Bottom section' ) : '';
				?>
					<li class="company-li">
						<span class="icon">
							<?php if(!empty($icon)): ?>	
								<i class="fa <?php echo esc_attr($icon); ?>"></i>
							<?php endif; ?>
						</span>
						<?php if(!empty($title)  && !empty($link)): ?>
							<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
						<?php else: ?>
							<span class="map-text"><?php echo esc_html($title); ?></span>
						<?php endif; ?>
					</li>
				<?php } }?>
			</ul>
		</div>
	</div>
<?php endif;
}
add_action( 'setto_below_footers', 'setto_below_footers');
endif;