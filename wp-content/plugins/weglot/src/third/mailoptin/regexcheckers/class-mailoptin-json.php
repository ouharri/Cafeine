<?php

namespace WeglotWP\Third\MailOptin\Regexcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Util\SourceType;


/**
 * @since 3.1.2
 */
class Mailoptin_Json {

	const REGEX = '#\<script type="text\/javascript">var .*_lightbox = (.*);\<\/script\>#';

	const TYPE = SourceType::SOURCE_JSON;

	const VAR_NUMBER = 1;

	public static $KEYS = array(
		'success_message',
		'unexpected_error',
		'email_missing_error',
		'name_missing_error',
		'note_acceptance_error',
		'honeypot_error',
	);
}
