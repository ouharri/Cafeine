<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blossom_Recipe
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
	        <?php 
	        /**
	         * blossom_recipe_posts_per_page_count - 10
	        */
	        do_action( 'blossom_recipe_before_posts_content' );
	        ?>
			<div class="article-group">
				<?php
				if ( have_posts() ) : 
		        
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</div>

	        <?php
	        /**
	         * After Posts hook
	         * @hooked blossom_recipe_navigation - 15
	        */
	        do_action( 'blossom_recipe_after_posts_content' );
	        ?>
		</main><!-- #main -->                          
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
