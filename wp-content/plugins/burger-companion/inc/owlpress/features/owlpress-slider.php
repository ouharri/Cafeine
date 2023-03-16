<?php
function owlpress_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'owlpress' ),
			'panel' => 'owlpress_frontpage_sections',
			'priority' => 1,
		)
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','owlpress'),
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
			  'default' => owlpress_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','owlpress'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'owlpress' ),
						'item_name'                         => esc_html__( 'Slider', 'owlpress' ),				
						
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_designation_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
	
	
	//Pro feature
		class Owlpress_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() {
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Crowl' == $theme->name){					
			?>
				<a class="customizer_OwlPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/crowl-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Crowl Pro','owlpress'); ?></a>
				
			<?php }else{ ?>	
			
				<a class="customizer_OwlPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/owlpress-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in OwlPress Pro','owlpress'); ?></a>
			<?php
			}}
		}
		
	$wp_customize->add_setting( 'owlpress_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Owlpress_slider_section_upgrade(
			$wp_customize,
			'owlpress_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);
		
		
	// slider opacity
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'slider_opacity',
			array(
				'default' => '0.8',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'priority' => 6,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'slider_opacity', 
			array(
				'label'      => __( 'Opacity', 'owlpress' ),
				'section'  => 'slider_setting',
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

add_action( 'customize_register', 'owlpress_slider_setting' );