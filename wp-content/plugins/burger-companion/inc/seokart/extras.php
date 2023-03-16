<?php
/*
 *
 * Social Icon
 */
function seokart_get_social_icon_default() {
	return apply_filters(
		'seokart_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'seokart' ),
					'link'	  =>  esc_html__( '#', 'seokart' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'seokart' ),
					'link'	  =>  esc_html__( '#', 'seokart' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'seokart' ),
					'link'	  =>  esc_html__( '#', 'seokart' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
			)
		)
	);
}


/*
 *
 * Slider Default
 */
$theme = wp_get_theme(); // gets the current theme
	
if( 'DigiPress' == $theme->name){ 
	 function seokart_get_slider_default() {
		return apply_filters(
			'seokart_get_slider_default', json_encode(
					 array(
					array(
						'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/slider/img01.jpg',
						'title'           => esc_html__( '30,000+', 'seokart' ),
						'subtitle'         => esc_html__( 'We Will Help You To Grow Your Business', 'seokart' ),
						'text'            => esc_html__( 'A thousand miles from the traditional sense, technically 730.3 miles from SC to NYC, we’re a stew of like-minded ', 'seokart' ),
						'text2'	  =>  esc_html__( 'Our Projects', 'seokart' ),
						'link'	  =>  esc_html__( '#', 'seokart' ),
						"slide_align" => "left", 
						'id'              => 'customizer_repeater_slider_001',
					),
					array(
						'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/slider/img02.jpg',
						'title'           => esc_html__( '30,000+', 'seokart' ),
						'subtitle'         => esc_html__( 'We Will Help You To Grow Your Business', 'seokart' ),
						'text'            => esc_html__( 'A thousand miles from the traditional sense, technically 730.3 miles from SC to NYC, we’re a stew of like-minded ', 'seokart' ),
						'text2'	  =>  esc_html__( 'Our Projects', 'seokart' ),
						'link'	  =>  esc_html__( '#', 'seokart' ),
						"slide_align" => "center", 
						'id'              => 'customizer_repeater_slider_002',
					),
					array(
						'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/slider/img03.jpg',
						'title'           => esc_html__( '30,000+', 'seokart' ),
						'subtitle'         => esc_html__( 'We Will Help You To Grow Your Business', 'seokart' ),
						'text'            => esc_html__( 'A thousand miles from the traditional sense, technically 730.3 miles from SC to NYC, we’re a stew of like-minded ', 'seokart' ),
						'text2'	  =>  esc_html__( 'Our Projects', 'seokart' ),
						'link'	  =>  esc_html__( '#', 'seokart' ),
						"slide_align" => "right", 
						'id'              => 'customizer_repeater_slider_003',
				
					),
				)
			)
		);
	}
}else{
	function seokart_get_slider_default() {
		return apply_filters(
			'seokart_get_slider_default', json_encode(
					 array(
					array(
						'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/slider-man-img.png',
						'title'           => esc_html__( '30,000+', 'seokart' ),
						'subtitle'         => esc_html__( 'We Will Help You To Grow Your Business', 'seokart' ),
						'text'            => esc_html__( 'A thousand miles from the traditional sense, technically 730.3 miles from SC to NYC, we’re a stew of like-minded ', 'seokart' ),
						'text2'	  =>  esc_html__( 'Our Projects', 'seokart' ),
						'link'	  =>  esc_html__( '#', 'seokart' ),
						"slide_align" => "left", 
						'id'              => 'customizer_repeater_slider_001',
					),
					array(
						'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/slider-man-img.png',
						'title'           => esc_html__( '30,000+', 'seokart' ),
						'subtitle'         => esc_html__( 'We Will Help You To Grow Your Business', 'seokart' ),
						'text'            => esc_html__( 'A thousand miles from the traditional sense, technically 730.3 miles from SC to NYC, we’re a stew of like-minded ', 'seokart' ),
						'text2'	  =>  esc_html__( 'Our Projects', 'seokart' ),
						'link'	  =>  esc_html__( '#', 'seokart' ),
						"slide_align" => "center", 
						'id'              => 'customizer_repeater_slider_002',
					),
					array(
						'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/slider-man-img.png',
						'title'           => esc_html__( '30,000+', 'seokart' ),
						'subtitle'         => esc_html__( 'We Will Help You To Grow Your Business', 'seokart' ),
						'text'            => esc_html__( 'A thousand miles from the traditional sense, technically 730.3 miles from SC to NYC, we’re a stew of like-minded ', 'seokart' ),
						'text2'	  =>  esc_html__( 'Our Projects', 'seokart' ),
						'link'	  =>  esc_html__( '#', 'seokart' ),
						"slide_align" => "right", 
						'id'              => 'customizer_repeater_slider_003',
				
					),
				)
			)
		);
	}
}

