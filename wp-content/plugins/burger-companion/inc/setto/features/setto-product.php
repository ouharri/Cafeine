<?php
function setto_product_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Product  Section
	=========================================*/
	$wp_customize->add_section(
		'product_setting', array(
			'title' => esc_html__( 'Product Section', 'setto' ),
			'priority' => 3,
			'panel' => 'setto_frontpage_sections',
		)
	);
	
	// Setting Head
	$wp_customize->add_setting(
		'product_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'product_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','setto'),
			'section' => 'product_setting',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting(
		'product_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'product_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','setto'),
			'section' => 'product_setting',
		)
	);
	

	// Product Header Section // 
	$wp_customize->add_setting(
		'product_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'product_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','setto'),
			'section' => 'product_setting',
		)
	);
	
	// Product Title // 
	$wp_customize->add_setting(
    	'product_title',
    	array(
	        'default'			=> __('Sale on','setto'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'product_title',
		array(
		    'label'   => __('Title','setto'),
		    'section' => 'product_setting',
			'type'           => 'text',
		)  
	);
	
	// Product Subtitle // 
	$wp_customize->add_setting(
    	'product_subtitle',
    	array(
	        'default'			=> __('New products','setto'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'product_subtitle',
		array(
		    'label'   => __('Subtitle','setto'),
		    'section' => 'product_setting',
			'type'           => 'text',
		)  
	);
}

add_action( 'customize_register', 'setto_product_setting' );

// product selective refresh
function setto_home_product_section_partials( $wp_customize ){	
	// product title
	$wp_customize->selective_refresh->add_partial( 'product_title', array(
		'selector'            => '.product-section1 .section-title span',
		'settings'            => 'product_title',
		'render_callback'  => 'setto_product_title_render_callback',
	
	) );
	
	// product subtitle
	$wp_customize->selective_refresh->add_partial( 'product_subtitle', array(
		'selector'            => '.product-section1 .section-title h2',
		'settings'            => 'product_subtitle',
		'render_callback'  => 'setto_product_subtitle_render_callback',
	
	) );
	
	}

add_action( 'customize_register', 'setto_home_product_section_partials' );

// product title
function setto_product_title_render_callback() {
	return get_theme_mod( 'product_title' );
}

// product subtitle
function setto_product_subtitle_render_callback() {
	return get_theme_mod( 'product_subtitle' );
}