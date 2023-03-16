<?php
/**
 * Color Setting
 *
 * @package Blossom_Fashion
 */

function blossom_fashion_customize_register_color( $wp_customize ) {
    
    /** Primary Color*/
    $wp_customize->add_setting( 
        'primary_color', array(
            'default'           => '#f1d3d3',
            'sanitize_callback' => 'sanitize_hex_color'
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'primary_color', 
            array(
                'label'       => __( 'Primary Color', 'blossom-fashion' ),
                'description' => __( 'Primary color of the theme.', 'blossom-fashion' ),
                'section'     => 'colors',
                'priority'    => 5,                
            )
        )
    );
    
}
add_action( 'customize_register', 'blossom_fashion_customize_register_color' );