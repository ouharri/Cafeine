<?php
function setto_below_footer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	/*=========================================
	Breadcrumb  Section
	=========================================*/
	$wp_customize->add_setting(
		'breadcrumb_contents'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'breadcrumb_contents',
		array(
			'type' => 'hidden',
			'label' => __('Content','setto'),
			'section' => 'breadcrumb_setting',
		)
	);
	
	
	// Content size // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
	$wp_customize->add_setting(
    	'breadcrumb_min_height',
    	array(
			'default'     	=> '50',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_range_value',
			'transport'         => 'postMessage',
			'priority' => 8,
		)
	);
	$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'breadcrumb_min_height', 
			array(
				'label'      => __( 'Min Height', 'setto'),
				'section'  => 'breadcrumb_setting',
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 1000,
					'step'   => 1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	}	
	/*=========================================
	Footer Bottom
	=========================================*/
	$wp_customize->add_section(
        'footer_bottom',
        array(
            'title' 		=> __('Footer Bottom','setto'),
			'panel'  		=> 'footer_section',
			'priority'      => 5,
		)
    );
	
	// Hide Show
	$wp_customize->add_setting(
		'footer_bottom_hs'
			,array(
			'default'     	=> '1',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'setto_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
	'footer_bottom_hs',
		array(
			'type' => 'checkbox',
			'label' => __('Hide / Show Section','setto'),
			'section' => 'footer_bottom',
			'priority'  => 1,
		)
	);
	
	/**
	 * Customizer Repeater for add Contact
	 */
	if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'footer_bottom_contact', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => setto_get_footer_bottom_contact_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_bottom_contact', 
					array(
						'label'   => esc_html__('Contact','setto'),
						'section' => 'footer_bottom',
						'add_field_label'                   => esc_html__( 'Add New Contact', 'setto' ),
						'item_name'                         => esc_html__( 'Contact', 'setto' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
	}

	//Pro feature
		class Setto_footer_contact_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
			if( 'Setto Lifestyle' == $theme->name){	
			?>	
				<a class="customizer_Setto_footer_contact_upgrade_section up-to-pro" href="https://burgerthemes.com/setto-lifestyle-pro/" target="_blank" style="display: none;"><?php _e('More Contact Available in Setto Lifestyle Pro','setto'); ?></a>
			<?php }else{ ?>	
				<a class="customizer_Setto_footer_contact_upgrade_section up-to-pro" href="https://burgerthemes.com/setto-pro/" target="_blank" style="display: none;"><?php _e('More Contact Available in Setto Pro','setto'); ?></a>
			<?php
			} }
		}
		
		$wp_customize->add_setting( 'setto_footer_contact_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 8,
		));
		$wp_customize->add_control(
			new Setto_footer_contact_section_upgrade(
			$wp_customize,
			'setto_footer_contact_upgrade_to_pro',
				array(
					'section'				=> 'footer_bottom'
				)
			)
		);
		
/**
	 * Customizer Repeater
	 */
	 if ( class_exists( 'Burger_Companion_Repeater' ) ) {
		$wp_customize->add_setting( 'footer_social_icons', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'default' => setto_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'footer_social_icons', 
					array(
						'label'   => esc_html__('Social Icons','setto'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'setto' ),
						'item_name'                         => esc_html__( 'Social', 'setto' ),
						'priority' => 13,
						'section' => 'footer_copyright',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
	 }		
}
add_action( 'customize_register', 'setto_below_footer' );
// Footer selective refresh
function setto_below_footer_partials( $wp_customize ){
	
	// footer_bottom_contact
	$wp_customize->selective_refresh->add_partial( 'footer_bottom_contact', array(
		'selector'            => '.footer-section .company-details-area .company-ul',
	) );
	
	}

add_action( 'customize_register', 'setto_below_footer_partials' );
