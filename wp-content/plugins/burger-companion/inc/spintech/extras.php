<?php
/*
 *
 * Social Icon
 */
function spintech_get_social_icon_default() {
	return apply_filters(
		'spintech_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
			)
		)
	);
}



/*
 *
 * Footer Social Icon
 */
function spintech_get_footer_social_icon_default() {
	return apply_filters(
		'spintech_get_footer_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_002',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-google-plus', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-pinterest', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_005',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-dribbble', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_006',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-linkedin', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_007',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-skype', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'id'              => 'customizer_repeater_footer_social_008',
				),
			)
		)
	);
}



/*
 *
 * Footer Contact Info
 */
 function spintech_get_foot_info_default() {
	return apply_filters(
		'spintech_get_foot_info_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Online 24/7', 'spintech' ),
					'text'            => esc_html__( '70 975 975 70', 'spintech' ),
					'icon_value'       => 'fa-comments',
					'id'              => 'customizer_repeater_foot_info_001',
					
				),
				array(
					'title'           => esc_html__( 'Send Us Email', 'spintech' ),
					'text'            => esc_html__( 'Info@example.com', 'spintech' ),
					'icon_value'       => 'fa-envelope',
					'id'              => 'customizer_repeater_foot_info_002',			
				),
			)
		)
	);
}


/*
 *
 * Slider Default
 */
 function spintech_get_slider_default() {
	return apply_filters(
		'spintech_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/slider/img01.jpg',
					'title'           => esc_html__( 'New Skills', 'spintech' ),
					'subtitle'         => esc_html__( 'Best Choice For Your Business', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor labore', 'spintech' ),
					'text2'	  =>  esc_html__( 'Purchase Now', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'button_second'	  =>  esc_html__( 'Learn More', 'spintech' ),
					'link2'	  =>  esc_html__( '#', 'spintech' ),
					"slide_align" => "left", 
					'id'              => 'customizer_repeater_slider_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/slider/img02.jpg',
					'title'           => esc_html__( 'Develop Stronger Minds', 'spintech' ),
					'subtitle'         => esc_html__( 'Better Coaching Gets', 'spintech' ),
					'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.', 'spintech' ),
					'text2'	  =>  esc_html__( 'Purchase Now', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'button_second'	  =>  esc_html__( 'Learn More', 'spintech' ),
					'link2'	  =>  esc_html__( '#', 'spintech' ),
					"slide_align" => "center", 
					'id'              => 'customizer_repeater_slider_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/slider/img03.jpg',
					'title'           => esc_html__( 'Industry Analysis', 'spintech' ),
					'subtitle'         => esc_html__( 'Marketing & Strategy', 'spintech' ),
					'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.', 'spintech' ),
					'text2'	  =>  esc_html__( 'Purchase Now', 'spintech' ),
					'link'	  =>  esc_html__( '#', 'spintech' ),
					'button_second'	  =>  esc_html__( 'Learn More', 'spintech' ),
					'link2'	  =>  esc_html__( '#', 'spintech' ),
					"slide_align" => "right", 
					'id'              => 'customizer_repeater_slider_003',
			
				),
			)
		)
	);
}


/*
 *
 * Info Default
 */
 function spintech_get_info_default() {
	return apply_filters(
		'spintech_get_info_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Expert Work', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'spintech' ),
					'icon_value'       => 'fa-user',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/about/design-img.png',
					'id'              => 'customizer_repeater_info_001',
					
				),
				array(
					'title'           => esc_html__( '24/7 Support', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'spintech' ),
					'icon_value'       => 'fa-headphones',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/about/design-img.png',
					'id'              => 'customizer_repeater_info_002',				
				),
				array(
					'title'           => esc_html__( 'Creative Design', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'spintech' ),
					'icon_value'       => 'fa-edit',
					'id'              => 'customizer_repeater_info_003',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/about/design-img.png',
				),
				array(
					'title'           => esc_html__( 'Well Experienced', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'spintech' ),
					'icon_value'       => 'fa-trophy',
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spintech/images/about/design-img.png',
					'id'              => 'customizer_repeater_info_004',
				),
			)
		)
	);
}



/*
 *
 * Service Default
 */
 function spintech_get_service_default() {
	return apply_filters(
		'spintech_get_service_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-bar-chart',
					'title'           => esc_html__( 'Web Development', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do etc.', 'spintech' ),
					'text2'	  =>  esc_html__( 'Read More', 'spintech' ),
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'icon_value'       => 'fa-life-ring',
					'title'           => esc_html__( 'Database Analysis', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do etc.', 'spintech' ),
					'text2'	  =>  esc_html__( 'Read More', 'spintech' ),
					'id'              => 'customizer_repeater_service_002',				
				),
				array(
					'icon_value'       => 'fa-paint-brush',
					'title'           => esc_html__( 'Server Security', 'spintech' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do etc.', 'spintech' ),
					'text2'	  =>  esc_html__( 'Read More', 'spintech' ),
					'id'              => 'customizer_repeater_service_003',
				),
			)
		)
	);
}


