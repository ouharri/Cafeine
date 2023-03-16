<?php
function decorme_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service  Section
	=========================================*/
	$wp_customize->add_section(
		'service_setting', array(
			'title' => esc_html__( 'Service Section', 'decorme' ),
			'priority' => 3,
			'panel' => 'decorme_frontpage_sections',
		)
	);

	// Setting head
	$wp_customize->add_setting(
		'service_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'service_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','decorme'),
			'section' => 'service_setting',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting(
		'service_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_checkbox',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'service_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','decorme'),
			'section' => 'service_setting',
		)
	);
	
	
	// Service Header Section // 
	$wp_customize->add_setting(
		'service_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'service_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','decorme'),
			'section' => 'service_setting',
		)
	);
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
	        'default'			=> __('Our Services','decorme'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','decorme'),
		    'section' => 'service_setting',
			'type'           => 'text',
		)  
	);
	
	// Service Subtitle // 
	$wp_customize->add_setting(
    	'service_subtitle',
    	array(
	        'default'			=> __('Our Services','decorme'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'service_subtitle',
		array(
		    'label'   => __('Subtitle','decorme'),
		    'section' => 'service_setting',
			'type'           => 'text',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('<span class="font-weight-normal">We Provide Best</span> Services','decorme'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','decorme'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);

	// Service content Section // 
	
	$wp_customize->add_setting(
		'service_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'service_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','decorme'),
			'section' => 'service_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add service
	 */
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'service_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => decorme_get_service_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','decorme'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'decorme' ),
						'item_name'                         => esc_html__( 'Service', 'decorme' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	}	
	
	//Pro feature
		class DecorMe_service_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_DecorMe_service_upgrade_section up-to-pro" href="https://burgerthemes.com/decorme-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in DecorMe Pro','decorme'); ?></a>
			<?php
			} 
		}	
	
		$wp_customize->add_setting( 'decorme_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 8,
		));
		$wp_customize->add_control(
			new DecorMe_service_section_upgrade(
			$wp_customize,
			'decorme_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
				)
			)
		);
}

add_action( 'customize_register', 'decorme_service_setting' );

// service selective refresh
function decorme_home_service_section_partials( $wp_customize ){	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.service-home .theme-heading .placeholder',
		'settings'            => 'service_title',
		'render_callback'  => 'decorme_service_title_render_callback',
	
	) );
	
	// service subtitle
	$wp_customize->selective_refresh->add_partial( 'service_subtitle', array(
		'selector'            => '.service-home .theme-heading h5.text-primary',
		'settings'            => 'service_subtitle',
		'render_callback'  => 'decorme_service_subtitle_render_callback',
	
	) );
	
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '.service-home .theme-heading h2 .font-weight-normal',
		'settings'            => 'service_description',
		'render_callback'  => 'decorme_service_desc_render_callback',
	
	) );
	// service content
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '.service-home .service-wrapper'
	
	) );
	
	}

add_action( 'customize_register', 'decorme_home_service_section_partials' );

// service title
function decorme_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}

// service subtitle
function decorme_service_subtitle_render_callback() {
	return get_theme_mod( 'service_subtitle' );
}

// service description
function decorme_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}