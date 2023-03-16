<?php
/**
 * @package   Appetizer
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/features/appetizer-general.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/features/appetizer-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/features/appetizer-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/sections/section-footer-above.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/features/appetizer-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/features/appetizer-service.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/features/appetizer-product.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/features/appetizer-typography.php';

if ( ! function_exists( 'burger_companion_appetizer_frontpage_sections' ) ) :
	function burger_companion_appetizer_frontpage_sections() {	
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/sections/section-slider.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/sections/section-service.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/appetizer/sections/section-product.php';
    }
	add_action( 'appetizer_sections', 'burger_companion_appetizer_frontpage_sections' );
endif;
