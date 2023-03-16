<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blossom_Fashion
 */

get_header(); ?>

	<div id="primary" class="content-area">
		
        <?php 
        /**
         * @hooked blossom_fashion_post_count
        */
        do_action( 'blossom_fashion_before_posts_content' );
        ?>
        
        <main id="main" class="site-main">

		<?php
		if ( have_posts() ) : 
        
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'archive' );

			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
        
        <?php
        /**
         * @hooked blossom_fashion_navigation - 15
        */
        do_action( 'blossom_fashion_after_posts_content' );
        ?>
        
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
