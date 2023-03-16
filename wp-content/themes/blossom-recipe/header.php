<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blossom_Recipe
 */
    /**
     * Doctype Hook
     * 
     * @hooked blossom_recipe_doctype
    */
    do_action( 'blossom_recipe_doctype' );
?>
<head itemscope itemtype="http://schema.org/WebSite">
	<?php 
    /**
     * Before wp_head
     * 
     * @hooked blossom_recipe_head
    */
    do_action( 'blossom_recipe_before_wp_head' );
    
    wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

<?php

    wp_body_open();
    
    /**
     * Before Header
     * 
     * @hooked blossom_recipe_page_start - 20 
     * @hooked blossom_recipe_sticky_newsletter - 30 
    */
    do_action( 'blossom_recipe_before_header' );
    
    /**
     * Header
     * 
     * @hooked blossom_recipe_header - 20     
    */
    do_action( 'blossom_recipe_header' );
    
    /**
     * Before Content
     * 
     * @hooked blossom_recipe_banner             - 15
    */
    do_action( 'blossom_recipe_after_header' );
    
    /**
     * Content
     * 
     * @hooked blossom_recipe_content_start
    */
    do_action( 'blossom_recipe_content' );
    