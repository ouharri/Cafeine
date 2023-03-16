<?php
/**
 * The template for displaying home page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Pizza Lite
 */
get_header(); 

$hideslide = get_theme_mod('hide_slides', 1);
$hide_pagefourboxes = get_theme_mod('hide_pagefourboxes', 1);
$secwithcontent = get_theme_mod('hide_home_secwith_content', 1);

if (!is_home() && is_front_page()) { 
if( $hideslide == '') { ?>
<!-- Slider Section -->
<?php 
$pages = array();// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
for($sld=7; $sld<10; $sld++) { 
	$mod = absint( get_theme_mod('page-setting'.$sld));
    if ( 'page-none-selected' != $mod ) {
      $pages[] = $mod; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
    }	
} 
if( !empty($pages) ) :
$args = array(
      'posts_per_page' => 3,
      'post_type' => 'page',
      'post__in' => $pages,
      'orderby' => 'post__in'
    );
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) :	
	$sld = 7;
?>
<section id="home_slider">
  <div class="slider-wrapper theme-default">
    <div id="slider" class="nivoSlider">
		<?php
        $i = 0;
        while ( $query->have_posts() ) : $query->the_post();
          $i++;
          $pizza_lite_slideno[] = $i;
          $pizza_lite_slidetitle[] = get_the_title();
		  $pizza_lite_slidedesc[] = get_the_excerpt();
          $pizza_lite_slidelink[] = esc_url(get_permalink());
          ?>
          <img src="<?php the_post_thumbnail_url('full'); ?>" title="#slidecaption<?php echo esc_attr( $i ); ?>" />
          <?php
        $sld++;
        endwhile;
          ?>
    </div>
        <?php
        $k = 0;
        foreach( $pizza_lite_slideno as $pizza_lite_sln ){ ?>
    <div id="slidecaption<?php echo esc_attr( $pizza_lite_sln ); ?>" class="nivo-html-caption">
      <div class="slide_info">
        <h2><?php echo esc_html($pizza_lite_slidetitle[$k] ); ?></h2>
        <p><?php echo esc_html($pizza_lite_slidedesc[$k] ); ?></p>
        <div class="clear"></div>
        <a class="slide_more" href="<?php echo esc_url($pizza_lite_slidelink[$k] ); ?>">
          <?php esc_html_e('Read More', 'pizza-lite');?>
          </a>
      </div>
    </div>
 	<?php $k++;
       wp_reset_postdata();
      } ?>
<?php endif; endif; ?>
  </div>
  <div class="clear"></div>
</section>
<div class="zig-zag-top"></div>
<?php } } 

	if(!is_home() && is_front_page()){ 
	if( $secwithcontent == '') {
?>
 <section id="sectionone">
 	<div class="container">
    <div class="home_sectionone_content">
            <div class="columns-row"> 
            <?php if( get_theme_mod('sec-column1', false)) { 
				$seccolbox = new WP_query('page_id='.get_theme_mod('sec-column1',true));
				while( $seccolbox->have_posts() ) : $seccolbox->the_post(); ?>
			<div class="col-columns-2 cl2content"> 
            <div class="fancy-title"><h2><?php the_title(); ?></h2></div> 
            <div class="fancy-desc"><?php the_content(); ?></div>
            <div class="section2button"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e('Read More', 'pizza-lite');?></a></div>
	          </div>  
              <?php if( has_post_thumbnail() ) { ?>	
			<div class="col-columns-2 cl2thumb"> 
                <?php the_post_thumbnail(); ?>
            </div>  	
            <?php }  endwhile; } ?>
            <div class="clear"></div></div>
            </div>
    </div>
 </section>
<?php }}

if (!is_home() && is_front_page()) { 
if( $hide_pagefourboxes == '') { ?>
<div class="pageallarea">
<section id="pagearea">
  <div class="container">   
 	  <div class="featured-box-heading"><?php echo esc_html('Our Services', 'pizza-lite');?></div>	
	  <div class="clear"></div>	
      <div class="services-row services-row-4column"> 
      <?php for($p=1; $p<4; $p++) { 
	  		if( get_theme_mod('page-column'.$p,false)) {
			$querypagethreeboxes = new WP_query('page_id='.get_theme_mod('page-column'.$p,true)); 
			while( $querypagethreeboxes->have_posts() ) : $querypagethreeboxes->the_post(); ?>
            <div class="servicebox">
                <div class="serviceboxbg">
					<?php if( has_post_thumbnail() ) { ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>"><div class="servicebox-icon"><?php the_post_thumbnail(); ?></div></a>
                    <?php } ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>">
                    <h3><?php the_title(); ?></h3>
                    </a>
                    <?php the_excerpt(); ?>
                </div>
            </div>
        <?php endwhile;
       wp_reset_postdata(); 
	   }} ?>
	<div class="clear"></div>
	</div>
      <div class="clear"></div> 
  </div><!-- container -->
</section><!-- #pagearea -->
</div>
<div class="clear"></div>
<?php } } ?>
<div class="container">
     <div class="page_content">
      <?php 
	if ( 'posts' == get_option( 'show_on_front' ) ) {
    ?>
    <section class="site-main">
      <div class="blog-post">
        <?php
                    if ( have_posts() ) :
                        // Start the Loop.
                        while ( have_posts() ) : the_post();
                            /*
                             * Include the post format-specific template for the content. If you want to
                             * use this in a child theme, then include a file called called content-___.php
                             * (where ___ is the post format) and that will be used instead.
                             */
                            get_template_part( 'content', get_post_format() );
                        endwhile;
                        // Previous/next post navigation.
						the_posts_pagination( array(
							'mid_size' => 2,
							'prev_text' => esc_html__( 'Back', 'pizza-lite' ),
							'next_text' => esc_html__( 'Next', 'pizza-lite' ),
						) );
                    else :
                        // If no content, include the "No posts found" template.
                         get_template_part( 'no-results', 'index' );
                    endif;
                    ?>
      </div>
      <!-- blog-post --> 
    </section>
    <?php
} else {
    ?>
	<section class="site-main">
      <div class="blog-post">
        <?php
                    if ( have_posts() ) :
                        // Start the Loop.
                        while ( have_posts() ) : the_post();
                            /*
                             * Include the post format-specific template for the content. If you want to
                             * use this in a child theme, then include a file called called content-___.php
                             * (where ___ is the post format) and that will be used instead.
                             */
							 ?>
                             <header class="entry-header">           
            				<h1><?php the_title(); ?></h1>
                    		</header>
                             <?php
                            the_content();
                        endwhile;
                        // Previous/next post navigation.
						the_posts_pagination( array(
							'mid_size' => 2,
							'prev_text' => esc_html__( 'Back', 'pizza-lite' ),
							'next_text' => esc_html__( 'Next', 'pizza-lite' ),
						) );
                    else :
                        // If no content, include the "No posts found" template.
                         get_template_part( 'no-results', 'index' );
                    endif;
                    ?>
      </div>
      <!-- blog-post --> 
    </section>
	<?php
}
	?>
    <?php get_sidebar();?>
    <div class="clear"></div>
  </div><!-- site-aligner -->
</div><!-- content -->
<?php get_footer(); ?>