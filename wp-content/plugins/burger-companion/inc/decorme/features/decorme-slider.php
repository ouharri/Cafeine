<?php
function decorme_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/
	$wp_customize->add_panel(
		'decorme_frontpage_sections', array(
			'priority' => 32,
			'title' => esc_html__( 'Frontpage Sections', 'decorme' ),
		)
	);
	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'decorme' ),
			'panel' => 'decorme_frontpage_sections',
			'priority' => 1,
		)
	);
	
	/*=========================================
	Slider Fifth
	=========================================*/
	
	// Setting head
	$wp_customize->add_setting(
		'slider_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 31,
		)
	);

	$wp_customize->add_control(
	'slider_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','decorme'),
			'section' => 'slider_setting',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting(
		'slider_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_checkbox',
			'priority' => 31,
		)
	);

	$wp_customize->add_control(
	'slider_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','decorme'),
			'section' => 'slider_setting',
		)
	);
	
	//Content Head
	$wp_customize->add_setting(
		'slider5_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 31,
		)
	);

	$wp_customize->add_control(
	'slider5_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','decorme'),
			'section' => 'slider_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add slides
	 */
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'slider5', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 32,
			  'default' => decorme_get_slider5_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider5', 
					array(
						'label'   => esc_html__('Slide','decorme'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'decorme' ),
						'item_name'                         => esc_html__( 'Slider', 'decorme' ),
						
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_subtitle2_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_image2_control' => true,						
					) 
				) 
			);
	}	

		//Pro feature
		class DecorMe_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_DecorMe_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/decorme-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in DecorMe Pro','decorme'); ?></a>
			<?php
			} 
		}	
	
		$wp_customize->add_setting( 'decorme_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 32,
		));
		$wp_customize->add_control(
			new DecorMe_slider_section_upgrade(
			$wp_customize,
			'decorme_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);
	// slider opacity
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'slider5_opacity',
			array(
				'default' => '0.8',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'priority' => 33,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'slider5_opacity', 
			array(
				'label'      => __( 'Opacity', 'decorme' ),
				'section'  => 'slider_setting',
				 'input_attrs' => array(
						'min'    => 0,
						'max'    => 1,
						'step'   => 0.1,
						//'suffix' => 'px', //optional suffix
					),
			) ) 
		);
	}
	
}

add_action( 'customize_register', 'decorme_slider_setting' );