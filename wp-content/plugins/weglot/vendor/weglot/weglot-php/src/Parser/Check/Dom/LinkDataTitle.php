<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class LinkDataTitle
 * @package Weglot\Parser\Check\Dom
 */
class LinkDataTitle extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-title';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;

    /**
	 * {@inheritdoc}
	 */
	const ESCAPE_SPECIAL_CHAR =true;
}
