<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class LinkTitle
 * @package Weglot\Parser\Check\Dom
 */
class LinkTitle extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'title';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;

    /**
	 * {@inheritdoc}
	 */
	const ESCAPE_SPECIAL_CHAR =true;
}
