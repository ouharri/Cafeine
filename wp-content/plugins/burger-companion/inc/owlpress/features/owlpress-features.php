<?php
function owlpress_features_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Features  Section
	=========================================*/
	$wp_customize->add_section(
		'features_setting', array(
			'title' => esc_html__( 'Features Section', 'owlpress' ),
			'priority' => 4,
			'panel' => 'owlpress_frontpage_sections',
		)
	);

	// Settings // 
	$wp_customize->add_setting(
		'features_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'features_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','owlpress'),
			'section' => 'features_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_features' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_features', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'owlpress' ),
			'section'     => 'features_setting',
			'type'        => 'checkbox',
		) 
	);
	
	// Features Header Section // 
	$wp_customize->add_setting(
		'features_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'features_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','owlpress'),
			'section' => 'features_setting',
		)
	);
	
	// Features Title // 
	$wp_customize->add_setting(
    	'features_title',
    	array(
	        'default'			=> __('Feature','owlpress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'features_title',
		array(
		    'label'   => __('Title','owlpress'),
		    'section' => 'features_setting',
			'type'           => 'text',
		)  
	);
	
	// Features Subtitle // 
	$wp_customize->add_setting(
    	'features_subtitle',
    	array(
	        'default'			=> __('Our <span class="text-primary">Solution</span>','owlpress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'features_subtitle',
		array(
		    'label'   => __('Subtitle','owlpress'),
		    'section' => 'features_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Features Description // 
	$wp_customize->add_setting(
    	'features_description',
    	array(
	        'default'			=> __('Lorem Ipsum. Proin Gravida Nibh Vel Velit Auctor Aliquet','owlpress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'features_description',
		array(
		    'label'   => __('Description','owlpress'),
		    'section' => 'features_setting',
			'type'           => 'textarea',
		)  
	);

	// Features content Section // 
	$wp_customize->add_setting(
		'features_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'features_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','owlpress'),
			'section' => 'features_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add features
	 */
	
		$wp_customize->add_setting( 'features_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => owlpress_get_features_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'features_contents', 
					array(
						'label'   => esc_html__('Features','owlpress'),
						'section' => 'features_setting',
						'add_field_label'                   => esc_html__( 'Add New Features', 'owlpress' ),
						'item_name'                         => esc_html__( 'Features', 'owlpress' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	
		//Pro feature
		class Owlpress_features_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Crowl' == $theme->name){	
			?>
				<a class="customizer_OwlPress_features_upgrade_section up-to-pro" href="https://burgerthemes.com/crowl-pro/" target="_blank" style="display: none;"><?php _e('More Features Available in Crowl Pro','owlpress'); ?></a>
				
				<?php }else{ ?>	
				
				<a class="customizer_OwlPress_features_upgrade_section up-to-pro" href="https://burgerthemes.com/owlpress-pro/" target="_blank" style="display: none;"><?php _e('More Features Available in OwlPress Pro','owlpress'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'owlpress_features_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Owlpress_features_section_upgrade(
			$wp_customize,
			'owlpress_features_upgrade_to_pro',
				array(
					'section'				=> 'features_setting',
				)
			)
		);
		
	// Features BG // 
	$wp_customize->add_setting(
		'features_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
			'priority' => 10,
		)
	);

	$wp_customize->add_control(
	'features_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','owlpress'),
			'section' => 'features_setting',
		)
	);
	
	
	// Background Image // 
    $wp_customize->add_setting( 
    	'features_bg_img' , 
    	array(
			'default' 			=> esc_url(BURGER_COMPANION_PLUGIN_URL .'inc/owlpress/images/features/feature_bg.jpg'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_url',	
			'priority' => 10,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'features_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'owlpress'),
			'section'        => 'features_setting',
		) 
	));
	
	// Background Attachment // 
	$wp_customize->add_setting( 
		'features_back_attach' , 
			array(
			'default' => 'fixed',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_select',
			'priority'  => 10,
		) 
	);
	
	$wp_customize->add_control(
	'features_back_attach' , 
		array(
			'label'          => __( 'Background Attachment', 'owlpress' ),
			'section'        => 'features_setting',
			'type'           => 'select',
			'choices'        => 
			array(
				'inherit' => __( 'Inherit', 'owlpress' ),
				'scroll' => __( 'Scroll', 'owlpress' ),
				'fixed'   => __( 'Fixed', 'owlpress' )
			) 
		) 
	);
}

add_action( 'customize_register', 'owlpress_features_setting' );

// features selective refresh
function owlpress_home_features_section_partials( $wp_customize ){	
	// features title
	$wp_customize->selective_refresh->add_partial( 'features_title', array(
		'selector'            => '.feature-home .heading-default h6',
		'settings'            => 'features_title',
		'render_callback'  => 'owlpress_features_title_render_callback',
	
	) );
	
	// features subtitle
	$wp_customize->selective_refresh->add_partial( 'features_subtitle', array(
		'selector'            => '.feature-home .heading-default h4',
		'settings'            => 'features_subtitle',
		'render_callback'  => 'owlpress_features_subtitle_render_callback',
	
	) );
	
	// features description
	$wp_customize->selective_refresh->add_partial( 'features_description', array(
		'selector'            => '.feature-home .heading-default p',
		'settings'            => 'features_description',
		'render_callback'  => 'owlpress_features_desc_render_callback',
	
	) );
	// features content
	$wp_customize->selective_refresh->add_partial( 'features_contents', array(
		'selector'            => '.feature-home .feature-content'
	
	) );
	
	}

add_action( 'customize_register', 'owlpress_home_features_section_partials' );

// features title
function owlpress_features_title_render_callback() {
	return get_theme_mod( 'features_title' );
}

// features subtitle
function owlpress_features_subtitle_render_callback() {
	return get_theme_mod( 'features_subtitle' );
}

// features description
function owlpress_features_desc_render_callback() {
	return get_theme_mod( 'features_description' );
}