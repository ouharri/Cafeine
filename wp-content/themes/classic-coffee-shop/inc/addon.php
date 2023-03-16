<?php
/*
 * @package Classic Coffee Shop
 */

function classic_coffee_shop_admin_enqueue_scripts() {
	wp_enqueue_style( 'classic-coffee-shop-admin-style', esc_url( get_template_directory_uri() ).'/css/addon.css' );
}
add_action( 'admin_enqueue_scripts', 'classic_coffee_shop_admin_enqueue_scripts' );

add_action('after_switch_theme', 'classic_coffee_shop_options');

function classic_coffee_shop_options () {
	global $pagenow;
	if( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) && current_user_can( 'manage_options' ) ) {
		wp_redirect( admin_url( 'themes.php?page=classic-coffee-shop' ) );
		exit;
	}
}

if ( ! defined( 'CLASSIC_COFFEE_SHOP_SUPPORT' ) ) {
define('CLASSIC_COFFEE_SHOP_SUPPORT',__('https://wordpress.org/support/theme/classic-coffee-shop','classic-coffee-shop'));
}
if ( ! defined( 'CLASSIC_COFFEE_SHOP_REVIEW' ) ) {
define('CLASSIC_COFFEE_SHOP_REVIEW',__('https://wordpress.org/support/theme/classic-coffee-shop/reviews/#new-post','classic-coffee-shop'));
}
if ( ! defined( 'CLASSIC_COFFEE_SHOP_PRO_DEMO' ) ) {
define('CLASSIC_COFFEE_SHOP_PRO_DEMO',__('https://theclassictemplates.com/demo/classic-coffee-shop/','classic-coffee-shop'));
}
if ( ! defined( 'CLASSIC_COFFEE_SHOP_THEME_PAGE' ) ) {
define('CLASSIC_COFFEE_SHOP_THEME_PAGE',__('https://www.theclassictemplates.com/themes/','classic-coffee-shop'));
}
if ( ! defined( 'CLASSIC_COFFEE_SHOP_PREMIUM_PAGE' ) ) {
define('CLASSIC_COFFEE_SHOP_PREMIUM_PAGE',__('https://www.theclassictemplates.com/wp-themes/cafe-wordpress-theme/','classic-coffee-shop'));
}
// Footer Link
define('CLASSIC_COFFEE_SHOP_FOOTER_LINK',__('https://theclassictemplates.com/themes/free-coffee-shop-wordpress-theme/','classic-coffee-shop'));

function classic_coffee_shop_theme_info_menu_link() {

	$theme = wp_get_theme();
	add_theme_page(
		sprintf( esc_html__( 'Welcome to %1$s %2$s', 'classic-coffee-shop' ), $theme->display( 'Name' ), $theme->display( 'Version' ) ),
		esc_html__( 'Theme Info', 'classic-coffee-shop' ),'edit_theme_options','classic-coffee-shop','classic_coffee_shop_theme_info_page'
	);
}
add_action( 'admin_menu', 'classic_coffee_shop_theme_info_menu_link' );

function classic_coffee_shop_theme_info_page() {

	$theme = wp_get_theme();
	?>
<div class="wrap theme-info-wrap">
	<h1><?php printf( esc_html__( 'Welcome to %1$s %2$s', 'classic-coffee-shop' ), esc_html($theme->display( 'Name', 'classic-coffee-shop'  )),esc_html($theme->display( 'Version', 'classic-coffee-shop' ))); ?>
	</h1>
	<p class="theme-description">
	<?php esc_html_e( 'Do you want to configure this theme? Look no further, our easy-to-follow theme documentation will walk you through it.', 'classic-coffee-shop' ); ?>
	</p>
	<hr>
	<div class="important-links clearfix">
		<p><strong><?php esc_html_e( 'Theme Links', 'classic-coffee-shop' ); ?>:</strong>
			<a href="<?php echo esc_url( CLASSIC_COFFEE_SHOP_THEME_PAGE ); ?>" target="_blank"><?php esc_html_e( 'Theme Page', 'classic-coffee-shop' ); ?></a>
			<a href="<?php echo esc_url( CLASSIC_COFFEE_SHOP_SUPPORT ); ?>" target="_blank"><?php esc_html_e( 'Contact Us', 'classic-coffee-shop' ); ?></a>
			<a href="<?php echo esc_url( CLASSIC_COFFEE_SHOP_REVIEW ); ?>" target="_blank"><?php esc_html_e( 'Rate This Theme', 'classic-coffee-shop' ); ?></a>
			<a href="<?php echo esc_url( CLASSIC_COFFEE_SHOP_PRO_DEMO ); ?>" target="_blank"><?php esc_html_e( 'Premium Demo', 'classic-coffee-shop' ); ?></a>
			<a href="<?php echo esc_url( CLASSIC_COFFEE_SHOP_PREMIUM_PAGE ); ?>" target="_blank"><?php esc_html_e( 'Go To Premium', 'classic-coffee-shop' ); ?></a>
		</p>
	</div>
	<hr>
	<div id="getting-started">
		<h3><?php printf( esc_html__( 'Getting started with %s', 'classic-coffee-shop' ), 
		esc_html($theme->display( 'Name', 'classic-coffee-shop' ))); ?></h3>
		<div class="columns-wrapper clearfix">
			<div class="column column-half clearfix">
				<div class="section">
					<h4><?php esc_html_e( 'Theme Description', 'classic-coffee-shop' ); ?></h4>
					<div class="theme-description-1"><?php echo esc_html($theme->display( 'Description' )); ?></div>
				</div>
			</div>
			<div class="column column-half clearfix">
				<img src="<?php echo esc_url( $theme->get_screenshot() ); ?>" />
				<div class="section">
					<h4><?php esc_html_e( 'Theme Options', 'classic-coffee-shop' ); ?></h4>
					<p class="about">
					<?php printf( esc_html__( '%s makes use of the Customizer for all theme settings. Click on "Customize Theme" to open the Customizer now.', 'classic-coffee-shop' ),esc_html($theme->display( 'Name', 'classic-coffee-shop' ))); ?></p>
					<p>
					<a href="<?php echo wp_customize_url(); ?>" class="button button-primary"><?php esc_html_e( 'Customize Theme', 'classic-coffee-shop' ); ?></a>
					<a href="<?php echo esc_url( CLASSIC_COFFEE_SHOP_PREMIUM_PAGE ); ?>" target="_blank" class="button button-secondary premium-btn"><?php esc_html_e( 'Checkout Premium', 'classic-coffee-shop' ); ?></a></p>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div id="theme-author">
	  <p><?php
		printf( esc_html__( '%1$s is proudly brought to you by %2$s. If you like this theme, %3$s :)', 'classic-coffee-shop' ),
			esc_html($theme->display( 'Name', 'classic-coffee-shop' )),
			'<a target="_blank" href="' . esc_url( 'https://www.theclassictemplates.com/', 'classic-coffee-shop' ) . '">classictemplate</a>',
			'<a target="_blank" href="' . esc_url( CLASSIC_COFFEE_SHOP_REVIEW ) . '" title="' . esc_attr__( 'Rate it', 'classic-coffee-shop' ) . '">' . esc_html_x( 'rate it', 'If you like this theme, rate it', 'classic-coffee-shop' ) . '</a>'
		)
		?></p>
	</div>
</div>
<?php
}
