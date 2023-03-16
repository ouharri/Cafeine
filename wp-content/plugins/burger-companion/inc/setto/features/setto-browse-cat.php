<?php
function setto_browse_cat_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Browse Category Panel
	=========================================*/	
	$wp_customize->add_section(
		'browse_cat_setting', array(
			'title' => esc_html__( 'Browse Category Section', 'setto' ),
			'panel' => 'setto_frontpage_sections',
			'priority' => 1,
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
			'section' => 'browse_cat_setting',
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
			'section' => 'browse_cat_setting',
		)
	);
}
add_action( 'customize_register', 'setto_browse_cat_setting' );