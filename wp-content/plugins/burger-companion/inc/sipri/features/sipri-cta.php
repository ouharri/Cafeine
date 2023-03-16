<?php
function cozipress_home_cta_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Info  Section
	=========================================*/
	$wp_customize->add_section(
		'cta_setting', array(
			'title' => esc_html__( 'Call to Action Section', 'cozipress' ),
			'priority' => 3,
			'panel' => 'cozipress_frontpage_sections',
		)
	);
	/*=========================================
	CTA Section
	=========================================*/
	
	// Settings
	$wp_customize->add_setting(
		'about_pg_cta_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 65,
		)
	);

	$wp_customize->add_control(
	'about_pg_cta_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozipress'),
			'section' => 'cta_setting',
		)
	);
	
	// Hide/ Show Setting // 
	$wp_customize->add_setting( 
		'hs_pg_about_cta' , 
			array(
			'default' => '1',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'capability' => 'edit_theme_options',
			'priority' => 66,
		) 
	);
	
	$wp_customize->add_control(
	'hs_pg_about_cta', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'cozipress' ),
			'section'     => 'cta_setting',
			'type'        => 'checkbox'
		) 
	);
	
	
	// Content
	$wp_customize->add_setting(
		'about_pg_cta_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 66,
		)
	);

	$wp_customize->add_control(
	'about_pg_cta_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress'),
			'section' => 'cta_setting',
		)
	);
	
	// icon // 
	$wp_customize->add_setting(
    	'pg_about_cta_head_icon',
    	array(
	        'default' => 'fa-phone',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'priority'  => 66,
		)
	);	

	$wp_customize->add_control(new Cozipress_Icon_Picker_Control($wp_customize, 
		'pg_about_cta_head_icon',
		array(
		    'label'   		=> __('Icon','cozipress'),
		    'section' 		=> 'cta_setting',
			'iconset' => 'fa',
			
		))  
	);	
	
	//  Image // 
    $wp_customize->add_setting( 
    	'pg_about_cta_head_img' , 
    	array(
			'default' 			=> BURGER_COMPANION_PLUGIN_URL .'inc/sipri/images/cta/avatar-1.png',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_url',	
			'priority' => 66,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'pg_about_cta_head_img' ,
		array(
			'label'          => esc_html__( 'Image', 'cozipress'),
			'section'        => 'cta_setting',
		) 
	));	
	
	// Title // 
	$wp_customize->add_setting(
    	'pg_about_cta_ttl',
    	array(
	        'default'			=> __("Let's Talk About Business Solutions",'cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 67,
		)
	);	
	
	$wp_customize->add_control( 
		'pg_about_cta_ttl',
		array(
		    'label'   => __('Title','cozipress'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// Description // 
	$wp_customize->add_setting(
    	'pg_about_cta_desc',
    	array(
	        'default'			=> __('It is a long established fact that a reader','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 68,
		)
	);	
	
	$wp_customize->add_control( 
		'pg_about_cta_desc',
		array(
		    'label'   => __('Description','cozipress'),
		    'section' => 'cta_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Contact Info // 
	$wp_customize->add_setting(
    	'pg_about_cta_ctinfo',
    	array(
	        'default'			=> '<h6 class="title">Help Desk 24/7</h6><p class="text"><a href="tel:+12 345 678 90">(+12 345 678 90)</a></p>',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 69,
		)
	);	
	
	$wp_customize->add_control( 
		'pg_about_cta_ctinfo',
		array(
		    'label'   => __('Contact Info','cozipress'),
		    'section' => 'cta_setting',
			'type'           => 'textarea',
		)  
	);
	
	// icon // 
	$wp_customize->add_setting(
    	'pg_about_cta_btn_icon',
    	array(
	        'default' => 'fa-headphones',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'priority'  => 70,
		)
	);	

	$wp_customize->add_control(new Cozipress_Icon_Picker_Control($wp_customize, 
		'pg_about_cta_btn_icon',
		array(
		    'label'   		=> __('Button Icon','cozipress'),
		    'section' 		=> 'cta_setting',
			'iconset' => 'fa',
			
		))  
	);	

	// Button Label // 
	$wp_customize->add_setting(
    	'pg_about_cta_btn_lbl',
    	array(
	        'default'			=> __('Live Chat','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 71,
		)
	);	
	
	$wp_customize->add_control( 
		'pg_about_cta_btn_lbl',
		array(
		    'label'   => __('Button Label','cozipress'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);	
	
	// Button Link // 
	$wp_customize->add_setting(
    	'pg_about_cta_btn_url',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_url',
			'priority' => 72,
		)
	);	
	
	$wp_customize->add_control( 
		'pg_about_cta_btn_url',
		array(
		    'label'   => __('Button Url','cozipress'),
		    'section' => 'cta_setting',
			'type'           => 'text',
		)  
	);
	
	// Background
	$wp_customize->add_setting(
		'about_pg_cta_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 72,
		)
	);

	$wp_customize->add_control(
	'about_pg_cta_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','cozipress'),
			'section' => 'cta_setting',
		)
	);
	
	//  Background Image // 
    $wp_customize->add_setting( 
    	'pg_about_cta_bg_img' , 
    	array(
			'default' 			=> BURGER_COMPANION_PLUGIN_URL . 'inc/sipri/images/cta/dotted_image.png',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_url',	
			'priority' => 73,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'pg_about_cta_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'cozipress'),
			'section'        => 'cta_setting',
		) 
	));	
	
	// Background Attachment // 
	$wp_customize->add_setting( 
		'pg_about_cta_bg_attach' , 
			array(
			'default' => 'scroll',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_select',
			'priority'  => 74,
		) 
	);
	
	$wp_customize->add_control(
	'pg_about_cta_bg_attach' , 
		array(
			'label'          => __( 'Background Attachment', 'cozipress' ),
			'section'        => 'cta_setting',
			'type'           => 'select',
			'choices'        => 
			array(
				'inherit' => __( 'Inherit', 'cozipress' ),
				'scroll' => __( 'Scroll', 'cozipress' ),
				'fixed'   => __( 'Fixed', 'cozipress' )
			) 
		) 
	);
}

add_action( 'customize_register', 'cozipress_home_cta_setting' );

// CTA selective refresh
function cozipress_home_cta_section_partials( $wp_customize ){	
	// pg_about_cta_ttl
	$wp_customize->selective_refresh->add_partial( 'pg_about_cta_ttl', array(
		'selector'            => '.cta-section .cta-info h3',
		'settings'            => 'pg_about_cta_ttl',
		'render_callback'  => 'cozipress_pg_about_cta_ttl_render_callback',
	) );
	
	// pg_about_cta_desc
	$wp_customize->selective_refresh->add_partial( 'pg_about_cta_desc', array(
		'selector'            => '.cta-section .cta-info h5',
		'settings'            => 'pg_about_cta_desc',
		'render_callback'  => 'cozipress_pg_about_cta_desc_render_callback',
	) );
	
	// pg_about_cta_ctinfo
	$wp_customize->selective_refresh->add_partial( 'pg_about_cta_ctinfo', array(
		'selector'            => '.cta-section .contact-info',
		'settings'            => 'pg_about_cta_ctinfo',
		'render_callback'  => 'cozipress_pg_about_cta_ctinfo_render_callback',
	) );
	
	// pg_about_cta_btn_lbl
	$wp_customize->selective_refresh->add_partial( 'pg_about_cta_btn_lbl', array(
		'selector'            => '.cta-section .cta-btns a',
		'settings'            => 'pg_about_cta_btn_lbl',
		'render_callback'  => 'cozipress_pg_about_cta_btn_lbl_render_callback',
	) );
}
add_action( 'customize_register', 'cozipress_home_cta_section_partials' );


// pg_about_cta_btn_lbl
function cozipress_pg_about_cta_btn_lbl_render_callback() {
	return get_theme_mod( 'pg_about_cta_btn_lbl' );
}

// pg_about_cta_ctinfo
function cozipress_pg_about_cta_ctinfo_render_callback() {
	return get_theme_mod( 'pg_about_cta_ctinfo' );
}

// pg_about_cta_desc
function cozipress_pg_about_cta_desc_render_callback() {
	return get_theme_mod( 'pg_about_cta_desc' );
}

// pg_about_cta_ttl
function cozipress_pg_about_cta_ttl_render_callback() {
	return get_theme_mod( 'pg_about_cta_ttl' );
}