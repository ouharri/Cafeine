<?php


if ( ! function_exists( 'cozipress_header_opening_hour' ) ) {
	function cozipress_header_opening_hour() {
		$hs_above_opening			=	get_theme_mod('hs_above_opening','1');
		$abv_hdr_opening_icon		=	get_theme_mod('abv_hdr_opening_icon','fa-clock-o');
		$abv_hdr_opening_ttl		=	get_theme_mod('abv_hdr_opening_ttl','Opening Hour');
		$abv_hdr_opening_content	=	get_theme_mod('abv_hdr_opening_content','Mon to Sat: 10 Am - 6 Pm');
		if($hs_above_opening == '1') : 
		?>
		<aside class="widget widget-contact first">
			<div class="contact-area">
				<div class="contact-icon">
					<div class="contact-corn"><i class="fa <?php echo esc_attr( $abv_hdr_opening_icon ); ?>"></i></div>
				</div>
				<div class="contact-info">
					<h6 class="title"><?php echo wp_kses_post( $abv_hdr_opening_ttl ); ?></h6>
					<p class="text"><a href="javascript:void(0);"><?php echo wp_kses_post( $abv_hdr_opening_content ); ?></a></p>
				</div>
			</div>
		</aside>
		<?php
		endif; 
	}
}
add_action('cozipress_header_opening_hour', 'cozipress_header_opening_hour');


if ( ! function_exists( 'cozipress_header_support' ) ) {
	function cozipress_header_support() {
		$hide_show_hdr_support	=	get_theme_mod('hide_show_hdr_support','1');	
		$hdr_support_icon 		=	get_theme_mod('hdr_support_icon','fa-phone');
		$hdr_support_ttl  		=	get_theme_mod('hdr_support_ttl','Customer Support');
		$hdr_support_text 		=	get_theme_mod('hdr_support_text','<a href="tel:66 555 555 66">66 555 555 66</a>');
		if($hide_show_hdr_support == '1') : 
		?>
		<aside class="widget widget-contact second">
			<div class="contact-area">
				<div class="contact-icon">
					<div class="contact-corn"><i class="fa <?php echo esc_attr( $hdr_support_icon ); ?>"></i></div>
				</div>
				<div class="contact-info">
					<h6 class="title"><?php echo wp_kses_post( $hdr_support_ttl ); ?></h6>
					<p class="text"><?php echo wp_kses_post( $hdr_support_text ); ?></p>
				</div>
			</div>
		</aside>
		<?php
		endif;
	}
}
add_action('cozipress_header_support', 'cozipress_header_support');


if ( ! function_exists( 'cozipress_header_social_icon' ) ) {
	function cozipress_header_social_icon() {
		$hide_show_social_icon	=	get_theme_mod('hide_show_social_icon','1');
		$social_icons 			=	get_theme_mod('social_icons',cozipress_get_social_icon_default());
		if($hide_show_social_icon == '1') :
		?>
		<aside class="widget widget_social_widget third">
			<ul>
				<?php
					$social_icons = json_decode($social_icons);
					if( $social_icons!='' )
					{
					foreach($social_icons as $social_item){	
					$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'cozipress_translate_single_string', $social_item->icon_value, 'Header section' ) : '';	
					$social_link = ! empty( $social_item->link ) ? apply_filters( 'cozipress_translate_single_string', $social_item->link, 'Header section' ) : '';
				?>
				<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
				<?php }} ?>
			</ul>
		</aside>
		<?php
		endif; 
	}
}
add_action('cozipress_header_social_icon', 'cozipress_header_social_icon');


if ( ! function_exists( 'cozipress_header_booknow_button' ) ) {
	function cozipress_header_booknow_button() {
		$hide_show_hdr_btn	=	get_theme_mod('hide_show_hdr_btn','1');
		$hdr_btn_icon 		=	get_theme_mod('hdr_btn_icon','fa-arrow-right');
		$hdr_btn_lbl 		=	get_theme_mod('hdr_btn_lbl','Get A Quote');
		$hdr_btn_url 		=	get_theme_mod('hdr_btn_url','');
		$hdr_btn_open_new_tab  = get_theme_mod('hdr_btn_open_new_tab','');	
		if($hide_show_hdr_btn == '1') : 		
		?>
			<a href="<?php echo esc_url( $hdr_btn_url ); ?>" <?php if($hdr_btn_open_new_tab == '1'): echo "target='_blank'"; endif;?> class="btn btn-primary btn-like-icon"><?php echo esc_html( $hdr_btn_lbl ); ?> <span class="bticn"><i class="fa <?php echo esc_attr( $hdr_btn_icon ); ?>"></i></span></a>
		<?php
		endif;
	}
}
add_action('cozipress_header_booknow_button', 'cozipress_header_booknow_button');


/*
 *
 * Social Icon
 */
