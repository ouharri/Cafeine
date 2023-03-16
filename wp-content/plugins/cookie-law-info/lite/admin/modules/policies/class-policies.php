<?php
/**
 * Class Policies file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Policies;

use CookieYes\Lite\Includes\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Policies
 * @version     3.0.0
 * @package     CookieYes
 */
class Policies extends Modules {

	/**
	 * Constructor.
	 */
	public function init() {
		add_filter( 'cky_registered_admin_menus', array( $this, 'register_menus' ) );
	}

	/**
	 * Pass menu items to be registered.
	 *
	 * @param array $menus Sub menu array.
	 * @return array
	 */
	public function register_menus( $menus ) {
		$menus['policies'] = array(
			'name'     => __( 'Policy Generators', 'cookie-law-info' ),
			'callback' => array( $this, 'menu_page_template' ),
			'order'    => 5,
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
