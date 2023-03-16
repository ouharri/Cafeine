<?php
function spintech_funfact_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Funfact Section
	=========================================*/
	$wp_customize->add_section(
		'funfact_setting', array(
			'title' => esc_html__( 'Funfact Section', 'spintech-pro' ),
			'priority' => 12,
			'panel' => 'spintech_frontpage_sections',
		)
	);

	// Funfact Settings Section // 
	
	$wp_customize->add_setting(
		'funfact_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'funfact_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','spintech'),
			'section' => 'funfact_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_funfact' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'priority' => 6,
		) 
	);
	
	$wp_customize->add_control(
	'hs_funfact', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'funfact_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Funfact content Section // 
	
	$wp_customize->add_setting(
		'funfact_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'funfact_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','spintech-pro'),
			'section' => 'funfact_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add Funfact
	 */
	
		$wp_customize->add_setting( 'funfact_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			'default' => spintech_get_funfact_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'funfact_contents', 
					array(
						'label'   => esc_html__('Funfact','spintech-pro'),
						'section' => 'funfact_setting',
						'add_field_label'                   => esc_html__( 'Add New Funfact', 'spintech-pro' ),
						'item_name'                         => esc_html__( 'Funfact', 'spintech-pro' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Spintech_funfact_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>	
			
				<a class="customizer_spintech_funfact_upgrade_section up-to-pro" href="https://burgerthemes.com/spinsoft-pro/" target="_blank" style="display: none;"><?php _e('More Funfact Available in SpinSoft Pro','spintech'); ?></a>
				
			<?php
			}
		}
		
		$wp_customize->add_setting( 'spintech_funfact_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Spintech_funfact_section_upgrade(
			$wp_customize,
			'spintech_funfact_upgrade_to_pro',
				array(
					'section'				=> 'funfact_setting',
				)
			)
		);
}

add_action( 'customize_register', 'spintech_funfact_setting' );

// service selective refresh
function spintech_funfact_section_partials( $wp_customize ){
	
	// Funfact content
	$wp_customize->selective_refresh->add_partial( 'funfact_contents', array(
		'selector'            => '.funfact-section'
	) );
	}

add_action( 'customize_register', 'spintech_funfact_section_partials' );