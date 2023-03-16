<?php
function cozipress_design_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Design  Section
	=========================================*/
	$wp_customize->add_section(
		'design_setting', array(
			'title' => esc_html__( 'Design Section', 'cozipress-pro' ),
			'priority' => 3,
			'panel' => 'cozipress_frontpage_sections',
		)
	);


	// Design Settings Section // 
	
	$wp_customize->add_setting(
		'design_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'design_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozipress'),
			'section' => 'design_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_design' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_design', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'design_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Design & Developemt Header Section // 
	$wp_customize->add_setting(
		'design_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'design_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','cozipress-pro'),
			'section' => 'design_setting',
		)
	);
	
	// Design & Developemt Title // 
	$wp_customize->add_setting(
    	'design_title',
    	array(
	        'default'			=> __('We Are Here','cozipress-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'design_title',
		array(
		    'label'   => __('Title','cozipress-pro'),
		    'section' => 'design_setting',
			'type'           => 'text',
		)  
	);
	
	// Design & Developemt Subtitle // 
	$wp_customize->add_setting(
    	'design_subtitle',
    	array(
	        'default'			=> __('About <span class="text-primary">Us</span>','cozipress-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'design_subtitle',
		array(
		    'label'   => __('Subtitle','cozipress-pro'),
		    'section' => 'design_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Design & Developemt Description // 
	$wp_customize->add_setting(
    	'design_description',
    	array(
	        'default'			=> __('This is Photoshop version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.','cozipress-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'design_description',
		array(
		    'label'   => __('Description','cozipress-pro'),
		    'section' => 'design_setting',
			'type'           => 'textarea',
		)  
	);

	// Design & Developemt content Section // 
	
	$wp_customize->add_setting(
		'design_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'design_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress-pro'),
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
			 'default' => cozipress_get_design_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'design_contents', 
					array(
						'label'   => esc_html__('Design','cozipress-pro'),
						'section' => 'design_setting',
						'add_field_label'                   => esc_html__( 'Add New Design', 'cozipress-pro' ),
						'item_name'                         => esc_html__( 'Design', 'cozipress-pro' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	
		//Pro feature
		class Cozipress_design_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'CoziWeb' == $theme->name){
			?>
				<a class="customizer_CoziPress_design_upgrade_section up-to-pro" href="https://burgerthemes.com/coziweb-pro/" target="_blank" style="display: none;"><?php _e('More Design Available in CoziWeb Pro','cozipress'); ?></a>
				
			<?php }else{ ?>
				
				<a class="customizer_CoziPress_design_upgrade_section up-to-pro" href="https://burgerthemes.com/coziplus-pro/" target="_blank" style="display: none;"><?php _e('More Design Available in CoziPlus Pro','cozipress'); ?></a>
			
			<?php
			}}
		}
		
		
		$wp_customize->add_setting( 'cozipress_design_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 8,
		));
		$wp_customize->add_control(
			new Cozipress_design_section_upgrade(
			$wp_customize,
			'cozipress_design_upgrade_to_pro',
				array(
					'section'				=> 'design_setting',
				)
			)
		);
		
	// Image // 
    $wp_customize->add_setting( 
    	'design_left_img' , 
    	array(
			'default' 			=> BURGER_COMPANION_PLUGIN_URL . 'inc/coziweb/images/design-img.jpg',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_url',	
			'priority' => 10,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'design_left_img' ,
		array(
			'label'          => esc_html__( 'Image', 'cozipress-pro'),
			'section'        => 'design_setting',
		) 
	));
}

add_action( 'customize_register', 'cozipress_design_setting' );

// design selective refresh
function cozipress_home_design_section_partials( $wp_customize ){	
	// design title
	$wp_customize->selective_refresh->add_partial( 'design_title', array(
		'selector'            => '.design-home .heading-default .ttl',
		'settings'            => 'design_title',
		'render_callback'  => 'cozipress_design_title_render_callback',
	
	) );
	
	// design subtitle
	$wp_customize->selective_refresh->add_partial( 'design_subtitle', array(
		'selector'            => '.design-home .heading-default h2',
		'settings'            => 'design_subtitle',
		'render_callback'  => 'cozipress_design_subtitle_render_callback',
	) );
	
	// design description
	$wp_customize->selective_refresh->add_partial( 'design_description', array(
		'selector'            => '.design-home .heading-default p',
		'settings'            => 'design_description',
		'render_callback'  => 'cozipress_design_desc_render_callback',
	) );
	// design content
	$wp_customize->selective_refresh->add_partial( 'design_contents', array(
		'selector'            => '.design-home .design-wrp'
	) );
	}

add_action( 'customize_register', 'cozipress_home_design_section_partials' );

// design title
function cozipress_design_title_render_callback() {
	return get_theme_mod( 'design_title' );
}

// design subtitle
function cozipress_design_subtitle_render_callback() {
	return get_theme_mod( 'design_subtitle' );
}

// design description
function cozipress_design_desc_render_callback() {
	return get_theme_mod( 'design_description' );
}