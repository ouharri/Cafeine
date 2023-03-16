<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class LinkDataText
 * @package Weglot\Parser\Check\Dom
 */
class LinkDataText extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-text';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
