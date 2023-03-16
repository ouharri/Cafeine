<?php
function spintech_testimonial_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Testimonial  Section
	=========================================*/
	$wp_customize->add_section(
		'testimonial_setting', array(
			'title' => esc_html__( 'Testimonial Section', 'spintech-pro' ),
			'priority' => 13,
			'panel' => 'spintech_frontpage_sections',
		)
	);
	
	// Testimonial Settings Section // 
	
	$wp_customize->add_setting(
		'testimonial_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'testimonial_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','spintech'),
			'section' => 'testimonial_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_testimonial' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_testimonial', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'testimonial_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Testimnial Header Section // 
	$wp_customize->add_setting(
		'testimonial_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'testimonial_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','spintech-pro'),
			'section' => 'testimonial_setting',
		)
	);
	
	// Testimonial Title // 
	$wp_customize->add_setting(
    	'testimonial_title',
    	array(
	        'default'			=> __('Explore','spintech-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_title',
		array(
		    'label'   => __('Title','spintech-pro'),
		    'section' => 'testimonial_setting',
			'type'           => 'text',
		)  
	);
	
	// Testimonial Subtitle // 
	$wp_customize->add_setting(
    	'testimonial_subtitle',
    	array(
	        'default'			=> __('Our <span class="text-primary">Testimonials</span>','spintech-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_subtitle',
		array(
		    'label'   => __('Subtitle','spintech-pro'),
		    'section' => 'testimonial_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Testimonial Description // 
	$wp_customize->add_setting(
    	'testimonial_description',
    	array(
	        'default'			=> __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','spintech-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_description',
		array(
		    'label'   => __('Description','spintech-pro'),
		    'section' => 'testimonial_setting',
			'type'           => 'textarea',
		)  
	);

	// Testimonial content Section // 
	
	$wp_customize->add_setting(
		'test_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'test_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','spintech-pro'),
			'section' => 'testimonial_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add Testimonial
	 */
	
		$wp_customize->add_setting( 'testimonials', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => spintech_get_testimonial_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'testimonials', 
					array(
						'label'   => esc_html__('Testimonial','spintech-pro'),
						'section' => 'testimonial_setting',
						'add_field_label'                   => esc_html__( 'Add New Testimonial', 'spintech-pro' ),
						'item_name'                         => esc_html__( 'Testimonial', 'spintech-pro' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Spintech_testimonial_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>	
			
				<a class="customizer_spintech_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/spinsoft-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial in SpinSoft Pro','spintech'); ?></a>
				
			<?php
			}
		}
		
		$wp_customize->add_setting( 'spintech_testimonial_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Spintech_testimonial_section_upgrade(
			$wp_customize,
			'spintech_testimonial_upgrade_to_pro',
				array(
					'section'				=> 'testimonial_setting',
				)
			)
		);
}

add_action( 'customize_register', 'spintech_testimonial_setting' );

// Testimonial selective refresh
function spintech_testimonial_section_partials( $wp_customize ){
	
	// testimonial_title
	$wp_customize->selective_refresh->add_partial( 'testimonial_title', array(
		'selector'            => '.home-testimonial .heading-default .ttl',
		'settings'            => 'testimonial_title',
		'render_callback'  => 'spintech_testimonial_title_render_callback',
	
	) );
	
	// testimonial_subtitle
	$wp_customize->selective_refresh->add_partial( 'testimonial_subtitle', array(
		'selector'            => '.home-testimonial .heading-default h2',
		'settings'            => 'testimonial_subtitle',
		'render_callback'  => 'spintech_testimonial_subtitle_render_callback',
	
	) );
	
	// testimonial_description
	$wp_customize->selective_refresh->add_partial( 'testimonial_description', array(
		'selector'            => '.home-testimonial .heading-default p',
		'settings'            => 'testimonial_description',
		'render_callback'  => 'spintech_testimonial_description_render_callback',
	
	) );
	// testimonials
	$wp_customize->selective_refresh->add_partial( 'testimonials', array(
		'selector'            => '.home-testimonial .testimonials-slider'
	) );
	
	}

add_action( 'customize_register', 'spintech_testimonial_section_partials' );

// testimonial_title
function spintech_testimonial_title_render_callback() {
	return get_theme_mod( 'testimonial_title' );
}

// testimonial_subtitle
function spintech_testimonial_subtitle_render_callback() {
	return get_theme_mod( 'testimonial_subtitle' );
}

// testimonial_description
function spintech_testimonial_description_render_callback() {
	return get_theme_mod( 'testimonial_description' );
}