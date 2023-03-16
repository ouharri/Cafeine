<?php
/**
 *  Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Barista Coffee Shop
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title('<h2 class="entry-title">', '</h2>'); ?>
        <?php if(has_post_thumbnail()) {?>
            <?php the_post_thumbnail(); ?>
        <?php }?>
    </header>
    <div class="meta-info-box my-2">
        <span class="entry-author"><?php esc_html_e('BY','barista-coffee-shop'); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?></a></span>
        <span class="ml-2"></i><?php echo esc_html(get_the_date()); ?></span>
    </div>
    <div class="entry-content">
        <?php
        the_content(sprintf(
            wp_kses(
            /* translators: %s: Name of current post. Only visible to screen readers */
                __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'barista-coffee-shop'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            esc_html( get_the_title() )
        ));

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'barista-coffee-shop'),
            'after' => '</div>',
        ));

        the_tags();
        ?>
    </div>
</article>