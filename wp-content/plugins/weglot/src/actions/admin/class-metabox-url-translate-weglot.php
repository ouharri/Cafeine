<?php

namespace WeglotWP\Actions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Helpers\Helper_Post_Meta_Weglot;

/**
 *
 * @since 2.1.0
 */
class Metabox_Url_Translate_Weglot implements Hooks_Interface_Weglot {

	/**
	 * @since 2.1.0
	 */
	public function __construct() {
	}

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.1.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes_url_translate' ) );
	}

	/**
	 * @since 2.1.0
	 * @return void
	 */
	public function add_meta_boxes_url_translate() {
		global $post;
		if ( ! $post ) {
			return;
		}

		$post_type = get_post_type();

		$exclude_post_type_metabox = apply_filters(
			'weglot_url_translate_metabox_post_type_exclude',
			array(
				'attachment',
				'shop_order',
				'shop_coupon',
			)
		);

		if ( in_array( $post_type, $exclude_post_type_metabox ) ) { //phpcs:ignore
			return;
		}

		add_meta_box( 'weglot-url-translate', __( 'Weglot URL Translate', 'weglot' ), array( $this, 'weglot_url_translate_box' ) );
	}

	/**
	 * @since 2.1.0
	 * @return void
	 * @param mixed $post
	 */
	public function weglot_url_translate_box( $post ) {
		if ( ! $post ) {
			return;
		}
		echo sprintf( esc_html__( 'The translation URL feature is now available in your Weglot account (Requires Advanced plan minimum) : %1$sTranslate URL slugs%2$s.', 'weglot' ), '<a target="_blank" href="https://dashboard.weglot.com/translations/slugs/">', '</a>' );
	}
}
