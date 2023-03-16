<?php
function cozipress_team_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Team Section
	=========================================*/
	$wp_customize->add_section(
		'team_setting', array(
			'title' => esc_html__( 'Team Section', 'cozipress-pro' ),
			'priority' => 15,
			'panel' => 'cozipress_frontpage_sections',
		)
	);
	
	
	// Team Settings Section // 
	$wp_customize->add_setting(
		'team_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'team_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozipress'),
			'section' => 'team_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_team' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_team', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'team_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Team Header Section // 
	$wp_customize->add_setting(
		'team_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'team_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','cozipress-pro'),
			'section' => 'team_setting',
		)
	);
	
	// Team Title // 
	$wp_customize->add_setting(
    	'team_title',
    	array(
	        'default'			=> __('What We Are','cozipress-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'team_title',
		array(
		    'label'   => __('Title','cozipress-pro'),
		    'section' => 'team_setting',
			'type'           => 'text',
		)  
	);
	
	// Team Subtitle // 
	$wp_customize->add_setting(
    	'team_subtitle',
    	array(
	        'default'			=> __('Our <span class="text-primary">Team</span>','cozipress-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'team_subtitle',
		array(
		    'label'   => __('Subtitle','cozipress-pro'),
		    'section' => 'team_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Team Description // 
	$wp_customize->add_setting(
    	'team_description',
    	array(
	        'default'			=> __('This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.','cozipress-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'team_description',
		array(
		    'label'   => __('Description','cozipress-pro'),
		    'section' => 'team_setting',
			'type'           => 'textarea',
		)  
	);

	// Team content Section // 
	
	$wp_customize->add_setting(
		'team_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'team_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress-pro'),
			'section' => 'team_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add Team
	 */
	
		$wp_customize->add_setting( 'team_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => cozipress_get_team_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'team_contents', 
					array(
						'label'   => esc_html__('Team','cozipress-pro'),
						'section' => 'team_setting',
						'add_field_label'                   => esc_html__( 'Add New Team', 'cozipress-pro' ),
						'item_name'                         => esc_html__( 'Teams', 'cozipress-pro' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_repeater_control' => true,
					) 
				) 
			);
			
			
			//Pro feature
		class Cozipress_team_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_CoziPress_team_upgrade_section up-to-pro" href="https://burgerthemes.com/coziplus-pro/" target="_blank" style="display: none;"><?php _e('More Teams Available in CoziPlus Pro','cozipress'); ?></a>
				
			<?php
			 }
		}
		
		
		$wp_customize->add_setting( 'cozipress_team_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 8,
		));
		$wp_customize->add_control(
			new Cozipress_team_section_upgrade(
			$wp_customize,
			'cozipress_team_upgrade_to_pro',
				array(
					'section'				=> 'team_setting',
				)
			)
		);
}

add_action( 'customize_register', 'cozipress_team_setting' );

// team selective refresh
function cozipress_team_section_partials( $wp_customize ){
	
	// team title
	$wp_customize->selective_refresh->add_partial( 'team_title', array(
		'selector'            => '.team-home .heading-default .ttl',
		'settings'            => 'team_title',
		'render_callback'  => 'cozipress_team_title_render_callback',
	
	) );
	
	// team subtitle
	$wp_customize->selective_refresh->add_partial( 'team_subtitle', array(
		'selector'            => '.team-home .heading-default h2',
		'settings'            => 'team_subtitle',
		'render_callback'  => 'cozipress_team_subtitle_render_callback',
	
	) );
	
	// team description
	$wp_customize->selective_refresh->add_partial( 'team_description', array(
		'selector'            => '.team-home .heading-default p',
		'settings'            => 'team_description',
		'render_callback'  => 'cozipress_team_desc_render_callback',
	
	) );
	// team content
	$wp_customize->selective_refresh->add_partial( 'team_contents', array(
		'selector'            => '.team-home .team-data'
	
	) );
	
	}

add_action( 'customize_register', 'cozipress_team_section_partials' );

// team title
function cozipress_team_title_render_callback() {
	return get_theme_mod( 'team_title' );
}

// team subtitle
function cozipress_team_subtitle_render_callback() {
	return get_theme_mod( 'team_subtitle' );
}

// team description
function cozipress_team_desc_render_callback() {
	return get_theme_mod( 'team_description' );
}