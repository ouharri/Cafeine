<?php
function appetizer_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service  Section
	=========================================*/
	$wp_customize->add_section(
		'service_setting', array(
			'title' => esc_html__( 'Service Section', 'appetizer' ),
			'priority' => 3,
			'panel' => 'appetizer_frontpage_sections',
		)
	);
	
	// Settings // 
	$wp_customize->add_setting(
		'service_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'service_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','appetizer'),
			'section' => 'service_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_service' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
			'priority' => 3,
		) 
	);
	
	$wp_customize->add_control(
	'hs_service', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'appetizer' ),
			'section'     => 'service_setting',
			'type'        => 'checkbox',
		) 
	);	
	

	// Service Header Section // 
	$wp_customize->add_setting(
		'service_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'service_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','appetizer'),
			'section' => 'service_setting',
		)
	);
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
	        'default'			=> __('Special Package','appetizer'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','appetizer'),
		    'section' => 'service_setting',
			'type'           => 'text',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('Find our all best packages','appetizer'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','appetizer'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);

	// Service content Section // 
	
	$wp_customize->add_setting(
		'service_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'service_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','appetizer'),
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
			 'default' => appetizer_get_service_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','appetizer'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'appetizer' ),
						'item_name'                         => esc_html__( 'Service', 'appetizer' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text2_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	}	
	
		//Pro feature
		class Appetizer_service_section_upgrade extends WP_Customize_Control {
			public function render_content() {
			$theme = wp_get_theme(); // gets the current theme
			if ( 'Rasam' == $theme->name){			
			?>
				<a class="customizer_Appetizer_service_upgrade_section up-to-pro" href="https://burgerthemes.com/rasam-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in Rasam Pro','appetizer'); ?></a>
			
			<?php }else{ ?>
			
				<a class="customizer_Appetizer_service_upgrade_section up-to-pro" href="https://burgerthemes.com/appetizer-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in Appetizer Pro','appetizer'); ?></a>
			
			<?php
			} }
		}
		
		$wp_customize->add_setting( 'appetizer_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Appetizer_service_section_upgrade(
			$wp_customize,
			'appetizer_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
				)
			)
		);
}

add_action( 'customize_register', 'appetizer_service_setting' );

// service selective refresh
function appetizer_home_service_section_partials( $wp_customize ){	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.service-home .heading-default h2',
		'settings'            => 'service_title',
		'render_callback'  => 'appetizer_service_title_render_callback',
	) );
	
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '.service-home .heading-default p',
		'settings'            => 'service_description',
		'render_callback'  => 'appetizer_service_desc_render_callback',
	) );
	// service content
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '.service-home .hm-serv-content'
	
	) );
	
	}

add_action( 'customize_register', 'appetizer_home_service_section_partials' );

// service title
function appetizer_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}

// service description
function appetizer_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}