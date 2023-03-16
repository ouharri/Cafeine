<?php
/**
 * @package   Setto
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/sections/section-below-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/features/setto-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/features/setto-browse.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/features/setto-product.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto/features/setto-typography.php';

if ( ! function_exists( 'burger_companion_setto_frontpage_sections' ) ) :
	function burger_companion_setto_frontpage_sections() {	
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/sections/section-slider.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/sections/section-browse.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/setto-lifestyle/sections/section-product.php';
    }
	add_action( 'setto_sections', 'burger_companion_setto_frontpage_sections' );
endif;
