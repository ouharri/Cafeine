<?php
function spabiz_typography( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

	$wp_customize->add_panel(
		'spabiz_typography', array(
			'priority' => 38,
			'title' => esc_html__( 'Typography', 'spabiz' ),
		)
	);	
	
	/*=========================================
	SpaBiz Typography
	=========================================*/
	$wp_customize->add_section(
        'spabiz_typography',
        array(
        	'priority'      => 1,
            'title' 		=> __('Body Typography','spabiz'),
			'panel'  		=> 'spabiz_typography',
		)
    );
	
	// Body Font Size // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'spabiz_body_font_size',
			array(
				'default'     	=> '16',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'spabiz_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'spabiz_body_font_size', 
			array(
				'label'      => __( 'Size', 'spabiz' ),
				'section'  => 'spabiz_typography',
				'priority'      => 2,
                'input_attr'    => array(
                        'min'           => 0,
                        'max'           => 50,
                        'step'          => 1,
                ),
			) ) 
		);
	}
	
	// Body Font Size // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'spabiz_body_line_height',
			array(
				'default'  =>'1.5',
				'capability'     	=> 'edit_theme_options',
				//'sanitize_callback' => 'spabiz_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'spabiz_body_line_height', 
			array(
				'label'      => __( 'Line Height', 'spabiz' ),
				'section'  => 'spabiz_typography',
				'priority'      => 3,
                'input_attrs' => array(
					'min'    => 0,
					'max'    => 3,
					'step'   => 0.1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	}
	
	// Body Font style // 
	 $wp_customize->add_setting( 'spabiz_body_font_style', array(
      'capability'        => 'edit_theme_options',
      'default'           => 'inherit',
      'transport'         => 'postMessage',
      'sanitize_callback' => 'spabiz_sanitize_select',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Control(
                $wp_customize, 'spabiz_body_font_style', array(
            'label'       => __( 'Font Style', 'spabiz' ),
            'section'     => 'spabiz_typography',
            'type'        =>  'select',
            'priority'    => 6,
            'choices'     =>  array(
                'inherit'   =>  __( 'Inherit', 'spabiz' ),
                'normal'       =>  __( 'Normal', 'spabiz' ),
                'italic'       =>  __( 'Italic', 'spabiz' ),
                'oblique'       =>  __( 'oblique', 'spabiz' ),
                ),
            )
        )
    );
	// Body Text Transform // 
	 $wp_customize->add_setting( 'spabiz_body_text_transform', array(
      'capability'        => 'edit_theme_options',
      'default'           => 'inherit',
      'transport'         => 'postMessage',
      'sanitize_callback' => 'spabiz_sanitize_select',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize, 'spabiz_body_text_transform', array(
                'label'       => __( 'Transform', 'spabiz' ),
                'section'     => 'spabiz_typography',
                'type'        => 'select',
                'priority'    => 7,
                'choices'     => array(
                    'inherit'       =>  __( 'Default', 'spabiz' ),
                    'uppercase'     =>  __( 'Uppercase', 'spabiz' ),
                    'lowercase'     =>  __( 'Lowercase', 'spabiz' ),
                    'capitalize'    =>  __( 'Capitalize', 'spabiz' ),
                ),
            )
        )
    );
	/*=========================================
	 SpaBiz Typography Headings
	=========================================*/
	$wp_customize->add_section(
        'spabiz_headings_typography',
        array(
        	'priority'      => 2,
            'title' 		=> __('Headings','spabiz'),
			'panel'  		=> 'spabiz_typography',
		)
    );
	
	/*=========================================
	 SpaBiz Typography H1
	=========================================*/
	for ( $i = 1; $i <= 6; $i++ ) {
	if($i  == '1'){$j=36;}elseif($i  == '2'){$j=32;}elseif($i  == '3'){$j=28;}elseif($i  == '4'){$j=24;}elseif($i  == '5'){$j=20;}else{$j=16;}
	$wp_customize->add_setting(
		'h' . $i . '_typography'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'h' . $i . '_typography',
		array(
			'type' => 'hidden',
			'label' => esc_html('H' . $i .'','spabiz'),
			'section' => 'spabiz_headings_typography',
		)
	);

	// Heading Font Size // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'spabiz_h' . $i . '_font_size',
			array(
				'default'     	=> $j,
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'spabiz_sanitize_range_value',
				'transport'         => 'postMessage'
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'spabiz_h' . $i . '_font_size', 
			array(
				'label'      => __( 'Font Size', 'spabiz' ),
				'section'  => 'spabiz_headings_typography',
				'input_attr'    => array(
                       'min'           => 1,
                        'max'           => 100,
                        'step'          => 1,
				)	
			) ) 
		);
	}
	
	// Heading Font Size // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'spabiz_h' . $i . '_line_height',
			array(
				'capability'     	=> 'edit_theme_options',
				//'sanitize_callback' => 'spabiz_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'spabiz_h' . $i . '_line_height', 
			array(
				'label'      => __( 'Line Height', 'spabiz' ),
				'section'  => 'spabiz_headings_typography',
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 5,
					'step'   => 0.1,
					//'suffix' => 'px', //optional suffix
				),
				 'input_attr'    => array(
                       'min'           => 0,
                        'max'           => 3,
                        'step'          => 0.1,
				)	
			) ) 
		);
		}
	
	// Heading Font style // 
	 $wp_customize->add_setting( 'spabiz_h' . $i . '_font_style', array(
      'capability'        => 'edit_theme_options',
      'default'           => 'inherit',
      'transport'         => 'postMessage',
      'sanitize_callback' => 'spabiz_sanitize_select',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Control(
                $wp_customize, 'spabiz_h' . $i . '_font_style', array(
            'label'       => __( 'Font Style', 'spabiz' ),
            'section'     => 'spabiz_headings_typography',
            'type'        =>  'select',
            'choices'     =>  array(
                'inherit'   =>  __( 'Inherit', 'spabiz' ),
                'normal'       =>  __( 'Normal', 'spabiz' ),
                'italic'       =>  __( 'Italic', 'spabiz' ),
                'oblique'       =>  __( 'oblique', 'spabiz' ),
                ),
            )
        )
    );
	
	// Heading Text Transform // 
	 $wp_customize->add_setting( 'spabiz_h' . $i . '_text_transform', array(
      'capability'        => 'edit_theme_options',
      'default'           => 'inherit',
      'transport'         => 'postMessage',
      'sanitize_callback' => 'spabiz_sanitize_select',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize, 'spabiz_h' . $i . '_text_transform', array(
                'label'       => __( 'Text Transform', 'spabiz' ),
                'section'     => 'spabiz_headings_typography',
                'type'        => 'select',
                'choices'     => array(
                    'inherit'       =>  __( 'Default', 'spabiz' ),
                    'uppercase'     =>  __( 'Uppercase', 'spabiz' ),
                    'lowercase'     =>  __( 'Lowercase', 'spabiz' ),
                    'capitalize'    =>  __( 'Capitalize', 'spabiz' ),
                ),
            )
        )
    );
}
}
add_action( 'customize_register', 'spabiz_typography' );