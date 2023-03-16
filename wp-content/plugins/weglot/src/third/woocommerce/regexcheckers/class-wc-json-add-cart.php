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
class Wc_Json_Add_Cart {

	const REGEX = '#wc_add_to_cart_params = (.*?);#';

	const TYPE = SourceType::SOURCE_JSON;

	const VAR_NUMBER = 1;

	public static $KEYS = array( 'i18n_view_cart' );
}
