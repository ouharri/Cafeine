<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blossom_Fashion
 */

$sidebar_layout = blossom_fashion_sidebar_layout();

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				/**
                 * Comment Template
                 * 
                 * @hooked blossom_fashion_comment
                */
                do_action( 'blossom_fashion_after_page_content' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
if( $sidebar_layout != 'full-width' )
get_sidebar();
get_footer();
