<?php
function spabiz_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/
	$wp_customize->add_panel(
		'spabiz_frontpage_sections', array(
			'priority' => 32,
			'title' => esc_html__( 'Frontpage Sections', 'spabiz' ),
		)
	);
	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'spabiz' ),
			'panel' => 'spabiz_frontpage_sections',
			'priority' => 1,
		)
	);
	
	
	
	// Head
	$wp_customize->add_setting(
		'slider_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','spabiz'),
			'section' => 'slider_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_slider' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_checkbox',
			'priority' => 1,
		) 
	);
	
	$wp_customize->add_control(
	'hs_slider', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'owlpress' ),
			'section'     => 'slider_setting',
			'type'        => 'checkbox',
		) 
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','spabiz'),
			'section' => 'slider_setting',
		)
	);
	
	// Slider
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 5,
			  'default' => spabiz_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slider','spabiz'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'spabiz' ),
						'item_name'                         => esc_html__( 'Slider', 'spabiz' ),
						
						
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_subtitle2_control' => true,
						'customizer_repeater_subtitle3_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
	
	
	//Pro feature
	class Spabiz_slider_section_upgrade extends WP_Customize_Control {
		public function render_content() { 
		?>	
		
			<a class="customizer_SpaBiz_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/spabiz-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in SpaBiz Pro','spabiz'); ?></a>
			
		<?php
		}
	}
	
	$wp_customize->add_setting( 'spabiz_slider_upgrade_to_pro', array(
		'capability'			=> 'edit_theme_options',
		'sanitize_callback'	=> 'wp_filter_nohtml_kses',
	));
	$wp_customize->add_control(
		new Spabiz_slider_section_upgrade(
		$wp_customize,
		'spabiz_slider_upgrade_to_pro',
			array(
				'section'				=> 'slider_setting'
			)
		)
	);	
	
	
	// Opacity Color
	$wp_customize->add_setting(
	'slider_opacity_color', 
	array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '#e3f5f1',
		'priority' => 6,
    ));
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control
		($wp_customize, 
			'slider_opacity_color', 
			array(
				'label'      => __( 'Opacity Color', 'spabiz' ),
				'section'    => 'slider_setting',
			) 
		) 
	);
}

add_action( 'customize_register', 'spabiz_slider_setting' );