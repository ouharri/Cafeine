<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_filter( 'wt_cli_third_party_scripts', 'wt_cli_instagram_feed_script' );
function wt_cli_instagram_feed_script( $tags ) {

	$tags['instagram-feed'] = array(

		'plugins/instagram-feed/js/sb-instagram.min.js',
		'plugins/instagram-feed/js/sb-instagram.js',
		'plugins/instagram-feed/js/sbi-scripts.min.js',
		'plugins/instagram-feed/js/sbi-scripts.js',
		'plugins/instagram-feed-pro/js/sb-instagram.min.js',
		'plugins/instagram-feed-pro/js/sb-instagram.js',
		'plugins/instagram-feed-pro/js/sbi-scripts.min.js',
		'plugins/instagram-feed-pro/js/sbi-scripts.js',

	);

	return $tags;
}
