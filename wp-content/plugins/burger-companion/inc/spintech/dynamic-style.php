<?php
if( ! function_exists( 'burger_com_spintech_dynamic_style' ) ):
    function burger_com_spintech_dynamic_style() {

		$output_css = '';
		
		/**
		 * Logo Width 
		 */
		 $logo_width			= get_theme_mod('logo_width','140');		 
		if($logo_width !== '') { 
				$output_css .=".logo img, .mobile-logo img {
					max-width: " .esc_attr($logo_width). "px;
				}\n";
			}
			
		
		 /**
		 *  Breadcrumb Style
		 */
		
		$breadcrumb_min_height			= get_theme_mod('breadcrumb_min_height','236');	
		
		if($breadcrumb_min_height !== '') { 
				$output_css .=".breadcrumb-area {
					min-height: " .esc_attr($breadcrumb_min_height). "px;
				}\n";
			}
		
		$breadcrumb_bg_img			= get_theme_mod('breadcrumb_bg_img',esc_url(get_template_directory_uri() .'/assets/images/bg/breadcrumbg.jpg')); 		
		$breadcrumb_bg_img_opacity	= get_theme_mod('breadcrumb_bg_img_opacity','0.6');

		if($breadcrumb_bg_img !== '') { 
			$output_css .=".breadcrumb-area:after {
					opacity: " .esc_attr($breadcrumb_bg_img_opacity). ";
				}\n";
		}		
		
		/**
		 *  Typography Body
		 */
		 $spintech_body_text_transform	 	 = get_theme_mod('spintech_body_text_transform','inherit');
		 $spintech_body_font_style	 		 = get_theme_mod('spintech_body_font_style','inherit');
		 $spintech_body_font_size	 		 = get_theme_mod('spintech_body_font_size','16');
		 $spintech_body_line_height		 = get_theme_mod('spintech_body_line_height');
		
		 $output_css .=" body{ 
			font-size: " .esc_attr($spintech_body_font_size). "px;
			line-height: " .esc_attr($spintech_body_line_height). ";
			text-transform: " .esc_attr($spintech_body_text_transform). ";
			font-style: " .esc_attr($spintech_body_font_style). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {	
			 $spintech_heading_text_transform 	= get_theme_mod('spintech_h' . $i . '_text_transform','inherit');
			 $spintech_heading_font_style	 	= get_theme_mod('spintech_h' . $i . '_font_style','inherit');
			 $spintech_heading_font_size	 		 = get_theme_mod('spintech_h' . $i . '_font_size');
			 $spintech_heading_line_height		 	 = get_theme_mod('spintech_h' . $i . '_line_height');
			 
			 $output_css .=" h" . $i . "{ 
				font-size: " .esc_attr($spintech_heading_font_size). "px;
				line-height: " .esc_attr($spintech_heading_line_height). ";
				text-transform: " .esc_attr($spintech_heading_text_transform). ";
				font-style: " .esc_attr($spintech_heading_font_style). ";
			}\n";
		 }
		 
		 
		
        wp_add_inline_style( 'spintech-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'burger_com_spintech_dynamic_style' );