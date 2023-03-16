<?php
/**
 * Handle the generic WPCode shortcode.
 *
 * @package WPCode
 */

add_shortcode( 'wpcode', 'wpcode_shortcode_handler' );

/**
 * Generic handler for the shortcode.
 *
 * @param array $args The shortcode attributes.
 *
 * @return string
 */
function wpcode_shortcode_handler( $args ) {
	$atts = wp_parse_args(
		$args,
		array(
			'id' => 0,
		)
	);

	if ( 0 === $atts['id'] ) {
		return '';
	}

	$snippet = new WPCode_Snippet( absint( $atts['id'] ) );

	if ( ! $snippet->is_active() ) {
		return '';
	}

	// Let's check that conditional logic rules are met.
	if ( ! wpcode()->conditional_logic->are_snippet_rules_met( $snippet ) && apply_filters( 'wpcode_shortcode_use_conditional_logic', true ) ) {
		return '';
	}

	$shortcode_location = apply_filters( 'wpcode_get_snippets_for_location', array( $snippet ), 'shortcode' );

	if ( empty( $shortcode_location ) ) {
		return '';
	}

	return wpcode()->execute->get_snippet_output( $snippet );
}
