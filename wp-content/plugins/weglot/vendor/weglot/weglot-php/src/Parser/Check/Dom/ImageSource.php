<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class ImageSource
 * @package Weglot\Parser\Check\Dom
 */
class ImageSource extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'img';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'src';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::IMG_SRC;
}
