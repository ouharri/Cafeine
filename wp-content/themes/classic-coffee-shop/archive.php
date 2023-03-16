<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Classic Coffee Shop
 */

get_header(); ?>

<div class="container">
    <div id="content" class="contentsecwrap">
         <div class="row">
            <div class="col-lg-9 col-md-8">
                <section class="site-main">
        			<?php if ( have_posts() ) : ?>
                        <header class="page-header">
                            <?php
        						the_archive_title( '<h1 class="entry-title">', '</h1>' );
        						the_archive_description( '<div class="taxonomy-description">', '</div>' );
        					?> 
                        </header>
        				<div class="postsec-list">
        					<?php /* Start the Loop */ ?>
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'content' ); ?>
                            <?php endwhile; ?>
                        </div>
                        <?php the_posts_pagination(); ?>
                    <?php else : ?>
                        <?php get_template_part( 'no-results', 'archive' ); ?>
                    <?php endif; ?>
                </section>
            </div>
            <div class="col-lg-3 col-md-4">
                <?php get_sidebar();?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
	
<?php get_footer(); ?>