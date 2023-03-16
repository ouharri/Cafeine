<?php
function seokart_above_footer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	// Footer Above // 
	$wp_customize->add_section(
        'footer_above',
        array(
            'title' 		=> __('Footer Above','seokart'),
			'panel'  		=> 'footer_section',
			'priority'      => 2,
		)
    );
	
	// Setting Head // 
	$wp_customize->add_setting(
		'footer_above_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 1,
		)
	);

	$wp_customize->add_control(
	'footer_above_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','seokart'),
			'section' => 'footer_above',
		)
	);
	
	// Hide / Show
	$wp_customize->add_setting( 
		'footer_above_hs' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_checkbox',
			'priority'  => 2,
		) 
	);
	
	$wp_customize->add_control(
	'footer_above_hs', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'seokart' ),
			'section'     => 'footer_above',
			'type'        => 'checkbox',
		) 
	);	
	
	
	// Content Head // 
	$wp_customize->add_setting(
		'footer_above_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'seokart_sanitize_text',
			'priority' => 3,
		)
	);

	$wp_customize->add_control(
	'footer_above_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','seokart'),
			'section' => 'footer_above',
		)
	);
	
	/**
	 * Customizer Repeater for add Info
	 */
	
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'footer_above_info', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 4,
			 'default' => seokart_get_footer_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_above_info', 
					array(
						'label'   => esc_html__('Info','seokart'),
						'section' => 'footer_above',
						'add_field_label'                   => esc_html__( 'Add New Info', 'seokart' ),
						'item_name'                         => esc_html__( 'Infos', 'seokart' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
					) 
				) 
			);
	}
	
	
	//Pro feature
		class Seokart_footer_info_section_upgrade extends WP_Customize_Control {
			public function render_content() {
			$theme = wp_get_theme(); // gets the current theme
			if ( 'DigiPress' == $theme->name){			
			?>
				<a class="customizer_SeoKart_footer_info_upgrade_section up-to-pro" href="https://burgerthemes.com/digipress-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in DigiPress Pro','seokart'); ?></a>
				
			<?php }else{ ?>	
				
				<a class="customizer_SeoKart_footer_info_upgrade_section up-to-pro" href="https://burgerthemes.com/seokart-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in SeoKart Pro','seokart'); ?></a>
				
			<?php
			} }
		}
		
	$wp_customize->add_setting( 'seokart_footer_info_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Seokart_footer_info_section_upgrade(
			$wp_customize,
			'seokart_footer_info_upgrade_to_pro',
				array(
					'section'				=> 'footer_above',
				)
			)
		);		
}
add_action( 'customize_register', 'seokart_above_footer' );

// Footer selective refresh
function seokart_above_footer_partials( $wp_customize ){
	// footer_above_info
	$wp_customize->selective_refresh->add_partial( 'footer_above_info', array(
		'selector'            => '.footer-area .footer-info',
	) );
	
	}
add_action( 'customize_register', 'seokart_above_footer_partials' );
