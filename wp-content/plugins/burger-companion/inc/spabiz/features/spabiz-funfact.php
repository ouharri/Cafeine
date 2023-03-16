<?php
function spabiz_funfact_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Funfact  Section
	=========================================*/
	$wp_customize->add_section(
		'funfact_setting', array(
			'title' => esc_html__( 'Funfact Section', 'spabiz' ),
			'priority' => 5,
			'panel' => 'spabiz_frontpage_sections',
		)
	);

	// Head
	$wp_customize->add_setting(
		'funfact_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'funfact_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','spabiz'),
			'section' => 'funfact_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_funfact' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_checkbox',
			'priority' => 1,
		) 
	);
	
	$wp_customize->add_control(
	'hs_funfact', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'owlpress' ),
			'section'     => 'funfact_setting',
			'type'        => 'checkbox',
		) 
	);
	
	// Funfact Header Section // 
	$wp_customize->add_setting(
		'funfact_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'funfact_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','spabiz'),
			'section' => 'funfact_setting',
		)
	);
	
	// Funfact Title // 
	$wp_customize->add_setting(
    	'funfact_title',
    	array(
	        'default'			=> __('<i class="fa fa-square"></i> What we do','spabiz'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'funfact_title',
		array(
		    'label'   => __('Title','spabiz'),
		    'section' => 'funfact_setting',
			'type'           => 'text',
		)  
	);
	
	// Funfact Subtitle // 
	$wp_customize->add_setting(
    	'funfact_subtitle',
    	array(
	        'default'			=> __('our funfact','spabiz'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'funfact_subtitle',
		array(
		    'label'   => __('Subtitle','spabiz'),
		    'section' => 'funfact_setting',
			'type'           => 'text',
		)  
	);
	
	// Funfact Description // 
	$wp_customize->add_setting(
    	'funfact_description',
    	array(
	        'default'			=> __('We are experienced professionals who understand that It funfacts is charging, and are true partners who care about your success experienced professionals','spabiz'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'funfact_description',
		array(
		    'label'   => __('Description','spabiz'),
		    'section' => 'funfact_setting',
			'type'           => 'textarea',
		)  
	);

	
	// Button Label // 
	$wp_customize->add_setting(
    	'funfact_btn_lbl',
    	array(
	        'default'			=> __('Learn More','spabiz'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'funfact_btn_lbl',
		array(
		    'label'   => __('Button Label','spabiz'),
		    'section' => 'funfact_setting',
			'type'           => 'text',
		)  
	);
	
	// Button Link // 
	$wp_customize->add_setting(
    	'funfact_btn_link',
    	array(
	        'default'			=> '#',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_url',
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'funfact_btn_link',
		array(
		    'label'   => __('Button Link','spabiz'),
		    'section' => 'funfact_setting',
			'type'           => 'text',
		)  
	);
	// Funfact content Section // 
	$wp_customize->add_setting(
		'funfact_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'funfact_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','spabiz'),
			'section' => 'funfact_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add funfact
	 */
	
		$wp_customize->add_setting( 'funfact_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => spabiz_get_funfact_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'funfact_contents', 
					array(
						'label'   => esc_html__('Funfact','spabiz'),
						'section' => 'funfact_setting',
						'add_field_label'                   => esc_html__( 'Add New Funfact', 'spabiz' ),
						'item_name'                         => esc_html__( 'Funfact', 'spabiz' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true
					) 
				) 
			);
			
			
	//Pro feature
	class Spabiz_funfact_section_upgrade extends WP_Customize_Control {
		public function render_content() { 
		$theme = wp_get_theme(); // gets the current theme
		if ( 'SpaCare' == $theme->name){
		?>	
		
			<a class="customizer_SpaCare_funfact_upgrade_section up-to-pro" href="https://burgerthemes.com/spacare-pro/" target="_blank" style="display: none;"><?php _e('More Funfact Available in Spacare Pro','spabiz'); ?></a>
			
		<?php }else{ ?>

			<a class="customizer_SpaBiz_funfact_upgrade_section up-to-pro" href="https://burgerthemes.com/spabiz-pro/" target="_blank" style="display: none;"><?php _e('More Funfact Available in SpaBiz Pro','spabiz'); ?></a>		
		
		<?php
		}}
	}
	
	$wp_customize->add_setting( 'spabiz_funfact_upgrade_to_pro', array(
		'capability'			=> 'edit_theme_options',
		'sanitize_callback'	=> 'wp_filter_nohtml_kses',
	));
	$wp_customize->add_control(
		new Spabiz_funfact_section_upgrade(
		$wp_customize,
		'spabiz_funfact_upgrade_to_pro',
			array(
				'section'				=> 'funfact_setting'
			)
		)
	);	
	
	// Background// 
	$wp_customize->add_setting(
		'funfact_content_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 8,
		)
	);

	$wp_customize->add_control(
	'funfact_content_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','spabiz'),
			'section' => 'funfact_setting',
		)
	);	

	// Background Image // 
    $wp_customize->add_setting( 
    	'funfact_bg_img' , 
    	array(
			'default' 			=> esc_url(BURGER_COMPANION_PLUGIN_URL .'inc/spabiz/images/funfact/fbg.png'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_url',	
			'priority' => 10,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'funfact_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'spabiz'),
			'section'        => 'funfact_setting',
		) 
	));
	
	// Image Opacity // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
	$wp_customize->add_setting(
    	'funfact_bg_img_opacity',
    	array(
	        'default'			=> '0.6',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority'  => 11,
		)
	);
	$wp_customize->add_control( 
	new Burger_Customizer_Range_Control( $wp_customize, 'funfact_bg_img_opacity', 
		array(
			'label'      => __( 'Opacity', 'spabiz'),
			'section'  => 'funfact_setting',
			'settings' => 'funfact_bg_img_opacity',
			 'input_attrs' => array(
				'min'    => 0,
				'max'    => 1,
				'step'   => 0.1,
				//'suffix' => 'px', //optional suffix
			)
		) ) 
	);
	}
	
}

add_action( 'customize_register', 'spabiz_funfact_setting' );

// funfact selective refresh
function spabiz_home_funfact_section_partials( $wp_customize ){	
	// funfact title
	$wp_customize->selective_refresh->add_partial( 'funfact_title', array(
		'selector'            => '.funfact-home .section-title .subtitle',
		'settings'            => 'funfact_title',
		'render_callback'  => 'spabiz_funfact_title_render_callback',
	
	) );
	
	// funfact subtitle
	$wp_customize->selective_refresh->add_partial( 'funfact_subtitle', array(
		'selector'            => '.funfact-home .section-title .title',
		'settings'            => 'funfact_subtitle',
		'render_callback'  => 'spabiz_funfact_subtitle_render_callback',
	
	) );
	
	// funfact description
	$wp_customize->selective_refresh->add_partial( 'funfact_description', array(
		'selector'            => '.funfact-home .section-title .text',
		'settings'            => 'funfact_description',
		'render_callback'  => 'spabiz_funfact_desc_render_callback',
	
	) );
	
	// funfact_btn_lbl
	$wp_customize->selective_refresh->add_partial( 'funfact_btn_lbl', array(
		'selector'            => '.funfact-home .main-btn',
		'settings'            => 'funfact_btn_lbl',
		'render_callback'  => 'spabiz_funfact_btn_lbl_render_callback',
	
	) );
	// funfact content
	$wp_customize->selective_refresh->add_partial( 'funfact_contents', array(
		'selector'            => '.funfact-home .counter-area'
	
	) );
	
	}

add_action( 'customize_register', 'spabiz_home_funfact_section_partials' );

// funfact title
function spabiz_funfact_title_render_callback() {
	return get_theme_mod( 'funfact_title' );
}

// funfact subtitle
function spabiz_funfact_subtitle_render_callback() {
	return get_theme_mod( 'funfact_subtitle' );
}

// funfact description
function spabiz_funfact_desc_render_callback() {
	return get_theme_mod( 'funfact_description' );
}

// funfact_btn_lbl
function spabiz_funfact_btn_lbl_render_callback() {
	return get_theme_mod( 'funfact_btn_lbl' );
}