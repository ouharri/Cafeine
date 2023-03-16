<?php
/**
 * Active Callback
 * 
 * @package Blossom_Recipe
*/

/**
 * Active Callback for Banner Slider
*/
function blossom_recipe_banner_ac( $control ){
    $banner        = $control->manager->get_setting( 'ed_banner_section' )->value();
    $slider_type   = $control->manager->get_setting( 'slider_type' )->value();
    $control_id    = $control->id;
    
    if ( $control_id == 'header_image' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'header_video' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'external_header_video' && $banner == 'static_banner' ) return true;
    
    if ( $control_id == 'slider_type' && $banner == 'slider_banner' ) return true;              
    if ( $control_id == 'slider_cat' && $banner == 'slider_banner' && $slider_type == 'cat' ) return true;
    if ( $control_id == 'no_of_slides' && $banner == 'slider_banner' && ( $slider_type == 'latest_posts' || $slider_type == 'latest_recipes' || ( blossom_recipe_is_delicious_recipe_activated() && $slider_type == 'latest_dr_recipe' ) ) ) return true;

    if ( $control_id == 'banner_hr' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'banner_title' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_subtitle' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_button' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_button' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_url' && $banner == 'static_banner' ) return true;
        
    return false;
}

/**
 * Active Callback for post/page
*/
function blossom_recipe_post_page_ac( $control ){
    
    $ed_related    = $control->manager->get_setting( 'ed_related' )->value();
    $control_id    = $control->id;

    if ( $control_id == 'related_post_title' && $ed_related == true ) return true;
    
    return false;
}

/**
 * Active Callback for Header Newsletter
*/
function blossom_recipe_header_newsletter_callback( $control ){
    $ed_header_newsletter = $control->manager->get_setting( 'ed_header_newsletter' )->value();
    $control_id = $control->id;
    
    if( $control_id == 'header_newsletter_shortcode' && $ed_header_newsletter ) return true;
    
    return false;
}

/**
 * Active Callback for Breadcrumbs
*/
function blossom_recipe_breadcrumbs_callback( $control ){
    $breadcrumbs = $control->manager->get_setting( 'ed_breadcrumb' )->value();
    $control_id = $control->id;
    
    if( $control_id == 'home_text' && $breadcrumbs ) return true;
    
    return false;
}

/**
 * Active Callback for local fonts
*/
function blossom_recipe_ed_localgoogle_fonts(){
    $ed_localgoogle_fonts = get_theme_mod( 'ed_localgoogle_fonts' , false );

    if( $ed_localgoogle_fonts ) return true;
    
    return false; 
}