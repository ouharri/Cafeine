<?php
/**
 * Customizer Partials
 *
 * @package Blossom_Fashion
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function blossom_fashion_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function blossom_fashion_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if( ! function_exists( 'blossom_fashion_get_shop_title' ) ) :
/**
 * Display blog readmore button
*/
function blossom_fashion_get_shop_title(){
    return esc_html( get_theme_mod( 'shop_section_title', __( 'Welcome to our Shop!', 'blossom-fashion' ) ) );    
}
endif;

if( ! function_exists( 'blossom_fashion_get_shop_content' ) ) :
/**
 * Display blog readmore button
*/
function blossom_fashion_get_shop_content(){
    return wp_kses_post( get_theme_mod( 'shop_section_content', __( 'This option can be change from Customize > General Settings > Shop settings.', 'blossom-fashion' ) ) );    
}
endif;

if( ! function_exists( 'blossom_fashion_get_read_more' ) ) :
/**
 * Display blog readmore button
*/
function blossom_fashion_get_read_more(){
    return esc_html( get_theme_mod( 'read_more_text', __( 'Continue Reading', 'blossom-fashion' ) ) );    
}
endif;

if( ! function_exists( 'blossom_fashion_get_author_title' ) ) :
/**
 * Display blog readmore button
*/
function blossom_fashion_get_author_title(){
    return esc_html( get_theme_mod( 'author_title', __( 'About Author', 'blossom-fashion' ) ) );
}
endif;

if( ! function_exists( 'blossom_fashion_get_related_title' ) ) :
/**
 * Display blog readmore button
*/
function blossom_fashion_get_related_title(){
    return esc_html( get_theme_mod( 'related_post_title', __( 'You may also like...', 'blossom-fashion' ) ) );
}
endif;

if( ! function_exists( 'blossom_fashion_get_popular_title' ) ) :
/**
 * Display blog readmore button
*/
function blossom_fashion_get_popular_title(){
    return esc_html( get_theme_mod( 'popular_post_title', __( 'Popular Posts', 'blossom-fashion' ) ) );
}
endif;

if( ! function_exists( 'blossom_fashion_get_bottom_shop_title' ) ) :
/**
 * Display blog readmore button
*/
function blossom_fashion_get_bottom_shop_title(){
    return esc_html( get_theme_mod( 'bottom_shop_section_title', __( 'Shop My Closet', 'blossom-fashion' ) ) );    
}
endif;

if( ! function_exists( 'blossom_fashion_get_footer_copyright' ) ) :
/**
 * Footer Copyright
*/
function blossom_fashion_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );
    echo '<span class="copyright">';
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'blossom-fashion' );
        echo date_i18n( esc_html__( 'Y', 'blossom-fashion' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
        esc_html_e( 'All Rights Reserved. ', 'blossom-fashion' );
    }
    echo '</span>'; 
}
endif;