<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_filter( 'wt_cli_third_party_scripts', 'wt_cli_facebook_wordpress_script' );
function wt_cli_facebook_wordpress_script( $tags ) {
	$tags['facebook-for-wordpress'] = 'fbq';
	return $tags;
}
