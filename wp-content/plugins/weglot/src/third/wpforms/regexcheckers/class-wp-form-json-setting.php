<?php

namespace WeglotWP\Third\Wpforms\Regexcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Util\SourceType;



/**
 * @since 2.0.7
 */
class Wp_Form_Json_Setting {

	const REGEX = '#wpforms_settings = (.*?)(\n)(\/\* ]]> \*\/)#';

	const TYPE = SourceType::SOURCE_JSON;

	const VAR_NUMBER = 1;

	public static $KEYS = array( 'val_required', 'val_url', 'val_email', 'val_email_suggestion', 'val_email_suggestion_title', 'val_number', 'val_confirm', 'val_fileextension', 'val_filesize', 'val_time12h', 'val_time24h', 'val_requiredpayment', 'val_creditcard', 'val_smart_phone', 'val_post_max_size', 'val_checklimit', 'val_checklimit' );
}
