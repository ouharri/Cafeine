<?php

namespace WeglotWP\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Abstract_Notices_Weglot;


/**
 * @since 2.0
 */
class No_Configuration_Weglot extends Abstract_Notices_Weglot {

	/**
	 * Get template file for admin notice
	 * @since 2.0
	 * @static
	 * @return string
	 */
	public static function get_template_file() {
		return WEGLOT_TEMPLATES_ADMIN_NOTICES . '/no-configuration.php';
	}
}

