<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Pizza Lite
 */
get_header(); ?>
<div class="container">
    <div class="page_content">
        <section class="site-main" id="sitemain">
            <header class="page-header">
                <h1 class="entry-title"><?php
esc_html_e('404 Not Found', 'pizza-lite'); ?></h1>
            </header><!-- .page-header -->
            <div class="page-content">
                <p class="text-404"><?php
esc_html_e('Looks like you have taken a wrong turn.....Dont worry... it happens to the best of us.', 'pizza-lite'); ?></p>
                <?php
get_search_form(); ?>
            </div><!-- .page-content -->
        </section>
     <?php
get_sidebar(); ?>      
    <div class="clear"></div>
    </div>
</div>
<?php
get_footer(); ?>