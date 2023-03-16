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
class Meta_Twitter_Image extends AbstractDomChecker {
	/**
	 * {@inheritdoc}
	 */
	const DOM = "meta[name='twitter:image'], meta[name='twitter:image:src']";
	/**
	 * {@inheritdoc}
	 */
	const PROPERTY = 'content';
	/**
	 * {@inheritdoc}
	 */
	const WORD_TYPE = WordType::IMG_SRC;
}
