<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_filter( 'wt_cli_third_party_scripts', 'wt_cli_google_analytics_wordpress_script' );
function wt_cli_google_analytics_wordpress_script( $tags ) {
	$tags['google-analytics-for-wordpress'] = array(
		'mi_track_user',
		'www.google-analytics.com/analytics.js',
		'google-analytics-for-wordpress/assets/js/',
	);
	return $tags;
}