function cozipress_get_social_icon_default() {
	return apply_filters(
		'cozipress_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'cozipress' ),
					'link'	  =>  esc_html__( '#', 'cozipress' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'cozipress' ),
					'link'	  =>  esc_html__( '#', 'cozipress' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'cozipress' ),
					'link'	  =>  esc_html__( '#', 'cozipress' ),
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
 function cozipress_get_slider_default() {
	return apply_filters(
		'cozipress_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/slider/img01.jpg',
					'title'           => esc_html__( ' New Skills', 'cozipress' ),
					'subtitle'         => esc_html__( 'Simple, Intuitive & Expertly Crafted!', 'cozipress' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging, and are true partners who care about your success.', 'cozipress' ),
					'text2'	  =>  esc_html__( 'Purchase Now', 'cozipress' ),
					'link'	  =>  esc_html__( '#', 'cozipress' ),
					'icon_value'	  =>  esc_html__( 'fa-arrow-right', 'cozipress' ),
					"slide_align" => "left", 
					'id'              => 'customizer_repeater_slider_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/slider/img02.jpg',
					'title'           => esc_html__( ' New Skills', 'cozipress' ),
					'subtitle'         => esc_html__( 'Simple, Intuitive & Expertly Crafted!', 'cozipress' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging, and are true partners who care about your success.', 'cozipress' ),
					'text2'	  =>  esc_html__( 'Purchase Now', 'cozipress' ),
					'link'	  =>  esc_html__( '#', 'cozipress' ),
					'icon_value'	  =>  esc_html__( 'fa-arrow-right', 'cozipress' ),
					"slide_align" => "center", 
					'id'              => 'customizer_repeater_slider_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/slider/img03.jpg',
					'title'           => esc_html__( ' New Skills', 'cozipress' ),
					'subtitle'         => esc_html__( 'Simple, Intuitive & Expertly Crafted!', 'cozipress' ),
					'text'            => esc_html__( 'We are experienced professionals who understand that It services is charging, and are true partners who care about your success.', 'cozipress' ),
					'text2'	  =>  esc_html__( 'Purchase Now', 'cozipress' ),
					'link'	  =>  esc_html__( '#', 'cozipress' ),
					'icon_value'	  =>  esc_html__( 'fa-arrow-right', 'cozipress' ),
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
 function cozipress_get_info_default() {
	return apply_filters(
		'cozipress_get_info_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Cloud', 'cozipress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'cozipress' ),
					'icon_value'       => 'fa-cloud',
					'id'              => 'customizer_repeater_info_001',
					
				),
				array(
					'title'           => esc_html__( 'Networking', 'cozipress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'cozipress' ),
					'icon_value'       => 'fa-signal',
					'id'              => 'customizer_repeater_info_002',				
				),
				array(
					'title'           => esc_html__( 'Mobility', 'cozipress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'cozipress' ),
					'icon_value'       => 'fa-mobile',
					'id'              => 'customizer_repeater_info_003',
				),
				array(
					'title'           => esc_html__( 'Cybersecurity', 'cozipress' ),
					'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetur.', 'cozipress' ),
					'icon_value'       => 'fa-shield',
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
 function cozipress_get_service_default() {
	return apply_filters(
		'cozipress_get_service_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/services/img01.jpg',
					'icon_value'           => 'fa-lightbulb-o',
					'title'           => esc_html__( 'Digital Branding', 'cozipress' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'cozipress' ),
					'text2'	  =>  esc_html__( 'Get Started ', 'cozipress' ),
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/services/img02.jpg',
					'icon_value'           => 'fa-search-plus',
					'title'           => esc_html__( 'Seo Optimization', 'cozipress' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'cozipress' ),
					'text2'	  =>  esc_html__( 'Get Started ', 'cozipress' ),
					'id'              => 'customizer_repeater_service_002',				
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/services/img03.jpg',
					'icon_value'           => 'fa-desktop',
					'title'           => esc_html__( 'Wireframe Design', 'cozipress' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'cozipress' ),
					'text2'	  =>  esc_html__( 'Get Started ', 'cozipress' ),
					'id'              => 'customizer_repeater_service_003',
				),
			)
		)
	);
}

/*
 *
 * Testimonial Default
 */
 
 function cozipress_get_testimonial_default() {
	return apply_filters(
		'cozipress_get_testimonial_default', json_encode(
			array(
				array(
					'title'           => esc_html__( 'Glenn Maxwell', 'cozipress' ),
					'subtitle'        => esc_html__( 'Project Manager', 'cozipress' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'cozipress' ),
					'image_url'		  =>  BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/testimonials/img01.png',
					'id'              => 'customizer_repeater_testimonial_001',
				),
				array(
					'title'           => esc_html__( 'Rizon Pet', 'cozipress' ),
					'subtitle'        => esc_html__( 'Project Manager', 'cozipress' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'cozipress' ),
					'image_url'		  =>  BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/testimonials/img02.png',
					'id'              => 'customizer_repeater_testimonial_002',
				),
				array(
					'title'           => esc_html__( 'Miekel Stark', 'cozipress' ),
					'subtitle'        => esc_html__( 'Project Manager', 'cozipress' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'cozipress' ),
					'image_url'		  =>  BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/testimonials/img03.png',
					'id'              => 'customizer_repeater_testimonial_003',
				),
		    )
		)
	);
}



/*
 *
 * Design & Develpement Default
 */
 function cozipress_get_design_default() {
	return apply_filters(
		'cozipress_get_design_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-bar-chart',
					'title'           => esc_html__( 'Our Mission', 'cozipress' ),
					'text'           => esc_html__( 'To generate highly focused leads ready to purchases.', 'cozipress' ),
					'id'              => 'customizer_repeater_design_001',
					
				),
				array(
					'icon_value'       => 'fa-search',
					'title'           => esc_html__( 'Our Vision', 'cozipress' ),
					'text'           => esc_html__( 'To generate highly focused leads ready to purchases.', 'cozipress' ),
					'id'              => 'customizer_repeater_design_002',				
				),
				array(
					'icon_value'       => 'fa-life-ring',
					'title'           => esc_html__( 'Our Work', 'cozipress' ),
					'text'           => esc_html__( 'To generate highly focused leads ready to purchases.', 'cozipress' ),
					'id'              => 'customizer_repeater_design_003',
				),
				array(
					'icon_value'       => 'fa-umbrella',
					'title'           => esc_html__( 'Our Team', 'cozipress' ),
					'text'           => esc_html__( 'To generate highly focused leads ready to purchases.', 'cozipress' ),
					'id'              => 'customizer_repeater_design_004',
				),
			)
		)
	);
}


/*
 *
 * Team Default
 */
 function cozipress_get_team_default() {
	return apply_filters(
		'cozipress_get_team_default', json_encode(
					  array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/coziplus/images/team/img01.jpg',
					'title'           => esc_html__( 'Steven Lucy', 'cozipress' ),
					'subtitle'        => esc_html__( 'Executive','cozipress' ),
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
							array(
								'id'   => 'customizer-repeater-social-repeater-team_009',
								'link' => 'pinterest.com',
								'icon' => 'fa-pinterest',
							),
						)
					),
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/coziplus/images/team/img02.jpg',
					'title'           => esc_html__( 'Glenn Maxwell', 'cozipress' ),
					'subtitle'        => esc_html__( 'Project Manager', 'cozipress' ),
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
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0018',
								'link' => 'pinterest.com',
								'icon' => 'fa-pinterest',
							),
						)
					),
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/coziplus/images/team/img03.jpg',
					'title'           => esc_html__( 'Aaron Finch', 'cozipress' ),
					'subtitle'        => esc_html__( 'Manager and director', 'cozipress' ),
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
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0028',
								'link' => 'pinterest.com',
								'icon' => 'fa-pinterest',
							),
						)
					),
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/coziplus/images/team/img04.jpg',
					'title'           => esc_html__( 'Christiana Ena', 'cozipress' ),
					'subtitle'        => esc_html__( 'Executive', 'cozipress' ),
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
							array(
								'id'   => 'customizer-repeater-social-repeater-team_0038',
								'link' => 'pinterest.com',
								'icon' => 'fa-pinterest',
							),
						)
					),
				),
			)
		)
	);
}


/*
 *
 * Funfact Default
 */
 function cozipress_get_funfact_default() {
	return apply_filters(
		'cozipress_get_funfact_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( '502', 'cozipress-pro' ),
					'subtitle'           => esc_html__( 'Clients', 'cozipress-pro' ),
					'icon_value'       => 'fa-user',
					'id'              => 'customizer_repeater_funfact_001',
					
				),
				array(
					'title'           => esc_html__( '635', 'cozipress-pro' ),
					'subtitle'           => esc_html__( 'Projects', 'cozipress-pro' ),
					'icon_value'       => 'fa-briefcase',
					'id'              => 'customizer_repeater_funfact_002',			
				),
				array(
					'title'           => esc_html__( '36', 'cozipress-pro' ),
					'subtitle'           => esc_html__( 'Team Experts', 'cozipress-pro' ),
					'icon_value'       => 'fa-users',
					'id'              => 'customizer_repeater_funfact_003',
				),
				array(
					'title'           => esc_html__( '723', 'cozipress-pro' ),
					'subtitle'           => esc_html__( 'Comments', 'cozipress-pro' ),
					'icon_value'       => 'fa-comment',
					'id'              => 'customizer_repeater_funfact_004',
				)
			)
		)
	);
}