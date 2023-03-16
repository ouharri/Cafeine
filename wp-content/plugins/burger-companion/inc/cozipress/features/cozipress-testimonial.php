<?php
function cozipress_testimonial_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Testimonial  Section
	=========================================*/
	$wp_customize->add_section(
		'testimonial_setting', array(
			'title' => esc_html__( 'Testimonial Section', 'cozipress' ),
			'priority' => 16,
			'panel' => 'cozipress_frontpage_sections',
		)
	);
	
	// Testimonial Settings Section // 
	$wp_customize->add_setting(
		'testimonial_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'testimonial_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozipress'),
			'section' => 'testimonial_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_testimonial' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_testimonial', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'testimonial_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Testimnial Header Section // 
	$wp_customize->add_setting(
		'testimonial_headings'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'testimonial_headings',
		array(
			'type' => 'hidden',
			'label' => __('Header','cozipress'),
			'section' => 'testimonial_setting',
		)
	);
	
	// Testimonial Title // 
	$wp_customize->add_setting(
    	'testimonial_title',
    	array(
	        'default'			=> __('Explore','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_title',
		array(
		    'label'   => __('Title','cozipress'),
		    'section' => 'testimonial_setting',
			'type'           => 'text',
		)  
	);
	
	// Testimonial Subtitle // 
	$wp_customize->add_setting(
    	'testimonial_subtitle',
    	array(
	        'default'			=> __('Our <span class="text-primary">Testimonials</span>','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_subtitle',
		array(
		    'label'   => __('Subtitle','cozipress'),
		    'section' => 'testimonial_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Testimonial Description // 
	$wp_customize->add_setting(
    	'testimonial_description',
    	array(
	        'default'			=> __('This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_description',
		array(
		    'label'   => __('Description','cozipress'),
		    'section' => 'testimonial_setting',
			'type'           => 'textarea',
		)  
	);

	// Testimonial content Section // 
	
	$wp_customize->add_setting(
		'test_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'test_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress'),
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
			 'default' => cozipress_get_testimonial_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'testimonials', 
					array(
						'label'   => esc_html__('Testimonial','cozipress'),
						'section' => 'testimonial_setting',
						'add_field_label'                   => esc_html__( 'Add New Testimonial', 'cozipress' ),
						'item_name'                         => esc_html__( 'Testimonial', 'cozipress' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
					) 
				) 
			);
			
	//Pro feature
		class Cozipress_testimonial_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Sipri' == $theme->name){
			?>
				<a class="customizer_CoziPress_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/sipri-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in Sipri Pro','cozipress'); ?></a>
			
			<?php }elseif('Anexa' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/anexa-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in Anexa Pro','cozipress'); ?></a>
				
			<?php }elseif('CoziWeb' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/coziweb-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in CoziWeb Pro','cozipress'); ?></a>	
				
			<?php }elseif('CoziPlus' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/coziplus-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in CoziPlus Pro','cozipress'); ?></a>		
				
			<?php }elseif('CoziBee' == $theme->name){ ?>
				
				<a class="customizer_CoziPress_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/cozibee-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in CoziBee Pro','cozipress'); ?></a>			
				
			<?php }else{ ?>	
			
				<a class="customizer_CoziPress_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/cozipress-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in CoziPress Pro','cozipress'); ?></a>
				
			<?php
			} } 
		}
		
		$wp_customize->add_setting( 'cozipress_testimonial_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Cozipress_testimonial_section_upgrade(
			$wp_customize,
			'cozipress_testimonial_upgrade_to_pro',
				array(
					'section'				=> 'testimonial_setting',
				)
			)
		);
		
	// Background // 
	$wp_customize->add_setting(
		'testimonial_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 12,
		)
	);

	$wp_customize->add_control(
	'testimonial_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','cozipress'),
			'section' => 'testimonial_setting',
		)
	);
	
	// Background Image // 
    $wp_customize->add_setting( 
    	'testimonial_bg_img' , 
    	array(
			'default' 			=> BURGER_COMPANION_PLUGIN_URL . 'inc/cozipress/images/testimonials/testimonial_bg.jpg',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_url',	
			'priority' => 13,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'testimonial_bg_img' ,
		array(
			'label'          => esc_html__( 'Background Image', 'cozipress'),
			'section'        => 'testimonial_setting',
		) 
	));
	
	// Background Attachment // 
	$wp_customize->add_setting( 
		'testimonial_back_attach' , 
			array(
			'default' => 'fixed',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_select',
			'priority'  => 14,
		) 
	);
	
	$wp_customize->add_control(
	'testimonial_back_attach' , 
		array(
			'label'          => __( 'Background Attachment', 'cozipress' ),
			'section'        => 'testimonial_setting',
			'type'           => 'select',
			'choices'        => 
			array(
				'inherit' => __( 'Inherit', 'cozipress' ),
				'scroll' => __( 'Scroll', 'cozipress' ),
				'fixed'   => __( 'Fixed', 'cozipress' )
			) 
		) 
	);
}

add_action( 'customize_register', 'cozipress_testimonial_setting' );

// Testimonial selective refresh
function cozipress_testimonial_section_partials( $wp_customize ){
	
	// testimonial_title
	$wp_customize->selective_refresh->add_partial( 'testimonial_title', array(
		'selector'            => '.home-testimonial .heading-default .ttl',
		'settings'            => 'testimonial_title',
		'render_callback'  => 'cozipress_testimonial_title_render_callback',
	
	) );
	
	// testimonial_subtitle
	$wp_customize->selective_refresh->add_partial( 'testimonial_subtitle', array(
		'selector'            => '.home-testimonial .heading-default h2',
		'settings'            => 'testimonial_subtitle',
		'render_callback'  => 'cozipress_testimonial_subtitle_render_callback',
	
	) );
	
	// testimonial_description
	$wp_customize->selective_refresh->add_partial( 'testimonial_description', array(
		'selector'            => '.home-testimonial .heading-default p',
		'settings'            => 'testimonial_description',
		'render_callback'  => 'cozipress_testimonial_description_render_callback',
	
	) );
	// testimonials
	$wp_customize->selective_refresh->add_partial( 'testimonials', array(
		'selector'            => '.home-testimonial .testimonials-slider'
	) );
	
	}

add_action( 'customize_register', 'cozipress_testimonial_section_partials' );

// testimonial_title
function cozipress_testimonial_title_render_callback() {
	return get_theme_mod( 'testimonial_title' );
}

// testimonial_subtitle
function cozipress_testimonial_subtitle_render_callback() {
	return get_theme_mod( 'testimonial_subtitle' );
}

// testimonial_description
function cozipress_testimonial_description_render_callback() {
	return get_theme_mod( 'testimonial_description' );
}