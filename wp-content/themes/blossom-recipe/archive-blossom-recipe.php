<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header(); ?>

<div class="wrap">

	<?php	
	if(have_posts()) : 
		?>

		<div id="primary" class="content-area" itemscope itemtype="http://schema.org/ItemList">
			<main id="main" class="site-main" role="main">

				<?php
				while( have_posts() ) : the_post();
			
						do_action( 'br_recipe_archive_action' );

				endwhile;
				
				?>
			</main><!-- #main -->

			<?php
			the_posts_pagination( array(
           		'prev_text'          => __( 'Previous', 'blossom-recipe' ),
           		'next_text'          => __( 'Next', 'blossom-recipe' ),
           		'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'blossom-recipe' ) . ' </span>',
       		) );

       		?>
		</div><!-- #primary -->
				
	<?php
	endif;        		
?>
</div><!-- .wrap -->

<?php get_sidebar();
get_footer();
