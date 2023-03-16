<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blossom_Fashion
 */
    
    /**
     * After Content
     * 
     * @hooked blossom_fashion_bottom_shop_section - 10
     * @hooked blossom_fashion_instagram_section   - 15 
     * @hooked blossom_fashion_content_end         - 20
    */
    do_action( 'blossom_fashion_before_footer' );
    
    /**
     * Footer
     * 
     * @hooked blossom_fashion_footer_start  - 20
     * @hooked blossom_fashion_footer_top    - 30
     * @hooked blossom_fashion_footer_bottom - 40
     * @hooked blossom_fashion_footer_end    - 50
    */
    do_action( 'blossom_fashion_footer' );
    
    /**
     * After Footer
     * 
     * @hooked blossom_fashion_page_end    - 20
    */
    do_action( 'blossom_fashion_after_footer' );
    
    wp_footer(); ?>

</body>
</html>
