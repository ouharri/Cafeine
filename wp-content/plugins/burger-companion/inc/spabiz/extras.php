<?php

// Header Info
if ( ! function_exists( 'spabiz_header_info' ) ) {
	function spabiz_header_info() {
		$hdr_info =	get_theme_mod('hdr_info',spabiz_get_hdr_info_default());
		?>
		<?php
			if ( ! empty( $hdr_info ) ) {
			$hdr_info = json_decode( $hdr_info );
			foreach ( $hdr_info as $contact_item ) {
				$title = ! empty( $contact_item->title ) ? apply_filters( 'spabiz_translate_single_string', $contact_item->title, 'Header Above section' ) : '';
				$link = ! empty( $contact_item->link ) ? apply_filters( 'spabiz_translate_single_string', $contact_item->link, 'Header Above section' ) : '';
				$icon = ! empty( $contact_item->icon_value ) ? apply_filters( 'spabiz_translate_single_string', $contact_item->icon_value, 'Header Above section' ) : '';
		?>
			<aside class="widget widget-contact">
				<div class="contact-area">
					<?php if(!empty($icon)): ?>
						<div class="contact-icon">
							<div class="contact-corn"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
						</div>
					<?php endif; ?>
					
					<?php if(!empty($title)): ?>
						<div class="contact-info">
							<p class="text"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></p>
						</div>
					<?php endif; ?>
				</div>
			</aside>
		<?php } } ?>
		<?php
	}
	add_action('spabiz_header_info','spabiz_header_info');
}


if ( ! function_exists( 'spabiz_header_social_icon' ) ) {
	function spabiz_header_social_icon() {
		$social_icons =	get_theme_mod('social_icons',spabiz_get_social_icon_default());
		?>
		<aside class="widget widget_social_widget">
			<ul>
				<?php
					$social_icons = json_decode($social_icons);
					if( $social_icons!='' )
					{
					foreach($social_icons as $social_item){	
					$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'spabiz_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
					$social_link = ! empty( $social_item->link ) ? apply_filters( 'spabiz_translate_single_string', $social_item->link, 'Header section' ) : '';
				?>
				<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
				<?php }} ?>
			</ul>
		</aside>
		<?php
	}
	add_action('spabiz_header_social_icon','spabiz_header_social_icon');
}


/*
 *
 * Social Icon
 */
function spabiz_get_social_icon_default() {
	return apply_filters(
		'spabiz_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'spabiz' ),
					'link'	  =>  esc_html__( '#', 'spabiz' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'spabiz' ),
					'link'	  =>  esc_html__( '#', 'spabiz' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'spabiz' ),
					'link'	  =>  esc_html__( '#', 'spabiz' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-pinterest-p', 'spabiz' ),
					'link'	  =>  esc_html__( '#', 'spabiz' ),
					'id'              => 'customizer_repeater_header_social_004',
				)
			)
		)
	);
}


/*
 *
 * Slider Default
 */
 function spabiz_get_slider_default() {
	return apply_filters(
		'spabiz_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spabiz/images/slider/01.png',
					'title'           => esc_html__( 'spa center', 'spabiz' ),
					'subtitle'         => esc_html__( 'you are', 'spabiz' ),
					'subtitle2'         => esc_html__( 'beautiful', 'spabiz' ),
					'subtitle3'         => esc_html__( 'Just remind yourself', 'spabiz' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging, and are true partners who care about your success.', 'spabiz' ),
					'text2'	  =>  esc_html__( 'Read More', 'spabiz' ),
					'link'	  =>  esc_html__( '#', 'spabiz' ),
					'id'              => 'customizer_repeater_slider_001'
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spabiz/images/slider/07.png',
					'title'           => esc_html__( 'spa center', 'spabiz' ),
					'subtitle'         => esc_html__( 'you are', 'spabiz' ),
					'subtitle2'         => esc_html__( 'beautiful', 'spabiz' ),
					'subtitle3'         => esc_html__( 'Just remind yourself', 'spabiz' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging, and are true partners who care about your success.', 'spabiz' ),
					'text2'	  =>  esc_html__( 'Read More', 'spabiz' ),
					'link'	  =>  esc_html__( '#', 'spabiz' ),
					'id'              => 'customizer_repeater_slider_002'
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spabiz/images/slider/06.png',
					'title'           => esc_html__( 'spa center', 'spabiz' ),
					'subtitle'         => esc_html__( 'you are', 'spabiz' ),
					'subtitle2'         => esc_html__( 'beautiful', 'spabiz' ),
					'subtitle3'         => esc_html__( 'Just remind yourself', 'spabiz' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging, and are true partners who care about your success.', 'spabiz' ),
					'text2'	  =>  esc_html__( 'Read More', 'spabiz' ),
					'link'	  =>  esc_html__( '#', 'spabiz' ),
					'id'              => 'customizer_repeater_slider_003'
				),
			)
		)
	);
}


