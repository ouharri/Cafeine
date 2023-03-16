<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class LinkDataContent
 * @package Weglot\Parser\Check\Dom
 */
class LinkDataContent extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-content';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
