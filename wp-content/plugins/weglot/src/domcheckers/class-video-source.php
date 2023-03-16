<?php

namespace WeglotWP\Domcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Parser\Check\Dom\AbstractDomChecker;
use Weglot\Client\Api\Enum\WordType;


class Video_Source extends AbstractDomChecker {
	/**
	 * {@inheritdoc}
	 */
	const DOM = 'video source,video';
	/**
	 * {@inheritdoc}
	 */
	const PROPERTY = 'src';
	/**
	 * {@inheritdoc}
	 */
	const WORD_TYPE = WordType::IMG_SRC;
}
