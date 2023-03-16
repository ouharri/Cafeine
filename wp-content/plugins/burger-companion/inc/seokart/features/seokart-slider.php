<?php
function seokart_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/
	$wp_customize->add_panel(
		'seokart_frontpage_sections', array(
			'priority' => 32,
			'title' => esc_html__( 'Frontpage Sections', 'seokart' ),
		)
	);
	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'seokart' ),
			'panel' => 'seokart_frontpage_sections',
			'priority' => 1,
		)
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','seokart'),
			'section' => 'slider_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add slides
	 */
	
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 5,
			  'default' => seokart_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','seokart'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'seokart' ),
						'item_name'                         => esc_html__( 'Slider', 'seokart' ),
						
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_slide_align' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
			
			
	//Pro feature
		class Seokart_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
			if ( 'DigiPress' == $theme->name){	
			?>
				<a class="customizer_SeoKart_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/digipress-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in DigiPress Pro','seokart'); ?></a>
				
			<?php }else{ ?>		
			
				<a class="customizer_SeoKart_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/seokart-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in SeoKart Pro','seokart'); ?></a>
				
			<?php
			} }
		}
		
	$wp_customize->add_setting( 'seokart_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Seokart_slider_section_upgrade(
			$wp_customize,
			'seokart_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);		
}

add_action( 'customize_register', 'seokart_slider_setting' );