<?php
function cozipress_burger_general_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	/*=========================================
	Blog  Section
	=========================================*/
	// Blog content Section // 
	
	$wp_customize->add_setting(
		'blog_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'blog_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress'),
			'section' => 'blog_setting',
		)
	);
	
	// blog_display_num
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'blog_display_num',
			array(
				'default' => '3',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'cozipress_sanitize_range_value',
				'priority' => 8,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'blog_display_num', 
			array(
				'label'      => __( 'No of Posts Display', 'cozipress' ),
				'section'  => 'blog_setting',
				 'input_attrs' => array(
					'min'    => 1,
					'max'    => 100,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	}
	/*=========================================
	Breadcrumb  Section
	=========================================*/
	
	// Breadcrumb Content Section // 
	$wp_customize->add_setting(
		'breadcrumb_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'breadcrumb_contents',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress'),
			'section' => 'breadcrumb_setting',
			'priority' => 5,
		)
	);
	
	// Content size // 
	$wp_customize->add_setting(
    	'breadcrumb_min_height',
    	array(
			'default'     	=> '236',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_range_value',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'breadcrumb_min_height', 
			array(
				'label'      => __( 'Min Height', 'cozipress'),
				'section'  => 'breadcrumb_setting',
				'priority' => 8,
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 1000,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	
	// Image Opacity // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
	$wp_customize->add_setting(
    	'breadcrumb_bg_img_opacity',
    	array(
	        'default'			=> '0.75',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 
	new Burger_Customizer_Range_Control( $wp_customize, 'breadcrumb_bg_img_opacity', 
		array(
			'label'      => __( 'Opacity', 'cozipress'),
			'section'  => 'breadcrumb_setting',
			'settings' => 'breadcrumb_bg_img_opacity',
			'priority' => 11,
			  'input_attrs' => array(
					'min'    => 0.1,
					'max'    => 1,
					'step'   => 0.1,
					//'suffix' => 'px', //optional suffix
				),
		) ) 
	);
	}
}

add_action( 'customize_register', 'cozipress_burger_general_setting' );

