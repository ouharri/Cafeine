<?php
/**
 * @package Classic Coffee Shop
 * Setup the WordPress core custom header feature.
 *
 * @uses classic_coffee_shop_header_style()
 */
function classic_coffee_shop_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'classic_coffee_shop_custom_header_args', array(
		'default-text-color'     => 'fff',
		'width'                  => 300,
		'height'                 => 2000,
		'wp-head-callback'       => 'classic_coffee_shop_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'classic_coffee_shop_custom_header_setup' );

if ( ! function_exists( 'classic_coffee_shop_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see classic_coffee_shop_custom_header_setup().
 */
function classic_coffee_shop_header_style() {
	$classic_coffee_shop_header_text_color = get_header_textcolor();
	?>
	<style type="text/css">
	<?php
		//Check if user has defined any header image.
		if ( get_header_image() || get_header_textcolor() ) :
	?>
		.bg-color {
			background: url(<?php echo esc_url( get_header_image() ); ?>) no-repeat;
			background-position: center top;
		}
	<?php endif; ?>





	h1.site-title a , #footer h1.site-title a{
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_sitetitle_color')); ?>;
	}

	span.site-description, #footer span.site-description {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_sitetagline_color')); ?>;
	}

	.social-icons .fa-facebook-f {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_fb_color')); ?>;
	}

	.social-icons .fa-twitter {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_twitter_color')); ?>;
	}

	.social-icons .fa-linkedin-in  {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_linkdin_color')); ?>;
	}

	.social-icons .fa-instagram  {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_instagram_color')); ?>;
	}

	.social-icons .fa-youtube {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_youtube_color')); ?>;
	}

	.bg-color {
		background: <?php echo esc_attr(get_theme_mod('classic_coffee_bg_color')); ?>;

	}

	.social-icons, .logo {
		border-color: <?php echo esc_attr(get_theme_mod('classic_coffee_headerborder_color')); ?>;

	}

	.header::-webkit-scrollbar-thumb {
		background-color: <?php echo esc_attr(get_theme_mod('classic_coffee_headerborder_color')); ?>;
	}


	.main-nav a {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_headermenu_color')); ?>;
	}

	.main-nav a:hover {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_headermenuhover_color')); ?>;
	}

	.main-nav ul ul a {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_headersubmenu_color')); ?>;

	}

	.main-nav ul ul a:hover {
		background: <?php echo esc_attr(get_theme_mod('classic_coffee_headersubmenuhover_color')); ?>;

	}

	.main-nav ul ul {
		background: <?php echo esc_attr(get_theme_mod('classic_coffee_headersubmenbg_color')); ?>;

	}

	.social-icons i:hover {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_headericonhvr_color')); ?>;

	}




	.slider-box h3 {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_title_color')); ?>;

	}

	.slider-box p {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_description_color')); ?>;

	}

	.rsvp_button a {
		border-color: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_buttonborder_color')); ?>;

	}

	.rsvp_button a {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_buttontext_color')); ?>;

	}

	.rsvp_button a:hover {
		background: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_buttonhover_color')); ?>;

	}

	.rsvp_button a:hover {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_buttontexthover_color')); ?>;

	}

	.slidesection {
		background: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_opacity_color')); ?>;

	}

	button.owl-prev span, button.owl-next span {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_slider_arrow_color')); ?>;

	}





	.product-head-box h3 {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_product_heading_color')); ?>;
	}

	.product-head-box p {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_product_subheading_color')); ?>;
	}

	h4.product-text a {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_product_title_color')); ?>;
	}


	.product-image {
		outline-color: <?php echo esc_attr(get_theme_mod('classic_coffee_product_border_color')); ?>;
	}


	.product-image {
		background: <?php echo esc_attr(get_theme_mod('classic_coffee_product_opacity_color')); ?>;
	}




	#footer {
		background: <?php echo esc_attr(get_theme_mod('classic_coffee_footerbg_color')); ?>;
	}
	.copywrap a{
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_footercopyright_color')); ?>;
	}

	#footer .social-icons .fa-facebook-f {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_footerfb_color')); ?>;
	}

	#footer .social-icons .fa-twitter {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_footertwitter_color')); ?>;
	}

	#footer .social-icons .fa-linkedin-in  {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_footerlinkedin_color')); ?>;
	}

	#footer .social-icons .fa-instagram  {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_footerinsta_color')); ?>;
	}

	#footer .social-icons .fa-youtube {
		color: <?php echo esc_attr(get_theme_mod('classic_coffee_footeryoutube_color')); ?>;
	}



	</style>
	<?php
}
endif;
