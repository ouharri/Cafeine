<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Classic Coffee Shop
 */

get_header(); ?>

<div class="container">
    <div id="content" class="contentsecwrap">
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <section class="site-main">
                    <div class="postsec-list">
        				<?php if ( have_posts() ) : ?>
                            <header>
                                <h1 class="entry-title"><?php /* translators: %s: post title */ printf( esc_attr__( 'Search Results for: %s', 'classic-coffee-shop' ), '<span>' . esc_attr( get_search_query() ) . '</span>' ); ?></h1>
                            </header>
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'content', 'search' ); ?>
                            <?php endwhile; ?>
                            <?php the_posts_pagination(); ?>
                        <?php else : ?>
                            <?php get_template_part( 'no-results', 'search' ); ?>
                        <?php endif; ?>
                    </div>
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