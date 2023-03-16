<?php
/**
 * Register custom post type and taxonomies.
 *
 * @package wpcode
 */

add_action( 'init', 'wpcode_register_post_type', - 5 );
add_action( 'init', 'wpcode_register_taxonomies', - 5 );
add_filter( 'update_post_term_count_statuses', 'wpcode_taxonomies_count_drafts', 10, 2 );

/**
 * Register the post type for snippets.
 *
 * @return void
 */
function wpcode_register_post_type() {
	register_post_type(
		'wpcode',
		array(
			'public'   => false,
			'show_ui'  => false,
		)
	);
}

/**
 * Register the custom taxonomies used for snippets.
 *
 * @return void
 */
function wpcode_register_taxonomies() {
	register_taxonomy(
		'wpcode_type',
		'wpcode',
		array(
			'public' => false,
		)
	);
	register_taxonomy(
		'wpcode_location',
		'wpcode',
		array(
			'public' => false,
		)
	);
	register_taxonomy(
		'wpcode_tags',
		'wpcode',
		array(
			'public' => false,
		)
	);
}

/**
 * Count draft (inactive) snippets as part of our custom taxonomies count.
 *
 * @param array       $statuses The statuses to include in the count.
 * @param WP_Taxonomy $taxonomy The taxonomy object.
 *
 * @return array
 */
function wpcode_taxonomies_count_drafts( $statuses, $taxonomy ) {
	$taxonomies = array(
		'wpcode_type',
		'wpcode_location',
		'wpcode_tags',
	);
	if ( in_array( $taxonomy->name, $taxonomies, true ) ) {
		$statuses[] = 'draft';
	}

	return $statuses;
}
