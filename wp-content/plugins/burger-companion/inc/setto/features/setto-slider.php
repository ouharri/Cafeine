<?php
function setto_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'setto' ),
			'panel' => 'setto_frontpage_sections',
			'priority' => 1,
		)
	);
	
	// Setting Head
	$wp_customize->add_setting(
		'slider_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','setto'),
			'section' => 'slider_setting',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting(
		'slider_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','setto'),
			'section' => 'slider_setting',
		)
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','setto'),
			'section' => 'slider_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add slides
	 */
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 5,
			  'default' => setto_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','setto'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'setto' ),
						'item_name'                         => esc_html__( 'Slider', 'setto' ),
						
						
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
	}	
	
	//Pro feature
		class Setto_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>	
				<a class="customizer_Setto_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/setto-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Setto Pro','setto'); ?></a>
			
			<?php
			} 
		}
		
		$wp_customize->add_setting( 'setto_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Setto_slider_section_upgrade(
			$wp_customize,
			'setto_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting'
				)
			)
		);
}

add_action( 'customize_register', 'setto_slider_setting' );