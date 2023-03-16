<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;
use Weglot\Util\Text as TextUtil;

/**
 * Class Text
 * @package Weglot\Parser\Check
 */
class Text extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'text';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'innertext';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::TEXT;

    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        return ($this->node->parent()->tag != 'script'
            && $this->node->parent()->tag != 'style'
            && $this->node->parent()->tag != 'noscript'
            && $this->node->parent()->tag != 'code'
            && !is_numeric(TextUtil::fullTrim($this->node->innertext))
            && !preg_match('/^\d+%$/', TextUtil::fullTrim($this->node->innertext))
            && strpos($this->node->innertext, '[vc_') === false
            && strpos($this->node->innertext, '<?php') === false);
    }
}
