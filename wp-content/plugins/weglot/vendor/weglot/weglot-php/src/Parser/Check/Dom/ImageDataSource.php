<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;

/**
 * Class ImageDataSource
 * @package Weglot\Parser\Check\Dom
 */
class ImageDataSource extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'img';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'data-src';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::IMG_SRC;
}
