<?php
function appetizer_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'appetizer' ),
			'panel' => 'appetizer_frontpage_sections',
			'priority' => 1,
		)
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','appetizer'),
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
			  'default' => appetizer_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','appetizer'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'appetizer' ),
						'item_name'                         => esc_html__( 'Slider', 'appetizer' ),
						
						
						'customizer_repeater_icon_control' => false,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_designation_control' => true,
						'customizer_repeater_slide_align' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
	}
	
	
	//Pro feature
		class Appetizer_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Rasam' == $theme->name){
			?>
				<a class="customizer_Appetizer_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/rasam-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Rasam Pro','appetizer'); ?></a>
				
			<?php }else{ ?>
			
				<a class="customizer_Appetizer_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/appetizer-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Appetizer Pro','appetizer'); ?></a>
			
			<?php
			}}
		}
		
	$wp_customize->add_setting( 'appetizer_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Appetizer_slider_section_upgrade(
			$wp_customize,
			'appetizer_slider_upgrade_to_pro',
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
				'label'      => __( 'Opacity', 'appetizer' ),
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

add_action( 'customize_register', 'appetizer_slider_setting' );