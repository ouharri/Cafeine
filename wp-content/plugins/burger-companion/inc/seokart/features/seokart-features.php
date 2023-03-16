<?php
function seokart_features_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Features  Section
	=========================================*/
	$wp_customize->add_section(
		'features_setting', array(
			'title' => esc_html__( 'Features Section', 'seokart' ),
			'priority' => 7,
			'panel' => 'seokart_frontpage_sections',
		)
	);


	//  Settings  // 
	
	$wp_customize->add_setting(
		'features_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'features_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','seokart'),
			'section' => 'features_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_features' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_features', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'seokart' ),
			'section'     => 'features_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Features Header Section // 
	$wp_customize->add_setting(
		'features_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'features_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','seokart'),
			'section' => 'features_setting',
		)
	);
	
	// Features Title // 
	$wp_customize->add_setting(
    	'features_title',
    	array(
	        'default'			=> __('Our Features','seokart'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'features_title',
		array(
		    'label'   => __('Title','seokart'),
		    'section' => 'features_setting',
			'type'           => 'text',
		)  
	);
	
	// Features Subtitle // 
	$wp_customize->add_setting(
    	'features_subtitle',
    	array(
	        'default'			=> __('Our Outstanding <i>Features </i>','seokart'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'features_subtitle',
		array(
		    'label'   => __('Subtitle','seokart'),
		    'section' => 'features_setting',
			'type'           => 'text',
		)  
	);
	
	// Features Description // 
	$wp_customize->add_setting(
    	'features_description',
    	array(
	        'default'			=> __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed eiusm tempor incididunt ut labore et dolore magna aliqua.','seokart'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'features_description',
		array(
		    'label'   => __('Description','seokart'),
		    'section' => 'features_setting',
			'type'           => 'textarea',
		)  
	);

	// Features content Section // 
	
	$wp_customize->add_setting(
		'features_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'features_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','seokart'),
			'section' => 'features_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add features
	 */
	
		$wp_customize->add_setting( 'features_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 8,
			 'default' => seokart_get_features_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'features_contents', 
					array(
						'label'   => esc_html__('Features','seokart'),
						'section' => 'features_setting',
						'add_field_label'                   => esc_html__( 'Add New Features', 'seokart' ),
						'item_name'                         => esc_html__( 'Features', 'seokart' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
					) 
				) 
			);
			
			
	//Pro feature
		class Seokart_features_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
			if ( 'DigiPress' == $theme->name){	
			?>
				<a class="customizer_SeoKart_features_upgrade_section up-to-pro" href="https://burgerthemes.com/digipress-pro/" target="_blank" style="display: none;"><?php _e('More Features Available in DigiPress Pro','seokart'); ?></a>
				
			<?php }else{ ?>	
			
				<a class="customizer_SeoKart_features_upgrade_section up-to-pro" href="https://burgerthemes.com/seokart-pro/" target="_blank" style="display: none;"><?php _e('More Features Available in SeoKart Pro','seokart'); ?></a>
				
			<?php
			} }
		}
		
	$wp_customize->add_setting( 'seokart_features_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Seokart_features_section_upgrade(
			$wp_customize,
			'seokart_features_upgrade_to_pro',
				array(
					'section'				=> 'features_setting',
				)
			)
		);	
		
	
	//  Image // 
    $wp_customize->add_setting( 
    	'features_center_img' , 
    	array(
			'default' 			=> esc_url(BURGER_COMPANION_PLUGIN_URL .'/inc/seokart/images/feture.png'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_url',	
			'priority' => 10,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'features_center_img' ,
		array(
			'label'          => esc_html__( 'Center Image', 'seokart'),
			'section'        => 'features_setting',
		) 
	));
}

add_action( 'customize_register', 'seokart_features_setting' );

// features selective refresh
function seokart_features_section_partials( $wp_customize ){	
	// features title
	$wp_customize->selective_refresh->add_partial( 'features_title', array(
		'selector'            => '.features-home .title h6',
		'settings'            => 'features_title',
		'render_callback'  => 'seokart_features_title_render_callback',
	
	) );
	
	// features subtitle
	$wp_customize->selective_refresh->add_partial( 'features_subtitle', array(
		'selector'            => '.features-home .title h2',
		'settings'            => 'features_subtitle',
		'render_callback'  => 'seokart_features_subtitle_render_callback',
	
	) );
	
	// features description
	$wp_customize->selective_refresh->add_partial( 'features_description', array(
		'selector'            => '.features-home .title p',
		'settings'            => 'features_description',
		'render_callback'  => 'seokart_features_desc_render_callback',
	
	) );	
	}

add_action( 'customize_register', 'seokart_features_section_partials' );

// features title
function seokart_features_title_render_callback() {
	return get_theme_mod( 'features_title' );
}

// features subtitle
function seokart_features_subtitle_render_callback() {
	return get_theme_mod( 'features_subtitle' );
}

// features description
function seokart_features_desc_render_callback() {
	return get_theme_mod( 'features_description' );
}