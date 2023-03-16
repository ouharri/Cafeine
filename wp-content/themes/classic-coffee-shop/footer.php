<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Classic Coffee Shop
 */
?>       
    <div class="clear"></div>
    <div id="footer">
    	<div class="container">
        <div class="logo text-center pt-5 pt-md-5">
          <?php classic_coffee_shop_the_custom_logo(); ?>
          <?php $classic_coffee_shop_blog_info = get_bloginfo( 'name' ); ?>
          <?php if ( ! empty( $classic_coffee_shop_blog_info ) ) : ?>
            <h1 class="site-title mt-3"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
            <?php $classic_coffee_shop_description = get_bloginfo( 'description', 'display' );
            if ( $classic_coffee_shop_description || is_customize_preview() ) : ?>
              <span class="site-description"><?php echo esc_html( $classic_coffee_shop_description ); ?></span>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <div class="social-icons text-center my-3">
          <?php if ( get_theme_mod('classic_coffee_shop_fb_link') != "") { ?>
            <a title="<?php esc_attr('facebook', 'classic-coffee-shop'); ?>" target="_blank" href="<?php echo esc_url(get_theme_mod('classic_coffee_shop_fb_link')); ?>"><i class="fab fa-facebook-f"></i></a> 
          <?php } ?>
          <?php if ( get_theme_mod('classic_coffee_shop_twitt_link') != "") { ?>
            <a title="<?php esc_attr('twitter', 'classic-coffee-shop'); ?>" target="_blank" href="<?php echo esc_url(get_theme_mod('classic_coffee_shop_twitt_link')); ?>"><i class="fab fa-twitter"></i></a>
          <?php } ?>
          <?php if ( get_theme_mod('classic_coffee_shop_linked_link') != "") { ?>
            <a title="<?php esc_attr('linkedin', 'classic-coffee-shop'); ?>" target="_blank" href="<?php echo esc_url(get_theme_mod('classic_coffee_shop_linked_link')); ?>"><i class="fab fa-linkedin-in"></i></a>
          <?php } ?>
          <?php if ( get_theme_mod('classic_coffee_shop_insta_link') != "") { ?>
            <a title="<?php esc_attr('instagram', 'classic-coffee-shop'); ?>" target="_blank" href="<?php echo esc_url(get_theme_mod('classic_coffee_shop_insta_link')); ?>"><i class="fab fa-instagram"></i></a>
          <?php } ?>
          <?php if ( get_theme_mod('classic_coffee_shop_youtube_link') != "") { ?>
            <a title="<?php esc_attr('youtube', 'classic-coffee-shop'); ?>" target="_blank" href="<?php echo esc_url(get_theme_mod('classic_coffee_shop_youtube_link')); ?>"><i class="fab fa-youtube"></i></a>
          <?php } ?>
        </div>
        <div class="copywrap text-center">
          <div class="container">
            <a href="<?php echo esc_html(get_theme_mod('classic_coffee_shop_copyright_link',__('https://www.theclassictemplates.com/themes/free-coffee-shop-wordpress-theme/','classic-coffee-shop'))); ?>" target="_blank"><?php echo esc_html(get_theme_mod('classic_coffee_shop_copyright_line',__('Coffee Shop WordPress Theme','classic-coffee-shop'))); ?></a>      
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>