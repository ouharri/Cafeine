<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Barista Coffee Shop
 */
get_header(); ?>

    <div id="skip-content" class="container">
        <div class="row">
            <div id="primary" class="content-area <?php echo is_active_sidebar('sidebar') ? "col-lg-9 col-md-8" : "col-lg-12"; ?>">
                <main id="main" class="site-main module-border-wrap mb-4">
                    <?php if (have_posts()) : ?>
                        <header class="page-header">
                            <h2 class="page-title">
                                <?php
                                    /* translators: %s: search query. */
                                    printf(esc_html__('Search Results for: %s', 'barista-coffee-shop'), '<span>' . get_search_query() . '</span>');
                                ?>
                            </h2>
                        </header>
                        <div class="row">
                            <?php
                            /* Start the Loop */
                            while (have_posts()) : the_post();

                                /**
                                 * Run the loop for the search to output the results.
                                 * If you want to overload this in a child theme then include a file
                                 * called content-search.php and that will be used instead.
                                 */
                                get_template_part('template-parts/content', 'search');

                            endwhile;

                            the_posts_navigation();

                        else :

                            get_template_part('template-parts/content', 'none');

                        endif; ?>
                    </div>
                </main>
            </div>
            <?php get_sidebar();?>
        </div>
    </div>

<?php get_footer();