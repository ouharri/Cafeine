<?php
function spintech_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service  Section
	=========================================*/
	$wp_customize->add_section(
		'service_setting', array(
			'title' => esc_html__( 'Service Section', 'spintech' ),
			'priority' => 3,
			'panel' => 'spintech_frontpage_sections',
		)
	);
	
	// Service Settings Section // 
	
	$wp_customize->add_setting(
		'service_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'service_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','spintech'),
			'section' => 'service_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_service' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'transport'         => $selective_refresh,
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_service', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'service_setting',
			'type'        => 'checkbox',
		) 
	);	
	

	// Service Header Section // 
	$wp_customize->add_setting(
		'service_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'service_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','spintech'),
			'section' => 'service_setting',
		)
	);
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
	        'default'			=> __('Explore','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','spintech'),
		    'section' => 'service_setting',
			'type'           => 'text',
		)  
	);
	
	// Service Subtitle // 
	$wp_customize->add_setting(
    	'service_subtitle',
    	array(
	        'default'			=> __('Our <span class="text-primary">Services</span>','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'service_subtitle',
		array(
		    'label'   => __('Subtitle','spintech'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','spintech'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);

	// Service content Section // 
	
	$wp_customize->add_setting(
		'service_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'service_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','spintech'),
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
			 'default' => spintech_get_service_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','spintech'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'spintech' ),
						'item_name'                         => esc_html__( 'Service', 'spintech' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
			
			//Pro feature
		class Spintech_service_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'ITpress' == $theme->name){
			?>
				<a class="customizer_spintech_service_upgrade_section up-to-pro" href="https://burgerthemes.com/itpress-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in ITpress Pro','spintech'); ?></a>
				
			<?php }elseif ( 'Burgertech' == $theme->name){ ?>	
			
				<a class="customizer_spintech_service_upgrade_section up-to-pro" href="https://burgerthemes.com/burgertech-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in Burgertech Pro','spintech'); ?></a>
			
			<?php }elseif ( 'KitePress' == $theme->name){ ?>	
			
				<a class="customizer_spintech_service_upgrade_section up-to-pro" href="https://burgerthemes.com/kitepress-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in KitePress Pro','spintech'); ?></a>
				
			<?php }elseif ( 'SpinSoft' == $theme->name){ ?>	
			
				<a class="customizer_spintech_service_upgrade_section up-to-pro" href="https://burgerthemes.com/spinsoft-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in SpinSoft Pro','spintech'); ?></a>
				
			<?php }else{ ?>		
			
				<a class="customizer_spintech_service_upgrade_section up-to-pro" href="https://burgerthemes.com/spintech-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in Spintech Pro','spintech'); ?></a>
				
			<?php
				}
			}
		}
		
		$wp_customize->add_setting( 'spintech_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Spintech_service_section_upgrade(
			$wp_customize,
			'spintech_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
				)
			)
		);
}

add_action( 'customize_register', 'spintech_service_setting' );

// service selective refresh
function spintech_home_service_section_partials( $wp_customize ){
	
	// hs_service
	$wp_customize->selective_refresh->add_partial(
		'hs_service', array(
			'selector' => '#service-section',
			'container_inclusive' => true,
			'render_callback' => 'service_setting',
			'fallback_refresh' => true,
		)
	);
	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.service-home .heading-default .ttl',
		'settings'            => 'service_title',
		'render_callback'  => 'spintech_service_title_render_callback',
	
	) );
	
	// service subtitle
	$wp_customize->selective_refresh->add_partial( 'service_subtitle', array(
		'selector'            => '.service-home .heading-default h2',
		'settings'            => 'service_subtitle',
		'render_callback'  => 'spintech_service_subtitle_render_callback',
	
	) );
	
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '.service-home .heading-default p',
		'settings'            => 'service_description',
		'render_callback'  => 'spintech_service_desc_render_callback',
	
	) );
	// service content
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '.service-home .hm-serv-content'
	
	) );
	
	}

add_action( 'customize_register', 'spintech_home_service_section_partials' );

// service title
function spintech_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}

// service subtitle
function spintech_service_subtitle_render_callback() {
	return get_theme_mod( 'service_subtitle' );
}

// service description
function spintech_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}