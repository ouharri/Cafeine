<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Blossom_Fashion
 */

get_header(); ?>

	<section id="primary" class="content-area">
		
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

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
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
        
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
