<?php

namespace WeglotWP\Actions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;
use WeglotWP\Helpers\Helper_API;
use WeglotWP\Helpers\Helper_Flag_Type;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Helpers\Helper_Pages_Weglot;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Option_Service_Weglot;
use WeglotWP\Services\User_Api_Service_Weglot;

/**
 * Enqueue CSS / JS on administration
 *
 * @since 2.0
 *
 */
class Admin_Enqueue_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var User_Api_Service_Weglot
	 */
	private $user_api_services;

	/**
	 * @throws Exception
	 * @since 2.0
	 */
	public function __construct() {
		$this->language_services = weglot_get_service( 'Language_Service_Weglot' );
		$this->option_services   = weglot_get_service( 'Option_Service_Weglot' );
		$this->user_api_services = weglot_get_service( 'User_Api_Service_Weglot' );
	}

	/**
	 * @return void
	 * @since 2.0
	 * @see Hooks_Interface_Weglot
	 *
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'weglot_admin_enqueue_scripts' ) );
		add_action( 'admin_head', array( $this, 'weglot_admin_print_head' ) );
	}


	/**
	 * Register CSS and JS
	 *
	 * @param string $page
	 *
	 * @return void
	 * @throws Exception
	 * @since 2.0
	 * @see admin_enqueue_scripts
	 */
	public function weglot_admin_enqueue_scripts( $page ) {
		if ( ! in_array( $page, array( 'toplevel_page_' . Helper_Pages_Weglot::SETTINGS ), true ) ) {
			return;
		}

		wp_enqueue_script( 'weglot-admin-selectize-js', WEGLOT_URL_DIST . '/selectize.js', array(
			'jquery',
			'jquery-ui-sortable'
		) );

		wp_enqueue_script( 'weglot-admin', WEGLOT_URL_DIST . '/admin-js.js', array( 'weglot-admin-selectize-js' ), WEGLOT_VERSION );

		$user_info = $this->user_api_services->get_user_info();
		$limit     = 10;

		if(isset($user_info['languages_limit'])){
			$limit = $user_info['languages_limit'];
		}
		wp_localize_script(
			'weglot-admin',
			'weglot_languages',
			array(
				'available' => $this->language_services->get_all_languages(),
				'limit'     => $limit,
				'original'  => $this->language_services->get_original_language()->getInternalCode(),
			)
		);

		wp_enqueue_style( 'weglot-admin-css', WEGLOT_URL_DIST . '/css/admin-css.css', array(), WEGLOT_VERSION );

		wp_enqueue_style( 'weglot-css', WEGLOT_URL_DIST . '/css/front-css.css', array(), WEGLOT_VERSION );

		//display new flags
		if ( empty( $this->option_services->get_option( 'flag_css' ) )
			&& strpos( $this->option_services->get_css_custom_inline(), 'background-position' ) == false
			&& strpos( $this->option_services->get_css_custom_inline(), 'background-image' ) == false ) {
				Helper_Flag_Type::get_new_flags(true);
		}

		wp_localize_script(
			'weglot-admin',
			'weglot_css',
			array(
				'inline'   => $this->option_services->get_css_custom_inline(),
				'flag_css' => $this->option_services->get_option( 'flag_css' ),
			)
		);

		/**
		 * Register Code Editor
		 */
		if ( function_exists( 'wp_enqueue_code_editor' ) ) {
			$cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
			wp_localize_script( 'jquery', 'cm_settings', $cm_settings );
			wp_enqueue_script( 'wp-theme-plugin-editor' );
			wp_enqueue_style( 'wp-codemirror' );
		}

	}

	/**
	 * Print in admin head
	 *
	 * @since 3.1.6
	 */
	public function weglot_admin_print_head() {
		?>
		<style type="text/css"> #toplevel_page_weglot-settings .wp-menu-image.svg {
				background-size: 24px auto !important;
			}

			#wp-admin-bar-weglot > .ab-item {
				background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZyBmaWxsPSIjYTBhNWFhIj48cGF0aCBkPSJNMjEuNzM5IDkyLjU2NWw1MS44MjggMTI5LjczMiAyMy42Ni02MC4yNzkgMjQuMTQ0IDYwLjI3OUwxNzMuMiA5Mi41NjVoLTI4LjAwN2wtMjMuODIyIDU4Ljc1LTIzLjkwMi01OC43NS0yMy45MDIgNTguNzUtMjMuOTAyLTU4Ljc1SDIxLjczOXoiLz48cGF0aCBkPSJNMjEwLjAwNiA5Mi43MWMtMTcuODY2IDAtMzMuMTU3IDYuMzU4LTQ1Ljg3MyAxOS4wNzQtMTIuNzE1IDEyLjcxNi0xOC45OTMgMjguMDA2LTE4Ljk5MyA0NS43OTIgMCAxNy44NjcgNi4yNzggMzMuMTU4IDE4Ljk5MyA0NS44NzMgMTIuNzE2IDEyLjcxNiAyOC4wMDcgMTguOTkzIDQ1Ljg3MyAxOC45OTMgMTcuNzg2IDAgMzMuMDc3LTYuMjc3IDQ1Ljc5My0xOC45OTMgMTIuNzE1LTEyLjcxNSAxOS4wNzMtMjguMDA2IDE5LjA3My00NS44NzMgMC00LjUwNy0uNDgzLTguODUyLTEuMjg4LTEyLjk1N2gtNjMuNTc4djI1LjkxNGgzNi42OTljLTIuNzM3IDcuNTY1LTcuNDg1IDEzLjg0My0xNC4wODQgMTguNjcxLTYuNjggNC44My0xNC4yNDUgNy4yNDQtMjIuNjE1IDcuMjQ0LTEwLjc4NCAwLTE5Ljk1OC0zLjc4My0yNy41MjMtMTEuMzQ4LTcuNTY2LTcuNTY1LTExLjM0OC0xNi43NC0xMS4zNDgtMjcuNTI0IDAtMTAuNjIzIDMuNzgyLTE5Ljc5OCAxMS4zNDgtMjcuNDQzIDcuNTY1LTcuNjQ1IDE2Ljc0LTExLjUwOCAyNy41MjMtMTEuNTA4IDEwLjYyMyAwIDE5Ljc5OCAzLjg2MyAyNy41MjQgMTEuNDI4bDE4LjM1LTE4LjM1YTY3Ljk2MyA2Ny45NjMgMCAwMC0yMC43NjQtMTMuODQyYy03Ljg4Ny0zLjM4LTE2LjI1Ny01LjE1LTI1LjExLTUuMTV6Ii8+PC9nPjwvc3ZnPg==") !important;
				background-size: 22px auto !important;
				background-repeat: no-repeat !important;
				background-position: 4px 5px !important;
				padding-left: 30px !important;
			}</style>
		<?php
	}
}
