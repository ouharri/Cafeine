<?php
function spintech_design_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service  Section
	=========================================*/
	$wp_customize->add_section(
		'design_setting', array(
			'title' => esc_html__( 'Design & Developemt Section', 'spintech' ),
			'priority' => 4,
			'panel' => 'spintech_frontpage_sections',
		)
	);
	
	// Design Settings Section // 
	
	$wp_customize->add_setting(
		'design_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'design_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','spintech'),
			'section' => 'design_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_design' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'transport'         => $selective_refresh,
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_design', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'design_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Design & Developemt Header Section // 
	$wp_customize->add_setting(
		'design_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'design_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','spintech'),
			'section' => 'design_setting',
		)
	);
	
	// Design & Developemt Title // 
	$wp_customize->add_setting(
    	'design_title',
    	array(
	        'default'			=> __('Explore','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'design_title',
		array(
		    'label'   => __('Title','spintech'),
		    'section' => 'design_setting',
			'type'           => 'text',
		)  
	);
	
	// Design & Developemt Subtitle // 
	$wp_customize->add_setting(
    	'design_subtitle',
    	array(
	        'default'			=> __('Design & Development','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'design_subtitle',
		array(
		    'label'   => __('Subtitle','spintech'),
		    'section' => 'design_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Design & Developemt Description // 
	$wp_customize->add_setting(
    	'design_description',
    	array(
	        'default'			=> __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'design_description',
		array(
		    'label'   => __('Description','spintech'),
		    'section' => 'design_setting',
			'type'           => 'textarea',
		)  
	);

	// Design & Developemt content Section // 
	
	$wp_customize->add_setting(
		'design_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'design_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','spintech'),
			'section' => 'design_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add design
	 */
	
		$wp_customize->add_setting( 'design_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => spintech_get_design_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'design_contents', 
					array(
						'label'   => esc_html__('Design','spintech'),
						'section' => 'design_setting',
						'add_field_label'                   => esc_html__( 'Add New Design', 'spintech' ),
						'item_name'                         => esc_html__( 'Design', 'spintech' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Spintech_design_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'ITpress' == $theme->name){
			?>
				<a class="customizer_spintech_design_upgrade_section up-to-pro"  href="https://burgerthemes.com/itpress-pro/" target="_blank" style="display: none;"><?php _e('More Designs Available in ITpress Pro','spintech'); ?></a>
				
			<?php }elseif ( 'Burgertech' == $theme->name){ ?>	
			
				<a class="customizer_spintech_design_upgrade_section up-to-pro"  href="https://burgerthemes.com/burgertech-pro/" target="_blank" style="display: none;"><?php _e('More Designs Available in Burgertech Pro','spintech'); ?></a>
				
			<?php }elseif ( 'KitePress' == $theme->name){ ?>	
			
				<a class="customizer_spintech_design_upgrade_section up-to-pro"  href="https://burgerthemes.com/kitepress-pro/" target="_blank" style="display: none;"><?php _e('More Designs Available in KitePress Pro','spintech'); ?></a>
			
			<?php }else{ ?>		
				
				<a class="customizer_spintech_design_upgrade_section up-to-pro"  href="https://burgerthemes.com/spintech-pro/" target="_blank" style="display: none;"><?php _e('More Designs Available in Spintech Pro','spintech'); ?></a>
				
			<?php
				}
			}
		}
		
		$wp_customize->add_setting( 'spintech_design_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Spintech_design_section_upgrade(
			$wp_customize,
			'spintech_design_upgrade_to_pro',
				array(
					'section'				=> 'design_setting',
				)
			)
		);
			
	// Image // 
    $wp_customize->add_setting( 
    	'design_left_img' , 
    	array(
			'default' 			=> BURGER_COMPANION_PLUGIN_URL .'inc/spintech/images/about/design-img.png',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_url',	
			'priority' => 10,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'design_left_img' ,
		array(
			'label'          => esc_html__( 'Image', 'spintech'),
			'section'        => 'design_setting',
		) 
	));
}

add_action( 'customize_register', 'spintech_design_setting' );

// design selective refresh
function spintech_home_design_section_partials( $wp_customize ){

	// hs_design
	$wp_customize->selective_refresh->add_partial(
		'hs_design', array(
			'selector' => '#design-section',
			'container_inclusive' => true,
			'render_callback' => 'design_setting',
			'fallback_refresh' => true,
		)
	);
	
	// design title
	$wp_customize->selective_refresh->add_partial( 'design_title', array(
		'selector'            => '.design-home .heading-default .ttl',
		'settings'            => 'design_title',
		'render_callback'  => 'spintech_design_title_render_callback',
	
	) );
	
	// design subtitle
	$wp_customize->selective_refresh->add_partial( 'design_subtitle', array(
		'selector'            => '.design-home .heading-default h2',
		'settings'            => 'design_subtitle',
		'render_callback'  => 'spintech_design_subtitle_render_callback',
	
	) );
	
	// design description
	$wp_customize->selective_refresh->add_partial( 'design_description', array(
		'selector'            => '.design-home .heading-default p',
		'settings'            => 'design_description',
		'render_callback'  => 'spintech_design_desc_render_callback',
	
	) );
	// design content
	$wp_customize->selective_refresh->add_partial( 'design_contents', array(
		'selector'            => '.design-home .design-wrp'
	
	) );
	}

add_action( 'customize_register', 'spintech_home_design_section_partials' );

// design title
function spintech_design_title_render_callback() {
	return get_theme_mod( 'design_title' );
}

// design subtitle
function spintech_design_subtitle_render_callback() {
	return get_theme_mod( 'design_subtitle' );
}

// design description
function spintech_design_desc_render_callback() {
	return get_theme_mod( 'design_description' );
}