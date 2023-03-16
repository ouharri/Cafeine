<?php
if( ! function_exists( 'burger_com_decorme_dynamic_style' ) ):
    function burger_com_decorme_dynamic_style() {

		$output_css = '';
		
		/**
		 *  Slider Style
		 */
		 $slider5_opacity			= get_theme_mod('slider5_opacity','0.8');	
		 $slider5_opacity_color		= get_theme_mod('slider5_opacity_color','#040021');
		 list($br, $bg, $bb) = sscanf($slider5_opacity_color, "#%02x%02x%02x");		 
		 $output_css .=".home-slider-five .main-slider {
					    background: rgba($br, $bg, $bb, " .esc_attr($slider5_opacity). ");
				}\n";		
		 
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
		 *  Typography Body
		 */
		 $decorme_body_text_transform	 	 = get_theme_mod('decorme_body_text_transform','inherit');
		 $decorme_body_font_style	 		 = get_theme_mod('decorme_body_font_style','inherit');
		 $decorme_body_font_size	 		 = get_theme_mod('decorme_body_font_size','16');
		 $decorme_body_line_height		 = get_theme_mod('decorme_body_line_height','1.5');
		
		 $output_css .=" body{ 
			font-size: " .esc_attr($decorme_body_font_size). "px;
			line-height: " .esc_attr($decorme_body_line_height). ";
			text-transform: " .esc_attr($decorme_body_text_transform). ";
			font-style: " .esc_attr($decorme_body_font_style). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {	
			 $decorme_heading_text_transform 	= get_theme_mod('decorme_h' . $i . '_text_transform','inherit');
			 $decorme_heading_font_style	 	= get_theme_mod('decorme_h' . $i . '_font_style','inherit');
			 $decorme_heading_font_size	 		 = get_theme_mod('decorme_h' . $i . '_font_size');
			 $decorme_heading_line_height		 	 = get_theme_mod('decorme_h' . $i . '_line_height');
			 
			 $output_css .=" h" . $i . "{ 
				font-size: " .esc_attr($decorme_heading_font_size). "px;
				line-height: " .esc_attr($decorme_heading_line_height). ";
				text-transform: " .esc_attr($decorme_heading_text_transform). ";
				font-style: " .esc_attr($decorme_heading_font_style). ";
			}\n";
		 }
		 
		 
		
        wp_add_inline_style( 'decorme-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'burger_com_decorme_dynamic_style' );