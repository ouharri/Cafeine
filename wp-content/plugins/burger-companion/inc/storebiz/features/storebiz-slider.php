<?php
function storebiz_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'storebiz' ),
			'panel' => 'storebiz_frontpage_sections',
			'priority' => 1,
		)
	);
	
	// Left Contents
	$wp_customize->add_setting(
		'slider_content_left_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_left_head',
		array(
			'type' => 'hidden',
			'label' => __('Left Content','storebiz'),
			'section' => 'slider_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_slider_content_left' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_checkbox',
			'priority' => 4,
		) 
	);
	
	$wp_customize->add_control(
	'hs_slider_content_left', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'storebiz' ),
			'section'     => 'slider_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Slider','storebiz'),
			'section' => 'slider_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add slides
	 */
	
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 5,
			  'default' => storebiz_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','storebiz'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'storebiz' ),
						'item_name'                         => esc_html__( 'Slider', 'storebiz' ),
						
						
						'customizer_repeater_icon_control' => false,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_slide_align' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
	
	    //Pro feature
		class Storebiz_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
				if ( 'ShopMax' == $theme->name){
			?>
				<a class="customizer_StoreBiz_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/shopmax-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in ShopMax Pro','storebiz'); ?></a>
			
			<?php }elseif('StoreWise' == $theme->name){ ?>		
				
				<a class="customizer_StoreBiz_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/storewise-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in StoreWise Pro','storebiz'); ?></a>
				
			<?php }else{ ?>	
				
				<a class="customizer_StoreBiz_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/storebiz-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in StoreBiz Pro','storebiz'); ?></a>
			<?php
				}
			}
		}
		
	$wp_customize->add_setting( 'storebiz_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Storebiz_slider_section_upgrade(
			$wp_customize,
			'storebiz_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);
		
	// slider opacity
	if ( class_exists( 'Burger_Customizer_Range_Control' ) ) {
		$wp_customize->add_setting(
			'slider_opacity',
			array(
				'default' => '0.35',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'priority' => 6,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'slider_opacity', 
			array(
				'label'      => __( 'Opacity', 'storebiz' ),
				'section'  => 'slider_setting',
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 0.9,
					'step'   => 0.1,
					//'suffix' => 'px', //optional suffix
				),
			) ) 
		);
	}
	
	// Right Contents
	$wp_customize->add_setting(
		'slider_content_right_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 21,
		)
	);

	$wp_customize->add_control(
	'slider_content_right_head',
		array(
			'type' => 'hidden',
			'label' => __('Right Content','storebiz'),
			'section' => 'slider_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_slider_content_right' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_checkbox',
			'priority' => 22,
		) 
	);
	
	$wp_customize->add_control(
	'hs_slider_content_right', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'storebiz' ),
			'section'     => 'slider_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	/**
	 * Customizer Repeater for add Info
	 */
	
		$wp_customize->add_setting( 'slider_right_info', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 23,
			  'default' => storebiz_get_slider_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider_right_info', 
					array(
						'label'   => esc_html__('Information','storebiz'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Information', 'storebiz' ),
						'item_name'                         => esc_html__( 'Information', 'storebiz' ),
						
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_image_control' => true,
					) 
				) 
			);
			
	
	//Pro feature
		class Storebiz_info_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			$theme = wp_get_theme(); // gets the current theme
				if ( 'ShopMax' == $theme->name){
			?>
				<a class="customizer_storebiz_info_upgrade_section up-to-pro"  href="https://burgerthemes.com/shopmax-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in ShopMax Pro','storebiz'); ?></a>
				
				<?php }else{ ?>	
				
				<a class="customizer_storebiz_info_upgrade_section up-to-pro"  href="https://burgerthemes.com/storebiz-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in StoreBiz Pro','storebiz'); ?></a>
				
			<?php
				}
			}
		}
		
		$wp_customize->add_setting( 'storebiz_info_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			 'priority' => 24,
		));
		$wp_customize->add_control(
			new Storebiz_info_section_upgrade(
			$wp_customize,
			'storebiz_info_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);
		
	/*=========================================
	Latest Products Section
	=========================================*/
	$wp_customize->add_section(
		'latest_product_setting', array(
			'title' => esc_html__( 'Latest Product Section', 'storebiz' ),
			'panel' => 'storebiz_frontpage_sections',
			'priority' => 2,
		)
	);
	
	// Left Contents
	$wp_customize->add_setting(
		'latest_product_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'latest_product_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Latest Product','storebiz'),
			'section' => 'latest_product_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_latest_product' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_checkbox',
			'priority' => 4,
		) 
	);
	
	$wp_customize->add_control(
	'hs_latest_product', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'storebiz' ),
			'section'     => 'latest_product_setting',
			'type'        => 'checkbox',
		) 
	);	
	
	
	// Content Head// 
	
	$wp_customize->add_setting(
		'latest_product_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'latest_product_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','storebiz'),
			'section' => 'latest_product_setting',
		)
	);
	
	// Title // 
	$wp_customize->add_setting(
    	'latest_product_title',
    	array(
	        'default'			=> __('Latest Product','storebiz'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'latest_product_title',
		array(
		    'label'   => __('Title','storebiz'),
		    'section' => 'latest_product_setting',
			'type'           => 'text',
		)  
	);	


	/*=========================================
	Featured Products Section
	=========================================*/
	$wp_customize->add_section(
		'featured_product_setting', array(
			'title' => esc_html__( 'Featured Product Section', 'storebiz' ),
			'panel' => 'storebiz_frontpage_sections',
			'priority' => 2,
		)
	);
	
	// Settings Head
	$wp_customize->add_setting(
		'featured_product_setting_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'featured_product_setting_head',
		array(
			'type' => 'hidden',
			'label' => __('Settings','storebiz'),
			'section' => 'featured_product_setting',
		)
	);
	
	// hide/show
	$wp_customize->add_setting( 
		'hs_featured_product' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_checkbox',
			'priority' => 4,
		) 
	);
	
	$wp_customize->add_control(
	'hs_featured_product', 
		array(
			'label'	      => esc_html__( 'Hide/Show', 'storebiz' ),
			'section'     => 'featured_product_setting',
			'type'        => 'checkbox',
		) 
	);	


	// Content Head// 
	
	$wp_customize->add_setting(
		'featured_product_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_text',
			'priority' => 5,
		)
	);

	$wp_customize->add_control(
	'featured_product_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','storebiz'),
			'section' => 'featured_product_setting',
		)
	);
	
	// Title // 
	$wp_customize->add_setting(
    	'featured_product_title',
    	array(
	        'default'			=> __('Featured Product','storebiz'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'storebiz_sanitize_html',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'featured_product_title',
		array(
		    'label'   => __('Title','storebiz'),
		    'section' => 'featured_product_setting',
			'type'           => 'text',
		)  
	);	
}

add_action( 'customize_register', 'storebiz_slider_setting' );


// selective refresh
function storebiz_home_section_partials( $wp_customize ){
	
	// latest_product_title
	$wp_customize->selective_refresh->add_partial( 'latest_product_title', array(
		'selector'            => '.storebiz-recent-products .heading-default h4',
		'settings'            => 'latest_product_title',
		'render_callback'  => 'storebiz_latest_product_title_render_callback',
	) );	
	
	// featured_product_title
	$wp_customize->selective_refresh->add_partial( 'featured_product_title', array(
		'selector'            => '.storebiz-featured-products .heading-default h4',
		'settings'            => 'featured_product_title',
		'render_callback'  => 'storebiz_featured_product_title_render_callback',
	) );	
	
	}

add_action( 'customize_register', 'storebiz_home_section_partials' );


// latest_product_title
function storebiz_latest_product_title_render_callback() {
	return get_theme_mod( 'latest_product_title' );
}

// featured_product_title
function storebiz_featured_product_title_render_callback() {
	return get_theme_mod( 'featured_product_title' );
}