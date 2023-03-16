<?php

namespace WeglotWP\Third\Woocommerce\Regexcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Parser\Check\Regex\RegexChecker;
use Weglot\Util\SourceType;


/**
 * @since 2.0.7
 */
class Wc_Json_Add_Cart_Variations {

	const REGEX = '#var wc_add_to_cart_variation_params = (.*);#';

	const TYPE = SourceType::SOURCE_JSON;

	const VAR_NUMBER = 1;

	public static $KEYS = array('i18n_no_matching_variations_text', 'i18n_make_a_selection_text', 'i18n_unavailable_text');
}
