<?php
/**
 * Class Cookies file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Cookies;

use CookieYes\Lite\Includes\Modules;
use CookieYes\Lite\Admin\Modules\Cookies\Includes\Cookie_Controller;
use CookieYes\Lite\Admin\Modules\Cookies\Includes\Category_Controller;
use CookieYes\Lite\Admin\Modules\Cookies\Api\Categories_API;
use CookieYes\Lite\Admin\Modules\Cookies\Api\Cookies_API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Cookies
 * @version     3.0.0
 * @package     CookieYes
 */
class Cookies extends Modules {

	/**
	 * Constructor.
	 */
	public function init() {
		$this->load_apis();
		add_action( 'admin_init', array( Category_Controller::get_instance(), 'install_tables' ) );
		add_action( 'cky_after_update_cookie', array( Category_Controller::get_instance(), 'delete_cache' ) );
		add_action( 'cky_after_update_cookie_category', array( Cookie_Controller::get_instance(), 'delete_cache' ) );
		add_action( 'cky_after_update_cookie_category', array( Category_Controller::get_instance(), 'delete_cache' ) );
		add_action( 'admin_init', array( Cookie_Controller::get_instance(), 'reset_cache' ) );
		add_action( 'admin_init', array( Category_Controller::get_instance(), 'reset_cache' ) );
		add_action( 'admin_init', array( Cookie_Controller::get_instance(), 'install_tables' ) );
		add_filter( 'cky_registered_admin_menus', array( $this, 'register_menus' ) );
		add_action( 'cky_reinstall_tables', array( Category_Controller::get_instance(), 'reinstall' ) );
		add_action( 'cky_reinstall_tables', array( Cookie_Controller::get_instance(), 'reinstall' ) );
	}

	/**
	 * Load API files
	 *
	 * @return void
	 */
	public function load_apis() {
		$cookie_cat_api = new Categories_API();
		$cookie_api     = new Cookies_API();
	}

	/**
	 * Pass menu items to be registered.
	 *
	 * @param array $menus Sub menu array.
	 * @return array
	 */
	public function register_menus( $menus ) {
		$menus['cookies'] = array(
			'name'     => __( 'Cookie Manager', 'cookie-law-info' ),
			'callback' => array( $this, 'menu_page_template' ),
			'order'    => 3,
			'redirect' => CKY_APP_URL . '/manage-cookies',

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
