<?php
/**
* Template Name: Left Sidebar
 */

get_header(); ?>

	<div id="skip-content" class="container">
		<div class="row">
			<?php get_sidebar(); ?>
		    <div id="primary" class="content-area col-sm-12 col-lg-8">
		        <main id="main" class="site-main module-border-wrap">
					<?php
					while (have_posts()) : the_post();

						get_template_part('template-parts/content', 'page');

						// If comments are open or we have at least one comment, load up the comment template.
						if (comments_open() || get_comments_number()) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
		        </main>
		    </div>
		</div>
	</div>
	
<?php get_footer();