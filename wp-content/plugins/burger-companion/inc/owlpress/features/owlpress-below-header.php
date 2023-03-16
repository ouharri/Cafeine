<?php
function owlpress_above_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	/*=========================================
	Owlpress Site Identity
	=========================================*/
	$wp_customize->add_section(
        'title_tagline',
        array(
        	'priority'      => 1,
            'title' 		=> __('Site Identity','owlpress'),
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
				'sanitize_callback' => 'owlpress_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'owlpress' ),
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
	
	
	
	// Header Social
	$wp_customize->add_setting(
		'abv_hdr_social_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icon','owlpress'),
			'section' => 'hdr_navigation',
			'priority'  => 9,
		)
	);

	$wp_customize->add_setting( 
		'hs_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'owlpress' ),
			'section'     => 'hdr_navigation',
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
			 'default' => owlpress_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','owlpress'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'owlpress' ),
						'item_name'                         => esc_html__( 'Social', 'owlpress' ),
						'priority' => 11,
						'section' => 'hdr_navigation',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
	 }		
			
	
	//Pro feature
		class Owlpress_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Crowl' == $theme->name){	
			?>
				<a class="customizer_OwlPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/crowl-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Crowl Pro','owlpress'); ?></a>
				
			<?php }else{ ?>		
			
				<a class="customizer_OwlPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/owlpress-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in OwlPress Pro','owlpress'); ?></a>
				
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'owlpress_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Owlpress_social_section_upgrade(
			$wp_customize,
			'owlpress_social_upgrade_to_pro',
				array(
					'section'				=> 'hdr_navigation',
					'priority' => 11,
				)
			)
		);		
		
	/*=========================================
	Above Header Section
	=========================================*/	
	$wp_customize->add_section(
        'below_header',
        array(
        	'priority'      => 3,
            'title' 		=> __('Below Header','owlpress'),
			'panel'  		=> 'header_section',
		)
    );
	
	// Header Info
	$wp_customize->add_setting(
		'abv_hdr_info_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_info_head',
		array(
			'type' => 'hidden',
			'label' => __('Info','owlpress'),
			'section' => 'below_header',
			'priority'  => 1,
		)
	);
	
	$wp_customize->add_setting( 
		'hs_hdr_info' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_hdr_info', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'owlpress' ),
			'section'     => 'below_header',
			'type'        => 'checkbox',
			'priority'  => 1,
		) 
	);
	
	/**
	 * Customizer Repeater for add service
	 */
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'hdr_info', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'default' => owlpress_get_hdr_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'hdr_info', 
					array(
						'label'   => esc_html__('Information','owlpress'),
						'section' => 'below_header',
						 'priority' => 2,
						'add_field_label'                   => esc_html__( 'Add New Information', 'owlpress' ),
						'item_name'                         => esc_html__( 'Information', 'owlpress' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	}
	
	//Pro feature
		class Owlpress_hdr_info_section_upgrade extends WP_Customize_Control {
			public function render_content() { 	
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Crowl' == $theme->name){	
			?>
				<a class="customizer_OwlPress_hdr_info_upgrade_section up-to-pro" href="https://burgerthemes.com/crowl-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in Crowl Pro','owlpress'); ?></a>
				
				<?php }else{ ?>	
				
				<a class="customizer_OwlPress_hdr_info_upgrade_section up-to-pro" href="https://burgerthemes.com/owlpress-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in OwlPress Pro','owlpress'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'owlpress_hdr_info_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Owlpress_hdr_info_section_upgrade(
			$wp_customize,
			'owlpress_hdr_info_upgrade_to_pro',
				array(
					'section'				=> 'below_header',
					'priority' => 3,
				)
			)
		);	
}
add_action( 'customize_register', 'owlpress_above_header_settings' );


// Header selective refresh
function owlpress_above_header_partials( $wp_customize ){
	// hdr_info
	$wp_customize->selective_refresh->add_partial( 'hdr_info', array(
		'selector'            => '.above-header .header-widget .widget-center',
	) );
	
	}

add_action( 'customize_register', 'owlpress_above_header_partials' );