<?php

namespace WeglotWP\Actions\Front;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;

/**
 *
 * @since 2.0
 */
class Redirect_Log_User_Weglot implements Hooks_Interface_Weglot {

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.0
	 * @return void
	 */
	public function hooks() {
		add_filter( 'logout_redirect', array( '\WeglotWP\Helpers\Helper_Filter_Url_Weglot', 'filter_url_log_redirect' ) );
	}
}

