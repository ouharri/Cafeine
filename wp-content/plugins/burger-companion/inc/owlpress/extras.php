<?php
/*
 *
 * Social Icon
 */
function owlpress_get_social_icon_default() {
	return apply_filters(
		'owlpress_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'id'              => 'customizer_repeater_header_social_004',
				)
			)
		)
	);
}


if ( ! function_exists( 'owlpress_header_social' ) ) :
	function owlpress_header_social() {
		$hs_social_icon =	get_theme_mod('hs_social_icon','1');
		$social_icons =	get_theme_mod('social_icons',owlpress_get_social_icon_default());
		if($hs_social_icon=='1'):
	?>
	<li class="widget-list">
		<aside class="widget widget_social_widget">
			<ul>
				<?php
					$social_icons = json_decode($social_icons);
					if( $social_icons!='' )
					{
					foreach($social_icons as $social_item){	
					$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'owlpress_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
					$social_link = ! empty( $social_item->link ) ? apply_filters( 'owlpress_translate_single_string', $social_item->link, 'Header section' ) : '';
				?>
				<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
				<?php }} ?>
			</ul>
		</aside>
	</li>
	<?php endif;
} endif;
add_action('owlpress_header_social', 'owlpress_header_social');		


/*
 *
 * Slider Default
 */
 function owlpress_get_slider_default() {
	return apply_filters(
		'owlpress_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/slider/img01.jpg',
					'title'           => esc_html__( 'Strategy & Planing', 'owlpress' ),
					'subtitle'         => esc_html__( 'The Fastest Way to', 'owlpress' ),
					'designation'         => esc_html__( 'Achieve Success', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eic dsds salacus vel facilisis. dolor sit amet', 'owlpress' ),
					'text2'	  =>  esc_html__( 'Read More', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'button_second'	  =>  esc_html__( 'Contact us', 'owlpress' ),
					'link2'	  =>  esc_html__( '#', 'owlpress' ),
					'icon_value'	  =>  'fa-play',
					'id'              => 'customizer_repeater_slider_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/slider/img02.jpg',
					'title'           => esc_html__( 'Strategy & Planing', 'owlpress' ),
					'subtitle'         => esc_html__( 'The Fastest Way to', 'owlpress' ),
					'designation'         => esc_html__( 'Achieve Success', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eic dsds salacus vel facilisis. dolor sit amet', 'owlpress' ),
					'text2'	  =>  esc_html__( 'Read More', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'button_second'	  =>  esc_html__( 'Contact us', 'owlpress' ),
					'link2'	  =>  esc_html__( '#', 'owlpress' ),
					'icon_value'	  =>  'fa-play',
					'id'              => 'customizer_repeater_slider_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/slider/img03.jpg',
					'title'           => esc_html__( 'Strategy & Planing', 'owlpress' ),
					'subtitle'         => esc_html__( 'The Fastest Way to', 'owlpress' ),
					'designation'         => esc_html__( 'Achieve Success', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eic dsds salacus vel facilisis. dolor sit amet', 'owlpress' ),
					'text2'	  =>  esc_html__( 'Read More', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'button_second'	  =>  esc_html__( 'Contact us', 'owlpress' ),
					'link2'	  =>  esc_html__( '#', 'owlpress' ),
					'icon_value'	  =>  'fa-play',
					'id'              => 'customizer_repeater_slider_003',
			
				),
			)
		)
	);
}


/*
 *
 * Header Info Default
 */
 function owlpress_get_hdr_info_default() {
	return apply_filters(
		'owlpress_get_hdr_info_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Our Head Office', 'owlpress' ),
					'text'            => esc_html__( '264 old york, newyork 5463', 'owlpress' ),
					'icon_value'       => 'fa-map-marker',
					'id'              => 'customizer_repeater_hdr_info_001',
					
				),
				array(
					'title'           => esc_html__( 'Send Email Us', 'owlpress' ),
					'text'            => esc_html__( 'suport@example.com', 'owlpress' ),
					'link'            => 'mailto:suport@example.com',
					'icon_value'       => 'fa-envelope',
					'id'              => 'customizer_repeater_hdr_info_002',			
				),
				array(
					'title'           => esc_html__( 'Visit Office Hours', 'owlpress' ),
					'text'            => esc_html__( '09.00am 07.00pm(Mon_Sat)', 'owlpress' ),
					'icon_value'       => 'fa-clock-o',
					'id'              => 'customizer_repeater_hdr_info_003',	
				),
				array(
					'title'           => esc_html__( 'Connect Us Now', 'owlpress' ),
					'text'            => esc_html__( '+123 456 7890, +987 654 3210', 'owlpress' ),
					'link'            => 'tel:+1234567890',
					'icon_value'       => 'fa-phone-square',
					'id'              => 'customizer_repeater_hdr_info_004',	
				),
			)
		)
	);
}


/*
 *
 * Footer Above Contact Default
 */
 function owlpress_get_footer_above_contact_default() {
	return apply_filters(
		'owlpress_get_footer_above_contact_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Business Consalting Services', 'owlpress' ),
					'icon_value'       => 'fa-home',
					'id'              => 'customizer_repeater_footer_above_contact_001',
				),
				array(
					'title'           => esc_html__( 'Cloud Computing Services', 'owlpress' ),
					'icon_value'       => 'fa-building',
					'id'              => 'customizer_repeater_footer_above_contact_002',			
				)
			)
		)
	);
}

/*
 *
 * Payment Icon
 */
