<?php

namespace WeglotWP\Domcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Parser\Check\Dom\AbstractDomChecker;
use Weglot\Client\Api\Enum\WordType;


/**
 * @since 2.5.0
 */
class Input_Reset extends AbstractDomChecker {
	/**
	 * {@inheritdoc}
	 */
	const DOM = "input[type='reset']";
	/**
	 * {@inheritdoc}
	 */
	const PROPERTY = 'value';
	/**
	 * {@inheritdoc}
	 */
	const WORD_TYPE = WordType::TEXT;
}
