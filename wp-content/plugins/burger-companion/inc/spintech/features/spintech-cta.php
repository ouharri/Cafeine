<?php
function spintech_cta_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	CTA  Section
	=========================================*/
	$wp_customize->add_section(
		'cta_setting', array(
			'title' => esc_html__( 'Call to Action Section', 'spintech' ),
			'priority' => 14,
			'panel' => 'spintech_frontpage_sections',
		)
	);
	
	// CTA Settings Section // 
	
	$wp_customize->add_setting(
		'cta_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'cta_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','spintech'),
			'section' => 'cta_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_cta' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'transport'         => $selective_refresh,
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_cta', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'cta_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// CTA Content Section // 
	$wp_customize->add_setting(
		'cta_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'cta_contents',
		array(
			'type' => 'hidden',
			'label' => __('Content','spintech'),
			'section' => 'cta_setting',
		)
	);
	
	// CTA Title // 
	$wp_customize->add_setting(
    	'cta_title',
    	array(
	        'default'			=> __('DO YOU HAVE ANY PROJECT ?','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_title',
		array(
		    'label'   => __('Title','spintech'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// CTA Description // 
	$wp_customize->add_setting(
    	'cta_description',
    	array(
	        'default'			=> __('Let’s Talk About Business Soluations With Us','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_description',
		array(
		    'label'   => __('Description','spintech'),
		    'section' => 'cta_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Button First //  
	$wp_customize->add_setting(
		'cta_btn_first'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'cta_btn_first',
		array(
			'type' => 'hidden',
			'label' => __('Button First','spintech'),
			'section' => 'cta_setting',
		)
	);
	
	$wp_customize->add_setting(
    	'cta_btn_lbl1',
    	array(
	        'default'			=> __('Join With Us','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 8,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_lbl1',
		array(
		    'label'   => __('Button Label','spintech'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	$wp_customize->add_setting(
    	'cta_btn_link1',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_url',
			'priority' => 9,
		)
	);	
	
	$wp_customize->add_control( 
		'cta_btn_link1',
		array(
		    'label'   => __('Link','spintech'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
}

add_action( 'customize_register', 'spintech_cta_setting' );

// CTA selective refresh
function spintech_ata_section_partials( $wp_customize ){
	
	// hs_cta
	$wp_customize->selective_refresh->add_partial(
		'hs_cta', array(
			'selector' => '#cta-section',
			'container_inclusive' => true,
			'render_callback' => 'cta_setting',
			'fallback_refresh' => true,
		)
	);
	
	// cta_title
	$wp_customize->selective_refresh->add_partial( 'cta_title', array(
		'selector'            => '.home-cta .cta-content p',
		'settings'            => 'cta_title',
		'render_callback'  => 'spintech_cta_title_render_callback',
	
	) );
	
	// cta_description
	$wp_customize->selective_refresh->add_partial( 'cta_description', array(
		'selector'            => '.home-cta .cta-content h3',
		'settings'            => 'cta_description',
		'render_callback'  => 'spintech_cta_description_render_callback',
	) );
	
	// cta_btn_lbl1
	$wp_customize->selective_refresh->add_partial( 'cta_btn_lbl1', array(
		'selector'            => '.home-cta  a.btn-white',
		'settings'            => 'cta_btn_lbl1',
		'render_callback'  => 'spintech_cta_btn_lbl1_render_callback',
	) );
	}

add_action( 'customize_register', 'spintech_ata_section_partials' );

// cta_title
function spintech_cta_title_render_callback() {
	return get_theme_mod( 'cta_title' );
}


// cta_description
function spintech_cta_description_render_callback() {
	return get_theme_mod( 'cta_description' );
}

// cta_btn_lbl1
function spintech_cta_btn_lbl1_render_callback() {
	return get_theme_mod( 'cta_btn_lbl1' );
}