<?php
function storebiz_testimonial_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Testimonial  Section
	=========================================*/
	$wp_customize->add_section(
		'testimonial_setting', array(
			'title' => esc_html__( 'Testimonial Section', 'storebiz' ),
			'priority' => 16,
			'panel' => 'storebiz_frontpage_sections',
		)
	);
	
	// Setting Head
	$wp_customize->add_setting(
		'testimonial_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'testimonial_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','storebiz'),
			'section' => 'testimonial_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_testimonial' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_testimonial', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'storebiz' ),
			'section'     => 'testimonial_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Testimonial content Section // 
	
	$wp_customize->add_setting(
		'test_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'test_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','storebiz'),
			'section' => 'testimonial_setting',
		)
	);
	
	// Title // 
	$wp_customize->add_setting(
    	'testimonial_title',
    	array(
	        'default'			=> __('Satisfy Clients','storebiz'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 7,
		)
	);	
	
	$wp_customize->add_control( 
		'testimonial_title',
		array(
		    'label'   => __('Title','storebiz'),
		    'section' => 'testimonial_setting',
			'type'           => 'text',
		)  
	);
	
	/**
	 * Customizer Repeater for add Testimonial
	 */
	
		$wp_customize->add_setting( 'testimonial_content', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => storebiz_get_testimonial_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'testimonial_content', 
					array(
						'label'   => esc_html__('Testimonial','storebiz'),
						'section' => 'testimonial_setting',
						'add_field_label'                   => esc_html__( 'Add New Testimonial', 'storebiz' ),
						'item_name'                         => esc_html__( 'Testimonial', 'storebiz' ),
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class StoreBiz_testimonial_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'ShopMax' == $theme->name){
			?>
				<a class="customizer_StoreBiz_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/shopmax-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in ShopMax Pro','storebiz'); ?></a>
			
			<?php }elseif('StoreWise' == $theme->name){ ?>
					
				<a class="customizer_StoreBiz_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/storewise-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in StoreWise Pro','storebiz'); ?></a>
				
			<?php }else{ ?>		
			
				<a class="customizer_StoreBiz_testimonial_upgrade_section up-to-pro" href="https://burgerthemes.com/storebiz-pro/" target="_blank" style="display: none;"><?php _e('More Testimonial Available in StoreBiz Pro','storebiz'); ?></a>
				
			<?php
				}
			} 
		}
		
		$wp_customize->add_setting( 'storebiz_testimonial_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new StoreBiz_testimonial_section_upgrade(
			$wp_customize,
			'storebiz_testimonial_upgrade_to_pro',
				array(
					'section'				=> 'testimonial_setting',
				)
			)
		);	
}

add_action( 'customize_register', 'storebiz_testimonial_setting' );

// Testimonial selective refresh
function storebiz_testimonial_section_partials( $wp_customize ){
	
	// testimonial_title
	$wp_customize->selective_refresh->add_partial( 'testimonial_title', array(
		'selector'            => '.front-testimonial .heading-default h4',
		'settings'            => 'testimonial_title',
		'render_callback'  => 'storebiz_testimonial_title_render_callback',
	) );	
	
	// testimonials
	$wp_customize->selective_refresh->add_partial( 'testimonials', array(
		'selector'            => '.home-testimonial .testimonials-slider'
	) );
	
	}

add_action( 'customize_register', 'storebiz_testimonial_section_partials' );


// testimonial_title
function storebiz_testimonial_title_render_callback() {
	return get_theme_mod( 'testimonial_title' );
}