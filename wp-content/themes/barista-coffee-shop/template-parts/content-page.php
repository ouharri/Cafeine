<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Barista Coffee Shop
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header>
    <?php if(has_post_thumbnail()) {?>
        <hr>
            <?php the_post_thumbnail(); ?>
        <hr>
    <?php }?>
    <div class="entry-content">
        <?php the_content();
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'barista-coffee-shop'),
                'after' => '</div>',
            ));
        ?>
    </div>

    <?php if (get_edit_post_link()) : ?>
        <footer class="entry-footer">
            <?php
                edit_post_link(
                    sprintf(
                        wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Edit <span class="screen-reader-text">%s</span>', 'barista-coffee-shop'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        esc_html( get_the_title())
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
            ?>
        </footer>
    <?php endif; ?>
</article>