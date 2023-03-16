<?php
/**
 * Template Name: Home Template
 */

get_header(); ?>

<main id="skip-content">
  <section id="top-slider">
    <?php $barista_coffee_shop_slide_pages = array();
      for ( $barista_coffee_shop_count = 1; $barista_coffee_shop_count <= 3; $barista_coffee_shop_count++ ) {
        $barista_coffee_shop_mod = intval( get_theme_mod( 'barista_coffee_shop_top_slider_page' . $barista_coffee_shop_count ));
        if ( 'page-none-selected' != $barista_coffee_shop_mod ) {
          $barista_coffee_shop_slide_pages[] = $barista_coffee_shop_mod;
        }
      }
      if( !empty($barista_coffee_shop_slide_pages) ) :
        $barista_coffee_shop_args = array(
          'post_type' => 'page',
          'post__in' => $barista_coffee_shop_slide_pages,
          'orderby' => 'post__in'
        );
        $barista_coffee_shop_query = new WP_Query( $barista_coffee_shop_args );
        if ( $barista_coffee_shop_query->have_posts() ) :
          $i = 1;
    ?>
    <div class="owl-carousel" role="listbox">
      <?php  while ( $barista_coffee_shop_query->have_posts() ) : $barista_coffee_shop_query->the_post(); ?>
        <div class="slider-box">
          <img src="<?php esc_url(the_post_thumbnail_url('full')); ?>"/>
          <div class="slider-inner-box">
            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <div class="slider-box-btn mt-4">
              <a href="<?php the_permalink(); ?>"><?php esc_html_e('Shop Now','barista-coffee-shop'); ?></a>
            </div>
          </div>
        </div>
      <?php $i++; endwhile;
      wp_reset_postdata();?>
    </div>
    <?php else : ?>
      <div class="no-postfound"></div>
    <?php endif;
    endif;?>
    <div class="social-link text-center text-md-right">
      <?php if(get_theme_mod('barista_coffee_shop_facebook_url') != ''){ ?>
        <a href="<?php echo esc_url(get_theme_mod('barista_coffee_shop_facebook_url','')); ?>"><i class="fab fa-facebook-f"></i></a>
      <?php }?>
      <?php if(get_theme_mod('barista_coffee_shop_twitter_url') != ''){ ?>
        <a href="<?php echo esc_url(get_theme_mod('barista_coffee_shop_twitter_url','')); ?>"><i class="fab fa-twitter"></i></a>
      <?php }?>
      <?php if(get_theme_mod('barista_coffee_shop_intagram_url') != ''){ ?>
        <a href="<?php echo esc_url(get_theme_mod('barista_coffee_shop_intagram_url','')); ?>"><i class="fab fa-instagram"></i></a>
      <?php }?>
      <?php if(get_theme_mod('barista_coffee_shop_linkedin_url') != ''){ ?>
        <a href="<?php echo esc_url(get_theme_mod('barista_coffee_shop_linkedin_url','')); ?>"><i class="fab fa-linkedin-in"></i></a>
      <?php }?>
      <?php if(get_theme_mod('barista_coffee_shop_youtube_url') != ''){ ?>
        <a href="<?php echo esc_url(get_theme_mod('barista_coffee_shop_youtube_url','')); ?>"><i class="fab fa-youtube"></i></a>
      <?php }?>
    </div>
  </section>

  <section id="new-products" class="py-5">
    <div class="container">
      <?php if(get_theme_mod('barista_coffee_shop_new_product_title') != ''){ ?>
        <h3 class="text-center"><?php echo esc_html(get_theme_mod('barista_coffee_shop_new_product_title','')); ?></h3>
      <?php }?>
      <?php if(get_theme_mod('barista_coffee_shop_new_product_text') != ''){ ?>
        <p class="text-center"><?php echo esc_html(get_theme_mod('barista_coffee_shop_new_product_text','')); ?></p>
      <?php }?>
      <div class="row mt-5">
        <?php
        if ( class_exists( 'WooCommerce' ) ) {
          $barista_coffee_shop_args = array(
            'post_type' => 'product',
            'posts_per_page' => get_theme_mod('barista_coffee_shop_new_product_number'),
            'product_cat' => get_theme_mod('barista_coffee_shop_new_product_category'),
            'order' => 'ASC'
          );
          $loop = new WP_Query( $barista_coffee_shop_args );
          while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
            <div class="col-lg-3 col-md-4 col-sm-4">
              <div class="product-box mb-4">
                <div class="product-image mb-4">
                  <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(woocommerce_placeholder_img_src()).'" />'; ?>
                </div>
                <?php woocommerce_show_product_sale_flash( $post, $product ); ?>
                <div class="row">
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                    <?php if( $product->is_type( 'simple' ) ){ woocommerce_template_loop_rating( $loop->post, $product ); } ?>
                    <p class="my-2 product-title"><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></p>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                    <p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?> mb-0"><?php echo $product->get_price_html(); ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; wp_reset_query(); ?>
        <?php } ?>
      </div>
    </div>
  </section>

  <section id="page-content">
    <div class="container">
      <div class="py-5">
        <?php
          if ( have_posts() ) :
            while ( have_posts() ) : the_post();
              the_content();
            endwhile;
          endif;
        ?>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
