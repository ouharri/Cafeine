<?php
function owlpress_footer_above( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Footer Above
	=========================================*/
	$wp_customize->add_section(
        'footer_above',
        array(
            'title' 		=> __('Footer Above','owlpress'),
			'panel'  		=> 'footer_section',
			'priority'      => 3,
		)
    );
	
	// Hide Show
	$wp_customize->add_setting(
		'footer_above_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'owlpress_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
	'footer_above_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show Section','owlpress'),
			'section' => 'footer_above',
			'priority'  => 1,
		)
	);
	
	/**
	 * Customizer Repeater for add Contact
	 */
	
	 if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'footer_above_contact', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => owlpress_get_footer_above_contact_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_above_contact', 
					array(
						'label'   => esc_html__('Contact','owlpress'),
						'section' => 'footer_above',
						'add_field_label'                   => esc_html__( 'Add New Contact', 'owlpress' ),
						'item_name'                         => esc_html__( 'Contact', 'owlpress' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	 }	
	 
	 //Pro feature
		class Owlpress_footer_abv_contact_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Crowl' == $theme->name){	
			?>
				<a class="customizer_OwlPress_footer_abv_ct_upgrade_section up-to-pro" href="https://burgerthemes.com/crowl-pro/" target="_blank" style="display: none;"><?php _e('More Contact Available in Crowl Pro','owlpress'); ?></a>
				
				<?php }else{ ?>	
				
				<a class="customizer_OwlPress_footer_abv_ct_upgrade_section up-to-pro" href="https://burgerthemes.com/owlpress-pro/" target="_blank" style="display: none;"><?php _e('More Contact Available in OwlPress Pro','owlpress'); ?></a>
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'owlpress_footer_abv_ct_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Owlpress_footer_abv_contact_section_upgrade(
			$wp_customize,
			'owlpress_footer_abv_ct_upgrade_to_pro',
				array(
					'section'				=> 'footer_above',
				)
			)
		);
	 
	 /**
	 * Customizer Repeater
	 */
	 if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'footer_payment_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => owlpress_get_payment_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_payment_icons', 
					array(
						'label'   => esc_html__('Payment Icons','owlpress'),
						'priority' => 13,
						'section' => 'footer_copyright',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
	 }
}
add_action( 'customize_register', 'owlpress_footer_above' );
// Footer selective refresh
function owlpress_footer_above_partials( $wp_customize ){
	
	// footer_above_contact
	$wp_customize->selective_refresh->add_partial( 'footer_above_contact', array(
		'selector'            => '.main-footer .footer-above .row',
	) );
	
	
	// footer_payment_icons
	$wp_customize->selective_refresh->add_partial( 'footer_payment_icons', array(
		'selector'            => '.footer-copyright .payment_methods',
	) );
	
	}
add_action( 'customize_register', 'owlpress_footer_above_partials' );