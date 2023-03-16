<?php
function appetizer_product_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Product  Section
	=========================================*/
	$wp_customize->add_section(
		'product_setting', array(
			'title' => esc_html__( 'Product Section', 'appetizer' ),
			'priority' => 8,
			'panel' => 'appetizer_frontpage_sections',
		)
	);

	// Settings // 
	$wp_customize->add_setting(
		'product_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'product_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','appetizer'),
			'section' => 'product_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_product' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
			'priority' => 3,
		) 
	);
	
	$wp_customize->add_control(
	'hs_product', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'appetizer' ),
			'section'     => 'product_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Product Header Section // 
	$wp_customize->add_setting(
		'product_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'product_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','appetizer'),
			'section' => 'product_setting',
		)
	);
	
	// Product Title // 
	$wp_customize->add_setting(
    	'product_title',
    	array(
	        'default'			=> __('Special Dishes Today','appetizer'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'product_title',
		array(
		    'label'   => __('Title','appetizer'),
		    'section' => 'product_setting',
			'type'           => 'text',
		)  
	);
	
	// Product Description // 
	$wp_customize->add_setting(
    	'product_description',
    	array(
	        'default'			=> __('Lets Discover Food','appetizer'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'product_description',
		array(
		    'label'   => __('Description','appetizer'),
		    'section' => 'product_setting',
			'type'           => 'textarea',
		)  
	);

	// Product content Section // 
	
	$wp_customize->add_setting(
		'product_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'product_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','appetizer'),
			'section' => 'product_setting',
		)
	);
	
	// product_display_num
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'product_display_num',
			array(
				'default' => '4',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'appetizer_sanitize_range_value',
				'priority' => 8,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'product_display_num', 
			array(
				'label'      => __( 'No of Product Display', 'appetizer' ),
				'section'  => 'product_setting',
				'input_attrs' => array(
					'min'    => 1,
					'max'    => 500,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	}
	
	
	// Product Background Section // 
	
	$wp_customize->add_setting(
		'product_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 11,
		)
	);

	$wp_customize->add_control(
	'product_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','appetizer'),
			'section' => 'product_setting',
		)
	);
	
	
	// Background Image // 
    $wp_customize->add_setting( 
    	'product_bg_img' , 
    	array(
			'default' 			=> esc_url(BURGER_COMPANION_PLUGIN_URL .'inc/appetizer/images/product/product_bg.jpg'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_url',	
			'priority' => 12,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'product_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'appetizer'),
			'section'        => 'product_setting',
		) 
	));
	
	// Background Attachment // 
	$wp_customize->add_setting( 
		'product_back_attach' , 
			array(
			'default' => 'fixed',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_select',
			'priority'  => 13,
		) 
	);
	
	$wp_customize->add_control(
	'product_back_attach' , 
		array(
			'label'          => __( 'Background Attachment', 'appetizer' ),
			'section'        => 'product_setting',
			'type'           => 'select',
			'choices'        => 
			array(
				'inherit' => __( 'Inherit', 'appetizer' ),
				'scroll' => __( 'Scroll', 'appetizer' ),
				'fixed'   => __( 'Fixed', 'appetizer' )
			) 
		) 
	);
}

add_action( 'customize_register', 'appetizer_product_setting' );

// product selective refresh
function appetizer_home_product_section_partials( $wp_customize ){	
	// product title
	$wp_customize->selective_refresh->add_partial( 'product_title', array(
		'selector'            => '.product-home .heading-default h2',
		'settings'            => 'product_title',
		'render_callback'  => 'appetizer_product_title_render_callback',
	) );
	
	// product description
	$wp_customize->selective_refresh->add_partial( 'product_description', array(
		'selector'            => '.product-home .heading-default p',
		'settings'            => 'product_description',
		'render_callback'  => 'appetizer_product_desc_render_callback',
	) );
	
	}

add_action( 'customize_register', 'appetizer_home_product_section_partials' );

// product title
function appetizer_product_title_render_callback() {
	return get_theme_mod( 'product_title' );
}

// product description
function appetizer_product_desc_render_callback() {
	return get_theme_mod( 'product_description' );
}