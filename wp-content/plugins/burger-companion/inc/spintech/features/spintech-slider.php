<?php
function spintech_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	
	$wp_customize->add_section(
		'slider_setting', array(
			'title' => esc_html__( 'Slider Section', 'spintech' ),
			'panel' => 'spintech_frontpage_sections',
			'priority' => 1,
		)
	);
	
	// slider Contents
	$wp_customize->add_setting(
		'slider_content_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'spintech_sanitize_text',
			'priority' => 4,
		)
	);

	$wp_customize->add_control(
	'slider_content_head',
		array(
			'type' => 'hidden',
			'label' => __('Contents','spintech'),
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
			  'default' => spintech_get_slider_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','spintech'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'spintech' ),
						'item_name'                         => esc_html__( 'Slider', 'spintech' ),
						
						
						'customizer_repeater_icon_control' => false,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_slide_align' => true,
						'customizer_repeater_checkbox_control' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
			
		//Pro feature
		class Spintech_slider_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'ITpress' == $theme->name){
			?>
				<a class="customizer_Spintech_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/itpress-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in ITpress Pro','spintech'); ?></a>
				
			<?php }elseif ( 'Burgertech' == $theme->name){ ?>	
			
				<a class="customizer_Spintech_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/burgertech-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Burgertech Pro','spintech'); ?></a>
			
			<?php }elseif ( 'KitePress' == $theme->name){ ?>	
			
				<a class="customizer_Spintech_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/kitepress-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in KitePress Pro','spintech'); ?></a>
				
			<?php }elseif ( 'SpinSoft' == $theme->name){ ?>	
			
				<a class="customizer_Spintech_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/spinsoft-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in SpinSoft Pro','spintech'); ?></a>	
				
			<?php }else{ ?>	
			
				<a class="customizer_Spintech_slider_upgrade_section up-to-pro" href="https://burgerthemes.com/spintech-pro/" target="_blank" style="display: none;"><?php _e('More Slides Available in Spintech Pro','spintech'); ?></a>				
			
			<?php
				}
			}
		}
		
		$wp_customize->add_setting( 'spintech_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			'priority' => 5,
		));
		$wp_customize->add_control(
			new Spintech_slider_section_upgrade(
			$wp_customize,
			'spintech_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
				)
			)
		);
		
}

add_action( 'customize_register', 'spintech_slider_setting' );