<?php
/**
 * Class ConsentLogs file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Consentlogs;

use CookieYes\Lite\Includes\Modules;
use CookieYes\Lite\Admin\Modules\Consentlogs\Includes\Controller;
use CookieYes\Lite\Admin\Modules\Consentlogs\Api\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Handles Cookies Operation
 *
 * @class       ConsentLogs
 * @version     3.0.0
 * @package     CookieYes
 */
class ConsentLogs extends Modules {

	/**
	 * Constructor.
	 */
	public function init() {
		$this->load_apis();
		$this->controller = Controller::get_instance();
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
		$menus['logs'] = array(
			'name'     => __( 'Consent Log', 'cookie-law-info' ),
			'callback' => array( $this, 'menu_page_template' ),
			'order'    => 4,
			'redirect' => CKY_APP_URL . '/consent-logs',
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
