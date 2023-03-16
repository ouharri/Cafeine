<?php

namespace WeglotWP\Actions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Button_Service_Weglot;

/**
 * Registe widget weglot
 *
 * @since 2.0
 */
class Register_Widget_Weglot implements Hooks_Interface_Weglot {

	/**
	 * @return void
	 * @see HooksInterface
	 */
	public function hooks() {
		add_action( 'widgets_init', array( $this, 'register_a_widget_weglot' ) );
		add_action( 'init', array( $this, 'weglot_widget_block' ) );
		add_action( 'init', array( $this, 'weglot_menu_block' ) );
		// Hook the enqueue functions into the editor.
		add_action( 'enqueue_block_editor_assets', array( $this, 'my_block_plugin_editor_scripts' ) );
	}

	/**
	 * @return string
	 * @since 2.0
	 */
	public function register_a_widget_weglot() {
		register_widget( 'WeglotWP\Widgets\Widget_Selector_Weglot' );
	}


	/**
	 * Enqueue block JavaScript and CSS for the editor
	 */
	public function my_block_plugin_editor_scripts() {
		// Enqueue block editor styles.
		wp_enqueue_style( 'weglot-editor-css', WEGLOT_URL_DIST . '/css/front-css.css', array( 'wp-edit-blocks' ), WEGLOT_VERSION );
	}

	/**
	 * @return string
	 * @since 2.0
	 */
	public function weglot_widget_block_render_callback( $block_attributes, $content ) {
		$type_block = $block_attributes['type'];
		/** @var $button_service Button_Service_Weglot */
		$button_service = weglot_get_service( 'Button_Service_Weglot' );

		$button = $button_service->get_html( 'weglot-widget weglot-widget-block' );

		if ( 'widget' === $type_block ) {
			$button = $button_service->get_html( 'weglot-widget weglot-widget-block' );
			$button = str_replace('name="menu" ', 'name="menu" value=""', $button);
			$button = str_replace('data-wg-notranslate=""', '', $button);
		} elseif ( 'menu' === $type_block ) {
			$button = $button_service->get_html( 'weglot-menu weglot-menu-block' );
			$button = str_replace('name="menu" ', 'name="menu" value=""', $button);
			$button = str_replace('data-wg-notranslate=""', '', $button);
		}

		return $button;
	}

	/**
	 * @return void
	 * @since 2.0
	 */
	public function weglot_widget_block() {
		register_block_type(
			WEGLOT_DIR . '/blocks/weglot-widget/build',
			array(
				'api_version'     => 2,
				'attributes'      => array(
					'type' => array(
						'default' => 'widget',
						'type'    => 'string',
					),
				),
				'render_callback' => array( $this, 'weglot_widget_block_render_callback' ),
			)
		);
	}

	/**
	 * @return void
	 * @since 2.0
	 */
	public function weglot_menu_block() {
		register_block_type(
			WEGLOT_DIR . '/blocks/weglot-menu/build',
			array(
				'api_version'     => 2,
				'attributes'      => array(
					'type' => array(
						'default' => 'menu',
						'type'    => 'string',
					),
				),
				'render_callback' => array( $this, 'weglot_widget_block_render_callback' ),
			)
		);
	}
}
