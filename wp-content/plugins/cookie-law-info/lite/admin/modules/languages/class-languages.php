<?php
/**
 * Class Languages file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Languages;

use CookieYes\Lite\Includes\Modules;
use CookieYes\Lite\Admin\Modules\Languages\Api\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Languages
 * @version     3.0.0
 * @package     CookieYes
 */
class Languages extends Modules {

	/**
	 * Constructor.
	 */
	public function init() {
		$controller = new \CookieYes\Lite\Admin\Modules\Languages\Includes\Controller();
		$this->load_apis();
		add_filter( 'cky_admin_scripts_languages', array( $controller, 'load_config' ) );
		add_filter( 'cky_registered_admin_menus', array( $this, 'register_menus' ) );
	}

	/**
	 * Load API files
	 *
	 * @return void
	 */
	public function load_apis() {
		new Api();
	}

	/**
	 * Pass menu items to be registered.
	 *
	 * @param array $menus Sub menu array.
	 * @return array
	 */
	public function register_menus( $menus ) {
		$menus['languages'] = array(
			'name'     => __( 'Languages', 'cookie-law-info' ),
			'callback' => array( $this, 'menu_page_template' ),
			'order'    => 4,
			'redirect' => CKY_APP_URL . '/languages',
		);

		$menus['edit-content'] = array(
			'name'     => __( 'Languages', 'cookie-law-info' ),
			'callback' => array( $this, 'menu_page_template' ),
			'order'    => 4,
			'redirect' => CKY_APP_URL . '/languages',
			'hidden'   => true,
		);

		return $menus;
	}
	/**
	 * Main menu template
	 *
	 * @return void
	 */
	public function menu_page_template() {
		echo '<div id="cky-app"></div>';
	}
}
