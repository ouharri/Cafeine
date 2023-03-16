<?php
if( ! function_exists( 'burger_com_owlpress_dynamic_style' ) ):
    function burger_com_owlpress_dynamic_style() {

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
		 *  Slider Style
		 */
		 $slider_opacity			= get_theme_mod('slider_opacity','0.8');	
		 $slider_opacity_color		= get_theme_mod('slider_opacity_color','#000000');	
		 list($br, $bg, $bb) = sscanf($slider_opacity_color, "#%02x%02x%02x");
		 $output_css .=".main-slider {
					    background: rgba($br, $bg, $bb, " .esc_attr($slider_opacity). ");
				}\n";
				
		 /**
		 *  Breadcrumb Style
		 */
		
		$breadcrumb_min_height			= get_theme_mod('breadcrumb_min_height','147');	
		
		if($breadcrumb_min_height !== '') { 
				$output_css .=".breadcrumb-content {
					min-height: " .esc_attr($breadcrumb_min_height). "px;
				}\n";
			}
		
		$breadcrumb_bg_img			= get_theme_mod('breadcrumb_bg_img',get_template_directory_uri() .'/assets/images/bg/breadcrumbg.jpg'); 
		$breadcrumb_bg_img_opacity	= get_theme_mod('breadcrumb_bg_img_opacity','0.80');
		if($breadcrumb_bg_img !== '') { 
			$output_css .=".breadcrumb-area:after {
					background-color: #000000;
					opacity: " .esc_attr($breadcrumb_bg_img_opacity). ";
				}\n";
		}	
		
		/**
		 *  Typography Body
		 */
		 $owlpress_body_text_transform	 	 = get_theme_mod('owlpress_body_text_transform','inherit');
		 $owlpress_body_font_style	 		 = get_theme_mod('owlpress_body_font_style','inherit');
		 $owlpress_body_font_size	 		 = get_theme_mod('owlpress_body_font_size','16');
		 $owlpress_body_line_height		 = get_theme_mod('owlpress_body_line_height','1.5');
		
		 $output_css .=" body{ 
			font-size: " .esc_attr($owlpress_body_font_size). "px;
			line-height: " .esc_attr($owlpress_body_line_height). ";
			text-transform: " .esc_attr($owlpress_body_text_transform). ";
			font-style: " .esc_attr($owlpress_body_font_style). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {	
			 $owlpress_heading_text_transform 	= get_theme_mod('owlpress_h' . $i . '_text_transform','inherit');
			 $owlpress_heading_font_style	 	= get_theme_mod('owlpress_h' . $i . '_font_style','inherit');
			 $owlpress_heading_font_size	 		 = get_theme_mod('owlpress_h' . $i . '_font_size');
			 $owlpress_heading_line_height		 	 = get_theme_mod('owlpress_h' . $i . '_line_height');
			 
			 $output_css .=" h" . $i . "{ 
				font-size: " .esc_attr($owlpress_heading_font_size). "px;
				line-height: " .esc_attr($owlpress_heading_line_height). ";
				text-transform: " .esc_attr($owlpress_heading_text_transform). ";
				font-style: " .esc_attr($owlpress_heading_font_style). ";
			}\n";
		 }
		 
		 
		 /**
		 *  Features
		 */
		 $features_bg_img			= get_theme_mod('features_bg_img',esc_url(BURGER_COMPANION_PLUGIN_URL .'inc/owlpress/images/features/feature_bg.jpg'));	
		 $features_back_attach	= get_theme_mod('features_back_attach','fixed');
		 $output_css .=".feature-section.feature-home {
						background: url(" .esc_url($features_bg_img). ") no-repeat " .esc_attr($features_back_attach). " center center / cover rgb(0 0 0 / 0.70);
						background-blend-mode: multiply;
				}\n";
		 
		
        wp_add_inline_style( 'owlpress-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'burger_com_owlpress_dynamic_style' );