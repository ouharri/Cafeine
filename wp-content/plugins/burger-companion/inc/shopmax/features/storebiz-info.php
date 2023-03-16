<?php 
function storebiz_info_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Info
	=========================================*/
		$wp_customize->add_section(
			'info_setting', array(
				'title' => esc_html__( 'Info Section', 'storebiz' ),
				'panel' => 'storebiz_frontpage_sections',
				'priority' => 1,
			)
		);
	
	/**
	 * Customizer Repeater for add Info
	 */
	
		$wp_customize->add_setting( 'info_content', 
			array(
			 'sanitize_callback' => 'burger_companion_repeater_sanitize',
			 'priority' => 5,
			  'default' => storebiz_get_info_default()
			)
		);
		
		$wp_customize->add_control( 
			new Burger_Companion_Repeater( $wp_customize, 
				'info_content', 
					array(
						'label'   => esc_html__('Informations','storebiz'),
						'section' => 'info_setting',
						'add_field_label'                   => esc_html__( 'Add New Informations', 'storebiz' ),
						'item_name'                         => esc_html__( 'Informations', 'storebiz' ),
						
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_slide_align' => true,
					) 
				) 
			);
			
			
		//Pro feature
		class Storebiz_infos_section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'ShopMax' == $theme->name){
			?>
				<a class="customizer_storebiz_info_upgrade_section up-to-pro"  href="https://burgerthemes.com/shopmax-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in ShopMax Pro','storebiz'); ?></a>
				
			<?php }elseif('StoreWise' == $theme->name){ ?>	
			
				<a class="customizer_storebiz_info_upgrade_section up-to-pro"  href="https://burgerthemes.com/storewise-pro/" target="_blank" style="display: none;"><?php _e('More Info Available in StoreWise Pro','storebiz'); ?></a>
				
			<?php
				}
			}
		}
		
		$wp_customize->add_setting( 'storebiz_infos_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
			 'priority' => 24,
		));
		$wp_customize->add_control(
			new Storebiz_infos_section_upgrade(
			$wp_customize,
			'storebiz_infos_upgrade_to_pro',
				array(
					'section'				=> 'info_setting',
				)
			)
		);	
}
add_action( 'customize_register', 'storebiz_info_setting' );