function owlpress_get_payment_icon_default() {
	return apply_filters(
		'owlpress_get_payment_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-visa', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'id'              => 'customizer_repeater_footer_payment_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-paypal', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'id'              => 'customizer_repeater_footer_payment_002',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-mastercard', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'id'              => 'customizer_repeater_footer_payment_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-discover', 'owlpress' ),
					'link'	  =>  esc_html__( '#', 'owlpress' ),
					'id'              => 'customizer_repeater_footer_payment_004',
				),
			)
		)
	);
}


if ( ! function_exists( 'owlpress_footer_payment_icons' ) ) :
	function owlpress_footer_payment_icons() {
	$footer_payment_icons = get_theme_mod('footer_payment_icons',owlpress_get_payment_icon_default());
		$footer_payment_icons = json_decode($footer_payment_icons);
			if( $footer_payment_icons!='' )
			{
			foreach($footer_payment_icons as $payment_item){	
			$social_icon = ! empty( $payment_item->icon_value ) ? apply_filters( 'owlpress_translate_single_string', $payment_item->icon_value, 'Footer section' ) : '';	
			$social_link = ! empty( $payment_item->link ) ? apply_filters( 'owlpress_translate_single_string', $payment_item->link, 'Footer section' ) : '';
		?>
			<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
		<?php }}
} endif;
add_action('owlpress_footer_payment_icons', 'owlpress_footer_payment_icons');	

/*
 *
 * Service Default
 */
 function owlpress_get_service_default() {
	return apply_filters(
		'owlpress_get_service_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-newspaper-o',
					'title'           => esc_html__( 'Banking & Market', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum doloips lacus vel facilisis', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'icon_value'       => 'fa-user',
					'title'           => esc_html__( 'Finance & Insurance', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum doloips lacus vel facilisis', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_002',			
				),
				array(
					'icon_value'       => 'fa-mortar-board',
					'title'           => esc_html__( 'Logistic & Trasportation', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum doloips lacus vel facilisis', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_003',
				),
				array(
					'icon_value'       => 'fa-smile-o',
					'title'           => esc_html__( 'Defence Security', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum doloips lacus vel facilisis', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_004',
				)
			)
		)
	);
}




/*
 *
 * Features Default
 */
 function owlpress_get_features_default() {
	return apply_filters(
		'owlpress_get_features_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-bell',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/features/feature_bg01.png',
					'title'           => esc_html__( 'Marketing', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_features_001',					
				),
				array(
					'icon_value'       => 'fa-user',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/features/feature_bg01.png',
					'title'           => esc_html__( 'Business', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_features_002',		
				),
				array(
					'icon_value'       => 'fa-home',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/features/feature_bg01.png',
					'title'           => esc_html__( 'Art & Design', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_features_003',
				),
				array(
					'icon_value'       => 'fa-money',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/features/feature_bg01.png',
					'title'           => esc_html__( 'Lifestyle', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_features_004',
				),
				array(
					'icon_value'       => 'fa-pencil',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/features/feature_bg01.png',
					'title'           => esc_html__( 'Photography', 'owlpress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor', 'owlpress' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_features_005',
				)
			)
		)
	);
}



/*
 *
 * Team Default
 */
 function owlpress_get_team_default() {
	return apply_filters(
		'owlpress_get_team_default', json_encode(
					  array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/team/team01.jpg',
					'title'           => esc_html__( 'Faria', 'owlpress-pro' ),
					'subtitle'        => esc_html__( 'Interface Designer','owlpress-pro' ),
					'text'        => esc_html__( 'Lorem ipsum dolor sit ae etes lacus vel facilisis.','owlpress-pro' ),
					'id'              => 'customizer_repeater_team_0001',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_001',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_003',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_004',
								'link' => 'instagram.com',
								'icon' => 'fa-instagram',
							),
						)
					),
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/team/team02.jpg',
					'title'           => esc_html__( 'Faria', 'owlpress-pro' ),
					'subtitle'        => esc_html__( 'Customer Officer','owlpress-pro' ),
					'text'        => esc_html__( 'Lorem ipsum dolor sit ae etes lacus vel facilisis.','owlpress-pro' ),
					'id'              => 'customizer_repeater_team_0002',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0011',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0012',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0013',
								'link' => 'pinterest.com',
								'icon' => 'fa-instagram',
							),
						)
					),
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/team/team03.jpg',
					'title'           => esc_html__( 'Max', 'owlpress-pro' ),
					'subtitle'        => esc_html__( 'Interface Designer','owlpress-pro' ),
					'text'        => esc_html__( 'Lorem ipsum dolor sit ae etes lacus vel facilisis.','owlpress-pro' ),
					'id'              => 'customizer_repeater_team_0003',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0021',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0022',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0023',
								'link' => 'linkedin.com',
								'icon' => 'fa-instagram',
							),
						)
					),
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/owlpress/images/team/team04.jpg',
					'title'           => esc_html__( 'Jack', 'owlpress-pro' ),
					'subtitle'        => esc_html__( 'Interface Designer','owlpress-pro' ),
					'text'        => esc_html__( 'Lorem ipsum dolor sit ae etes lacus vel facilisis.','owlpress-pro' ),
					'id'              => 'customizer_repeater_team_0004',
					'social_repeater' => json_encode(
						array(
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0031',
								'link' => 'facebook.com',
								'icon' => 'fa-facebook',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0032',
								'link' => 'twitter.com',
								'icon' => 'fa-twitter',
							),
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0033',
								'link' => 'linkedin.com',
								'icon' => 'fa-instagram',
							),
						)
					),
				)
			)
		)
	);
}