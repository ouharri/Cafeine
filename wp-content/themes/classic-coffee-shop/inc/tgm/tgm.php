<?php
require get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';
/**
 * Recommended plugins.
 */
function classic_coffee_shop_register_recommended_plugins() {
	$plugins = array(
		array(
			'name'             => __( 'WooCommerce', 'classic-coffee-shop' ),
			'slug'             => 'woocommerce',
			'required'         => false,
			'force_activation' => false,
		),
	);
	$config = array();
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'classic_coffee_shop_register_recommended_plugins' );