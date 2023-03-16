<?php
function setto_above_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Setto Site Identity
	=========================================*/
	$wp_customize->add_section(
        'title_tagline',
        array(
        	'priority'      => 1,
            'title' 		=> __('Site Identity','setto'),
			'panel'  		=> 'header_section',
		)
    );

	// Logo Width // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'logo_width',
			array(
				'default'			=> '140',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'setto_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'setto' ),
				'section'  => 'title_tagline',
				 'input_attrs' => array(
					'min'    => 0,
					'max'    => 500,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	}
	
	/*=========================================
	Above Header Section
	=========================================*/	
	$wp_customize->add_section(
        'above_header',
        array(
        	'priority'      => 1,
            'title' 		=> __('Above Header','setto'),
			'panel'  		=> 'header_section',
		)
    );
	
	// Setting Head
	$wp_customize->add_setting(
		'abv_hdr_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','setto'),
			'section' => 'above_header',
			'priority'  => 1,
		)
	);
	
	// Hide/Show
	$wp_customize->add_setting( 
		'hs_abv_hdr' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_abv_hdr', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'setto' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 1,
		) 
	);
			
	
	// Header Info 1
	$wp_customize->add_setting(
		'abv_hdr_info1_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_info1_head',
		array(
			'type' => 'hidden',
			'label' => __('Info 1','setto'),
			'section' => 'above_header',
			'priority'  => 1,
		)
	);
	
	
	// Hide/Show
	$wp_customize->add_setting( 
		'hs_abv_hdr_info1' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_abv_hdr_info1', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'setto' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 1,
		) 
	);
	
	
	// Info 1 // 
	$wp_customize->add_setting(
    	'abv_hdr_info1',
    	array(
	        'default'			=> __('Shop today free shipping on order over $50.00','setto'),
			'sanitize_callback' => 'setto_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_info1',
		array(
		    'label'   		=> __('Content','setto'),
		    'section' 		=> 'above_header',
			'type'		 =>	'textarea',
			'priority' => 2,
		)  
	);	
	
	
	// Header Info 2
	$wp_customize->add_setting(
		'abv_hdr_info2_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_info2_head',
		array(
			'type' => 'hidden',
			'label' => __('Info 2','setto'),
			'section' => 'above_header',
			'priority'  => 3,
		)
	);
	
	
	// Hide/Show
	$wp_customize->add_setting( 
		'hs_abv_hdr_info2' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_abv_hdr_info2', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'setto' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 4,
		) 
	);
	
	
	/**
	 * Customizer Repeater
	 */
	 if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'social_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => setto_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','setto'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'setto' ),
						'item_name'                         => esc_html__( 'Social', 'setto' ),
						'priority' => 5,
						'section' => 'above_header',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
	 }
	 
	 //Pro feature
		class Setto_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
			if( 'Setto Lifestyle' == $theme->name){
			?>	
				<a class="customizer_Setto_social_upgrade_section up-to-pro" href="https://burgerthemes.com/setto-lifestyle-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Setto Lifestyle Pro','setto'); ?></a>
			<?php }else{ ?>	
				<a class="customizer_Setto_social_upgrade_section up-to-pro" href="https://burgerthemes.com/setto-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Setto Pro','setto'); ?></a>
			<?php
			} }
		}
		
		$wp_customize->add_setting( 'setto_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Setto_social_section_upgrade(
			$wp_customize,
			'setto_social_upgrade_to_pro',
				array(
					'section'				=> 'above_header',
					'priority' => 6,
				)
			)
		);	

	// Header Info 3
	$wp_customize->add_setting(
		'abv_hdr_info3_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_info3_head',
		array(
			'type' => 'hidden',
			'label' => __('Info 3','setto'),
			'section' => 'above_header',
			'priority'  => 6,
		)
	);
	
	
	// Hide/Show
	$wp_customize->add_setting( 
		'hs_abv_hdr_info3' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_abv_hdr_info3', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'setto' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 7,
		) 
	);
	
	
	// Info 3 // 
	$wp_customize->add_setting(
    	'abv_hdr_info3',
    	array(
	        'default'			=> __('<span class="fa fa-truck">
                                    </span>
                                    <span class="track-text">Track your order</span>','setto'),
			'sanitize_callback' => 'setto_sanitize_text',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_info3',
		array(
		    'label'   		=> __('Content','setto'),
		    'section' 		=> 'above_header',
			'type'		 =>	'textarea',
			'priority' => 8,
		)  
	);	
	
	// Info 3 // 
	$wp_customize->add_setting(
    	'abv_hdr_info3_link',
    	array(
	        'default'			=> '#',
			'sanitize_callback' => 'setto_sanitize_url',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_info3_link',
		array(
		    'label'   		=> __('Link','setto'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 8,
		)  
	);	
}
add_action( 'customize_register', 'setto_above_header_settings' );


// Header selective refresh
function setto_above_header_partials( $wp_customize ){
	// abv_hdr_info1
	$wp_customize->selective_refresh->add_partial( 'abv_hdr_info1', array(
		'selector'            => '.above-header .info1 p, .above-header .info1 span',
		'settings'            => 'abv_hdr_info1',
		'render_callback'  => 'setto_abv_hdr_info1_render_callback',
	) );
	
	// abv_hdr_info3
	$wp_customize->selective_refresh->add_partial( 'abv_hdr_info3', array(
		'selector'            => '.above-header .info3',
		'settings'            => 'abv_hdr_info3',
		'render_callback'  => 'setto_abv_hdr_info3_render_callback',
	) );
	
	}

add_action( 'customize_register', 'setto_above_header_partials' );



// abv_hdr_info1
function setto_abv_hdr_info1_render_callback() {
	return get_theme_mod( 'abv_hdr_info1' );
}

// abv_hdr_info3
function setto_abv_hdr_info3_render_callback() {
	return get_theme_mod( 'abv_hdr_info3' );
}