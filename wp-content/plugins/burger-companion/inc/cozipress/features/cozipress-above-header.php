<?php
function cozipress_above_header_settings( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Header Settings Panel
	=========================================*/
	$wp_customize->add_panel( 
		'header_section', 
		array(
			'priority'      => 2,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Header', 'cozipress'),
		) 
	);
	
	/*=========================================
	Cozipress Site Identity
	=========================================*/

	// Logo Width // 
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'logo_width',
			array(
				'default'			=> '140',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'cozipress_sanitize_range_value',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'logo_width', 
			array(
				'label'      => __( 'Logo Width', 'cozipress' ),
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
        	'priority'      => 2,
            'title' 		=> __('Above Header','cozipress'),
			'panel'  		=> 'header_section',
		)
    );

	// Header Opening Hour Section
	$wp_customize->add_setting(
		'abv_hdr_ohour_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_ohour_head',
		array(
			'type' => 'hidden',
			'label' => __('Opening Hour','cozipress'),
			'section' => 'above_header',
			'priority'  => 2,
		)
	);	
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_above_opening' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hs_above_opening', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority' => 2,
		) 
	);	
	
	
	// icon // 
	$wp_customize->add_setting(
    	'abv_hdr_opening_icon',
    	array(
	        'default' => 'fa-clock-o',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Cozipress_Icon_Picker_Control($wp_customize, 
		'abv_hdr_opening_icon',
		array(
		    'label'   		=> __('Icon','cozipress'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 3,
			
		))  
	);		
	
	// above header opening title // 
	$wp_customize->add_setting(
    	'abv_hdr_opening_ttl',
    	array(
			'default' => __('Opening Hour','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_opening_ttl',
		array(
		    'label'   		=> __('Title','cozipress'),
		    'section'		=> 'above_header',
			'type' 			=> 'text',
			'priority'      => 3,
		)  
	);	
	
	// above header opening title // 
	$wp_customize->add_setting(
    	'abv_hdr_opening_content',
    	array(
			'default' => __('Mon to Sat: 10 Am - 6 Pm','cozipress'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	

	$wp_customize->add_control( 
		'abv_hdr_opening_content',
		array(
		    'label'   		=> __('Content','cozipress'),
		    'section'		=> 'above_header',
			'type' 			=> 'textarea',
			'priority'      => 3,
		)  
	);
	
	// Header Support 
	$wp_customize->add_setting(
		'abv_hdr_support_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_support_head',
		array(
			'type' => 'hidden',
			'label' => __('Support','cozipress'),
			'section' => 'above_header',
			'priority'  => 5,
		)
	);	
	
	$wp_customize->add_setting( 
		'hide_show_hdr_support' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_hdr_support', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 6,
		) 
	);	
	
	// icon // 
	$wp_customize->add_setting(
    	'hdr_support_icon',
    	array(
	        'default' => 'fa-phone',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Cozipress_Icon_Picker_Control($wp_customize, 
		'hdr_support_icon',
		array(
		    'label'   		=> __('Icon','cozipress'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 7,
			
		))  
	);	

	// Support Title // 
	$wp_customize->add_setting(
    	'hdr_support_ttl',
    	array(
	        'default'			=> __('Customer Support','cozipress'),
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_support_ttl',
		array(
		    'label'   		=> __('Text','cozipress'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 8,
		)  
	);	
	// Support Text // 
	$wp_customize->add_setting(
    	'hdr_support_text',
    	array(
	        'default'			=> __('<a href="tel:66 555 555 66">66 555 555 66</a>','cozipress'),
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_support_text',
		array(
		    'label'   		=> __('Text','cozipress'),
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
			'sanitize_callback' => 'cozipress_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_social_head',
		array(
			'type' => 'hidden',
			'label' => __('Social Icon','cozipress'),
			'section' => 'above_header',
			'priority'  => 9,
		)
	);

	$wp_customize->add_setting( 
		'hide_show_social_icon' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_social_icon', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
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
			 'default' => cozipress_get_social_icon_default()
		)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'social_icons', 
					array(
						'label'   => esc_html__('Social Icons','cozipress'),
						'add_field_label'                   => esc_html__( 'Add New Social', 'cozipress' ),
						'item_name'                         => esc_html__( 'Social', 'cozipress' ),
						'priority' => 11,
						'section' => 'above_header',
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	
			
		//Pro feature
		class Cozipress_social_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Sipri' == $theme->name){	
			?>
				<a class="customizer_CoziPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/sipri-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Sipri Pro','cozipress'); ?></a>
			
			<?php }elseif('Anexa' == $theme->name){ ?>
					
					<a class="customizer_CoziPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/anexa-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in Anexa Pro','cozipress'); ?></a>
			
			<?php }elseif('CoziWeb' == $theme->name){ ?>
					
					<a class="customizer_CoziPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/coziweb-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in CoziWeb Pro','cozipress'); ?></a>
					
			<?php }elseif('CoziPlus' == $theme->name){ ?>
					
					<a class="customizer_CoziPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/coziplus-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in CoziPlus Pro','cozipress'); ?></a>		
					
			<?php }elseif('CoziBee' == $theme->name){ ?>
					
					<a class="customizer_CoziPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/cozibee-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in CoziBee Pro','cozipress'); ?></a>			
					
			<?php }else{ ?>		
			
				<a class="customizer_CoziPress_social_upgrade_section up-to-pro" href="https://burgerthemes.com/cozipress-pro/" target="_blank" style="display: none;"><?php _e('More Icons Available in CoziPress Pro','cozipress'); ?></a>
				
			<?php
			} }
		}
		
		$wp_customize->add_setting( 'cozipress_social_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Cozipress_social_section_upgrade(
			$wp_customize,
			'cozipress_social_upgrade_to_pro',
				array(
					'section'				=> 'above_header',
					'priority' => 11,
				)
			)
		);		
	

	// Header Button 
	$wp_customize->add_setting(
		'abv_hdr_btn_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
		)
	);

	$wp_customize->add_control(
	'abv_hdr_btn_head',
		array(
			'type' => 'hidden',
			'label' => __('Button','cozipress'),
			'section' => 'above_header',
			'priority'  => 15,
		)
	);	
	
	$wp_customize->add_setting( 
		'hide_show_hdr_btn' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_hdr_btn', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'cozipress' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 16,
		) 
	);	
	
	// icon // 
	$wp_customize->add_setting(
    	'hdr_btn_icon',
    	array(
	        'default' => 'fa-arrow-right',
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control(new Cozipress_Icon_Picker_Control($wp_customize, 
		'hdr_btn_icon',
		array(
		    'label'   		=> __('Icon','cozipress'),
		    'section' 		=> 'above_header',
			'iconset' => 'fa',
			'priority'  => 17,
			
		))  
	);	

	// Button Label // 
	$wp_customize->add_setting(
    	'hdr_btn_lbl',
    	array(
	        'default'			=> __('Get A Quote','cozipress'),
			'sanitize_callback' => 'cozipress_sanitize_text',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_btn_lbl',
		array(
		    'label'   		=> __('Label','cozipress'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 18,
		)  
	);	
	
	// Button URL // 
	$wp_customize->add_setting(
    	'hdr_btn_url',
    	array(
	        'default'			=> '',
			'sanitize_callback' => 'cozipress_sanitize_url',
			'transport'         => $selective_refresh,
			'capability' => 'edit_theme_options',
		)
	);	

	$wp_customize->add_control( 
		'hdr_btn_url',
		array(
		    'label'   		=> __('Link','cozipress'),
		    'section' 		=> 'above_header',
			'type'		 =>	'text',
			'priority' => 19,
		)  
	);	

	$wp_customize->add_setting( 
		'hdr_btn_open_new_tab' , 
			array(
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_checkbox',
		) 
	);
	
	$wp_customize->add_control(
	'hdr_btn_open_new_tab', 
		array(
			'label'	      => esc_html__( 'Open in New Tab ?', 'cozipress' ),
			'section'     => 'above_header',
			'type'        => 'checkbox',
			'priority'  => 19,
		) 
	);	
}
add_action( 'customize_register', 'cozipress_above_header_settings' );


// Header selective refresh
function cozipress_above_header_partials( $wp_customize ){

	// hs_above_opening
	$wp_customize->selective_refresh->add_partial(
		'hs_above_opening', array(
			'selector' => '.main-header .widget-contact.first',
			'container_inclusive' => true,
			'render_callback' => 'above_header',
			'fallback_refresh' => true,
		)
	);
	
	// hide_show_hdr_support
	$wp_customize->selective_refresh->add_partial(
		'hide_show_hdr_support', array(
			'selector' => '.main-header .widget-contact.second',
			'container_inclusive' => true,
			'render_callback' => 'above_header',
			'fallback_refresh' => true,
		)
	);
	
	// hide_show_social_icon
	$wp_customize->selective_refresh->add_partial(
		'hide_show_social_icon', array(
			'selector' => '.main-header .widget_social_widget.third',
			'container_inclusive' => true,
			'render_callback' => 'above_header',
			'fallback_refresh' => true,
		)
	);
	
	// hide_show_hdr_btn
	$wp_customize->selective_refresh->add_partial(
		'hide_show_hdr_btn', array(
			'selector' => '.main-header .textwidget.btn',
			'container_inclusive' => true,
			'render_callback' => 'above_header',
			'fallback_refresh' => true,
		)
	);
	
	
	// abv_hdr_opening_ttl
	$wp_customize->selective_refresh->add_partial( 'abv_hdr_opening_ttl', array(
		'selector'            => '.main-header .widget-contact.first h6',
		'settings'            => 'abv_hdr_opening_ttl',
		'render_callback'  => 'cozipress_abv_hdr_opening_ttl_render_callback',
	) );
	
	// abv_hdr_opening_content
	$wp_customize->selective_refresh->add_partial( 'abv_hdr_opening_content', array(
		'selector'            => '.main-header .widget-contact.first p',
		'settings'            => 'abv_hdr_opening_content',
		'render_callback'  => 'cozipress_abv_hdr_opening_content_render_callback',
	) );
	
	// hdr_support_ttl
	$wp_customize->selective_refresh->add_partial( 'hdr_support_ttl', array(
		'selector'            => '.main-header .widget-contact.second h6',
		'settings'            => 'hdr_support_ttl',
		'render_callback'  => 'cozipress_hdr_support_ttl_render_callback',
	) );
	
	// hdr_support_text
	$wp_customize->selective_refresh->add_partial( 'hdr_support_text', array(
		'selector'            => '.main-header .widget-contact.second p',
		'settings'            => 'hdr_support_text',
		'render_callback'  => 'cozipress_hdr_support_text_render_callback',
	) );
	
	// hdr_btn_lbl
	$wp_customize->selective_refresh->add_partial( 'hdr_btn_lbl', array(
		'selector'            => '.main-header .textwidget.btn a',
		'settings'            => 'hdr_btn_lbl',
		'render_callback'  => 'cozipress_hdr_btn_lbl_render_callback',
	) );
	
	}

add_action( 'customize_register', 'cozipress_above_header_partials' );


// abv_hdr_opening_ttl
function cozipress_abv_hdr_opening_ttl_render_callback() {
	return get_theme_mod( 'abv_hdr_opening_ttl' );
}

// abv_hdr_opening_content
function cozipress_abv_hdr_opening_content_render_callback() {
	return get_theme_mod( 'abv_hdr_opening_content' );
}

// hdr_support_ttl
function cozipress_hdr_support_ttl_render_callback() {
	return get_theme_mod( 'hdr_support_ttl' );
}

// hdr_support_text
function cozipress_hdr_support_text_render_callback() {
	return get_theme_mod( 'hdr_support_text' );
}

// hdr_btn_lbl
function cozipress_hdr_btn_lbl_render_callback() {
	return get_theme_mod( 'hdr_btn_lbl' );
}