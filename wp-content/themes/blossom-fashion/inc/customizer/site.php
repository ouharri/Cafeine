<?php
/**
 * Blossom Fashion Theme Customizer
 *
 * @package Blossom_Fashion
 */

function blossom_fashion_customize_register( $wp_customize ) {
	
    /** Add postMessage support for site title and description */
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'background_color' )->transport = 'refresh';
    $wp_customize->get_setting( 'background_image' )->transport = 'refresh';
	
    if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'blossom_fashion_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'blossom_fashion_customize_partial_blogdescription',
		) );
	}
    
    /** Site Title Font */
    $wp_customize->add_setting( 
        'site_title_font', 
        array(
            'default' => array(                                			
                'font-family' => 'Rufina',
                'variant'     => 'regular',
            ),
            'sanitize_callback' => array( 'Blossom_Fashion_Fonts', 'sanitize_typography' )
        ) 
    );

	$wp_customize->add_control( 
        new Blossom_Fashion_Typography_Control( 
            $wp_customize, 
            'site_title_font', 
            array(
                'label'       => __( 'Site Title Font', 'blossom-fashion' ),
                'description' => __( 'Site title and tagline font.', 'blossom-fashion' ),
                'section'     => 'title_tagline',
                'priority'    => 60, 
            ) 
        ) 
    );
    
    /** Site Title Font Size*/
    $wp_customize->add_setting( 
        'site_title_font_size', 
        array(
            'default'           => 120,
            'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Fashion_Slider_Control( 
			$wp_customize,
			'site_title_font_size',
			array(
				'section'	  => 'title_tagline',
				'label'		  => __( 'Site Title Font Size', 'blossom-fashion' ),
				'description' => __( 'Change the font size of your site title.', 'blossom-fashion' ),
                'priority'    => 65,
                'choices'	  => array(
					'min' 	=> 10,
					'max' 	=> 200,
					'step'	=> 1,
				)                 
			)
		)
	);
    
}
add_action( 'customize_register', 'blossom_fashion_customize_register' );