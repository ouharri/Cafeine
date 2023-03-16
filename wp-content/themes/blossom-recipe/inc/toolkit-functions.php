<?php
/**
 * Toolkit Filters
 *
 * @package Blossom_Recipe
 */

if( ! function_exists( 'blossom_recipe_default_cta_color' ) ) :
    function blossom_recipe_default_cta_color(){
        return '#9cbe9c';
    }
endif;
add_filter( 'bttk_cta_bg_color', 'blossom_recipe_default_cta_color' );

if( ! function_exists( 'blossom_recipe_default_team_member_image_size' ) ) :
    function blossom_recipe_default_team_member_image_size(){
        return 'full';
    }
endif;
add_filter( 'bttk_team_member_icon_img_size', 'blossom_recipe_default_team_member_image_size' );

if( ! function_exists( 'blossom_recipe_newsletter_bg_image_size' ) ) :
    function blossom_recipe_newsletter_bg_image_size(){
        return 'full';
    }
endif;
add_filter( 'bt_newsletter_img_size', 'blossom_recipe_newsletter_bg_image_size' );

if( ! function_exists( 'blossom_recipe_ad_image' ) ) :
    function blossom_recipe_ad_image(){
        return 'full';
    }
endif;
add_filter( 'bttk_ad_img_size', 'blossom_recipe_ad_image' );

if( ! function_exists( 'blossom_recipe_newsletter_bg_color' ) ) :
    function blossom_recipe_newsletter_bg_color(){
        return '#56cc9d';
    }
endif;
add_filter( 'bt_newsletter_bg_color_setting', 'blossom_recipe_newsletter_bg_color' );

if( ! function_exists( 'blossom_recipe_author_image' ) ) :
   function blossom_recipe_author_image(){
       return 'blossom-recipe-blog';
   }
endif;
add_filter( 'author_bio_img_size', 'blossom_recipe_author_image' );

if( ! function_exists( 'blossom_recipe_defer_js_files' ) ) :
    function blossom_recipe_defer_js_files(){
        $defer_js = get_theme_mod( 'ed_defer', false );

        return ( $defer_js ) ? false : true;

    }
endif;
add_filter( 'bttk_public_assets_enqueue', 'blossom_recipe_defer_js_files' );

if( ! function_exists( 'blossom_recipe_archives_image_size' ) ) :
    function blossom_recipe_archives_image_size(){
        $image_size = 'blossom-recipe-blog';
        return $image_size;
    }
endif;
add_filter( 'brm_archive_img_size', 'blossom_recipe_archives_image_size' );

if( ! function_exists( 'blossom_recipe_single_image_size' ) ) :
    function blossom_recipe_single_image_size(){
        $sidebar     = blossom_recipe_sidebar();
        $image_size = ( $sidebar ) ? 'blossom-recipe-blog' : 'blossom-recipe-blog-one';
        return $image_size;
    }
endif;
add_filter( 'br_feat_img_size', 'blossom_recipe_single_image_size' );