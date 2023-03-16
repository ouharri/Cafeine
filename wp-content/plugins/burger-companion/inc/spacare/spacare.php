<?php
/**
 * @package   SpaCare
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/features/spabiz-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/features/spabiz-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/features/spabiz-info.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/features/spabiz-service.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/features/spabiz-funfact.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/features/spabiz-typography.php';

if ( ! function_exists( 'burger_companion_spabiz_frontpage_sections' ) ) :
	function burger_companion_spabiz_frontpage_sections() {	
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/sections/section-slider.php';
		 //require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/sections/section-info.php';
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/spacare/sections/section-service.php';
		 require BURGER_COMPANION_PLUGIN_DIR . 'inc/spabiz/sections/section-funfact.php';
    }
	add_action( 'spabiz_sections', 'burger_companion_spabiz_frontpage_sections' );
endif;