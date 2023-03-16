<?php
function decorme_info_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Info  Section
	=========================================*/
	$wp_customize->add_section(
		'info_setting', array(
			'title' => esc_html__( 'Info Section', 'decorme' ),
			'priority' => 2,
			'panel' => 'decorme_frontpage_sections',
		)
	);
	
	// Setting head
	$wp_customize->add_setting(
		'info_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 10,
		)
	);

	$wp_customize->add_control(
	'info_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','decorme'),
			'section' => 'info_setting',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting(
		'info_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_checkbox',
			'priority' => 10,
		)
	);

	$wp_customize->add_control(
	'info_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','decorme'),
			'section' => 'info_setting',
		)
	);
	
	
	// Info content Section // 	
	$wp_customize->add_setting(
		'info2_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 11,
		)
	);

	$wp_customize->add_control(
	'info2_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Info','decorme'),
			'section' => 'info_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add info
	 */
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'info2_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 12,
			 'default' => decorme_get_info2_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'info2_contents', 
					array(
						'label'   => esc_html__('Information','decorme'),
						'section' => 'info_setting',
						'add_field_label'                   => esc_html__( 'Add New Information', 'decorme' ),
						'item_name'                         => esc_html__( 'Information', 'decorme' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	}

	//Pro feature
		class DecorMe_info_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_DecorMe_info_upgrade_section up-to-pro" href="https://burgerthemes.com/decorme-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in DecorMe Pro','decorme'); ?></a>
			<?php
			} 
		}	
	
		$wp_customize->add_setting( 'decorme_info_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 12,
		));
		$wp_customize->add_control(
			new DecorMe_info_section_upgrade(
			$wp_customize,
			'decorme_info_upgrade_to_pro',
				array(
					'section'				=> 'info_setting',
				)
			)
		);	
}

add_action( 'customize_register', 'decorme_info_setting' );

// info selective refresh
function decorme_home_info_section_partials( $wp_customize ){	
	// info content
	$wp_customize->selective_refresh->add_partial( 'info_contents', array(
		'selector'            => '.info-section.info-one .info-wrapper'
	) );
	
	}

add_action( 'customize_register', 'decorme_home_info_section_partials' );