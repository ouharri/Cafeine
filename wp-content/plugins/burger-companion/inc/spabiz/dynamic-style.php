<?php
if( ! function_exists( 'burger_spabiz_dynamic_style' ) ):
    function burger_spabiz_dynamic_style() {

		$output_css = '';
		
		/**
		 *  Slider Style
		 */
		 $slider_opacity_color		= get_theme_mod('slider_opacity_color','#e3f5f1');	
		 $output_css .=".slider-one .home-slider {
					       background: " .esc_attr($slider_opacity_color). ";
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
		 *  Funfact
		 */
		 $funfact_bg_img_opacity	= get_theme_mod('funfact_bg_img_opacity','0.6');	
		 $funfact_overlay_color		= get_theme_mod('funfact_overlay_color','#00a17d');	
		 list($br, $bg, $bb) = sscanf($funfact_overlay_color, "#%02x%02x%02x");
		 $output_css .=".funfact-one:before {
						background: rgba(" .esc_attr($br). ", " .esc_attr($bg). ", " .esc_attr($bb). ", " .esc_attr($funfact_bg_img_opacity). ");
				}\n";
				
		/**
		 *  Typography Body
		 */
		 $spabiz_body_text_transform	 	 = get_theme_mod('spabiz_body_text_transform','inherit');
		 $spabiz_body_font_style	 		 = get_theme_mod('spabiz_body_font_style','inherit');
		 $spabiz_body_font_size	 		 = get_theme_mod('spabiz_body_font_size','16');
		 $spabiz_body_line_height		 = get_theme_mod('spabiz_body_line_height','1.5');
		
		 $output_css .=" body{ 
			font-size: " .esc_attr($spabiz_body_font_size). "px;
			line-height: " .esc_attr($spabiz_body_line_height). ";
			text-transform: " .esc_attr($spabiz_body_text_transform). ";
			font-style: " .esc_attr($spabiz_body_font_style). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {	
			 $spabiz_heading_text_transform 	= get_theme_mod('spabiz_h' . $i . '_text_transform','inherit');
			 $spabiz_heading_font_style	 	= get_theme_mod('spabiz_h' . $i . '_font_style','inherit');
			 $spabiz_heading_font_size	 		 = get_theme_mod('spabiz_h' . $i . '_font_size');
			 $spabiz_heading_line_height		 	 = get_theme_mod('spabiz_h' . $i . '_line_height');
			 
			 $output_css .=" h" . $i . "{ 
				font-size: " .esc_attr($spabiz_heading_font_size). "px;
				line-height: " .esc_attr($spabiz_heading_line_height). ";
				text-transform: " .esc_attr($spabiz_heading_text_transform). ";
				font-style: " .esc_attr($spabiz_heading_font_style). ";
			}\n";
		 }		
				
        wp_add_inline_style( 'spabiz-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'burger_spabiz_dynamic_style' );