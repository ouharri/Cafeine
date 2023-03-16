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
class Wc_Json_Address {

	const REGEX = '#wc_address_i18n_params = (.*?);#';

	const TYPE = SourceType::SOURCE_JSON;

	const VAR_NUMBER = 1;

	public static $KEYS = array( 'label', 'placeholder', 'i18n_required_text', 'i18n_optional_text' );
}
