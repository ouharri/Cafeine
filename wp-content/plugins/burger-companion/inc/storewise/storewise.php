<?php
/**
 * @package   StoreWise
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/dynamic-style.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storewise/sections/section-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/features/storebiz-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/features/storebiz-testimonial.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/features/storebiz-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/shopmax/features/storebiz-info.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/features/storebiz-typography.php';


/**
 * Remove Customize Panel 
 */
function storewise_remove_parent_setting( $wp_customize ) {
	$wp_customize->remove_control('slider_content_right_head');
	$wp_customize->remove_control('hs_slider_content_right');
	$wp_customize->remove_control('slider_right_info');
}
add_action( 'customize_register', 'storewise_remove_parent_setting',99 );

if ( ! function_exists( 'burger_companion_storebiz_frontpage_sections' ) ) :
	function burger_companion_storebiz_frontpage_sections() {
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/shopmax/sections/section-info.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/sections/section-latest-product.php';
	    require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/sections/section-feature-product.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/sections/section-testimonial.php';
    }
	add_action( 'storebiz_sections', 'burger_companion_storebiz_frontpage_sections' );
endif;
