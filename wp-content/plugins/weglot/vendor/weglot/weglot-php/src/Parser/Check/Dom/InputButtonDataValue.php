<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class InputButtonDataValue
 * @package Weglot\Parser\Check\Dom
 */
class InputButtonDataValue extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="submit"],input[type="button"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-value';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
