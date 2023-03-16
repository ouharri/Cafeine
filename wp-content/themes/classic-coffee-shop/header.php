<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package Classic Coffee Shop
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) {
  wp_body_open();
} else {
  do_action( 'wp_body_open' );
} ?>

<?php if ( get_theme_mod('classic_coffee_shop_preloader',true) != "") { ?>
  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>
<?php }?>

<a class="screen-reader-text skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'classic-coffee-shop' ); ?></a>

<div class="row m-0">
  <div class="col-lg-12 col-md-12 bg-color">
    <div class="header">
      <div class="row m-0">
        <div class="col-12 col-md-5 col-lg-12 p-0 align-self-center">
          <div class="logo text-center py-5 py-md-2 py-lg-5">
            <?php classic_coffee_shop_the_custom_logo(); ?>
            <?php $classic_coffee_shop_blog_info = get_bloginfo( 'name' ); ?>
            <?php if ( ! empty( $classic_coffee_shop_blog_info ) ) : ?>
              <?php if ( get_theme_mod('classic_coffee_shop_title_enable',true) != "") { ?>
              <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
              <?php } ?>
              <?php $classic_coffee_shop_description = get_bloginfo( 'description', 'display' );
              if ( $classic_coffee_shop_description || is_customize_preview() ) : ?>
                <?php if ( get_theme_mod('classic_coffee_shop_tagline_enable',false) != "") { ?>
                <span class="site-description"><?php echo esc_html( $classic_coffee_shop_description ); ?></span>
                <?php } ?>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-12 col-md-2 align-self-center">
          <div class="toggle-nav text-center text-md-right">
            <?php if(has_nav_menu('primary')){ ?>
              <button role="tab"><?php esc_html_e('MENU','classic-coffee-shop'); ?></button>
            <?php }?>
          </div>
        </div>
        <div id="mySidenav" class="nav sidenav text-center">
          <nav id="site-navigation" class="main-nav my-2" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu','classic-coffee-shop' ); ?>">
            <?php if(has_nav_menu('primary')){
              wp_nav_menu( array(
                'theme_location' => 'primary',
                'container_class' => 'main-menu clearfix' ,
                'menu_class' => 'clearfix',
                'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
                'fallback_cb' => 'wp_page_menu',
              ) );
            } ?>
            <a href="javascript:void(0)" class="close-button"><?php esc_html_e('CLOSE','classic-coffee-shop'); ?></a>
          </nav>
        </div>
        <div class="col-lg-12 col-md-5 col-12 social-icons text-center py-4 align-self-center">
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
      </div>
    </div>
  </div>
  <div class="outer-area">
    <div class="scroll-box">
