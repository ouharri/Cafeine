<?php
/**
 * Displays main header
 *
 * @package Barista Coffee Shop
 */
?>
<?php
$barista_coffee_shop_sticky_header = get_theme_mod('barista_coffee_shop_sticky_header');
    $barista_coffee_shop_data_sticky = "false";
    if ($barista_coffee_shop_sticky_header) {
    $barista_coffee_shop_data_sticky = "true";
    }
?>
<div class="main-header" data-sticky="<?php echo esc_attr($barista_coffee_shop_data_sticky); ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-4 align-self-center">
                <div class="navbar-brand text-center text-md-left">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="site-logo"><?php the_custom_logo(); ?></div>
                    <?php endif; ?>
                    <?php $barista_coffee_shop_blog_info = get_bloginfo( 'name' ); ?>
                        <?php if ( ! empty( $barista_coffee_shop_blog_info ) ) : ?>
                            <?php if ( is_front_page() && is_home() ) : ?>
                              <?php if( get_theme_mod('barista_coffee_shop_logo_title',true) != ''){ ?>
                                <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                              <?php } ?>
                            <?php else : ?>
                              <?php if( get_theme_mod('barista_coffee_shop_logo_title',true) != ''){ ?>
                                <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                                  <?php } ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                            $barista_coffee_shop_description = get_bloginfo( 'description', 'display' );
                            if ( $barista_coffee_shop_description || is_customize_preview() ) :
                        ?>
                        <?php if( get_theme_mod('barista_coffee_shop_theme_description',false) != ''){ ?>
                        <p class="site-description"><?php echo esc_html($barista_coffee_shop_description); ?></p>
                      <?php } ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 align-self-center phone-box">
                <?php if(get_theme_mod('barista_coffee_shop_phone_text') != '' || get_theme_mod('barista_coffee_shop_phone') != ''){ ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 align-self-center">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 align-self-center">
                            <p class="mb-0"><?php echo esc_html(get_theme_mod('barista_coffee_shop_phone_text','')); ?></p>
                            <h6><?php echo esc_html(get_theme_mod('barista_coffee_shop_phone','')); ?></h6>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="col-lg-6 col-md-2 col-sm-2 col-8 align-self-center">
                <?php get_template_part('template-parts/navigation/nav'); ?>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2 col-4  align-self-center p-0">
                <div class="cart_no">
                    <?php if(class_exists('woocommerce')){ ?>
                        <?php global $woocommerce; ?>
                        <a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e( 'shopping cart','barista-coffee-shop' ); ?>"><i class="fas fa-shopping-bag"></i><span class="cart-total"><?php echo wp_kses_data( WC()->cart->get_cart_total() ); ?></span></a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
