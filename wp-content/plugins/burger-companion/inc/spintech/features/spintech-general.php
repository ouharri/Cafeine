<?php
function spintech_burger_general_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	
	// Blog content Section // 
	
	$wp_customize->add_setting(
		'blog_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'blog_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','spintech'),
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
				'sanitize_callback' => 'spintech_sanitize_range_value',
				'priority' => 8,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'blog_display_num', 
			array(
				'label'      => __( 'No of Posts Display', 'spintech' ),
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
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'breadcrumb_contents',
		array(
			'type' => 'hidden',
			'label' => __('Content','spintech'),
			'section' => 'breadcrumb_setting',
		)
	);
	
	// Content size // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'breadcrumb_min_height',
			array(
				'default'     	=> '236',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'spintech_sanitize_range_value',
				'transport'         => 'postMessage',
				'priority' => 8,
			)
		);
		$wp_customize->add_control( 
			new Burger_Customizer_Range_Control( $wp_customize, 'breadcrumb_min_height', 
				array(
					'label'      => __( 'Min Height', 'spintech'),
					'section'  => 'breadcrumb_setting',
					'input_attrs' => array(
						'min'    => 1,
						'max'    => 1000,
						'step'   => 1,
						//'suffix' => 'px', //optional suffix
					),
				) ) 
			);
	}		
	
	// Image Opacity // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
	$wp_customize->add_setting(
    	'breadcrumb_bg_img_opacity',
    	array(
	        'default'			=> '0.6',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'priority'  => 11,
		)
	);
	$wp_customize->add_control( 
	new Burger_Customizer_Range_Control( $wp_customize, 'breadcrumb_bg_img_opacity', 
		array(
			'label'      => __( 'Opacity', 'spintech'),
			'section'  => 'breadcrumb_setting',
			'settings' => 'breadcrumb_bg_img_opacity',
             'input_attrs' => array(
				'min'    => 0,
				'max'    => 0.9,
				'step'   => 0.1,
				//'suffix' => 'px', //optional suffix
			),
		) ) 
	);
	}
}

add_action( 'customize_register', 'spintech_burger_general_setting' );
