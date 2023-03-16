<?php
/**
 * @package   DecorMe
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/features/decorme-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/features/decorme-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/features/decorme-info.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/features/decorme-service.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/features/decorme-cta.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/features/decorme-typography.php';

if ( ! function_exists( 'burger_companion_decorme_frontpage_sections' ) ) :
	function burger_companion_decorme_frontpage_sections() {	
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/sections/section-slider.php';
		  require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/sections/section-info.php';
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/sections/section-service.php';
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/decorme/sections/section-cta.php';
    }
	add_action( 'decorme_sections', 'burger_companion_decorme_frontpage_sections' );
endif;
