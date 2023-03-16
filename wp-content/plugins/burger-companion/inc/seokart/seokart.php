<?php
/**
 * @package   Seokart
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/dynamic_style.php';
// require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-general.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-above-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-above-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-features.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-team.php';
// require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-testimonial.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-typography.php';

if ( ! function_exists( 'burger_companion_seokart_frontpage_sections' ) ) :
	function burger_companion_seokart_frontpage_sections() {	
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-slider.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-features.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-team.php';
		// require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-testimonial.php';
    }
	add_action( 'seokart_sections', 'burger_companion_seokart_frontpage_sections' );
endif;
