<?php
/**
 * The Template Name: Home Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Classic Coffee Shop
 */

get_header(); ?>

<div id="content">
  <?php
    $classic_coffee_shop_hidcatslide = get_theme_mod('classic_coffee_shop_hide_categorysec', true);
    if( $classic_coffee_shop_hidcatslide != ''){
  ?>
    <section id="catsliderarea">
      <div class="catwrapslider">
        <div class="owl-carousel">
          <?php if( get_theme_mod('classic_coffee_shop_slidersection',false) ) { ?>
          <?php $classic_coffee_shop_queryvar = new WP_Query('cat='.esc_attr(get_theme_mod('classic_coffee_shop_slidersection',true)));
            while( $classic_coffee_shop_queryvar->have_posts() ) : $classic_coffee_shop_queryvar->the_post(); ?>
              <div class="slidesection">
                <?php esc_url(the_post_thumbnail( 'full' )); ?>
                <div class="slider-box text-center">
                  <h3><?php the_title(); ?></h3>
                  <?php
                    $classic_coffee_shop_trimexcerpt = get_the_excerpt();
                    $classic_coffee_shop_shortexcerpt = wp_trim_words( $classic_coffee_shop_trimexcerpt, $num_words = 15 );
                    echo '<p class="mt-4">' . esc_html( $classic_coffee_shop_shortexcerpt ) . '</p>';
                  ?>
                  <?php if ( get_theme_mod('classic_coffee_shop_button_text',true) != "") { ?>
                    <div class="rsvp_button mt-0 mt-md-5">
                      <a href="<?php the_permalink(); ?>"><?php echo esc_html(get_theme_mod('classic_coffee_shop_button_text',__('SHOP HERE','classic-coffee-shop'))); ?></a>
                    </div>
                  <?php }?>
                </div>
              </div>
            <?php endwhile; wp_reset_postdata(); ?>
          <?php } ?>
        </div>
      </div>
    </section>
  <?php } ?>

  <section id="product_cat_slider" class="py-5">
    <div class="container">
      <div class="product-head-box">
        <?php if ( get_theme_mod('classic_coffee_shop_product_title') != "") { ?>
          <img src="<?php echo esc_url(get_template_directory_uri()) . '/images/head.png' ?>">
          <h3><?php echo esc_html(get_theme_mod('classic_coffee_shop_product_title','')); ?></h3>
        <?php }?>
        <?php if ( get_theme_mod('classic_coffee_shop_product_text') != "") { ?>
          <p class="mb-0"><?php echo esc_html(get_theme_mod('classic_coffee_shop_product_text','')); ?></p>
        <?php }?>
      </div>
      <div class="owl-carousel">
        <?php if(class_exists('woocommerce')){
          $args = array(
            'post_type' => 'product',
            'posts_per_page' => 50,
            'product_cat' => get_theme_mod('classic_coffee_shop_hot_products_cat'),
            'order' => 'ASC'
          );
          $loop = new WP_Query( $args );
          while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
            <div class="product-image">
              <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(woocommerce_placeholder_img_src()).'" />'; ?>
              <?php if ( has_post_thumbnail() ) { ?>
                  <?php woocommerce_show_product_sale_flash( $post, $product ); ?>
              <?php }?>
              <div class="box-content">
                <h4 class="product-text my-2 "><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h4>
              </div>
            </div>
          <?php endwhile; wp_reset_query(); ?>
        <?php }?>
      </div>
    </div>
  </section>
</div>

<?php get_footer(); ?>
