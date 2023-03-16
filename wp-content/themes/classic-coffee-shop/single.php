<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Classic Coffee Shop
 */

get_header(); ?>

<div class="container">
    <div id="content" class="contentsecwrap">
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <section class="site-main">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'content', 'single' ); ?>
                        <?php the_post_navigation(); ?>
                        <?php
                        // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || '0' != get_comments_number() )
                        	comments_template();
                        ?>
                    <?php endwhile; // end of the loop. ?>
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