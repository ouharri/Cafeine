<?php
function owlpress_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service  Section
	=========================================*/
	$wp_customize->add_section(
		'service_setting', array(
			'title' => esc_html__( 'Service Section', 'owlpress' ),
			'priority' => 3,
			'panel' => 'owlpress_frontpage_sections',
		)
	);
	
	// Settings // 
	$wp_customize->add_setting(
		'service_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'service_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','owlpress'),
			'section' => 'service_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_service' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_service', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'owlpress' ),
			'section'     => 'service_setting',
			'type'        => 'checkbox',
		) 
	);
	
	// Service Header Section // 
	$wp_customize->add_setting(
		'service_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'service_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','owlpress'),
			'section' => 'service_setting',
		)
	);
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
	        'default'			=> __('What We Do','owlpress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','owlpress'),
		    'section' => 'service_setting',
			'type'           => 'text',
		)  
	);
	
	// Service Subtitle // 
	$wp_customize->add_setting(
    	'service_subtitle',
    	array(
	        'default'			=> __('Our <span class="text-primary">Services</span>','owlpress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'service_subtitle',
		array(
		    'label'   => __('Subtitle','owlpress'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('Lorem Ipsum. Proin Gravida Nibh Vel Velit Auctor Aliquet','owlpress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','owlpress'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);

	// Service content Section // 
	
	$wp_customize->add_setting(
		'service_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'service_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','owlpress'),
			'section' => 'service_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add service
	 */
	
		$wp_customize->add_setting( 'service_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => owlpress_get_service_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','owlpress'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'owlpress' ),
						'item_name'                         => esc_html__( 'Service', 'owlpress' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Owlpress_service_section_upgrade extends WP_Customize_Control {
			public function render_content() {
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Crowl' == $theme->name){					
			?>
				<a class="customizer_OwlPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/crowl-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in Crowl Pro','owlpress'); ?></a>
				
			<?php }else{ ?>	
				
				<a class="customizer_OwlPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/owlpress-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in OwlPress Pro','owlpress'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'owlpress_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Owlpress_service_section_upgrade(
			$wp_customize,
			'owlpress_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
				)
			)
		);
}

add_action( 'customize_register', 'owlpress_service_setting' );

// service selective refresh
function owlpress_home_service_section_partials( $wp_customize ){	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.service-home .heading-default h6',
		'settings'            => 'service_title',
		'render_callback'  => 'owlpress_service_title_render_callback',
	
	) );
	
	// service subtitle
	$wp_customize->selective_refresh->add_partial( 'service_subtitle', array(
		'selector'            => '.service-home .heading-default h4',
		'settings'            => 'service_subtitle',
		'render_callback'  => 'owlpress_service_subtitle_render_callback',
	
	) );
	
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '.service-home .heading-default p',
		'settings'            => 'service_description',
		'render_callback'  => 'owlpress_service_desc_render_callback',
	
	) );
	// service content
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '.service-home .hm-serv-content'
	
	) );
	
	}

add_action( 'customize_register', 'owlpress_home_service_section_partials' );

// service title
function owlpress_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}

// service subtitle
function owlpress_service_subtitle_render_callback() {
	return get_theme_mod( 'service_subtitle' );
}

// service description
function owlpress_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}