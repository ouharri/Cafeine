<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class TdDataTitle
 * @package Weglot\Parser\Check\Dom
 */
class TdDataTitle extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'td';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-title';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::VALUE;
}
