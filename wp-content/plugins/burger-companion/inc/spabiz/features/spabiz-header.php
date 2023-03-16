<?php
function burger_spabiz_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	
	/*=========================================
	SpaBiz Site Identity
	=========================================*/
	$wp_customize->add_section(
        'title_tagline',
        array(
        	'priority'      => 1,
            'title' 		=> __('Site Identity','spabiz'),
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
				'sanitize_callback' => 'spabiz_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'spabiz' ),
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
        	'priority'      => 1,
            'title' 		=> __('Above Header','spabiz'),
			'panel'  		=> 'header_section',
		)
    );
	
	// Header Info
	$wp_customize->add_setting(
		'abv_hdr_info_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_info_head',
		array(
			'type' => 'hidden',
			'label' => __('Info','spabiz'),
			'section' => 'above_header',
			'priority'  => 1,
		)
	);
	
	$wp_customize->add_setting( 
		'hs_hdr_info' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_hdr_info', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spabiz' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 1,
		) 
	);
	
	/**
	 * Customizer Repeater for add service
	 */
	
		$wp_customize->add_setting( 'hdr_info', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'default' => spabiz_get_hdr_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'hdr_info', 
					array(
						'label'   => esc_html__('Info','spabiz'),
						'section' => 'above_header',
						 'priority' => 2,
						'add_field_label'                   => esc_html__( 'Add New Contact', 'spabiz' ),
						'item_name'                         => esc_html__( 'Contact', 'spabiz' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	
	
	//Pro feature
	class Spabiz_contact_section_upgrade extends WP_Customize_Control {
		public function render_content() { 
		?>	
		
			<a class="customizer_SpaBiz_contact_upgrade_section up-to-pro" href="https://burgerthemes.com/spabiz-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in SpaBiz Pro','spabiz'); ?></a>
			
		<?php
		}
	}
	
	$wp_customize->add_setting( 'spabiz_contact_upgrade_to_pro', array(
		'capability'			=> 'edit_theme_options',
		'sanitize_callback'	=> 'wp_filter_nohtml_kses',
	));
	$wp_customize->add_control(
		new Spabiz_contact_section_upgrade(
		$wp_customize,
		'spabiz_contact_upgrade_to_pro',
			array(
				'section'				=> 'above_header',
				'priority' => 2,
			)
		)
	);	
	
	
	// Header Social
	$wp_customize->add_setting(
		'abv_hdr_social_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icon','spabiz'),
			'section' => 'above_header',
			'priority'  => 9,
		)
	);

	$wp_customize->add_setting( 
		'hs_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'spabiz' ),
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
			 'default' => spabiz_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','spabiz'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'spabiz' ),
						'item_name'                         => esc_html__( 'Social', 'spabiz' ),
						'priority' => 11,
						'section' => 'above_header',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
			
	//Pro feature
		class Spabiz_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>	
			
				<a class="customizer_SpaBiz_social_upgrade_section up-to-pro" href="https://burgerthemes.com/spabiz-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in SpaBiz Pro','spabiz'); ?></a>
				
			<?php
			}
		}
		
		$wp_customize->add_setting( 'spabiz_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Spabiz_social_section_upgrade(
			$wp_customize,
			'spabiz_social_upgrade_to_pro',
				array(
					'section'				=> 'above_header',
					'priority' => 11,
				)
			)
		);			
			
}
add_action( 'customize_register', 'burger_spabiz_header_settings' );


// Header selective refresh
function burger_spabiz_header_partials( $wp_customize ){
	// hdr_info
	$wp_customize->selective_refresh->add_partial( 'hdr_info', array(
		'selector'            => '.above-header .header-widget .widget-center',
	) );
	
}
add_action( 'customize_register', 'burger_spabiz_header_partials' );
