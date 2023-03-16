<?php

namespace Weglot\Parser\Check\Dom;

use Weglot\Client\Api\Enum\WordType;
use Weglot\Util\Text as TextUtil;

/**
 * Class LinkHref
 * @package Weglot\Parser\Check\Dom
 */
class ExternalLinkHref extends AbstractDomChecker
{
    /**
     * {@inheritdoc}
     */
    const DOM = 'a';

    /**
     * {@inheritdoc}
     */
    const PROPERTY = 'href';

    /**
     * {@inheritdoc}
     */
    const WORD_TYPE = WordType::EXTERNAL_LINK;


    /**
     * {@inheritdoc}
     */
    protected function check()
    {
        $boolean = false;

        $current_url = $this->node->href;
        $parsed_url = parse_url( $current_url );
        $server_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']:null;

        if( isset($server_host) && isset($parsed_url['host']) && str_replace('www.', '', $parsed_url['host']) !== str_replace('www.', '', $server_host) ) {
            return true;
        }
        else {
            return false;
        }
    }
}
