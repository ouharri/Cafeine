<?php
function spintech_abv_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// Logo Width // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'logo_width',
			array(
				'default'			=> '140',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'spintech_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'spintech' ),
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
        	'priority'      => 2,
            'title' 		=> __('Above Header','spintech'),
			'panel'  		=> 'header_section',
		)
    );

	// Header Hiring Section
	$wp_customize->add_setting(
		'abv_hdr_hiring_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_hiring_head',
		array(
			'type' => 'hidden',
			'label' => __('Hiring','spintech'),
			'section' => 'above_header',
			'priority'  => 2,
		)
	);	
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_above_hiring' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hs_above_hiring', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority' => 2,
		) 
	);	
	
	// above header hiring title // 
	$wp_customize->add_setting(
    	'abv_hdr_hiring_ttl',
    	array(
			'default' => __('Now Hiring:','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_hiring_ttl',
		array(
		    'label'   		=> __('Title','spintech'),
		    'section'		=> 'above_header',
			'type' 			=> 'text',
			'priority'      => 3,
		)  
	);	
	
	// above header hiring title // 
	$wp_customize->add_setting(
    	'abv_hdr_hiring_content',
    	array(
			'default' => __('"Are you a driven 1st Line IT Support?","Are you a driven 1st Line IT Support?","Are you a driven 1st Line IT Support?"','spintech'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_html',
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_hiring_content',
		array(
		    'label'   		=> __('Content','spintech'),
		    'section'		=> 'above_header',
			'type' 			=> 'textarea',
			'priority'      => 3,
		)  
	);
	
	// Header Contact Info 
	$wp_customize->add_setting(
		'abv_hdr_ct_info_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_ct_info_head',
		array(
			'type' => 'hidden',
			'label' => __('Contact Info','spintech'),
			'section' => 'above_header',
			'priority'  => 5,
		)
	);	
	
	$wp_customize->add_setting( 
		'hide_show_cntct_info' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_cntct_info', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 6,
		) 
	);	
	
	// icon // 
	$wp_customize->add_setting(
    	'th_contct_icon',
    	array(
	        'default' => 'fa-clock-o',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Spintech_Icon_Picker_Control($wp_customize, 
		'th_contct_icon',
		array(
		    'label'   		=> __('Icon','spintech'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 7,
			
		))  
	);		
	// Mobile title // 
	$wp_customize->add_setting(
    	'th_contact_text',
    	array(
	        'default'			=> __('Office Hours 8:00AM - 6:00PM','spintech'),
			'sanitize_callback' => 'spintech_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'th_contact_text',
		array(
		    'label'   		=> __('Text','spintech'),
		    'section' 		=> 'above_header',
			'type'		 =>	'textarea',
			'priority' => 8,
		)  
	);
	
	// Header Social
	$wp_customize->add_setting(
		'abv_hdr_social_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icon','spintech'),
			'section' => 'above_header',
			'priority'  => 9,
		)
	);

	$wp_customize->add_setting( 
		'hide_show_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spintech' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority' => 10,
		) 
	);
	
	/**
	 * Customizer Repeater
	 */
		$wp_customize->add_setting( 'social_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => spintech_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','spintech'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'spintech' ),
						'item_name'                         => esc_html__( 'Social', 'spintech' ),
						'priority' => 11,
						'section' => 'above_header',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
			
			
	//Pro feature
		class Spintech_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'ITpress' == $theme->name){	
			?>
				<a class="customizer_spintech_social_upgrade_section up-to-pro" href="https://burgerthemes.com/itpress-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in ITpress Pro','spintech'); ?></a>
				
			<?php }elseif ( 'Burgertech' == $theme->name){ ?>	
			
				<a class="customizer_spintech_social_upgrade_section up-to-pro" href="https://burgerthemes.com/burgertech-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Burgertech Pro','spintech'); ?></a>
				
			<?php }elseif ( 'KitePress' == $theme->name){ ?>	
			
				<a class="customizer_spintech_social_upgrade_section up-to-pro" href="https://burgerthemes.com/kitepress-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in KitePress Pro','spintech'); ?></a>
				
			<?php }elseif ( 'SpinSoft' == $theme->name){ ?>	
			
				<a class="customizer_spintech_social_upgrade_section up-to-pro" href="https://burgerthemes.com/spinsoft-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in SpinSoft Pro','spintech'); ?></a>	
				
			<?php }else{ ?>	
				<a class="customizer_spintech_social_upgrade_section up-to-pro" href="https://burgerthemes.com/spintech-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Spintech Pro','spintech'); ?></a>
			<?php
				}
			}
		}
		
		$wp_customize->add_setting( 'spintech_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Spintech_social_section_upgrade(
			$wp_customize,
			'spintech_social_upgrade_to_pro',
				array(
					'section'				=> 'above_header',
					'priority' => 12,
				)
			)
		);			
}
add_action( 'customize_register', 'spintech_abv_header_settings' );


// Header selective refresh
function spintech_abv_header_partials( $wp_customize ){

	// hs_above_hiring
	$wp_customize->selective_refresh->add_partial(
		'hs_above_hiring', array(
			'selector' => '.main-header .hiring',
			'container_inclusive' => true,
			'render_callback' => 'above_header',
			'fallback_refresh' => true,
		)
	);
	
	// hide_show_cntct_info
	$wp_customize->selective_refresh->add_partial(
		'hide_show_cntct_info', array(
			'selector' => '.above-header .widget-contact',
			'container_inclusive' => true,
			'render_callback' => 'above_header',
			'fallback_refresh' => true,
		)
	);
	
	// hide_show_social_icon
	$wp_customize->selective_refresh->add_partial(
		'hide_show_social_icon', array(
			'selector' => '.main-header .widget_social_widget',
			'container_inclusive' => true,
			'render_callback' => 'above_header',
			'fallback_refresh' => true,
		)
	);
	
	// abv_hdr_hiring_ttl
	$wp_customize->selective_refresh->add_partial( 'abv_hdr_hiring_ttl', array(
		'selector'            => '.main-header .hiring .text-heading strong',
		'settings'            => 'abv_hdr_hiring_ttl',
		'render_callback'  => 'spintech_abv_hdr_hiring_ttl_render_callback',
	) );
	
	// th_contact_text
	$wp_customize->selective_refresh->add_partial( 'th_contact_text', array(
		'selector'            => '.above-header .widget-contact p',
		'settings'            => 'th_contact_text',
		'render_callback'  => 'spintech_th_contact_text_render_callback',
	) );
	}

add_action( 'customize_register', 'spintech_abv_header_partials' );


// abv_hdr_hiring_ttl
function spintech_abv_hdr_hiring_ttl_render_callback() {
	return get_theme_mod( 'abv_hdr_hiring_ttl' );
}

// th_contact_text
function spintech_th_contact_text_render_callback() {
	return get_theme_mod( 'th_contact_text' );
}