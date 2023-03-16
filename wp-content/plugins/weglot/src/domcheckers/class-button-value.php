<?php

namespace WeglotWP\Domcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Parser\Check\Dom\AbstractDomChecker;
use Weglot\Client\Api\Enum\WordType;


/**
 * @since 2.0.6
 */
class Button_Value extends AbstractDomChecker {
	/**
	 * {@inheritdoc}
	 */
	const DOM = 'button';
	/**
	 * {@inheritdoc}
	 */
	const PROPERTY = 'value';
	/**
	 * {@inheritdoc}
	 */
	const WORD_TYPE = WordType::VALUE;
}
