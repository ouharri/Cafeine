<?php
/**
 * Class Dashboard file.
 *
 * @package CookieYes
 */

namespace CookieYes\Lite\Admin\Modules\Dashboard;

use CookieYes\Lite\Includes\Modules;
use CookieYes\Lite\Admin\Modules\Dashboard\Api\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Dashboard
 * @version     3.0.0
 * @package     CookieYes
 */
class Dashboard extends Modules {

	/**
	 * Constructor.
	 */
	public function init() {
		$this->load_apis();

	}

	/**
	 * Load API files
	 *
	 * @return void
	 */
	public function load_apis() {
		new Api();
	}
}
