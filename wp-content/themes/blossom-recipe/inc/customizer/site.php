<?php
/**
 * Site Title Setting
 *
 * @package Blossom_Recipe
 */

function blossom_recipe_customize_register( $wp_customize ) {
	
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'background_color' )->transport = 'refresh';
    $wp_customize->get_setting( 'background_image' )->transport = 'refresh';
	
	if( isset( $wp_customize->selective_refresh ) ){
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'blossom_recipe_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'blossom_recipe_customize_partial_blogdescription',
		) );
	}
    
    $wp_customize->get_section( 'background_image' )->priority = 40;
    
}
add_action( 'customize_register', 'blossom_recipe_customize_register' );