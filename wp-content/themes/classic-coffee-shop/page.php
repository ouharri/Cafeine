<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Classic Coffee Shop
 */

get_header(); ?>

<div class="container">
    <div id="content" class="contentsecwrap">
        <div class="row">
            <div class="col-lg-9 col-md-8">
            	<section class="site-main">
            		<?php while( have_posts() ) : the_post(); ?>
            			<?php get_template_part( 'content', 'page' ); ?>
                        <?php
                            //If comments are open or we have at least one comment, load up the comment template
                            if ( comments_open() || '0' != get_comments_number() )
                                comments_template();
                        ?>
                    <?php endwhile; ?>
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