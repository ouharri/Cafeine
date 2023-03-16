<?php

namespace WeglotWP\Third\Calderaforms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Helpers\Helper_Filter_Url_Weglot;


/**
 * @since 3.0.0
 */
class Caldera_I18n_Inline implements Hooks_Interface_Weglot {
	/**
	 * @var Caldera_Active
	 */
	private $caldera_active_services;

	/**
	 * @since 3.0.0
	 * @return void
	 */
	public function __construct() {
		$this->caldera_active_services = weglot_get_service( 'Caldera_Active' );
	}

	/**
	 * @since 3.0.0
	 */
	public function hooks() {
		if ( ! $this->caldera_active_services->is_active() ) {
			return;
		}

		add_filter( 'caldera_forms_print_translation_strings_in_footer', '__return_true' );
	}
}
