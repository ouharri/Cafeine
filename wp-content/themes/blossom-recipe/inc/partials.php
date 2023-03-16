<?php
/**
 * Blossom Recipe Customizer Partials
 *
 * @package Blossom_Recipe
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function blossom_recipe_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function blossom_recipe_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if( ! function_exists( 'blossom_recipe_get_read_more' ) ) :
/**
 * Display blog readmore button
*/
function blossom_recipe_get_read_more(){
    return esc_html( get_theme_mod( 'read_more_text', __( 'Read More', 'blossom-recipe' ) ) );    
}
endif;

if( ! function_exists( 'blossom_recipe_get_banner_title' ) ) :
/**
 * Display Banner Title
*/
function blossom_recipe_get_banner_title(){
    return esc_html( get_theme_mod( 'banner_title', __( 'Relaxing Is Never Easy On Your Own', 'blossom-recipe' ) ) );
}
endif;

if( ! function_exists( 'blossom_recipe_get_banner_sub_title' ) ) :
/**
 * Display Banner SubTitle
*/
function blossom_recipe_get_banner_sub_title(){
    return wpautop( wp_kses_post( get_theme_mod( 'banner_subtitle', __( 'Come and discover your oasis. It has never been easier to take a break from stress and the harmful factors that surround you every day!', 'blossom-recipe' ) ) ) );
}
endif;

if( ! function_exists( 'blossom_recipe_get_banner_button' ) ) :
/**
 * Display Banner Button Label
*/
function blossom_recipe_get_banner_button(){
    return esc_html( get_theme_mod( 'banner_button', __( 'Read More', 'blossom-recipe' ) ) );
}
endif;

if( ! function_exists( 'blossom_recipe_get_author_title' ) ) :
/**
 * Display about author title
*/
function blossom_recipe_get_author_title(){
    return esc_html( get_theme_mod( 'author_title', __( 'About Author', 'blossom-recipe' ) ) );
}
endif;

if( ! function_exists( 'blossom_recipe_get_related_title' ) ) :
/**
 * Display single related title
*/
function blossom_recipe_get_related_title(){
    return esc_html( get_theme_mod( 'related_post_title', __( 'You may also like...', 'blossom-recipe' ) ) );
}
endif;

if( ! function_exists( 'blossom_recipe_get_footer_copyright' ) ) :
/**
 * Footer Copyright
*/
function blossom_recipe_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );
    echo '<span class="copyright-text">';
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'blossom-recipe' );
        echo date_i18n( esc_html__( 'Y', 'blossom-recipe' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
        esc_html_e( 'All Rights Reserved. ', 'blossom-recipe' );
    }
    echo '</span>'; 
}
endif;