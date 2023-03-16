<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Barista Coffee Shop
 */
?>

<div class="col-lg-6 col-md-6 col-sm-6">
	<article id="post-<?php the_ID(); ?>" <?php post_class('article-box'); ?>>
        <?php if ( has_post_thumbnail() ) { ?><?php barista_coffee_shop_post_thumbnail(); ?><?php }?>
        <div class="meta-info-box my-2">
            <span class="entry-author"><?php esc_html_e('BY','barista-coffee-shop'); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?></a></span>
            <span class="ml-2"></i><?php echo esc_html(get_the_date()); ?></span>
        </div>
        <div class="post-summary m-0">
            <?php the_title('<h3 class="entry-title pb-3"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>');?>
            <p><?php echo wp_trim_words( get_the_content(), 30 ); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn-text"><?php esc_html_e('Read More','barista-coffee-shop'); ?></a>
        </div>
    </article>
</div>