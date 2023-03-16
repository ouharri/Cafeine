<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class LinkDataHover
 * @package Weglot\Parser\Check\Dom
 */
class LinkDataHover extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-hover';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
