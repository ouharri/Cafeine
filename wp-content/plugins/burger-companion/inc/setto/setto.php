<?php
/**
 * @package   Setto
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/sections/section-below-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-browse-cat.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-product.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-typography.php';

if ( ! function_exists( 'burger_companion_setto_frontpage_sections' ) ) :
	function burger_companion_setto_frontpage_sections() {	
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/sections/section-slider.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/sections/section-browse-cat.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/sections/section-product.php';
    }
	add_action( 'setto_sections', 'burger_companion_setto_frontpage_sections' );
endif;
