<?php
function cozipress_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'cozipress' ),
			'panel' => 'cozipress_frontpage_sections',
			'priority' => 1,
		)
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'cozipress_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','cozipress'),
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
			  'default' => cozipress_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','cozipress'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'cozipress' ),
						'item_name'                         => esc_html__( 'Slider', 'cozipress' ),
						
						
						'customizer_repeater_icon_control' => false,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_slide_align' => true,
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
			
	//Pro feature
		class Cozipress_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'Sipri' == $theme->name){	
			?>
				<a class="customizer_CoziPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/sipri-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Sipri Pro','cozipress'); ?></a>
			
			<?php }elseif('Anexa' == $theme->name){ ?>
					
				<a class="customizer_CoziPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/anexa-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Anexa Pro','cozipress'); ?></a>
			
			<?php }elseif('CoziWeb' == $theme->name){ ?>
					
				<a class="customizer_CoziPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/coziweb-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in CoziWeb Pro','cozipress'); ?></a>
				
			<?php }elseif('CoziPlus' == $theme->name){ ?>
					
				<a class="customizer_CoziPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/coziplus-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in CoziPlus Pro','cozipress'); ?></a>
				
			<?php }elseif('CoziBee' == $theme->name){ ?>
					
				<a class="customizer_CoziPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/cozibee-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in CoziBee Pro','cozipress'); ?></a>	
				
			<?php }else{ ?>		
			
				<a class="customizer_CoziPress_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/cozipress-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in CoziPress Pro','cozipress'); ?></a>
				
			<?php
			} }
		}
		
	$wp_customize->add_setting( 'cozipress_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Cozipress_slider_section_upgrade(
			$wp_customize,
			'cozipress_slider_upgrade_to_pro',
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
				'default' => '0.75',
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'priority' => 6,
			)
		);
		$wp_customize->add_control( 
		new Burger_Customizer_Range_Control( $wp_customize, 'slider_opacity', 
			array(
				'label'      => __( 'Opacity', 'cozipress' ),
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
}

add_action( 'customize_register', 'cozipress_slider_setting' );