<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Pizza Lite
 */
get_header(); ?>
<div class="container">
      <div class="page_content">
    		 <section class="site-main">               
            		<?php if( have_posts() ) :
							while( have_posts() ) : the_post(); ?>
                            	<h1 class="entry-title"><?php the_title(); ?></h1>
                                <div class="entry-content">
<?php
                                        the_content();
                                        wp_link_pages( array(
                                            'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'pizza-lite' ) . '</span>',
                                            'after'       => '</div>',
                                            'link_before' => '<span>',
                                            'link_after'  => '</span>',
                                            'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'pizza-lite' ) . ' </span>%',
                                            'separator'   => '<span class="screen-reader-text">, </span>',
                                        ) );
                                    ?> 
                                            <?php
												//If comments are open or we have at least one comment, load up the comment template
												if ( comments_open() || '0' != get_comments_number() )
													comments_template();
												?>
                                </div><!-- entry-content -->
                      		<?php endwhile; endif; ?>
            </section><!-- section-->
     <?php get_sidebar();?>      
    <div class="clear"></div>
    </div><!-- .page_content --> 
 </div><!-- .container --> 
<?php get_footer(); ?>