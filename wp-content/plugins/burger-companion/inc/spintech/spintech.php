<?php
/**
 * @package   Spintech
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-general.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-cta.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-design.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-info.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-service.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/features/spintech-typography.php';

if ( ! function_exists( 'burger_companion_spintech_frontpage_sections' ) ) :
	function burger_companion_spintech_frontpage_sections() {	
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/sections/section-slider.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/sections/section-info.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/sections/section-service.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/sections/section-design.php';
	    require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/sections/section-cta.php';
    }
	add_action( 'spintech_sections', 'burger_companion_spintech_frontpage_sections' );
endif;