/*
 *
 * Team Default
 */
 function seokart_get_team_default() {
	return apply_filters(
		'seokart_get_team_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/team-img-1.png',
					'title'           => esc_html__( 'Maria Rodriguez', 'seokart' ),
					'subtitle'        => esc_html__( 'Project Manager', 'seokart' ),
					'id'              => 'customizer_repeater_team_001',
					
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/team-img-2.png',
					'title'           => esc_html__( 'James Smith', 'seokart' ),
					'subtitle'        => esc_html__( 'Project Manager', 'seokart' ),
					'id'              => 'customizer_repeater_team_002',				
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/team-img-3.png',
					'title'           => esc_html__( 'Thomas bill', 'seokart' ),
					'subtitle'        => esc_html__( 'Project Manager', 'seokart' ),
					'id'              => 'customizer_repeater_team_003',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/team-img-4.png',
					'title'           => esc_html__( 'David Smith', 'seokart' ),
					'subtitle'        => esc_html__( 'Project Manager', 'seokart' ),
					'id'              => 'customizer_repeater_team_004',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/team-img-5.png',
					'title'           => esc_html__( 'Alex William', 'seokart' ),
					'subtitle'        => esc_html__( 'Project Manager', 'seokart' ),
					'id'              => 'customizer_repeater_team_005',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/seokart/images/team-img-6.png',
					'title'           => esc_html__( 'Welia Sundrop', 'seokart' ),
					'subtitle'        => esc_html__( 'Project Manager', 'seokart' ),
					'id'              => 'customizer_repeater_team_006',
				),
			)
		)
	);
}



/*
 *
 * Features Default
 */
 function seokart_get_features_default() {
	return apply_filters(
		'seokart_get_features_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-bullseye',
					'title'           => esc_html__( 'Seo Optimization', 'seokart' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard', 'seokart' ),
					'id'              => 'customizer_repeater_features_001',
					
				),
				array(
					'icon_value'       => 'fa-google-wallet',
					'title'           => esc_html__( 'Pay Per Click', 'seokart' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard', 'seokart' ),
					'id'              => 'customizer_repeater_features_002',			
				),
				array(
					'icon_value'       => 'fa-apple',
					'title'           => esc_html__( 'App Development', 'seokart' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard', 'seokart' ),
					'id'              => 'customizer_repeater_features_003',
				),
				array(
					'icon_value'       => 'fa-tachometer',
					'title'           => esc_html__( 'Social Media', 'seokart' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard', 'seokart' ),
					'id'              => 'customizer_repeater_features_004',
				),
				array(
					'icon_value'       => 'fa-paper-plane',
					'title'           => esc_html__( 'Email Marketing', 'seokart' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard', 'seokart' ),
					'id'              => 'customizer_repeater_features_005',
				),
				array(
					'icon_value'       => 'fa-bar-chart',
					'title'           => esc_html__( 'We Analysis', 'seokart' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard', 'seokart' ),
					'id'              => 'customizer_repeater_features_006',
				),
			)
		)
	);
}


/*
 *
 * Footer Info Default
 */
 function seokart_get_footer_info_default() {
	return apply_filters(
		'seokart_get_footer_info_default', json_encode(
				 array(
				array(
					'icon_value'           => 'fa-map-signs',
					'title'           => esc_html__( '25, King ST, 20170', 'seokart' ),
					'subtitle'            => esc_html__( 'Melbourne Australia', 'seokart' ),
					'id'              => 'customizer_repeater_footer_info_001',					
				),
				array(
					'icon_value'           => 'fa-headphones',
					'title'           => esc_html__( '25, King ST, 20170', 'seokart' ),
					'subtitle'            => esc_html__( 'Melbourne Australia', 'seokart' ),
					'id'              => 'customizer_repeater_footer_info_002',				
				),
				array(
					'icon_value'           => 'fa-envelope-o',
					'title'           => esc_html__( '25, King ST, 20170', 'seokart' ),
					'subtitle'            => esc_html__( 'Melbourne Australia', 'seokart' ),
					'id'              => 'customizer_repeater_footer_info_003',
				),
			)
		)
	);
}