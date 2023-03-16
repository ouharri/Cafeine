<?php
if( ! function_exists( 'burger_seokart_dynamic_style' ) ):
    function burger_seokart_dynamic_style() {

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
		
		$theme = wp_get_theme(); // gets the current theme
		if( 'DigiPress' == $theme->name){
			$seokart_hs_breadcrumb					= get_theme_mod('hs_breadcrumb','1');
			if($seokart_hs_breadcrumb == '') {
			$output_css .="@media (min-width: 992px){
			.digipress-theme .main-header:not(.header-fixed) .navbar .navbar-menu ul li:not(.active):not(:hover):not(:focus) a {
					color: #020d2d !important;
			}}.digipress-theme .main-header:not(.header-fixed) .navbar .navbar-menu ul li:not(.toggle-button) a svg {
				fill: #020d2d !important;
			}\n";
			}	
		}
		/**
		 *  team_bg_img
		 */
		 $team_bg_img	= get_theme_mod('team_bg_img',esc_url(BURGER_COMPANION_PLUGIN_URL .'/inc/seokart/images/team-bg.jpg'));	
		 
		 if(!empty($team_bg_img)):
			 $output_css .=".team-area {
							background: url(". esc_url($team_bg_img) .") no-repeat fixed;
					}\n";	
		else:
		
			$output_css .=".team-area {
							background: var(--color-secondary) no-repeat fixed;
					}\n";	
		endif;			
		
		
		/**
		 *  Typography Body
		 */
		 $seokart_body_text_transform	 	 = get_theme_mod('seokart_body_text_transform','inherit');
		 $seokart_body_font_style	 		 = get_theme_mod('seokart_body_font_style','inherit');
		 $seokart_body_font_size	 		 = get_theme_mod('seokart_body_font_size','16');
		 $seokart_body_line_height		 	 = get_theme_mod('seokart_body_line_height','1.5');
		
		 $output_css .=" body,body p{ 
			font-size: " .esc_attr($seokart_body_font_size). "px;
			line-height: " .esc_attr($seokart_body_line_height). ";
			text-transform: " .esc_attr($seokart_body_text_transform). ";
			font-style: " .esc_attr($seokart_body_font_style). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {	
			 $seokart_heading_text_transform 	= get_theme_mod('seokart_h' . $i . '_text_transform','inherit');
			 $seokart_heading_font_style	 	= get_theme_mod('seokart_h' . $i . '_font_style','inherit');
			 $seokart_heading_font_size	 		 = get_theme_mod('seokart_h' . $i . '_font_size');
			 $seokart_heading_line_height		 	 = get_theme_mod('seokart_h' . $i . '_line_height');
			 
			 $output_css .=" h" . $i . "{ 
				font-size: " .esc_attr($seokart_heading_font_size). "px;
				line-height: " .esc_attr($seokart_heading_line_height). ";
				text-transform: " .esc_attr($seokart_heading_text_transform). ";
				font-style: " .esc_attr($seokart_heading_font_style). ";
			}\n";
		 }
		 
		 
        wp_add_inline_style( 'seokart-style', $output_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'burger_seokart_dynamic_style' );