/*
 *
 * Design & Develpement Default
 */
 function spintech_get_design_default() {
	return apply_filters(
		'spintech_get_design_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-bar-chart',
					'title'           => esc_html__( 'Flexible Office ', 'spintech' ),
					'id'              => 'customizer_repeater_design_001',
					
				),
				array(
					'icon_value'       => 'fa-search',
					'title'           => esc_html__( 'Macbook Pro', 'spintech' ),
					'id'              => 'customizer_repeater_design_002',				
				),
				array(
					'icon_value'       => 'fa-life-ring',
					'title'           => esc_html__( 'Training & Support', 'spintech' ),
					'id'              => 'customizer_repeater_design_003',
				),
				array(
					'icon_value'       => 'fa-umbrella',
					'title'           => esc_html__( 'Generous Holidays', 'spintech' ),
					'id'              => 'customizer_repeater_design_004',
				),
				array(
					'icon_value'       => 'fa-coffee',
					'title'           => esc_html__( 'Friday Teatime', 'spintech' ),
					'id'              => 'customizer_repeater_design_005',
				),
				array(
					'icon_value'       => 'fa-th',
					'title'           => esc_html__( 'Well Stocked Fridge', 'spintech' ),
					'id'              => 'customizer_repeater_design_006',
				),
				array(
					'icon_value'       => 'fa-paint-brush',
					'title'           => esc_html__( 'Design & Branding', 'spintech' ),
					'id'              => 'customizer_repeater_design_007',
				),
				array(
					'icon_value'       => 'fa-truck',
					'title'           => esc_html__( 'More Stuff', 'spintech' ),
					'id'              => 'customizer_repeater_design_008',
				)
			)
		)
	);
}


/*
 *
 * Funfact Default
 */
 function spintech_get_funfact_default() {
	return apply_filters(
		'spintech_get_funfact_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( '254', 'spintech-pro' ),
					'subtitle'           => esc_html__( '+', 'spintech-pro' ),
					'text'            => esc_html__( 'Expert Consultants', 'spintech-pro' ),
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spinsoft/images/funfact/img01.png',
					'id'              => 'customizer_repeater_funfact_001',
					
				),
				array(
					'title'           => esc_html__( '807', 'spintech-pro' ),
					'subtitle'           => esc_html__( '+', 'spintech-pro' ),
					'text'            => esc_html__( 'Development Hours', 'spintech-pro' ),
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spinsoft/images/funfact/img02.png',
					'id'              => 'customizer_repeater_funfact_002',			
				),
				array(
					'title'           => esc_html__( '926', 'spintech-pro' ),
					'subtitle'           => esc_html__( '+', 'spintech-pro' ),
					'text'            => esc_html__( 'Trusted Clients', 'spintech-pro' ),
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spinsoft/images/funfact/img03.png',
					'id'              => 'customizer_repeater_funfact_003',
				),
				array(
					'title'           => esc_html__( '543', 'spintech-pro' ),
					'subtitle'           => esc_html__( '+', 'spintech-pro' ),
					'text'            => esc_html__( 'Projects Delivered', 'spintech-pro' ),
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/spinsoft/images/funfact/img04.png',
					'id'              => 'customizer_repeater_funfact_004',
				)
			)
		)
	);
}



/*
 *
 * Testimonial Default
 */
 
 function spintech_get_testimonial_default() {
	return apply_filters(
		'spintech_get_testimonial_default', json_encode(
			array(
				array(
					'title'           => esc_html__( 'Julia Corner', 'spintech-pro' ),
					'subtitle'        => esc_html__( 'CEO', 'spintech-pro' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, Connect adipisicing elit, sed do tempor et aliqua.', 'spintech-pro' ),
					'image_url'		  =>  BURGER_COMPANION_PLUGIN_URL . 'inc/spinsoft/images/testimonials/img01.png',
					'id'              => 'customizer_repeater_testimonial_001',
				),
				array(
					'title'           => esc_html__( 'Rizon Pet', 'spintech-pro' ),
					'subtitle'        => esc_html__( 'Founder', 'spintech-pro' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, Connect adipisicing elit, sed do tempor et aliqua.', 'spintech-pro' ),
					'image_url'		  =>  BURGER_COMPANION_PLUGIN_URL . 'inc/spinsoft/images/testimonials/img02.png',
					'id'              => 'customizer_repeater_testimonial_002',
				),
				array(
					'title'           => esc_html__( 'Miekel Stark', 'spintech-pro' ),
					'subtitle'        => esc_html__( 'Designer', 'spintech-pro' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, Connect adipisicing elit, sed do tempor et aliqua.', 'spintech-pro' ),
					'image_url'		  =>  BURGER_COMPANION_PLUGIN_URL . 'inc/spinsoft/images/testimonials/img03.png',
					'id'              => 'customizer_repeater_testimonial_003',
				)
		    )
		)
	);
}