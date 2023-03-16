<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class InputButtonOrderText
 * @package Weglot\Parser\Check\Dom
 */
class InputButtonOrderText extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="submit"],input[type="button"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-order_button_text';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;
}
