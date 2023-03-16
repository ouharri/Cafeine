<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blossom_Fashion
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Blog">
	
    <?php 
        /**
         * @hooked blossom_fashion_post_thumbnail
        */
        do_action( 'blossom_fashion_before_entry_content' );
    ?>
    
    <div class="text-holder">
    <?php    
        /**
         * @hooked blossom_fashion_entry_header  - 10
         * @hooked blossom_fashion_entry_content - 15
         * @hooked blossom_fashion_entry_footer  - 20
        */
        do_action( 'blossom_fashion_entry_content' );
    ?>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->
