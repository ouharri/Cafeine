<?php
/*
 *
 * Social Icon
 */
function decorme_get_social_icon_default() {
	return apply_filters(
		'decorme_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'decorme' ),
					'link'	  =>  esc_html__( '#', 'decorme' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'decorme' ),
					'link'	  =>  esc_html__( '#', 'decorme' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'decorme' ),
					'link'	  =>  esc_html__( '#', 'decorme' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-skype', 'decorme' ),
					'link'	  =>  esc_html__( '#', 'decorme' ),
					'id'              => 'customizer_repeater_header_social_005',
				)
			)
		)
	);
}



/*
 *
 * Slider 5 Default
 */
 function decorme_get_slider5_default() {
	return apply_filters(
		'decorme_get_slider5_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/slider_five/img01.jpg',
					'image_url2'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/slider_five/sofa.png',
					'title'           => esc_html__( 'INTERIOR', 'decorme' ),
					'subtitle'         => esc_html__( 'Create a New Luxury', 'decorme' ),
					'subtitle2'         => esc_html__( '& Modern Interior', 'decorme' ),
					'text'            => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'decorme' ),
					'text2'	  =>  esc_html__( 'Get Started', 'decorme' ),
					'link'	  =>  esc_html__( '#', 'decorme' ),
					'id'              => 'customizer_repeater_slider5_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/slider_five/img01.jpg',
					'image_url2'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/slider_five/sofa.png',
					'title'           => esc_html__( 'INTERIOR', 'decorme' ),
					'subtitle'         => esc_html__( 'Create a New Luxury', 'decorme' ),
					'subtitle2'         => esc_html__( '& Modern Interior', 'decorme' ),
					'text'            => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'decorme' ),
					'text2'	  =>  esc_html__( 'Get Started', 'decorme' ),
					'link'	  =>  esc_html__( '#', 'decorme' ),
					'id'              => 'customizer_repeater_slider5_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/slider_five/img01.jpg',
					'image_url2'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/slider_five/sofa.png',
					'title'           => esc_html__( 'INTERIOR', 'decorme' ),
					'subtitle'         => esc_html__( 'Create a New Luxury', 'decorme' ),
					'subtitle2'         => esc_html__( '& Modern Interior', 'decorme' ),
					'text'            => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'decorme' ),
					'text2'	  =>  esc_html__( 'Get Started', 'decorme' ),
					'link'	  =>  esc_html__( '#', 'decorme' ),
					'id'              => 'customizer_repeater_slider5_003',
				),
			)
		)
	);
}

/*
 *
 * Info 2 Default
 */
 function decorme_get_info2_default() {
	return apply_filters(
		'decorme_get_info2_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/info/icon-1.png',
					'id'              => 'customizer_repeater_info2_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/info/icon-2.png',
					'id'              => 'customizer_repeater_info2_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/info/icon-3.png',
					'id'              => 'customizer_repeater_info2_003',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/info/icon-4.png',
					'id'              => 'customizer_repeater_info2_004',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/info/icon-5.png',
					'id'              => 'customizer_repeater_info2_005',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/decorme/images/info/icon-6.png',
					'id'              => 'customizer_repeater_info2_006',
				),
			)
		)
	);
}


/*
 *
 * Service Default
 */
 function decorme_get_service_default() {
	return apply_filters(
		'decorme_get_service_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-gear',
					'title'           => esc_html__( 'Architectural', 'decorme' ),
					'subtitle'           => esc_html__( 'Design', 'decorme' ),
					'text'            => esc_html__( 'It is a long established fact it’s a that reader will be distracted by the del readable content of a page.', 'decorme' ),
					'text2'            => esc_html__( 'Get Started', 'decorme' ),
					'link'            => '#',
					'id'              => 'customizer_repeater_service_001',
				),
				array(
					'icon_value'       => 'fa-bed',
					'title'           => esc_html__( 'Interior', 'decorme' ),
					'subtitle'           => esc_html__( 'Design', 'decorme' ),
					'text'            => esc_html__( 'It is a long established fact it’s a that reader will be distracted by the del readable content of a page.', 'decorme' ),
					'text2'            => esc_html__( 'Get Started', 'decorme' ),
					'link'            => '#',
					'id'              => 'customizer_repeater_service_002',
				),
				array(
					'icon_value'       => 'fa-copyright',
					'title'           => esc_html__( 'Corporate', 'decorme' ),
					'subtitle'           => esc_html__( 'Design', 'decorme' ),
					'text'            => esc_html__( 'It is a long established fact it’s a that reader will be distracted by the del readable content of a page.', 'decorme' ),
					'text2'            => esc_html__( 'Get Started', 'decorme' ),
					'link'            => '#',
					'id'              => 'customizer_repeater_service_003',
				)
			)
		)
	);
}


/*
 *
 * Payment Icon
 */
function decorme_get_payment_icon_default() {
	return apply_filters(
		'decorme_get_payment_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  'fa-cc-visa',
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_footer_payment_001',
				),
				array(
					'icon_value'	  => 'fa-cc-paypal',
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_footer_payment_002',
				),
				array(
					'icon_value'	  => 'fa-cc-mastercard', 
					'link'	  =>  '#', 
					'id'              => 'customizer_repeater_footer_payment_003',
				),
				array(
					'icon_value'	  => 'fa-cc-discover',
					'link'	  =>  '#', 
					'id'              => 'customizer_repeater_footer_payment_004',
				),
			)
		)
	);
}




// Footer Payment Icons
if ( ! function_exists( 'decorme_footer_payment_icons' ) ) :
	function decorme_footer_payment_icons() {
	$hs_footer_payment  	= get_theme_mod('hs_footer_payment','1');
	$footer_payment_icons	= get_theme_mod('footer_payment_icons',decorme_get_payment_icon_default());
	if($hs_footer_payment=='1'){
?>
	<ul class="payment_methods">
		<?php
			$footer_payment_icons = json_decode($footer_payment_icons);
			if( $footer_payment_icons!='' )
			{
			foreach($footer_payment_icons as $payment_item){	
			$social_icon = ! empty( $payment_item->icon_value ) ? apply_filters( 'decorme_translate_single_string', $payment_item->icon_value, 'Footer section' ) : '';	
			$social_link = ! empty( $payment_item->link ) ? apply_filters( 'decorme_translate_single_string', $payment_item->link, 'Footer section' ) : '';
		?>
			<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
		<?php }} ?>
	</ul>
<?php }
}
add_action( 'decorme_footer_payment_icons', 'decorme_footer_payment_icons');
endif;