<?php
function appetizer_abv_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Header Settings Panel
	=========================================*/
	$wp_customize->add_panel( 
		'header_section', 
		array(
			'priority'      => 2,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Header', 'appetizer'),
		) 
	);
	
	/*=========================================
	Appetizer Site Identity
	=========================================*/
	$wp_customize->add_section(
        'title_tagline',
        array(
        	'priority'      => 1,
            'title' 		=> __('Site Identity','appetizer'),
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
				'sanitize_callback' => 'appetizer_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'appetizer' ),
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
            'title' 		=> __('Above Header','appetizer'),
			'panel'  		=> 'header_section',
		)
    );

	// Header Opening Hour Section
	$wp_customize->add_setting(
		'abv_hdr_ohour_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_ohour_head',
		array(
			'type' => 'hidden',
			'label' => __('Opening Hour','appetizer'),
			'section' => 'above_header',
			'priority'  => 2,
		)
	);	
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_above_opening' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_above_opening', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'appetizer' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority' => 2,
		) 
	);	
	
	
	// icon // 
	$wp_customize->add_setting(
    	'abv_hdr_opening_icon',
    	array(
	        'default' => 'fa-clock-o',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Appetizer_Icon_Picker_Control($wp_customize, 
		'abv_hdr_opening_icon',
		array(
		    'label'   		=> __('Icon','appetizer'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 3,
			
		))  
	);		
	
	// above header opening title // 
	$wp_customize->add_setting(
    	'abv_hdr_opening_ttl',
    	array(
			'default' => __('Opening Hours - 10 Am to 6 PM','appetizer'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_opening_ttl',
		array(
		    'label'   		=> __('Title','appetizer'),
		    'section'		=> 'above_header',
			'type' 			=> 'text',
			'priority'      => 3,
		)  
	);	
	
	// Header Support 
	$wp_customize->add_setting(
		'abv_hdr_phone_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_phone_head',
		array(
			'type' => 'hidden',
			'label' => __('Phone','appetizer'),
			'section' => 'above_header',
			'priority'  => 5,
		)
	);	
	
	$wp_customize->add_setting( 
		'hide_show_hdr_phone' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_hdr_phone', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'appetizer' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 6,
		) 
	);	
	
	// icon // 
	$wp_customize->add_setting(
    	'hdr_phone_icon',
    	array(
	        'default' => 'fa-mobile',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Appetizer_Icon_Picker_Control($wp_customize, 
		'hdr_phone_icon',
		array(
		    'label'   		=> __('Icon','appetizer'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 7,
			
		))  
	);	

	// Support Title // 
	$wp_customize->add_setting(
    	'hdr_phone_ttl',
    	array(
	        'default'			=> '<a href="tel:+91 123 456 7890">+91 123 456 7890</a>',
			'sanitize_callback' => 'appetizer_sanitize_html',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_phone_ttl',
		array(
		    'label'   		=> __('Text','appetizer'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 8,
		)  
	);	
	// Header Social
	$wp_customize->add_setting(
		'abv_hdr_social_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icon','appetizer'),
			'section' => 'above_header',
			'priority'  => 9,
		)
	);

	$wp_customize->add_setting( 
		'hide_show_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'appetizer' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority' => 10,
		) 
	);
	
	/**
	 * Customizer Repeater
	 */
	 if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'social_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => appetizer_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','appetizer'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'appetizer' ),
						'item_name'                         => esc_html__( 'Social', 'appetizer' ),
						'priority' => 11,
						'section' => 'above_header',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
	
	 }
	 
	 //Pro feature
		class Appetizer_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Rasam' == $theme->name){	
			?>	
				<a class="customizer_Appetizer_social_upgrade_section up-to-pro" href="https://burgerthemes.com/rasam-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Rasam Pro','appetizer'); ?></a>
				
			<?php }else{ ?>
				
				<a class="customizer_Appetizer_social_upgrade_section up-to-pro" href="https://burgerthemes.com/appetizer-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Appetizer Pro','appetizer'); ?></a>
			
			<?php
			} }
		}
		
		$wp_customize->add_setting( 'appetizer_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Appetizer_social_section_upgrade(
			$wp_customize,
			'appetizer_social_upgrade_to_pro',
				array(
					'section'				=> 'above_header',
					'priority' => 11,
				)
			)
		);		
}
add_action( 'customize_register', 'appetizer_abv_header_settings' );


// Header selective refresh
function appetizer_abv_header_partials( $wp_customize ){
	
	// abv_hdr_opening_ttl
	$wp_customize->selective_refresh->add_partial( 'abv_hdr_opening_ttl', array(
		'selector'            => '.above-header .widget-contact.first .contact-info p',
		'settings'            => 'abv_hdr_opening_ttl',
		'render_callback'  => 'appetizer_abv_hdr_opening_ttl_render_callback',
	) );
	
	// hdr_phone_ttl
	$wp_customize->selective_refresh->add_partial( 'hdr_phone_ttl', array(
		'selector'            => '.above-header .widget-contact.second .contact-info p',
		'settings'            => 'hdr_phone_ttl',
		'render_callback'  => 'appetizer_hdr_phone_ttl_render_callback',
	) );
	}

add_action( 'customize_register', 'appetizer_abv_header_partials' );


// abv_hdr_opening_ttl
function appetizer_abv_hdr_opening_ttl_render_callback() {
	return get_theme_mod( 'abv_hdr_opening_ttl' );
}


// hdr_phone_ttl
function appetizer_hdr_phone_ttl_render_callback() {
	return get_theme_mod( 'hdr_phone_ttl' );
}

