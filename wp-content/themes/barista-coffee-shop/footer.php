<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Barista Coffee Shop
 */
?>

<footer id="colophon" class="site-footer border-top">
    <div class="container">
    	<div class="row">
    		<div class="col-lg-5 col-md-5 col-12 align-self-lg-center">
				<?php if ( has_nav_menu( 'footer' ) ): ?>
		            <nav class="navbar footer-menu">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer',
								'container'      => 'div',
								'container_id'   => 'main-nav',
								'menu_id'        => false,
								'depth'          => 1,
							) );
						?>
		            </nav>
				<?php endif ?>
			</div>
	        <div class="site-info col-lg-7 col-md-7 col-12">
	            <div class="footer-menu-left">
	            	<?php if(! get_theme_mod('barista_coffee_shop_footer_text_setting') != ''){ ?>
					    <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'barista-coffee-shop' ) ); ?>">
							<?php
							/* translators: %s: CMS name, i.e. WordPress. */
							printf( esc_html__( 'Proudly powered by %s', 'barista-coffee-shop' ), 'WordPress' );
							?>
					    </a>
					    <span class="sep mr-1"> | </span>
					    <span>
                <a target="_blank" href="<?php echo esc_url( 'https://www.themagnifico.net/themes/free-coffee-shop-wordpress-theme/' ); ?>">
                  <?php
                    /* translators: 1: Theme name,  */
                    printf( esc_html__( ' %1$s ', 'barista-coffee-shop' ),'Coffee Shop WordPress Theme' );
                  ?>
                </a>
					    	<?php
						    	/* translators: 1: Theme author. */
						    	printf( esc_html__( 'by %1$s.', 'barista-coffee-shop' ),'by TheMagnifico'  );
						    ?>
					    </span>
					<?php }?>
					<?php echo esc_html(get_theme_mod('barista_coffee_shop_footer_text_setting','')); ?>
	            </div>
	        </div>
	    </div>
      <?php if(get_theme_mod('barista_coffee_shop_scroll_hide','')){ ?>
	       <a id="button"><?php esc_html_e('TOP','barista-coffee-shop'); ?></a>
      <?php } ?>
    </div>
</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
