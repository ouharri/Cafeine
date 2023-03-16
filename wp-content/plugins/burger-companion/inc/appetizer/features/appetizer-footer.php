<?php
function burger_appetizer_footer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	// Footer Above // 
	$wp_customize->add_section(
        'footer_above',
        array(
            'title' 		=> __('Footer Above','appetizer'),
			'panel'  		=> 'footer_section',
			'priority'      => 2,
		)
    );
	
	// Setting Head
	$wp_customize->add_setting(
		'footer_abv_Setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'footer_abv_Setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','appetizer'),
			'section' => 'footer_above',
			'priority'  => 1,
		)
	);
	
	
	// Hide / Show
	$wp_customize->add_setting(
		'footer_abv_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
	'footer_abv_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show','appetizer'),
			'section' => 'footer_above',
			'priority'  => 2,
		)
	);
	
	
	// Content Head
	$wp_customize->add_setting(
		'footer_abv_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'footer_abv_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','appetizer'),
			'section' => 'footer_above',
			'priority'  => 3,
		)
	);
	
	/**
	 * Customizer Repeater for add Info
	 */
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'footer_abv_info', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'default' => appetizer_get_footer_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_abv_info', 
					array(
						'label'   => esc_html__('Information','appetizer'),
						'section' => 'footer_above',
						'add_field_label'                   => esc_html__( 'Add New Information', 'appetizer' ),
						'item_name'                         => esc_html__( 'Information', 'appetizer' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	}	
	
	
		//Pro feature
		class Appetizer_information_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
			if ( 'Rasam' == $theme->name){	
			?>
				<a class="customizer_Appetizer_information_upgrade_section up-to-pro" href="https://burgerthemes.com/rasam-pro/" target="_blank" style="display: none;"><?php _e('More Information Available in Rasam Pro','appetizer'); ?></a>
				
			<?php }else{ ?>

				<a class="customizer_Appetizer_information_upgrade_section up-to-pro" href="https://burgerthemes.com/appetizer-pro/" target="_blank" style="display: none;"><?php _e('More Information Available in Appetizer Pro','appetizer'); ?></a>
			
			<?php
			} }
		}
		
		$wp_customize->add_setting( 'appetizer_information_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Appetizer_information_section_upgrade(
			$wp_customize,
			'appetizer_information_upgrade_to_pro',
				array(
					'section'				=> 'footer_above',
				)
			)
		);
		
		
		// Payment Icon 
	$wp_customize->add_setting(
		'footer_btm_payment_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'footer_btm_payment_head',
		array(
			'type' => 'hidden',
			'label' => __('Payment','appetizer'),
			'section' => 'footer_bottom',
			'priority'  => 11,
		)
	);	
	
	$wp_customize->add_setting( 
		'hs_footer_payment' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'appetizer_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hs_footer_payment', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'appetizer' ),
			'section'     => 'footer_bottom',
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
			 'default' => appetizer_get_payment_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_payment_icons', 
					array(
						'label'   => esc_html__('Payment Icons','appetizer'),
						'priority' => 13,
						'section' => 'footer_bottom',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
		}	
}
add_action( 'customize_register', 'burger_appetizer_footer' );
// Footer selective refresh
function burger_appetizer_footer_partials( $wp_customize ){
	
	// footer_abv_info
	$wp_customize->selective_refresh->add_partial( 'footer_abv_info', array(
		'selector'            => '.footer-above .info-wrp',
	) );
	
	}
add_action( 'customize_register', 'burger_appetizer_footer_partials' );