/*
 *
 * Header Info Default
 */
 function spabiz_get_hdr_info_default() {
	return apply_filters(
		'spabiz_get_hdr_info_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( '123 456 7890', 'spabiz' ),
					'link'            => 'tel:123 456 7890',
					'icon_value'       => 'fa-phone',
					'id'              => 'customizer_repeater_hdr_info_001',
					
				),
				array(
					'title'           => esc_html__( 'example@123.com', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-envelope',
					'id'              => 'customizer_repeater_hdr_info_002',			
				),
				array(
					'title'           => esc_html__( 'Mon - Sun 10 Am to 10 Pm', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-clock-o',
					'id'              => 'customizer_repeater_hdr_info_003',	
				)
			)
		)
	);
}


/*
 *
 * Info Default
 */
 function spabiz_get_info_default() {
	return apply_filters(
		'spabiz_get_info_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Massage', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-smile-o',
					'id'              => 'customizer_repeater_info_001'					
				),
				array(
					'title'           => esc_html__( 'Waxing', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-star-half-o',
					'id'              => 'customizer_repeater_info_002'			
				),
				array(
					'title'           => esc_html__( 'Facial', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-leaf',
					'id'              => 'customizer_repeater_info_003'	
				),
				array(
					'title'           => esc_html__( 'Nail Care', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-magic',
					'id'              => 'customizer_repeater_info_004'	
				),
				array(
					'title'           => esc_html__( 'Hair Cut', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-scissors',
					'id'              => 'customizer_repeater_info_005'	
				),
				array(
					'title'           => esc_html__( 'Mackup', 'spabiz' ),
					'link'            => '#',
					'icon_value'       => 'fa-eyedropper',
					'id'              => 'customizer_repeater_info_006'	
				)
			)
		)
	);
}

/*
 *
 * Service Default
 */
 function spabiz_get_service_default() {
	return apply_filters(
		'spabiz_get_service_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-anchor',
					'title'           => esc_html__( 'Candle Massage', 'spabiz' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging', 'spabiz' ),
					'text2'           => esc_html__( 'Read More', 'spabiz' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_001'					
				),
				array(
					'icon_value'       => 'fa-gavel',
					'title'           => esc_html__( 'Body Treatment', 'spabiz' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging', 'spabiz' ),
					'text2'           => esc_html__( 'Read More', 'spabiz' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_002'		
				),
				array(
					'icon_value'       => 'fa-anchor',
					'title'           => esc_html__( 'Stone Spa', 'spabiz' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging', 'spabiz' ),
					'text2'           => esc_html__( 'Read More', 'spabiz' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_003'
				),
				array(
					'icon_value'       => 'fa-plane',
					'title'           => esc_html__( 'World Wide', 'spabiz' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging', 'spabiz' ),
					'text2'           => esc_html__( 'Read More', 'spabiz' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_004'
				)
			)
		)
	);
}




/*
 *
 * Funfact Default
 */
 function spabiz_get_funfact_default() {
	return apply_filters(
		'spabiz_get_funfact_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-user',
					'title'           => esc_html__( '560', 'spabiz' ),
					'subtitle'           => esc_html__( '+', 'spabiz' ),
					'text'            => esc_html__( 'Cutomers', 'spabiz' ),
					'id'              => 'customizer_repeater_funfact_001'					
				),
				array(
					'icon_value'       => 'fa-file',
					'title'           => esc_html__( '560', 'spabiz' ),
					'subtitle'           => esc_html__( '+', 'spabiz' ),
					'text'            => esc_html__( 'Cutomers', 'spabiz' ),
					'id'              => 'customizer_repeater_funfact_002'	
				),
				array(
					'icon_value'       => 'fa-thumbs-o-up',
					'title'           => esc_html__( '560', 'spabiz' ),
					'subtitle'           => esc_html__( '+', 'spabiz' ),
					'text'            => esc_html__( 'Cutomers', 'spabiz' ),
					'id'              => 'customizer_repeater_funfact_003'
				),
				array(
					'icon_value'       => 'fa-star-half-o',
					'title'           => esc_html__( '560', 'spabiz' ),
					'subtitle'           => esc_html__( '+', 'spabiz' ),
					'text'            => esc_html__( 'Cutomers', 'spabiz' ),
					'id'              => 'customizer_repeater_funfact_004'
				)
			)
		)
	);
}