<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_filter( 'wt_cli_third_party_scripts', 'wt_cli_twitter_feed_script' );
function wt_cli_twitter_feed_script( $tags ) {
	$tags['twitter-feed'] = array(
		'plugins/custom-twitter-feeds/js/ctf-scripts.js',
		'plugins/custom-twitter-feeds/js/ctf-scripts.min.js',
		'plugins/custom-twitter-feeds-pro/js/ctf-scripts.js',
		'plugins/custom-twitter-feeds-pro/js/ctf-scripts.min.js',
	);
	return $tags;
}
