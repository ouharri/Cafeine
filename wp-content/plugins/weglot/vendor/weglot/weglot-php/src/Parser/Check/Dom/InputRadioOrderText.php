<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class InputRadioOrderText
 * @package Weglot\Parser\Check\Dom
 */
class InputRadioOrderText extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="radio"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-order_button_text';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::VALUE;
}
