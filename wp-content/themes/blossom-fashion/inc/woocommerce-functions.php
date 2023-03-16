<?php
/**
 * Blossom Fashion woocommerce hooks and functions.
 *
 * @link https://docs.woothemes.com/document/third-party-custom-theme-compatibility/
 *
 * @package Blossom_Fashion
 */

/**
 * Woocommerce related hooks
*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar', 10 );

add_action( 'woocommerce_before_main_content', 'blossom_fashion_wc_wrapper', 10 );
add_action( 'woocommerce_after_main_content',  'blossom_fashion_wc_wrapper_end', 10 );
add_action( 'after_setup_theme',               'blossom_fashion_woocommerce_support');
add_action( 'blossom_fashion_wo_sidebar',      'blossom_fashion_wc_sidebar_cb' );
add_action( 'widgets_init',                    'blossom_fashion_wc_widgets_init' );

/**
 * Declare Woocommerce Support
*/
function blossom_fashion_woocommerce_support() {
    global $woocommerce;
    
    add_theme_support( 'woocommerce', array(
        'gallery_thumbnail_image_width' => 300,
    ) );
    
    if( version_compare( $woocommerce->version, '3.0', ">=" ) ) {
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}

/**
 * Woocommerce Sidebar
*/
function blossom_fashion_wc_widgets_init(){
    register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'blossom-fashion' ),
		'id'            => 'shop-sidebar',
		'description'   => esc_html__( 'Sidebar displaying only in woocommerce pages.', 'blossom-fashion' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );    
}

/**
 * Before Content
 * Wraps all WooCommerce content in wrappers which match the theme markup
*/
function blossom_fashion_wc_wrapper(){    
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
    <?php
}

/**
 * After Content
 * Closes the wrapping divs
*/
function blossom_fashion_wc_wrapper_end(){
    ?>
        </main>
    </div>
    <?php
    if( is_active_sidebar( 'shop-sidebar' ) );
    do_action( 'blossom_fashion_wo_sidebar' );
}

/**
 * Callback function for Shop sidebar
*/
function blossom_fashion_wc_sidebar_cb(){
    if( is_active_sidebar( 'shop-sidebar' ) ){
        echo '<aside id="secondary" class="widget-area" role="complementary">';
        dynamic_sidebar( 'shop-sidebar' );
        echo '</aside>'; 
    }
}

/**
 * Removes the "shop" title on the main shop page
*/
add_filter( 'woocommerce_show_page_title' , '__return_false' );

if( ! function_exists( 'blossom_fashion_wc_cart_count' ) ) :
/**
 * Woocommerce Cart Count
 * @link https://isabelcastillo.com/woocommerce-cart-icon-count-theme-header 
*/
function blossom_fashion_wc_cart_count(){
    $count = WC()->cart->cart_contents_count; ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart" title="<?php esc_attr_e( 'View your shopping cart', 'blossom-fashion' ); ?>">
        <i class="fa fa-shopping-cart"></i>
        <span class="number"><?php echo esc_html( $count ); ?></span>
    </a>
    <?php
}
endif;

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 * @link https://isabelcastillo.com/woocommerce-cart-icon-count-theme-header
 */
function blossom_fashion_add_to_cart_fragment( $fragments ){
    ob_start();
    $count = WC()->cart->cart_contents_count; ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart" title="<?php esc_attr_e( 'View your shopping cart', 'blossom-fashion' ); ?>">
        <i class="fa fa-shopping-cart"></i>
        <span class="number"><?php echo esc_html( $count ); ?></span>
    </a>
    <?php
 
    $fragments['a.cart'] = ob_get_clean();
     
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'blossom_fashion_add_to_cart_fragment' );

/**
 * Ajax Callback for adding product in cart
*/
function blossom_fashion_add_cart_ajax() {
	global $woocommerce;
    
    $product_id = $_POST['product_id'];

	WC()->cart->add_to_cart( $product_id, 1 );
	$count = WC()->cart->cart_contents_count;
	$cart_url = $woocommerce->cart->get_cart_url(); 
    
    ?>
    <a href="<?php echo esc_url( $cart_url ); ?>" rel="bookmark" class="btn-add-to-cart"><?php esc_html_e( 'View Cart', 'blossom-fashion' ); ?></a>
    <input type="hidden" id="<?php echo esc_attr( 'cart-' . $product_id ); ?>" value="<?php echo esc_attr( $count ); ?>" />
    <?php 
    die();
}

add_action( 'wp_ajax_blossom_fashion_add_cart_single', 'blossom_fashion_add_cart_ajax' );
add_action( 'wp_ajax_nopriv_blossom_fashion_add_cart_single', 'blossom_fashion_add_cart_ajax' );