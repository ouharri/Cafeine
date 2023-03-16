<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class LinkDataValue
 * @package Weglot\Parser\Check\Dom
 */
class LinkDataValue extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-value';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
