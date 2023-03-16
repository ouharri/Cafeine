<?php

namespace Weglot\Client\Api\Enum;

/**
 * Enum WordType
 * Used to define where was the text we are parsing
 *
 * @package Weglot\Client\Api\Enum
 */
abstract class WordType
{
    const OTHER = 0;
    const TEXT = 1;
    const VALUE = 2;
    const PLACEHOLDER = 3;
    const META_CONTENT = 4;
    const IFRAME_SRC = 5;
    const IMG_SRC = 6;
    const IMG_ALT = 7;
    const PDF_HREF = 8;
    const TITLE = 9;
    const EXTERNAL_LINK = 10;

    /**
     * Only for internal use, if you have to add a value in this enum,
     * please increments the __MAX value.
     */
    const __MIN = 0;
    const __MAX = 10;
}
