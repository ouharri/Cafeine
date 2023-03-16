<?php
/**
 * The template for displaying image attachments.
 *
 * @package Pizza Lite
 */
$metadata = wp_get_attachment_metadata();
get_header(); ?>
<div class="container">
     <div class="page_content">
        <section class="site-main">
			<?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        <div class="entry-meta">
					<span class="entry-date"><time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>

					<span class="full-size-link"><a href="<?php echo esc_url( wp_get_attachment_url() ); ?>"><?php echo esc_html( $metadata['width'] ); ?> &times; <?php echo esc_html( $metadata['height'] ); ?></a></span>

					<span class="parent-post-link"><a href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>" rel="gallery"><?php echo esc_html(get_the_title( $post->post_parent )); ?></a></span>
					<?php edit_post_link( __( 'Edit', 'pizza-lite' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-meta -->
                        <nav role="navigation" id="image-navigation" class="image-navigation">
                            <div class="nav-previous"><?php previous_image_link( false, wp_kses( '<span class="meta-nav">&larr;</span> Previous', 'pizza-lite' ) ); ?></div>
                            <div class="nav-next"><?php next_image_link( false, wp_kses( 'Next <span class="meta-nav">&rarr;</span>', 'pizza-lite' ) ); ?></div>
                        </nav><!-- #image-navigation -->
                    </header><!-- .entry-header -->
                    <div class="entry-content">
                        <div class="entry-attachment">
                            <div class="attachment">
                                <?php pizza_lite_the_attached_image(); ?>
                            </div><!-- .attachment -->
                            <?php if ( has_excerpt() ) : ?>
                            <div class="entry-caption">
                                <?php the_excerpt(); ?>
                            </div><!-- .entry-caption -->
                            <?php endif; ?>
                        </div><!-- .entry-attachment -->
                        <?php
                            the_content();
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . esc_html_e( 'Pages:', 'pizza-lite' ),
                                'after'  => '</div>',
                            ) );
                        ?>
                    </div><!-- .entry-content -->
                    <?php edit_post_link( esc_html_e( 'Edit', 'pizza-lite' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
                </article><!-- #post-## -->
                <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || '0' != get_comments_number() )
                        comments_template();
                ?>
            <?php endwhile; // end of the loop. ?>
        </section>
        <?php get_sidebar();?>
        <div class="clear"></div>
    </div>
</div>
<?php get_footer(); ?>