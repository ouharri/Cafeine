<?php
/**
 * @package Pizza Lite
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
    <header class="entry-header">
        <h1 class="single_title"><?php the_title(); ?></h1>
    </header><!-- .entry-header -->
     <div class="postmeta">
            <div class="post-date"><?php the_date(); ?></div><!-- post-date -->
            <div class="post-comment"> &nbsp;|&nbsp; <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a></div> 
            <div class="clear"></div>         
    </div><!-- postmeta -->
	<?php if (has_post_thumbnail() ){ ?>
    	<div class="post-thumb"><?php the_post_thumbnail(); ?></div>
    <?php }?>
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
        <div class="postmeta">           
            <div class="post-tags"><?php the_tags(); ?> </div>
            <div class="clear"></div>
        </div><!-- postmeta -->
    </div><!-- .entry-content -->
    <footer class="entry-meta">
      <?php edit_post_link(); ?>
    </footer><!-- .entry-meta -->
</article>