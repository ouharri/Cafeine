<?php
/**
 * Barista Coffee Shop functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Barista Coffee Shop
 */

include get_theme_file_path( 'vendor/wptrt/autoload/src/Barista_Coffee_Shop_Loader.php' );

$Barista_Coffee_Shop_Loader = new \WPTRT\Autoload\Barista_Coffee_Shop_Loader();

$Barista_Coffee_Shop_Loader->barista_coffee_shop_add( 'WPTRT\\Customize\\Section', get_theme_file_path( 'vendor/wptrt/customize-section-button/src' ) );

$Barista_Coffee_Shop_Loader->barista_coffee_shop_register();

if ( ! function_exists( 'barista_coffee_shop_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function barista_coffee_shop_setup() {

		add_theme_support( 'woocommerce' );
		add_theme_support( "responsive-embeds" );
		add_theme_support( "align-wide" );
		add_theme_support( "wp-block-styles" );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

        add_image_size('barista-coffee-shop-featured-header-image', 2000, 660, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary','barista-coffee-shop' ),
	        'footer'=> esc_html__( 'Footer Menu','barista-coffee-shop' ),
        ) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'barista_coffee_shop_custom_background_args', array(
			'default-color' => 'f7ebe5',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 50,
			'width'       => 50,
			'flex-width'  => true,
		) );

		add_editor_style( array( '/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'barista_coffee_shop_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function barista_coffee_shop_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'barista_coffee_shop_content_width', 1170 );
}
add_action( 'after_setup_theme', 'barista_coffee_shop_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function barista_coffee_shop_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'barista-coffee-shop' ),
		'id'            => 'sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'barista-coffee-shop' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );
}
add_action( 'widgets_init', 'barista_coffee_shop_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function barista_coffee_shop_scripts() {

	require_once get_theme_file_path( 'inc/wptt-webfont-loader.php' );

	wp_enqueue_style(
		'outfit',
		wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap' ),
		array(),
		'1.0'
	);

	wp_enqueue_style(
		'yesteryear',
		wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Yesteryear&display=swap' ),
		array(),
		'1.0'
	);

	wp_enqueue_style( 'barista-coffee-shop-block-editor-style', get_theme_file_uri('/assets/css/block-editor-style.css') );

	// load bootstrap css
    wp_enqueue_style( 'bootstrap-css',(get_template_directory_uri()) . '/assets/css/bootstrap.css');

    wp_enqueue_style( 'owl.carousel-css',(get_template_directory_uri()) . '/assets/css/owl.carousel.css');

	wp_enqueue_style( 'barista-coffee-shop-style', get_stylesheet_uri() );

	// fontawesome
	wp_enqueue_style( 'fontawesome-style',(get_template_directory_uri()).'/assets/css/fontawesome/css/all.css' );

    wp_enqueue_script('barista-coffee-shop-theme-js',(get_template_directory_uri()) . '/assets/js/theme-script.js', array('jquery'), '', true );

    wp_enqueue_script('owl.carousel-js',(get_template_directory_uri()) . '/assets/js/owl.carousel.js', array('jquery'), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'barista_coffee_shop_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/*dropdown page sanitization*/
function barista_coffee_shop_sanitize_dropdown_pages( $page_id, $setting ) {
	$page_id = absint( $page_id );
	return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
}

/*checkbox sanitization*/
function barista_coffee_shop_sanitize_checkbox( $input ) {
	// Boolean check
	return ( ( isset( $input ) && true == $input ) ? true : false );
}

function barista_coffee_shop_sanitize_phone_number( $phone ) {
	return preg_replace( '/[^\d+]/', '', $phone );
}

function barista_coffee_shop_sanitize_select( $input, $setting ){
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

//Float
function barista_coffee_shop_sanitize_float( $input ) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Get CSS
 */

function barista_coffee_shop_getpage_css($hook) {
	if ( 'appearance_page_barista-coffee-shop-info' != $hook ) {
		return;
	}
	wp_enqueue_style( 'barista-coffee-shop-demo-style', get_template_directory_uri() . '/assets/css/demo.css' );
}
add_action( 'admin_enqueue_scripts', 'barista_coffee_shop_getpage_css' );

add_action('after_switch_theme', 'barista_coffee_shop_setup_options');

function barista_coffee_shop_setup_options () {
	wp_redirect( admin_url() . 'themes.php?page=barista-coffee-shop-info.php' );
}

if ( ! defined( 'BARISTA_COFFEE_SHOP_CONTACT_SUPPORT' ) ) {
define('BARISTA_COFFEE_SHOP_CONTACT_SUPPORT',__('https://wordpress.org/support/theme/barista-coffee-shop/','barista-coffee-shop'));
}
if ( ! defined( 'BARISTA_COFFEE_SHOP_REVIEW' ) ) {
define('BARISTA_COFFEE_SHOP_REVIEW',__('https://wordpress.org/support/theme/barista-coffee-shop/reviews/','barista-coffee-shop'));
}
if ( ! defined( 'BARISTA_COFFEE_SHOP_LIVE_DEMO' ) ) {
define('BARISTA_COFFEE_SHOP_LIVE_DEMO',__('https://www.themagnifico.net/demo/barista-coffee-shop/','barista-coffee-shop'));
}
if ( ! defined( 'BARISTA_COFFEE_SHOP_GET_PREMIUM_PRO' ) ) {
define('BARISTA_COFFEE_SHOP_GET_PREMIUM_PRO',__('https://www.themagnifico.net/themes/coffee-wordpress-theme/','barista-coffee-shop'));
}
if ( ! defined( 'BARISTA_COFFEE_SHOP_PRO_DOC' ) ) {
define('BARISTA_COFFEE_SHOP_PRO_DOC',__('https://www.themagnifico.net/eard/wathiqa/barista-coffee-shop-pro-doc/','barista-coffee-shop'));
}

add_action('admin_menu', 'barista_coffee_shop_themepage');
function barista_coffee_shop_themepage(){
	$theme_info = add_theme_page( __('Theme Options','barista-coffee-shop'), __('Theme Options','barista-coffee-shop'), 'manage_options', 'barista-coffee-shop-info.php', 'barista_coffee_shop_info_page' );
}

function barista_coffee_shop_info_page() {
	$user = wp_get_current_user();
	$theme = wp_get_theme();
	?>
	<div class="wrap about-wrap barista-coffee-shop-add-css">
		<div>
			<h1>
				<?php esc_html_e('Welcome To ','barista-coffee-shop'); ?><?php echo esc_html( $theme ); ?>
			</h1>
			<div class="feature-section three-col">
				<div class="col">
					<div class="widgets-holder-wrap">
						<h3><?php esc_html_e("Contact Support", "barista-coffee-shop"); ?></h3>
						<p><?php esc_html_e("Thank you for trying Barista Coffee Shop , feel free to contact us for any support regarding our theme.", "barista-coffee-shop"); ?></p>
						<p><a target="_blank" href="<?php echo esc_url( BARISTA_COFFEE_SHOP_CONTACT_SUPPORT ); ?>" class="button button-primary get">
							<?php esc_html_e("Contact Support", "barista-coffee-shop"); ?>
						</a></p>
					</div>
				</div>
				<div class="col">
					<div class="widgets-holder-wrap">
						<h3><?php esc_html_e("Checkout Premium", "barista-coffee-shop"); ?></h3>
						<p><?php esc_html_e("Our premium theme comes with extended features like demo content import , responsive layouts etc.", "barista-coffee-shop"); ?></p>
						<p><a target="_blank" href="<?php echo esc_url( BARISTA_COFFEE_SHOP_GET_PREMIUM_PRO ); ?>" class="button button-primary get">
							<?php esc_html_e("Get Premium", "barista-coffee-shop"); ?>
						</a></p>
					</div>
				</div>
				<div class="col">
					<div class="widgets-holder-wrap">
						<h3><?php esc_html_e("Review", "barista-coffee-shop"); ?></h3>
						<p><?php esc_html_e("If You love Barista Coffee Shop theme then we would appreciate your review about our theme.", "barista-coffee-shop"); ?></p>
						<p><a target="_blank" href="<?php echo esc_url( BARISTA_COFFEE_SHOP_REVIEW ); ?>" class="button button-primary get">
							<?php esc_html_e("Review", "barista-coffee-shop"); ?>
						</a></p>
					</div>
				</div>
			</div>
		</div>
		<hr>

		<h2><?php esc_html_e("Free Vs Premium","barista-coffee-shop"); ?></h2>
		<div class="barista-coffee-shop-button-container">
			<a target="_blank" href="<?php echo esc_url( BARISTA_COFFEE_SHOP_PRO_DOC ); ?>" class="button button-primary get">
				<?php esc_html_e("Checkout Documentation", "barista-coffee-shop"); ?>
			</a>
			<a target="_blank" href="<?php echo esc_url( BARISTA_COFFEE_SHOP_LIVE_DEMO ); ?>" class="button button-primary get">
				<?php esc_html_e("View Theme Demo", "barista-coffee-shop"); ?>
			</a>
		</div>

		<table class="wp-list-table widefat">
			<thead class="table-book">
				<tr>
					<th><strong><?php esc_html_e("Theme Feature", "barista-coffee-shop"); ?></strong></th>
					<th><strong><?php esc_html_e("Basic Version", "barista-coffee-shop"); ?></strong></th>
					<th><strong><?php esc_html_e("Premium Version", "barista-coffee-shop"); ?></strong></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><?php esc_html_e("Header Background Color", "barista-coffee-shop"); ?></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Custom Navigation Logo Or Text", "barista-coffee-shop"); ?></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Hide Logo Text", "barista-coffee-shop"); ?></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>

				<tr>
					<td><?php esc_html_e("Premium Support", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Fully SEO Optimized", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Recent Posts Widget", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>

				<tr>
					<td><?php esc_html_e("Easy Google Fonts", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Pagespeed Plugin", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Only Show Header Image On Front Page", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Show Header Everywhere", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Custom Text On Header Image", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Full Width (Hide Sidebar)", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Only Show Upper Widgets On Front Page", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Replace Copyright Text", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Customize Upper Widgets Colors", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Customize Navigation Color", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Customize Post/Page Color", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Customize Blog Feed Color", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Customize Footer Color", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Customize Sidebar Color", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Customize Background Color", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
				<tr>
					<td><?php esc_html_e("Importable Demo Content	", "barista-coffee-shop"); ?></td>
					<td><span class="cross"><span class="dashicons dashicons-dismiss"></span></span></td>
					<td><span class="tick"><span class="dashicons dashicons-yes-alt"></span></span></td>
				</tr>
			</tbody>
		</table>
		<div class="barista-coffee-shop-button-container">
			<a target="_blank" href="<?php echo esc_url( BARISTA_COFFEE_SHOP_GET_PREMIUM_PRO ); ?>" class="button button-primary get">
				<?php esc_html_e("Go Premium", "barista-coffee-shop"); ?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Enqueue S Header.
 */
function barista_coffee_shop_sticky_header() {

	$barista_coffee_shop_sticky_header = get_theme_mod('barista_coffee_shop_sticky_header');

	$barista_coffee_shop_custom_style= "";

	if($barista_coffee_shop_sticky_header != true){

		$barista_coffee_shop_custom_style .='.stick_header{';

			$barista_coffee_shop_custom_style .='position: static;';

		$barista_coffee_shop_custom_style .='}';
	}

	wp_add_inline_style( 'barista-coffee-shop-style',$barista_coffee_shop_custom_style );

}
add_action( 'wp_enqueue_scripts', 'barista_coffee_shop_sticky_header' );

// Change number or products per row to 3
add_filter('loop_shop_columns', 'barista_coffee_shop_loop_columns');
if (!function_exists('barista_coffee_shop_loop_columns')) {
	function barista_coffee_shop_loop_columns() {
		$columns = get_theme_mod( 'barista_coffee_shop_products_per_row', 3 );
		return $columns; // 3 products per row
	}
}

//Change number of products that are displayed per page (shop page)
add_filter( 'loop_shop_per_page', 'barista_coffee_shop_shop_per_page', 9 );
function barista_coffee_shop_shop_per_page( $cols ) {
  	$cols = get_theme_mod( 'barista_coffee_shop_product_per_page', 9 );
	return $cols;
}
