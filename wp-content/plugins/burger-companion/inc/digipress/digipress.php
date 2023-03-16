<?php
/**
 * @package   DigiPress
 */
 
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/extras.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/dynamic_style.php';
// require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-general.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/digipress/sections/section-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-above-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-above-header.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-above-footer.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-slider.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-features.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-team.php';
// require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-testimonial.php';
require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/features/seokart-typography.php';

/**
 * Remove Setting
 */
function digipress_remove_setting( $wp_customize ) {
	$wp_customize->remove_control('abv_hdr_info_head');
	$wp_customize->remove_control('hide_show_hdr_info');
	$wp_customize->remove_control('hdr_info_ttl');
	$wp_customize->remove_control('hdr_info_link');
}
add_action( 'customize_register', 'digipress_remove_setting',99 );


if ( ! function_exists( 'burger_companion_seokart_frontpage_sections' ) ) :
	function burger_companion_seokart_frontpage_sections() {	
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/digipress/sections/section-slider.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-features.php';
		require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-team.php';
		// require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/sections/section-testimonial.php';
    }
	add_action( 'seokart_sections', 'burger_companion_seokart_frontpage_sections' );
endif;
