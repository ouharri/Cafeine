<?php
function seokart_team_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Team  Section
	=========================================*/
	$wp_customize->add_section(
		'team_setting', array(
			'title' => esc_html__( 'Team Section', 'seokart' ),
			'priority' => 8,
			'panel' => 'seokart_frontpage_sections',
		)
	);


	//  Settings  // 
	
	$wp_customize->add_setting(
		'team_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'team_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','seokart'),
			'section' => 'team_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_team' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_team', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'seokart' ),
			'section'     => 'team_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Team Header Section // 
	$wp_customize->add_setting(
		'team_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'team_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','seokart'),
			'section' => 'team_setting',
		)
	);
	
	// Team Title // 
	$wp_customize->add_setting(
    	'team_title',
    	array(
	        'default'			=> __('Our Team','seokart'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'team_title',
		array(
		    'label'   => __('Title','seokart'),
		    'section' => 'team_setting',
			'type'           => 'text',
		)  
	);
	
	// Team Subtitle // 
	$wp_customize->add_setting(
    	'team_subtitle',
    	array(
	        'default'			=> __('Our Awesome Team <i>Members</i>','seokart'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'team_subtitle',
		array(
		    'label'   => __('Subtitle','seokart'),
		    'section' => 'team_setting',
			'type'           => 'text',
		)  
	);
	
	// Team Description // 
	$wp_customize->add_setting(
    	'team_description',
    	array(
	        'default'			=> __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed eiusm tempor incididunt ut labore et dolore magna aliqua.','seokart'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'team_description',
		array(
		    'label'   => __('Description','seokart'),
		    'section' => 'team_setting',
			'type'           => 'textarea',
		)  
	);

	// Team content Section // 
	
	$wp_customize->add_setting(
		'team_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'team_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','seokart'),
			'section' => 'team_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add team
	 */
	
		$wp_customize->add_setting( 'team_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => seokart_get_team_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'team_contents', 
					array(
						'label'   => esc_html__('Team','seokart'),
						'section' => 'team_setting',
						'add_field_label'                   => esc_html__( 'Add New Team', 'seokart' ),
						'item_name'                         => esc_html__( 'Teams', 'seokart' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
					) 
				) 
			);
	
	
	//Pro feature
		class Seokart_team_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
			if ( 'DigiPress' == $theme->name){	
			?>
				<a class="customizer_SeoKart_team_upgrade_section up-to-pro" href="https://burgerthemes.com/digipress-pro/" target="_blank" style="display: none;"><?php _e('More Teams Available in DigiPress Pro','seokart'); ?></a>
				
			<?php }else{ ?>	
				
				<a class="customizer_SeoKart_team_upgrade_section up-to-pro" href="https://burgerthemes.com/seokart-pro/" target="_blank" style="display: none;"><?php _e('More Teams Available in SeoKart Pro','seokart'); ?></a>
				
			<?php
			} }
		}
		
	$wp_customize->add_setting( 'seokart_team_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 9,
		));
		$wp_customize->add_control(
			new Seokart_team_section_upgrade(
			$wp_customize,
			'seokart_team_upgrade_to_pro',
				array(
					'section'				=> 'team_setting',
				)
			)
		);		
		
	// Background // 
	$wp_customize->add_setting(
		'team_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 9,
		)
	);

	$wp_customize->add_control(
	'team_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','seokart'),
			'section' => 'team_setting',
		)
	);
	
	// Background Image // 
    $wp_customize->add_setting( 
    	'team_bg_img' , 
    	array(
			'default' 			=> esc_url(BURGER_COMPANION_PLUGIN_URL .'/inc/seokart/images/team-bg.jpg'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_url',	
			'priority' => 10,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'team_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'seokart'),
			'section'        => 'team_setting',
		) 
	));
}

add_action( 'customize_register', 'seokart_team_setting' );

// team selective refresh
function seokart_home_team_section_partials( $wp_customize ){	
	// team title
	$wp_customize->selective_refresh->add_partial( 'team_title', array(
		'selector'            => '.team-home .title h6',
		'settings'            => 'team_title',
		'render_callback'  => 'seokart_team_title_render_callback',
	
	) );
	
	// team subtitle
	$wp_customize->selective_refresh->add_partial( 'team_subtitle', array(
		'selector'            => '.team-home .title h2',
		'settings'            => 'team_subtitle',
		'render_callback'  => 'seokart_team_subtitle_render_callback',
	
	) );
	
	// team description
	$wp_customize->selective_refresh->add_partial( 'team_description', array(
		'selector'            => '.team-home .title p',
		'settings'            => 'team_description',
		'render_callback'  => 'seokart_team_desc_render_callback',
	
	) );
	// team content
	$wp_customize->selective_refresh->add_partial( 'team_contents', array(
		'selector'            => '.team-home .hm-team-content'
	) );
	
	}

add_action( 'customize_register', 'seokart_home_team_section_partials' );

// team title
function seokart_team_title_render_callback() {
	return get_theme_mod( 'team_title' );
}

// team subtitle
function seokart_team_subtitle_render_callback() {
	return get_theme_mod( 'team_subtitle' );
}

// team description
function seokart_team_desc_render_callback() {
	return get_theme_mod( 'team_description' );
}