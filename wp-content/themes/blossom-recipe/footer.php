<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blossom_Recipe
 */
    
    /**
     * After Content
     * 
     * @hooked blossom_recipe_content_end - 20
    */
    do_action( 'blossom_recipe_before_footer' );
    
    /**
     * Before footer
     * 
     * @hooked blossom_recipe_newsletter_section - 10
     * @hooked blossom_recipe_instagram_section - 20
    */
    do_action( 'blossom_recipe_before_footer_start' );

    /**
     * Footer
     * 
     * @hooked blossom_recipe_footer_start  - 20
     * @hooked blossom_recipe_footer_top    - 30
     * @hooked blossom_recipe_footer_bottom - 40
     * @hooked blossom_recipe_footer_end    - 50
    */
    do_action( 'blossom_recipe_footer' );
    
    /**
     * After Footer
     * 
     * @hooked blossom_recipe_back_to_top - 15
     * @hooked blossom_recipe_page_end    - 20
    */
    do_action( 'blossom_recipe_after_footer' );

    wp_footer(); ?>

</body>
</html>
