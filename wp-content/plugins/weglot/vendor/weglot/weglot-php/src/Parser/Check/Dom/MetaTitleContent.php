<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;
use Weglot\Util\Text as TextUtil;

/**
 * Class MetaContent
 * @package Weglot\Parser\Check\Dom
 */
class MetaTitleContent extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'meta[property="og:title"],meta[name="twitter:title"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'content';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TITLE;

    /**
	 * {@inheritdoc}
	 */
    const ESCAPE_SPECIAL_CHAR = true;

    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        return (!is_numeric(TextUtil::fullTrim($this->node->placeholder))
            && !preg_match('/^\d+%$/', TextUtil::fullTrim($this->node->placeholder)));
    }
}
