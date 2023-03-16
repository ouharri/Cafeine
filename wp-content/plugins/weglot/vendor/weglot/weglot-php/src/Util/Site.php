<?php

namespace Weglot\Util;

/**
 * Class Site
 * @package Weglot\Parser\Util
 */
class Site
{
    /**
     * @param string $url
     * @param string $userAgent
     * @return mixed
     */
    public static function get($url, $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
}
