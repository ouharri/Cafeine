<?php
/**
 * Blossom Recipe Theme Customizer
 *
 * @package Blossom_Recipe
 */

/**
 * Requiring customizer panels & sections
*/
$blossom_recipe_panels = array( 'info', 'site', 'layout', 'general', 'footer' );

foreach( $blossom_recipe_panels as $p ){
    require get_template_directory() . '/inc/customizer/' . $p . '.php';
}

/**
 * Sanitization Functions
*/
require get_template_directory() . '/inc/customizer/sanitization-functions.php';

/**
 * Active Callbacks
*/
require get_template_directory() . '/inc/customizer/active-callback.php';

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blossom_recipe_customize_preview_js() {
	wp_enqueue_script( 'blossom-recipe-customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), BLOSSOM_RECIPE_THEME_VERSION, true );
}
add_action( 'customize_preview_init', 'blossom_recipe_customize_preview_js' );

function blossom_recipe_customize_script(){    
	$array = array(
        'flushFonts'        => wp_create_nonce( 'blossom-recipe-local-fonts-flush' ),
    );
    wp_enqueue_style( 'blossom-recipe-customize', get_template_directory_uri() . '/inc/css/customize.css', array(), BLOSSOM_RECIPE_THEME_VERSION );
    wp_enqueue_script( 'blossom-recipe-customize', get_template_directory_uri() . '/inc/js/customize.js', array( 'jquery', 'customize-controls' ), BLOSSOM_RECIPE_THEME_VERSION, true );

    wp_localize_script( 'blossom-recipe-customize', 'blossom_recipe_cdata', $array );

    wp_localize_script( 'blossom-recipe-repeater', 'blossom_recipe_customize',
		array(
			'nonce' => wp_create_nonce( 'blossom_recipe_customize_nonce' )
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'blossom_recipe_customize_script' );

/*
 * Notifications in customizer
 */
require get_template_directory() . '/inc/customizer-plugin-recommend/plugin-install/class-plugin-install-helper.php';

require get_template_directory() . '/inc/customizer-plugin-recommend/plugin-install/class-plugin-recommend.php';

/**
 * Reset font folder
 *
 * @access public
 * @return void
 */
function blossom_recipe_ajax_delete_fonts_folder() {
	// Check request.
	if ( ! check_ajax_referer( 'blossom-recipe-local-fonts-flush', 'nonce', false ) ) {
		wp_send_json_error( 'invalid_nonce' );
	}
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( 'invalid_permissions' );
	}
	if ( class_exists( '\Blossom_Recipe_WebFont_Loader' ) ) {
		$font_loader = new \Blossom_Recipe_WebFont_Loader( '' );
		$removed = $font_loader->delete_fonts_folder();
		if ( ! $removed ) {
			wp_send_json_error( 'failed_to_flush' );
		}
		wp_send_json_success();
	}
	wp_send_json_error( 'no_font_loader' );
}
add_action( 'wp_ajax_blossom_recipe_flush_fonts_folder', 'blossom_recipe_ajax_delete_fonts_folder' );