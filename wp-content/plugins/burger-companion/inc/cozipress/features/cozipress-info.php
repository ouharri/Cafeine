<?php
function cozipress_info_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Info  Section
	=========================================*/
	$wp_customize->add_section(
		'info_setting', array(
			'title' => esc_html__( 'Info Section', 'cozipress' ),
			'priority' => 3,
			'panel' => 'cozipress_frontpage_sections',
		)
	);
	// Info Settings Section // 
	
	$wp_customize->add_setting(
		'info_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'info_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozipress'),
			'section' => 'info_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_info' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'priority' => 6,
		) 
	);
	
	$wp_customize->add_control(
	'hs_info', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'info_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Info content Section // 
	
	$wp_customize->add_setting(
		'info_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'info_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress'),
			'section' => 'info_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add info
	 */
	
		$wp_customize->add_setting( 'info_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => cozipress_get_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'info_contents', 
					array(
						'label'   => esc_html__('Information','cozipress'),
						'section' => 'info_setting',
						'add_field_label'                   => esc_html__( 'Add New Information', 'cozipress' ),
						'item_name'                         => esc_html__( 'Information', 'cozipress' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Cozipress_info_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
				if ( 'CoziWeb' == $theme->name){	
			?>
				<a class="customizer_CoziPress_info_upgrade_section up-to-pro" href="https://burgerthemes.com/coziweb-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in CoziWeb Pro','cozipress'); ?></a>
			<?php }else{ ?>		
				<a class="customizer_CoziPress_info_upgrade_section up-to-pro" href="https://burgerthemes.com/cozipress-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in Cozipress Pro','cozipress'); ?></a>
				
			<?php
			}}
		}
		
		$wp_customize->add_setting( 'cozipress_info_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Cozipress_info_section_upgrade(
			$wp_customize,
			'cozipress_info_upgrade_to_pro',
				array(
					'section'				=> 'info_setting',
				)
			)
		);
		
}

add_action( 'customize_register', 'cozipress_info_setting' );

// info selective refresh
function cozipress_home_info_section_partials( $wp_customize ){	
	// info content
	$wp_customize->selective_refresh->add_partial( 'info_contents', array(
		'selector'            => '.info-section .info-wrapper'
	) );
	
	}

add_action( 'customize_register', 'cozipress_home_info_section_partials' );