<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;
use Weglot\Util\Text as TextUtil;

/**
 * Class MetaContent
 * @package Weglot\Parser\Check\Dom
 */
class MetaContent extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'meta[name="description"],meta[property="og:description"],meta[property="og:site_name"],meta[name="twitter:description"]';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'content';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::META_CONTENT;

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
