<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;
use Weglot\Util\Text as TextUtil;

/**
 * Class Button
 * @package Weglot\Parser\Check\Dom
 */
class Button extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'input[type="submit"],input[type="button"],button';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'value';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::VALUE;

    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        return (!is_numeric(TextUtil::fullTrim($this->node->value))
            && !preg_match('/^\d+%$/', TextUtil::fullTrim($this->node->value)));
    }
}
