<?php

namespace Weglot\Util;

/**
 * Class Text
 * @package Weglot\Parser\Util
 */
class Text
{
    /**
     * @param string $word
     * @return string
     */
    public static function fullTrim($word)
    {
        return trim($word, " \t\n\r\0\x0B\xA0�");
    }

    /**
     * @param string $haystack
     * @param string $search
     * @return bool
     */
    public static function contains($haystack, $search)
    {
        return strpos($haystack, $search) !== false;
    }

    /**
     * @param string $filename
     * @return string
     */
    public static function removeFileExtension($filename)
    {
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
    }

    /**
     * @param string $regex
     * @return string
     */
    public static function escapeForRegex($regex)
    {
        return str_replace('\\\\/', '\/', str_replace('/', '\/', $regex));
    }

    public static function isJSON($string) {
            $json = json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE && in_array(substr($string, 0 , 1), array('{' , '[')) );
    }

    public static function isHTML($string) {
        return strip_tags($string) !== $string && is_string($string);
    }
}
