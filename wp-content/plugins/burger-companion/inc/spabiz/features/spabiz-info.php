<?php
function spabiz_info_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Info  Section
	=========================================*/
	$wp_customize->add_section(
		'info_setting', array(
			'title' => esc_html__( 'Info Section', 'spabiz' ),
			'priority' => 2,
			'panel' => 'spabiz_frontpage_sections',
		)
	);
	
	
	// Head
	$wp_customize->add_setting(
		'info_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'info_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Setting','spabiz'),
			'section' => 'info_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_info' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_checkbox',
			'priority' => 4,
		) 
	);
	
	$wp_customize->add_control(
	'hs_info', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'owlpress' ),
			'section'     => 'info_setting',
			'type'        => 'checkbox',
		) 
	);
	
	
	// Info content Section // 
	
	$wp_customize->add_setting(
		'info_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spabiz_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'info_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','spabiz'),
			'section' => 'info_setting',
		)
	);
	
	// Info
		$wp_customize->add_setting( 'info_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => spabiz_get_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'info_contents', 
					array(
						'label'   => esc_html__('Information','spabiz'),
						'section' => 'info_setting',
						'add_field_label'                   => esc_html__( 'Add New Information', 'spabiz' ),
						'item_name'                         => esc_html__( 'Information', 'spabiz' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
			
	//Pro feature
	class Spabiz_information_section_upgrade extends WP_Customize_Control {
		public function render_content() { 
		$theme = wp_get_theme(); // gets the current theme
		if ( 'SpaCare' == $theme->name){
		?>	
		
			<a class="customizer_SpaCare_information_upgrade_section up-to-pro" href="https://burgerthemes.com/spacare-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in Spacare Pro','spabiz'); ?></a>
			
		<?php }else{ ?>	
		
			<a class="customizer_SpaBiz_information_upgrade_section up-to-pro" href="https://burgerthemes.com/spabiz-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in SpaBiz Pro','spabiz'); ?></a>
			
		<?php
		}}
	}
	
	$wp_customize->add_setting( 'spabiz_info_upgrade_to_pro', array(
		'capability'			=> 'edit_theme_options',
		'sanitize_callback'	=> 'wp_filter_nohtml_kses',
	));
	$wp_customize->add_control(
		new Spabiz_information_section_upgrade(
		$wp_customize,
		'spabiz_info_upgrade_to_pro',
			array(
				'section'				=> 'info_setting'
			)
		)
	);			
}

add_action( 'customize_register', 'spabiz_info_setting' );

// info selective refresh
function spabiz_home_info_section_partials( $wp_customize ){	
	
	// info content
	$wp_customize->selective_refresh->add_partial( 'info_contents', array(
		'selector'            => '.info-home .category-main'
	
	) );
	
	}

add_action( 'customize_register', 'spabiz_home_info_section_partials' );