<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Blossom_Fashion
 */

get_header(); ?>

	<div class="img-holder">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/error.jpg' ); ?>" alt="<?php esc_attr_e( '404 Not Found', 'blossom-fashion' ); ?>">
    </div>
	
    <div class="text-holder">
		<h2><?php esc_html_e( 'Ooops!', 'blossom-fashion' ); ?></h2>
		<?php echo wpautop( esc_html__( 'The page you are looking for may have been moved, deleted, or possibly never existed.', 'blossom-fashion' ) ); ?>
        
		<a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-home"><?php esc_html_e( 'Return Home', 'blossom-fashion' ); ?></a>
	</div>
	
    <?php 
        get_search_form(); 
        blossom_fashion_latest_posts();
    ?>
	
<?php
get_footer();