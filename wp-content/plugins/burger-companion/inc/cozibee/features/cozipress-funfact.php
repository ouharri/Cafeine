<?php
function cozipress_funfact_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Funfact Section
	=========================================*/
	$wp_customize->add_section(
		'funfact_setting', array(
			'title' => esc_html__( 'Funfact Section', 'cozipress' ),
			'priority' => 17,
			'panel' => 'cozipress_frontpage_sections',
		)
	);

	// Funfact Settings Section // 
	
	$wp_customize->add_setting(
		'funfact_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 2,
		)
	);

	$wp_customize->add_control(
	'funfact_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','cozipress'),
			'section' => 'funfact_setting',
		)
	);
	// hide/show
	$wp_customize->add_setting( 
		'hs_funfact' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'priority' => 2,
		) 
	);
	
	$wp_customize->add_control(
	'hs_funfact', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'funfact_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	// Funfact content Section // 
	
	$wp_customize->add_setting(
		'funfact_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'funfact_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','cozipress'),
			'section' => 'funfact_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add Funfact
	 */
	
		$wp_customize->add_setting( 'funfact_contents', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'priority' => 8,
			'default' => cozipress_get_funfact_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'funfact_contents', 
					array(
						'label'   => esc_html__('Funfact','cozipress'),
						'section' => 'funfact_setting',
						'add_field_label'                   => esc_html__( 'Add New Funfact', 'cozipress' ),
						'item_name'                         => esc_html__( 'Funfact', 'cozipress' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Cozipress_funfact_section_upgrade extends WP_Customize_Control {
			public function render_content() {
			?>
				<a class="customizer_CoziPress_funfact_upgrade_section up-to-pro" href="https://burgerthemes.com/cozibee-pro/" target="_blank" style="display: none;"><?php _e('More Funfact Available in CoziBee Pro','cozipress'); ?></a>
				
			<?php
			} 
		}
		
		$wp_customize->add_setting( 'cozipress_funfact_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Cozipress_funfact_section_upgrade(
			$wp_customize,
			'cozipress_funfact_upgrade_to_pro',
				array(
					'section'				=> 'funfact_setting',
				)
			)
		);	
	
	// Funfact Background // 	
	$wp_customize->add_setting(
		'funfact_bg_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 10,
		)
	);

	$wp_customize->add_control(
	'funfact_bg_head',
		array(
			'type' => 'hidden',
			'label' => __('Background','cozipress'),
			'section' => 'funfact_setting',
		)
	);
	
    $wp_customize->add_setting( 
    	'funfact_bg_setting' , 
    	array(
			'default'			=> BURGER_COMPANION_PLUGIN_URL .'inc/cozibee/images/funfact/dotted_image.png',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_url',	
			'priority' => 11,
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'funfact_bg_setting' ,
		array(
			'label'          => __( 'Background Image', 'cozipress' ),
			'section'        => 'funfact_setting',
		) 
	));

	$wp_customize->add_setting( 
		'funfact_bg_position' , 
			array(
			'default' => 'fixed',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_select',
			'priority' => 12,
		) 
	);
	
	$wp_customize->add_control(
		'funfact_bg_position' , 
			array(
				'label'          => __( 'Image Position', 'cozipress' ),
				'section'        => 'funfact_setting',
				'type'           => 'radio',
				'choices'        => 
				array(
					'fixed'=> __( 'Fixed', 'cozipress' ),
					'scroll' => __( 'Scroll', 'cozipress' )
			)  
		) 
	);
}

add_action( 'customize_register', 'cozipress_funfact_setting' );

// service selective refresh
function cozipress_funfact_section_partials( $wp_customize ){
	
	// Funfact content
	$wp_customize->selective_refresh->add_partial( 'funfact_contents', array(
		'selector'            => '.fun-contents'
	) );
	}

add_action( 'customize_register', 'cozipress_funfact_section_partials' );