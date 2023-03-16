<?php
function decorme_abv_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Header Settings Panel
	=========================================*/
	$wp_customize->add_panel( 
		'header_section', 
		array(
			'priority'      => 2,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Header', 'decorme'),
		) 
	);
	
	/*=========================================
	DecorMe Site Identity
	=========================================*/
	$wp_customize->add_section(
        'title_tagline',
        array(
        	'priority'      => 1,
            'title' 		=> __('Site Identity','decorme'),
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
				'sanitize_callback' => 'decorme_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'decorme' ),
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
	Header Navigation
	=========================================*/	
	$wp_customize->add_section(
        'hdr_navigation',
        array(
        	'priority'      => 2,
            'title' 		=> __('Header Navigation','decorme'),
			'panel'  		=> 'header_section',
		)
    );
	
	
	// Header Social
	$wp_customize->add_setting(
		'abv_hdr_social_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icon','decorme'),
			'section' => 'hdr_navigation',
			'priority'  => 2,
		)
	);

	$wp_customize->add_setting( 
		'hs_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'decorme' ),
			'section'     => 'hdr_navigation',
			'type'        => 'checkbox',
			'priority' => 3,
		) 
	);
	
	/**
	 * Customizer Repeater
	 */
	 if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'social_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => decorme_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','decorme'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'decorme' ),
						'item_name'                         => esc_html__( 'Social', 'decorme' ),
						'priority' => 4,
						'section' => 'hdr_navigation',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
			
	 }
	
	//Pro feature
		class Decoreme_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_DecorMe_social_upgrade_section up-to-pro" href="https://burgerthemes.com/decorme-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in DecorMe Pro','decorme'); ?></a>
				
			<?php
			} 
		}
		
		$wp_customize->add_setting( 'decorme_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Decoreme_social_section_upgrade(
			$wp_customize,
			'decorme_social_upgrade_to_pro',
				array(
					'section'				=> 'hdr_navigation',
					'priority' => 4,
				)
			)
		);		
		
	
	/*=========================================
	Contact Info
	=========================================*/	
	$wp_customize->add_setting(
		'abv_hdr_ct_info_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_ct_info_head',
		array(
			'type' => 'hidden',
			'label' => __('Contact Info','decorme'),
			'section' => 'hdr_navigation',
			'priority'  => 21,
		)
	);	
	
	$wp_customize->add_setting( 
		'hs_hdr_ct_info' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_hdr_ct_info', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'decorme' ),
			'section'     => 'hdr_navigation',
			'type'        => 'checkbox',
			'priority'  => 22,
		) 
	);	
	// icon // 
	$wp_customize->add_setting(
    	'hdr_ct_info_icon',
    	array(
			'default'			=> 'fa-phone',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new DecorMe_Icon_Picker_Control($wp_customize, 
		'hdr_ct_info_icon',
		array(
		    'label'   		=> __('Icon','decorme'),
		    'section' 		=> 'hdr_navigation',
			'iconset' => 'fa',
			'priority'  => 23,
			
		))  
	);	
	
	// Subtitle // 
	$wp_customize->add_setting(
    	'hdr_ct_info_subttl',
    	array(
	        'default'			=> __('+1-202-555-0170 ','decorme'),
			'sanitize_callback' => 'decorme_sanitize_html',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_ct_info_subttl',
		array(
		    'label'   		=> __('Subtitle','decorme'),
		    'section' 		=> 'hdr_navigation',
			'type'		 =>	'text',
			'priority' => 24,
		)  
	);	
	
	
	// Footer Payment Head
	$wp_customize->add_setting(
		'footer_payment_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_text',
			'priority'  => 11,
		)
	);

	$wp_customize->add_control(
	'footer_payment_head',
		array(
			'type' => 'hidden',
			'label' => __('Payment Icon','decorme'),
			'section' => 'footer_copyright',
		)
	);
	
	$wp_customize->add_setting( 
		'hs_footer_payment' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'decorme_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_footer_payment', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'decorme' ),
			'section'     => 'footer_copyright',
			'type'        => 'checkbox',
			'priority'  => 12,
		) 
	);	
	
	
	/**
	 * Customizer Repeater
	 */
	 if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'footer_payment_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => decorme_get_payment_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_payment_icons', 
					array(
						'label'   => esc_html__('Payment Icons','decorme'),
						'add_field_label'                   => esc_html__( 'Add New Payment', 'decorme' ),
						'item_name'                         => esc_html__( 'Payment', 'decorme' ),
						'priority' => 13,
						'section' => 'footer_copyright',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
	 }		
	 
	 //Pro feature
		class DecorMe_payment_icon_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_DecorMe_payment_icon_upgrade_section up-to-pro" href="#" target="_blank" style="display: none;"><?php _e('More Icons Available in DecorMe Pro','decorme'); ?></a>
			<?php
			} 
		}	
	
		$wp_customize->add_setting( 'decorme_payment_icon_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses'
		));
		$wp_customize->add_control(
			new DecorMe_payment_icon_section_upgrade(
			$wp_customize,
			'decorme_payment_icon_upgrade_to_pro',
				array(
					'section'				=> 'footer_copyright',
					'priority' => 13,
				)
			)
		);
}
add_action( 'customize_register', 'decorme_abv_header_settings' );


// Header selective refresh
function decorme_abv_header_partials( $wp_customize ){

	// hdr_ct_info_subttl
	$wp_customize->selective_refresh->add_partial( 'hdr_ct_info_subttl', array(
		'selector'            => '.header-two .main-navigation .menu-right-list .contact-info .text,.header-three .main-navigation .menu-right-list .widget-contact.first .text, .header-four .main-navigation .menu-right-list .contact-info .title',
		'settings'            => 'hdr_ct_info_subttl',
		'render_callback'  => 'decorme_hdr_ct_info_subttl_render_callback',
	) );
	
	// footer_payment_icons
	$wp_customize->selective_refresh->add_partial( 'footer_payment_icons', array(
		'selector'            => '.footer-copyright .payment_methods',
	) );
	}

add_action( 'customize_register', 'decorme_abv_header_partials' );



// hdr_ct_info_subttl
function decorme_hdr_ct_info_subttl_render_callback() {
	return get_theme_mod( 'hdr_ct_info_subttl' );
}
