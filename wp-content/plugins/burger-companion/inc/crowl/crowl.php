<?php
/**
 * @package   Crowl
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/features/owlpress-general.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/crowl/sections/section-below-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/sections/section-above-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/features/owlpress-below-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/features/owlpress-above-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/features/owlpress-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/features/owlpress-service.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/features/owlpress-features.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/crowl/features/owlpress-team.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/features/owlpress-typography.php';

if ( ! function_exists( 'burger_companion_owlpress_frontpage_sections' ) ) :
	function burger_companion_owlpress_frontpage_sections() {	
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/sections/section-slider.php';
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/sections/section-service.php';
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/owlpress/sections/section-features.php';
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/crowl/sections/section-team.php';
    }
	add_action( 'owlpress_sections', 'burger_companion_owlpress_frontpage_sections' );
endif;
