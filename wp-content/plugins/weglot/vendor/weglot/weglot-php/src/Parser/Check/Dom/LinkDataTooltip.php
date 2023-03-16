<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class LinkDataTooltip
 * @package Weglot\Parser\Check\Dom
 */
class LinkDataTooltip extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-tooltip';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
