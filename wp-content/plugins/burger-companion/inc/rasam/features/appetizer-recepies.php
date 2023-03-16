<?php
function appetizer_recepies_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Recepies  Section
	=========================================*/
	$wp_customize->add_section(
		'recepies_setting', array(
			'title' => esc_html__( 'Recepies Section', 'appetizer' ),
			'priority' => 8,
			'panel' => 'appetizer_frontpage_sections',
		)
	);
	
	// Settings // 
	$wp_customize->add_setting(
		'recepies_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'recepies_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','appetizer'),
			'section' => 'recepies_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_recepies' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
			'priority' => 3,
		) 
	);
	
	$wp_customize->add_control(
	'hs_recepies', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'appetizer' ),
			'section'     => 'recepies_setting',
			'type'        => 'checkbox',
		) 
	);	
	

	// Recepies Header Section // 
	$wp_customize->add_setting(
		'recepies_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'recepies_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','appetizer'),
			'section' => 'recepies_setting',
		)
	);
	
	// Recepies Title // 
	$wp_customize->add_setting(
    	'recepies_title',
    	array(
	        'default'			=> __('Top Recipes','appetizer'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'recepies_title',
		array(
		    'label'   => __('Title','appetizer'),
		    'section' => 'recepies_setting',
			'type'           => 'text',
		)  
	);
	
	// Recepies Description // 
	$wp_customize->add_setting(
    	'recepies_description',
    	array(
	        'default'			=> __('Our Talented Chefs','appetizer'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'recepies_description',
		array(
		    'label'   => __('Description','appetizer'),
		    'section' => 'recepies_setting',
			'type'           => 'textarea',
		)  
	);

	// Recepies content Section // 
	
	$wp_customize->add_setting(
		'recepies_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'recepies_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Left Content','appetizer'),
			'section' => 'recepies_setting',
		)
	);
	
	// recepies_display_num
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'recepies_display_num',
			array(
				'default' => '6',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'appetizer_sanitize_range_value',
				'priority' => 8,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'recepies_display_num', 
			array(
				'label'      => __( 'No of Recepies Display', 'appetizer' ),
				'section'  => 'recepies_setting',
				 'media_query'   => false,
					'input_attr'    => array(
						'desktop' => array(
							'min'    => 1,
							'max'    => 500,
							'step'   => 1,
							'default_value' => 6,
						),
					),
			) ) 
		);
	}
	
	
	// Recepies content Right // 
	$wp_customize->add_setting(
		'recepies_content_rt_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 9,
		)
	);

	$wp_customize->add_control(
	'recepies_content_rt_head',
		array(
			'type' => 'hidden',
			'label' => __('Right Content','appetizer'),
			'section' => 'recepies_setting',
		)
	);
	
	// Recepies List // 
	$wp_customize->add_setting(
			'recepies_list', array(
				'default' => '<div class="top-list-heading">
							<h3>Super Delicious</h3>
							<h2 class="text-primary">Chicken Burger</h2>
						</div>
						<div class="top-list-footer">
							<h5>Call Us Now:</h5>   
							<h3 class="text-primary">+123 456 7890</h3>
						</div>
						<img src="'.esc_url(BURGER_COMPANION_PLUGIN_URL .'inc/rasam/images/toprecipes/toprecipes-list.png').'">',
				'sanitize_callback' => 'wp_kses_post',
				'priority' => 10,
				
			)
		);
		
		$wp_customize->add_control(
	'recepies_list',
		array(
			'type' => 'textarea',
			'label' => __('Recepies List','appetizer'),
			'section' => 'recepies_setting',
		)
	);
}

add_action( 'customize_register', 'appetizer_recepies_setting' );

// recepies selective refresh
function appetizer_home_recepies_section_partials( $wp_customize ){	
	// recepies title
	$wp_customize->selective_refresh->add_partial( 'recepies_title', array(
		'selector'            => '.toprecipes-home .heading-default h2',
		'settings'            => 'recepies_title',
		'render_callback'  => 'appetizer_recepies_title_render_callback',
	) );
	
	// recepies description
	$wp_customize->selective_refresh->add_partial( 'recepies_description', array(
		'selector'            => '.toprecipes-home .heading-default p',
		'settings'            => 'recepies_description',
		'render_callback'  => 'appetizer_recepies_desc_render_callback',
	) );
	
	}

add_action( 'customize_register', 'appetizer_home_recepies_section_partials' );

// recepies title
function appetizer_recepies_title_render_callback() {
	return get_theme_mod( 'recepies_title' );
}

// recepies description
function appetizer_recepies_desc_render_callback() {
	return get_theme_mod( 'recepies_description' );
}