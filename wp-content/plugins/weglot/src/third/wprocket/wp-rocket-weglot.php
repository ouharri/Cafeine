<?php

function weglot_mandatory_cookie( $cookies ) {
	$cookies[] = 'weglot_wp_rocket_cache';
	return $cookies;
}

function flush_wp_rocket() {

	if ( ! function_exists( 'flush_rocket_htaccess' )
		|| ! function_exists( 'rocket_generate_config_file' ) ) {
		return false;
	}

	// Update WP Rocket .htaccess rules.
	flush_rocket_htaccess();

	// Regenerate WP Rocket config file.
	rocket_generate_config_file();
}
