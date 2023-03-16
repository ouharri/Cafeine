<?php
if( ! function_exists( 'burger_com_setto_dynamic_style' ) ):
    function burger_com_setto_dynamic_style() {

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
		$breadcrumb_min_height			= get_theme_mod('breadcrumb_min_height','50');	
		
		if($breadcrumb_min_height !== '') { 
				$output_css .=".breadcrumb-area {
					min-height: " .esc_attr($breadcrumb_min_height). "px;
				}\n";
			}
		
		$browse_cat_hs 		= get_theme_mod('browse_cat_hs','1');
		if($browse_cat_hs==''){
			$output_css .=".product-section1 .product-tab-ptb {
					    padding-top: 100px;
				}\n";
		}
		
			
		/**
		 *  Typography Body
		 */
		 $setto_body_text_transform	 	 = get_theme_mod('setto_body_text_transform','inherit');
		 $setto_body_font_style	 		 = get_theme_mod('setto_body_font_style','inherit');
		 $setto_body_font_size	 		 = get_theme_mod('setto_body_font_size','16');
		 $setto_body_line_height		 = get_theme_mod('setto_body_line_height','1.5');
		
		 $output_css .=" body{ 
			font-size: " .esc_attr($setto_body_font_size). "px;
			line-height: " .esc_attr($setto_body_line_height). ";
			text-transform: " .esc_attr($setto_body_text_transform). ";
			font-style: " .esc_attr($setto_body_font_style). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {	
			 $setto_heading_text_transform 	= get_theme_mod('setto_h' . $i . '_text_transform','inherit');
			 $setto_heading_font_style	 	= get_theme_mod('setto_h' . $i . '_font_style','inherit');
			 $setto_heading_font_size	 		 = get_theme_mod('setto_h' . $i . '_font_size');
			 $setto_heading_line_height		 	 = get_theme_mod('setto_h' . $i . '_line_height');
			 
			 $output_css .=" h" . $i . "{ 
				font-size: " .esc_attr($setto_heading_font_size). "px;
				line-height: " .esc_attr($setto_heading_line_height). ";
				text-transform: " .esc_attr($setto_heading_text_transform). ";
				font-style: " .esc_attr($setto_heading_font_style). ";
			}\n";
		 }
		 
		 
		
        wp_add_inline_style( 'setto-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'burger_com_setto_dynamic_style' );