<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class SpanTitle
 * @package Weglot\Parser\Check\Dom
 */
class SpanTitle extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'span[title]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'title';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
