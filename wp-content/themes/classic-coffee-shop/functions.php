<?php
/**
 * Classic Coffee Shop functions and definitions
 *
 * @package Classic Coffee Shop
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

if ( ! function_exists( 'classic_coffee_shop_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function classic_coffee_shop_setup() {
	global $classic_coffee_shop_content_width;
	if ( ! isset( $classic_coffee_shop_content_width ) )
		$classic_coffee_shop_content_width = 680;

	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'wp-block-styles');
	add_theme_support( 'align-wide' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-header', array(
		'default-text-color' => false,
		'header-text' => false,
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 100,
		'flex-height' => true,
	) );
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'classic-coffee-shop' ),
	) );
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff'
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	add_editor_style( 'editor-style.css' );
}
endif; // classic_coffee_shop_setup
add_action( 'after_setup_theme', 'classic_coffee_shop_setup' );

function classic_coffee_shop_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'classic-coffee-shop' ),
		'description'   => __( 'Appears on blog page sidebar', 'classic-coffee-shop' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'classic_coffee_shop_widgets_init' );

function classic_coffee_shop_scripts() {
	wp_enqueue_style( 'bootstrap-css', esc_url(get_template_directory_uri())."/css/bootstrap.css" );
	wp_enqueue_style( 'owl.carousel-css', esc_url(get_template_directory_uri())."/css/owl.carousel.css" );
	wp_enqueue_style( 'classic-coffee-shop-basic-style', get_stylesheet_uri() );
	wp_style_add_data('classic-coffee-shop-basic-style', 'rtl', 'replace');
	wp_enqueue_style( 'classic-coffee-shop-responsive', esc_url(get_template_directory_uri())."/css/responsive.css" );
	wp_enqueue_style( 'classic-coffee-shop-default', esc_url(get_template_directory_uri())."/css/default.css" );
	wp_enqueue_script( 'owl.carousel-js', esc_url(get_template_directory_uri()). '/js/owl.carousel.js', array('jquery') );
	wp_enqueue_script( 'bootstrap-js', esc_url(get_template_directory_uri()). '/js/bootstrap.js', array('jquery') );
	wp_enqueue_script( 'classic-coffee-shop-theme', esc_url(get_template_directory_uri()) . '/js/theme.js' );
	wp_enqueue_script( 'jquery.superfish', esc_url(get_template_directory_uri()) . '/js/jquery.superfish.js' );
	wp_enqueue_style( 'font-awesome-css', esc_url(get_template_directory_uri())."/css/fontawesome-all.css" );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	require_once get_theme_file_path( 'inc/wptt-webfont-loader.php' );

	require get_parent_theme_file_path( '/inc/color-scheme/custom-color-control.php' );

	wp_add_inline_style( 'classic-coffee-shop-basic-style',$classic_coffee_shop_color_scheme_css );
	wp_add_inline_style( 'classic-coffee-shop-default',$classic_coffee_shop_color_scheme_css );

	$classic_coffee_shop_headings_font = esc_html(get_theme_mod('classic_coffee_shop_headings_fonts'));
	$classic_coffee_shop_body_font = esc_html(get_theme_mod('classic_coffee_shop_body_fonts'));

	if( $classic_coffee_shop_headings_font ) {
		wp_enqueue_style( 'classic-coffee-shop-headings-fonts', '//fonts.googleapis.com/css?family='. $classic_coffee_shop_headings_font );
	} else {
		wp_enqueue_style( 'classic-coffee-shop-emilys', '//fonts.googleapis.com/css2?family=Merienda+One');
	}
	if( $classic_coffee_shop_body_font ) {
		wp_enqueue_style( 'classic-coffee-shop-poppins', '//fonts.googleapis.com/css?family='. $classic_coffee_shop_body_font );
	} else {
		wp_enqueue_style( 'classic-coffee-shop-source-body', '//fonts.googleapis.com/css2?family=Poppins:0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900');
	}
}
add_action( 'wp_enqueue_scripts', 'classic_coffee_shop_scripts' );

/**
 * PRO Button Link
 */
load_template( trailingslashit( get_template_directory() ) . 'inc/button-link/class-button-link.php' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load TGM.
 */
require get_template_directory() . '/inc/tgm/tgm.php';

/**
 * Google Fonts
 */
require get_template_directory() . '/inc/gfonts.php';

/**
 * Theme Info Page.
 */
require get_template_directory() . '/inc/addon.php';


if ( ! function_exists( 'classic_coffee_shop_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 */
function classic_coffee_shop_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;
