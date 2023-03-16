<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blossom_Fashion
 */

$sidebar_layout = blossom_fashion_sidebar_layout();
 
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
        
        <?php
        /**
         * @hooked blossom_fashion_navigation    - 15 
         * @hooked blossom_fashion_author        - 20
         * @hooked blossom_fashion_newsletter    - 25
         * @hooked blossom_fashion_related_posts - 30
         * @hooked blossom_fashion_popular_posts - 35
         * @hooked blossom_fashion_comment       - 40
        */
        do_action( 'blossom_fashion_after_post_content' );
        ?>
        
	</div><!-- #primary -->

<?php
if( $sidebar_layout != 'full-width' )
get_sidebar();
get_footer();
