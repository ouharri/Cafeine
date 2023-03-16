<?php
/**
 * Blossom Fashion Theme Customizer
 *
 * @package Blossom_Fashion
 */

/**
 * Requiring customizer panels & sections
*/
$blossom_fashion_panels = array( 'info', 'site', 'color', 'appearance', 'general', 'footer' );

foreach( $blossom_fashion_panels as $p ){
    require get_template_directory() . '/inc/customizer/' . $p . '.php';
}

/**
 * Sanitization Functions
*/
require get_template_directory() . '/inc/customizer/sanitization-functions.php';

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blossom_fashion_customize_preview_js() {
	wp_enqueue_script( 'blossom-fashion-customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'blossom_fashion_customize_preview_js' );

function blossom_fashion_customize_script(){
	$array = array(
        'flushFonts'        => wp_create_nonce( 'blossom-fashion-local-fonts-flush' ),
    );
    wp_enqueue_style( 'blossom-fashion-customize', get_template_directory_uri() . '/inc/css/customize.css', array(), BLOSSOM_FASHION_THEME_VERSION );
    wp_enqueue_script( 'blossom-fashion-customize', get_template_directory_uri() . '/inc/js/customize.js', array( 'jquery' ), BLOSSOM_FASHION_THEME_VERSION, true );
    wp_localize_script( 'blossom-fashion-customize', 'blossom_fashion_cdata', $array );
    wp_localize_script( 'blossom-fashion-repeater', 'blossom_fashion_customize',
		array(
			'nonce' => wp_create_nonce( 'blossom_fashion_customize_nonce' )
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'blossom_fashion_customize_script' );

/**
 * Reset font folder
 *
 * @access public
 * @return void
 */
function blossom_fashion_ajax_delete_fonts_folder() {
	// Check request.
	if ( ! check_ajax_referer( 'blossom-fashion-local-fonts-flush', 'nonce', false ) ) {
		wp_send_json_error( 'invalid_nonce' );
	}
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( 'invalid_permissions' );
	}
	if ( class_exists( '\Blossom_Fashion_WebFont_Loader' ) ) {
		$font_loader = new \Blossom_Fashion_WebFont_Loader( '' );
		$removed = $font_loader->delete_fonts_folder();
		if ( ! $removed ) {
			wp_send_json_error( 'failed_to_flush' );
		}
		wp_send_json_success();
	}
	wp_send_json_error( 'no_font_loader' );
}
add_action( 'wp_ajax_blossom_fashion_flush_fonts_folder', 'blossom_fashion_ajax_delete_fonts_folder' );