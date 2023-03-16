<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blossom_Fashion
 */

    /**
     * Doctype Hook
     * 
     * @hooked blossom_fashion_doctype
    */
    do_action( 'blossom_fashion_doctype' );
?>
<head itemscope itemtype="http://schema.org/WebSite">
	<?php 
    /**
     * Before wp_head
     * 
     * @hooked blossom_fashion_head
    */
    do_action( 'blossom_fashion_before_wp_head' );
    
    wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

<?php

    wp_body_open();
    
    /**
     * Before Header
     * 
     * @hooked blossom_fashion_page_start - 20 
    */
    do_action( 'blossom_fashion_before_header' );
    
    /**
     * Header
     * 
     * @hooked blossom_fashion_header - 20     
    */
    do_action( 'blossom_fashion_header' );
    
    /**
     * Before Content
     * 
     * @hooked blossom_fashion_banner             - 15
     * @hooked blossom_fashion_top_section        - 20
     * @hooked blossom_fashion_shop_section       - 25
     * @hooked blossom_fashion_top_author_section - 30
     * @hooked blossom_fashion_top_search_section - 35
     * @hooked blossom_fashion_top_bar            - 40
    */
    do_action( 'blossom_fashion_after_header' );
    
    /**
     * Content
     * 
     * @hooked blossom_fashion_content_start
    */
    do_action( 'blossom_fashion_content' );