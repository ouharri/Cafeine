<?php
function decorme_cta_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Call Action  Section
	=========================================*/
	$wp_customize->add_section(
		'cta_setting', array(
			'title' => esc_html__( 'Call Action Section', 'decorme' ),
			'priority' => 10,
			'panel' => 'decorme_frontpage_sections',
		)
	);
	
	// Setting head
	$wp_customize->add_setting(
		'cta_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'cta_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','decorme'),
			'section' => 'cta_setting',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting(
		'cta_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_checkbox',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'cta_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','decorme'),
			'section' => 'cta_setting',
		)
	);
	
	

// Call Action Contact // 
	$wp_customize->add_setting(
		'cta_contact_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'cta_contact_head',
		array(
			'type' => 'hidden',
			'label' => __('contact','decorme'),
			'section' => 'cta_setting',
		)
	);
	
	// icon // 
	$wp_customize->add_setting(
    	'cta_contact_icon',
    	array(
			'default'			=> 'fa-phone',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'priority'  => 2,
		)
	);	

	$wp_customize->add_control(new DecorMe_Icon_Picker_Control($wp_customize, 
		'cta_contact_icon',
		array(
		    'label'   		=> __('Icon','decorme'),
		    'section' 		=> 'cta_setting',
			'iconset' => 'fa'
			
		))  
	);	
	
	// Title // 
	$wp_customize->add_setting(
    	'cta_contact_ttl',
    	array(
	        'default'			=> '+1-202-555-0170 ',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 2,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_contact_ttl',
		array(
		    'label'   => __('Title','decorme'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// Content // 
	$wp_customize->add_setting(
		'cta_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'cta_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','decorme'),
			'section' => 'cta_setting',
		)
	);
	
	// Call Action Title // 
	$wp_customize->add_setting(
    	'cta_title',
    	array(
	        'default'			=> 'Contact Us For Your Dreams Home design',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_title',
		array(
		    'label'   => __('Title','decorme'),
		    'section' => 'cta_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Call Action Description // 
	$wp_customize->add_setting(
    	'cta_description',
    	array(
	        'default'			=> __('There are many variations of passages of Lorem Ipsum available but of the majority have suffered alteration in some form.','decorme'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_description',
		array(
		    'label'   => __('Description','decorme'),
		    'section' => 'cta_setting',
			'type'           => 'textarea',
		)  
	);

	// Call Action Button // 
	$wp_customize->add_setting(
		'cta_contact_btn_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 10,
		)
	);

	$wp_customize->add_control(
	'cta_contact_btn_head',
		array(
			'type' => 'hidden',
			'label' => __('Button','decorme'),
			'section' => 'cta_setting',
		)
	);
	
	
	// Button Label // 
	$wp_customize->add_setting(
    	'cta_contact_btn_lbl',
    	array(
	        'default'			=> 'Contact Us',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 8,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_contact_btn_lbl',
		array(
		    'label'   => __('Button Label','decorme'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	
	// Button Link // 
	$wp_customize->add_setting(
    	'cta_contact_btn_url',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_url',
			'priority' => 11,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_contact_btn_url',
		array(
		    'label'   => __('Button Link','decorme'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
}

add_action( 'customize_register', 'decorme_cta_setting' );

// cta selective refresh
function decorme_home_cta_section_partials( $wp_customize ){	
	// cta title
	$wp_customize->selective_refresh->add_partial( 'cta_title', array(
		'selector'            => '.home-cta .cta-info h3',
		'settings'            => 'cta_title',
		'render_callback'  => 'decorme_cta_title_render_callback',
	) );
	
	// cta description
	$wp_customize->selective_refresh->add_partial( 'cta_description', array(
		'selector'            => '.home-cta .cta-info p',
		'settings'            => 'cta_description',
		'render_callback'  => 'decorme_cta_desc_render_callback',
	) );
	
	// cta_contact_ttl
	$wp_customize->selective_refresh->add_partial( 'cta_contact_ttl', array(
		'selector'            => '.home-cta .cta-icon-wrap .cta-number',
		'settings'            => 'cta_contact_ttl',
		'render_callback'  => 'decorme_cta_contact_ttl_render_callback',
	) );
	
	// cta_contact_btn_lbl
	$wp_customize->selective_refresh->add_partial( 'cta_contact_btn_lbl', array(
		'selector'            => '.home-cta .cta-btn-wrap .btn',
		'settings'            => 'cta_contact_btn_lbl',
		'render_callback'  => 'decorme_cta_contact_btn_lbl_render_callback',
	) );
	
	
	}

add_action( 'customize_register', 'decorme_home_cta_section_partials' );

// cta title
function decorme_cta_title_render_callback() {
	return get_theme_mod( 'cta_title' );
}

// cta description
function decorme_cta_desc_render_callback() {
	return get_theme_mod( 'cta_description' );
}


// cta_contact_ttl
function decorme_cta_contact_ttl_render_callback() {
	return get_theme_mod( 'cta_contact_ttl' );
}

// cta_contact_btn_lbl
function decorme_cta_contact_btn_lbl_render_callback() {
	return get_theme_mod( 'cta_contact_btn_lbl' );
}