<?php
/**
 * @package Classic Coffee Shop
 */
?>

<div class="listarticle">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	<?php if (has_post_thumbnail() ){ ?>
            <div class="post-thumb">
               <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
            </div>
	    <?php } ?>
        <header class="entry-header">
            <h2 class="single_title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
            <?php if ( 'post' == get_post_type() ) : ?>
                <div class="postmeta">
                    <div class="post-date"><i class="fas fa-calendar-alt"></i> &nbsp; <?php the_date(); ?></div>
                    <div class="post-comment">&nbsp; &nbsp; <i class="fa fa-comment"></i> &nbsp;  <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a></div>
                </div>
            <?php endif; ?>
        </header>
        <?php if ( is_search() || !is_single() ) : // Only display Excerpts for Search ?>
        <div class="entry-summary">
           	<?php the_excerpt(); ?>
            <div class="rsvp_button mt-5">
                <div class="rsvp_inner d-inline-block"><a href="<?php the_permalink(); ?>"><?php esc_html_e('Read More','classic-coffee-shop'); ?></a></div>
            </div>
        </div>
        <?php else : ?>
        <div class="entry-content">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'classic-coffee-shop' ) ); ?>
            <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'classic-coffee-shop' ),
                    'after'  => '</div>',
                ) );
            ?>
        </div>
        <?php endif; ?>
        <div class="clear"></div>
    </article>
</div>