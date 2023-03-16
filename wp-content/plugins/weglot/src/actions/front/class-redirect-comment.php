<?php

namespace WeglotWP\Actions\Front;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;

/**
 *
 * @since 2.7.0
 */
class Redirect_Comment implements Hooks_Interface_Weglot {

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.7.0
	 * @return void
	 */
	public function hooks() {
		add_filter( 'comment_post_redirect', array( '\WeglotWP\Helpers\Helper_Filter_Url_Weglot', 'filter_url_lambda' ) );
	}
}


