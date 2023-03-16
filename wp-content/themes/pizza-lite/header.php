<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package Pizza Lite
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="header">	
<div class="head-info-area">
        	<div class="center">
            	<?php $contact_add = get_theme_mod('contact_add');
				if (!empty($contact_add)) { ?>
            	<div class="left">
                <span class="phntp"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/location.png"> <?php echo wp_kses_post($contact_add); ?></span></div>   
                <?php } ?>   
                <?php $contact_no = get_theme_mod('contact_no'); 
			  	if (!empty($contact_no)) { ?>          				
                <div class="right">
                <span class="suptp"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/delivery-icon.png"> <?php echo esc_html_e('Free Delivery:','pizza-lite');?> <strong><?php echo esc_html($contact_no); ?></strong></span></div>
                 <?php } ?>               
                <div class="clear"></div>                
            </div>
        <div class="zig-zag-bottom"></div>
        </div>
  <div class="clear"></div>
  <div class="container">
  <div class="clear"></div>      
    <div class="logo">
		<?php pizza_lite_the_custom_logo(); ?>
        <div class="clear"></div>
		<?php	
        $description = get_bloginfo( 'description', 'display' );
        ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <h2 class="site-title"><?php bloginfo('name'); ?></h2>
        <?php if ( $description || is_customize_preview() ) :?>
        <p class="site-description"><?php echo esc_html($description); ?></p>                          
        <?php endif; ?>
        </a>
    </div>
         <div class="toggle"><a class="toggleMenu" href="#" style="display:none;"><?php esc_html_e('Menu','pizza-lite'); ?></a></div> 
        <div class="sitenav">
          <?php wp_nav_menu( array('theme_location' => 'primary') ); ?>         
        </div><!-- .sitenav--> 
        <div class="clear"></div> 
  </div> <!-- container -->
</div><!--.header -->