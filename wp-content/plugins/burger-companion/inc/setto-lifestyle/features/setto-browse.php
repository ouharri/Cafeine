<?php
function setto_browse_cat4_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Browse Category Section Panel
	=========================================*/	
	$wp_customize->add_section(
		'browse_cat4_setting', array(
			'title' => esc_html__( 'Browse Category Section', 'setto' ),
			'panel' => 'setto_frontpage_sections',
			'priority' => 2,
		)
	);
	
	// Setting Head
	$wp_customize->add_setting(
		'browse_cat_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'browse_cat_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','setto'),
			'section' => 'browse_cat4_setting',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting(
		'browse_cat_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'browse_cat_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','setto'),
			'section' => 'browse_cat4_setting',
		)
	);
	
	/*=========================================
	Head
	=========================================*/
	 $wp_customize->add_setting(
		'browse_cat4_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'browse_cat4_head',
		array(
			'type' => 'hidden',
			'label' => __('Heading','setto'),
			'section' => 'browse_cat4_setting',
		)
	);
	
	//  Title // 
	$wp_customize->add_setting(
    	'browse_cat4_ttl',
    	array(
	        'default'			=> __('Browse categories','setto'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 2,
		)
	);	
	
	$wp_customize->add_control( 
		'browse_cat4_ttl',
		array(
		    'label'   => __('Title','setto'),
		    'section' => 'browse_cat4_setting',
			'type'           => 'text',
		)  
	);
	
	//  Subtitle // 
	$wp_customize->add_setting(
    	'browse_cat4_subttl',
    	array(
	        'default'			=> __('You may also like','setto'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 3,
		)
	);	
	
	$wp_customize->add_control( 
		'browse_cat4_subttl',
		array(
		    'label'   => __('Subtitle','setto'),
		    'section' => 'browse_cat4_setting',
			'type'           => 'text',
		)  
	);
	 
	/*=========================================
	 Content Head
	=========================================*/
	$wp_customize->add_setting(
		'browse_cat4_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'browse_cat4_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','setto'),
			'section' => 'browse_cat4_setting',
		)
	);
	
	//  More // 
	$wp_customize->add_setting(
    	'browse_cat4_more',
    	array(
	        'default'			=> __('FIND OUT MORE','setto'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'browse_cat4_more',
		array(
		    'label'   => __('More Title','setto'),
		    'section' => 'browse_cat4_setting',
			'type'           => 'text',
		)  
	);
	
	//  Link // 
	$wp_customize->add_setting(
    	'browse_cat4_more_link',
    	array(
	        'default'			=> "#",
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_url',
			'priority' => 7,
		)
	);	
	
	$wp_customize->add_control( 
		'browse_cat4_more_link',
		array(
		    'label'   => __('link','setto'),
		    'section' => 'browse_cat4_setting',
			'type'           => 'text',
		)  
	);
	
}

add_action( 'customize_register', 'setto_browse_cat4_setting' );


// selective refresh
function setto_home_browse_cat4_section_partials( $wp_customize ){	
	// browse_cat4_ttl
	$wp_customize->selective_refresh->add_partial( 'browse_cat4_ttl', array(
		'selector'            => '.pdt-category.home5 .section-title .sub-title',
		'settings'            => 'browse_cat4_ttl',
		'render_callback'  => 'setto_browse_cat4_ttl_render_callback',
	) );
	
	// browse_cat4_subttl
	$wp_customize->selective_refresh->add_partial( 'browse_cat4_subttl', array(
		'selector'            => '.pdt-category.home5 .section-title h2',
		'settings'            => 'browse_cat4_subttl',
		'render_callback'  => 'setto_browse_cat4_subttl_render_callback',
	) );
	
	// browse_cat4_more
	$wp_customize->selective_refresh->add_partial( 'browse_cat4_more', array(
		'selector'            => '.pdt-category.home5 .more-collection h2',
		'settings'            => 'browse_cat4_more',
		'render_callback'  => 'setto_browse_cat4_more_render_callback',
	) );
	
	}
add_action( 'customize_register', 'setto_home_browse_cat4_section_partials' );

// browse_cat4_ttl
function setto_browse_cat4_ttl_render_callback() {
	return get_theme_mod( 'browse_cat4_ttl' );
}

// browse_cat4_subttl
function setto_browse_cat4_subttl_render_callback() {
	return get_theme_mod( 'browse_cat4_subttl' );
}

// browse_cat4_more
function setto_browse_cat4_more_render_callback() {
	return get_theme_mod( 'browse_cat4_more' );
}