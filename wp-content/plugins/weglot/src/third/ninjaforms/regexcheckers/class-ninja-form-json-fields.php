<?php

namespace WeglotWP\Third\Ninjaforms\Regexcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Util\SourceType;


/**
 * @since 2.0.7
 */
class Ninja_Form_Json_Fields {

	const REGEX = '#form.fields=(.*?);nfForms#';

	const TYPE = SourceType::SOURCE_JSON;

	const VAR_NUMBER = 1;

	public static $KEYS = array( 'label', 'help_text' );
}
