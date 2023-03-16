<?php
function seokart_above_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

	/*=========================================
	Seokart Site Identity
	=========================================*/
	
	// Logo Width // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'logo_width',
			array(
				'default'			=> '140',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'seokart_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'seokart' ),
				'section'  => 'title_tagline',
				  'input_attrs' => array(
					'min'    => 1,
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
        	'priority'      => 2,
            'title' 		=> __('Above Header','seokart'),
			'panel'  		=> 'header_section',
		)
    );

	//  Phone
	$wp_customize->add_setting(
		'abv_hdr_phone_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_phone_head',
		array(
			'type' => 'hidden',
			'label' => __('Phone','seokart'),
			'section' => 'above_header',
			'priority'  => 2,
		)
	);	
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_above_phone' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_above_phone', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'seokart' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority' => 2,
		) 
	);	
		
	
	// Phone title // 
	$wp_customize->add_setting(
    	'abv_hdr_phone_ttl',
    	array(
			'default' => __('+01 2345 6789','seokart'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_html',
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_phone_ttl',
		array(
		    'label'   		=> __('Title','seokart'),
		    'section'		=> 'above_header',
			'type' 			=> 'text',
			'priority'      => 3,
		)  
	);	
	
	// Header Email 
	$wp_customize->add_setting(
		'abv_hdr_email_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_email_head',
		array(
			'type' => 'hidden',
			'label' => __('Email','seokart'),
			'section' => 'above_header',
			'priority'  => 5,
		)
	);	
	
	$wp_customize->add_setting( 
		'hide_show_hdr_email' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_hdr_email', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'seokart' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 6,
		) 
	);	

	// Email Title // 
	$wp_customize->add_setting(
    	'hdr_email_ttl',
    	array(
	        'default'			=> __('hello@example.com','seokart'),
			'sanitize_callback' => 'seokart_sanitize_text',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_email_ttl',
		array(
		    'label'   		=> __('Text','seokart'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 8,
		)  
	);	
	
	
	
	// Header Info 
	$wp_customize->add_setting(
		'abv_hdr_info_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_info_head',
		array(
			'type' => 'hidden',
			'label' => __('Info','seokart'),
			'section' => 'above_header',
			'priority'  => 9,
		)
	);	
	
	$wp_customize->add_setting( 
		'hide_show_hdr_info' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_hdr_info', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'seokart' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 10,
		) 
	);	

	// Info Title // 
	$wp_customize->add_setting(
    	'hdr_info_ttl',
    	array(
	        'default'			=> __('92 Bowery St, New york, NY 10013','seokart'),
			'sanitize_callback' => 'seokart_sanitize_text',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_info_ttl',
		array(
		    'label'   		=> __('Text','seokart'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 11,
		)  
	);	
	
	// Info Link // 
	$wp_customize->add_setting(
    	'hdr_info_link',
    	array(
			'sanitize_callback' => 'seokart_sanitize_url',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_info_link',
		array(
		    'label'   		=> __('Link','seokart'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 12,
		)  
	);	
	
	// Header Social
	$wp_customize->add_setting(
		'abv_hdr_social_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icon','seokart'),
			'section' => 'above_header',
			'priority'  => 13,
		)
	);

	$wp_customize->add_setting( 
		'hide_show_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'seokart' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority' => 14,
		) 
	);
	
	/**
	 * Customizer Repeater
	 */
		$wp_customize->add_setting( 'social_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => seokart_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','seokart'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'seokart' ),
						'item_name'                         => esc_html__( 'Social', 'seokart' ),
						'priority' => 15,
						'section' => 'above_header',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
	
	//Pro feature
		class Seokart_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
			if ( 'DigiPress' == $theme->name){	
			?>
				<a class="customizer_SeoKart_social_upgrade_section up-to-pro" href="https://burgerthemes.com/digipress-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in DigiPress Pro','seokart'); ?></a>
			<?php }else{ ?>	
				<a class="customizer_SeoKart_social_upgrade_section up-to-pro" href="https://burgerthemes.com/seokart-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in SeoKart Pro','seokart'); ?></a>
			<?php
			}} 
		}
		
	$wp_customize->add_setting( 'seokart_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Seokart_social_section_upgrade(
			$wp_customize,
			'seokart_social_upgrade_to_pro',
				array(
					'section'				=> 'above_header',
					'priority' => 16,
				)
			)
		);		
}
add_action( 'customize_register', 'seokart_above_header_settings' );


// Header selective refresh
function seokart_above_header_partials( $wp_customize ){
	
	// abv_hdr_phone_ttl
	$wp_customize->selective_refresh->add_partial( 'abv_hdr_phone_ttl', array(
		'selector'            => '.top-header-wrap li.phone a',
		'settings'            => 'abv_hdr_phone_ttl',
		'render_callback'  => 'seokart_abv_hdr_phone_ttl_render_callback',
	) );
	
	// hdr_email_ttl
	$wp_customize->selective_refresh->add_partial( 'hdr_email_ttl', array(
		'selector'            => '.top-header-wrap li.email a',
		'settings'            => 'hdr_email_ttl',
		'render_callback'  => 'seokart_hdr_email_ttl_render_callback',
	) );
	
	// hdr_info_ttl
	$wp_customize->selective_refresh->add_partial( 'hdr_info_ttl', array(
		'selector'            => '.top-header-wrap li.info a',
		'settings'            => 'hdr_info_ttl',
		'render_callback'  => 'seokart_hdr_info_ttl_render_callback',
	) );
	
	}

add_action( 'customize_register', 'seokart_above_header_partials' );


// abv_hdr_phone_ttl
function seokart_abv_hdr_phone_ttl_render_callback() {
	return get_theme_mod( 'abv_hdr_phone_ttl' );
}

// hdr_email_ttl
function seokart_hdr_email_ttl_render_callback() {
	return get_theme_mod( 'hdr_email_ttl' );
}

// hdr_info_ttl
function seokart_hdr_info_ttl_render_callback() {
	return get_theme_mod( 'hdr_info_ttl' );
}