<?php
/**
 * Toolkit Filters
 *
 * @package Blossom_Fashion
 */
 
if( ! function_exists( 'blossom_fashion_portfolio_single_image' )  ) :
    function blossom_fashion_portfolio_single_image(){
        return 'blossom-fashion-fullwidth';
    }
endif;
add_filter( 'bttk_single_portfolio_image', 'blossom_fashion_portfolio_single_image' );

if( ! function_exists( 'blossom_fashion_portfolio_related_image' ) ) :
    function blossom_fashion_portfolio_related_image(){
        return 'blossom-fashion-blog-home';
    }
endif;
add_filter( 'bttk_related_portfolio_image', 'blossom_fashion_portfolio_related_image' );

if( ! function_exists( 'blossom_fashion_ad_image' ) ) :
    function blossom_fashion_ad_image(){
        return 'full';
    }
endif;
add_filter( 'bttk_ad_img_size', 'blossom_fashion_ad_image' );