<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class ImageAlt
 * @package Weglot\Parser\Check\Dom
 */
class ImageAlt extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'img';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'alt';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::IMG_ALT;

    /**
	 * {@inheritdoc}
	 */
	const ESCAPE_SPECIAL_CHAR =true;
}
