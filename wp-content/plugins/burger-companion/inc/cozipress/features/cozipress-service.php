<?php
function cozipress_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service  Section
	=========================================*/
	$wp_customize->add_section(
		'service_setting', array(
			'title' => esc_html__( 'Service Section', 'cozipress' ),
			'priority' => 3,
			'panel' => 'cozipress_frontpage_sections',
		)
	);
	
	// Service Settings Section // 
	
	$wp_customize->add_setting(
		'service_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'service_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozipress'),
			'section' => 'service_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_service' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_service', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'service_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Service Header Section // 
	$wp_customize->add_setting(
		'service_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'service_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','cozipress'),
			'section' => 'service_setting',
		)
	);
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
	        'default'			=> __('What We Do','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','cozipress'),
		    'section' => 'service_setting',
			'type'           => 'text',
		)  
	);
	
	// Service Subtitle // 
	$wp_customize->add_setting(
    	'service_subtitle',
    	array(
	        'default'			=> __('Our <span class="text-primary">Services</span>','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'service_subtitle',
		array(
		    'label'   => __('Subtitle','cozipress'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','cozipress'),
		    'section' => 'service_setting',
			'type'           => 'textarea',
		)  
	);

	// Service content Section // 
	
	$wp_customize->add_setting(
		'service_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'service_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress'),
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
			 'default' => cozipress_get_service_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','cozipress'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'cozipress' ),
						'item_name'                         => esc_html__( 'Service', 'cozipress' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
			
			
		//Pro feature
		class Cozipress_service_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Sipri' == $theme->name){
			?>
				<a class="customizer_CoziPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/sipri-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in Sipri Pro','cozipress'); ?></a>
			
			<?php }elseif('Anexa' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/anexa-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in Anexa Pro','cozipress'); ?></a>
				
			<?php }elseif('CoziWeb' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/coziweb-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in CoziWeb Pro','cozipress'); ?></a>
				
			<?php }elseif('CoziPlus' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/coziplus-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in CoziPlus Pro','cozipress'); ?></a>	
				
			<?php }elseif('CoziBee' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/cozibee-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in CoziBee Pro','cozipress'); ?></a>		
				
			<?php }else{ ?>		
			
				<a class="customizer_CoziPress_service_upgrade_section up-to-pro" href="https://burgerthemes.com/cozipress-pro/" target="_blank" style="display: none;"><?php _e('More Services Available in CoziPress Pro','cozipress'); ?></a>
				
			<?php
			} }
		}
		
		$wp_customize->add_setting( 'cozipress_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Cozipress_service_section_upgrade(
			$wp_customize,
			'cozipress_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
				)
			)
		);
		
}

add_action( 'customize_register', 'cozipress_service_setting' );

// service selective refresh
function cozipress_home_service_section_partials( $wp_customize ){	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.service-home .heading-default .ttl',
		'settings'            => 'service_title',
		'render_callback'  => 'cozipress_service_title_render_callback',
	
	) );
	
	// service subtitle
	$wp_customize->selective_refresh->add_partial( 'service_subtitle', array(
		'selector'            => '.service-home .heading-default h2',
		'settings'            => 'service_subtitle',
		'render_callback'  => 'cozipress_service_subtitle_render_callback',
	
	) );
	
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '.service-home .heading-default p',
		'settings'            => 'service_description',
		'render_callback'  => 'cozipress_service_desc_render_callback',
	
	) );
	// service content
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '.service-home .hm-serv-content'
	
	) );
	
	}

add_action( 'customize_register', 'cozipress_home_service_section_partials' );

// service title
function cozipress_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}

// service subtitle
function cozipress_service_subtitle_render_callback() {
	return get_theme_mod( 'service_subtitle' );
}

// service description
function cozipress_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}