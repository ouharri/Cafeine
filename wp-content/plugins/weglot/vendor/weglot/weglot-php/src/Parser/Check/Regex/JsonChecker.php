<?php

namespace Weglot\Parser\Check\Regex;

use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Parser\Parser;
use Weglot\Util\JsonUtil;
use Weglot\Util\Text;

/**
 * Class JsonLdChecker
 * @package Weglot\Parser\Check
 */
class JsonChecker
{
    protected $default_keys = array(  'description' , 'name' );

    protected $jsonString;
    protected $parser;
    protected $extraKeys;

    /**
     * @param Parser $parser
     * @return $this
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

    public function __construct(Parser $parser, $jsonString, $extraKeys)
    {
        $this
            ->setParser($parser)
            ->setJSonString($jsonString)
            ->setExtraKeys($extraKeys);
    }

    /**
     * @param string $jsonString
     * @return $this
     */
    public function setJsonString($jsonString)
    {
        $this->jsonString = $jsonString;

        return $this;
    }

    /**
     * @return string
     */
    public function getJsonString()
    {
        return $this->jsonString;
    }

    /**
     * @param array $extraKeys
     * @return $this
     */
    public function setExtraKeys($extraKeys)
    {
        $this->extraKeys = $extraKeys;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraKeys()
    {
        return $this->extraKeys;
    }

    /**
     * @return array
     * @throws InvalidWordTypeException
     */
    public function handle()
    {
        $json = json_decode($this->jsonString, true);

        $paths = [];
        $this->findWords($json, "", $paths);

        return array(
            "type" => "JSON",
            "source" => $this->jsonString,
            "jsonArray" => $json,
            "paths" => $paths);

    }

    public function findWords($json, $currentKey, &$paths) {

        if( !empty($json)){
            foreach ($json as $key => $value) {
                if(is_array($value)) {
                    $this->findWords($value, ltrim($currentKey.JsonUtil::SEPARATOR.$key, JsonUtil::SEPARATOR), $paths);
                }
                else {
                    $k = ltrim($currentKey.JsonUtil::SEPARATOR.$key, JsonUtil::SEPARATOR);
                    if(Text::isJSON($value)) {
                        $parsed = $this->getParser()->parseJSON($value, $this->getExtraKeys());
                        array_push($paths, array( "key" => $k, "parsed" => $parsed));
                    }
                    elseif(Text::isHTML($value)) {
                        $parsed = $this->getParser()->parseHTML($value);
                        array_push($paths, array( "key" => $k , "parsed" => $parsed));

                    }
                    elseif(
                        (!is_int($key) && in_array($key, array_unique(array_merge($this->default_keys , $this->getExtraKeys())) , true))
                        || (is_int($key) && in_array(substr($currentKey, (strrpos($currentKey, JsonUtil::SEPARATOR) ?: -strlen(JsonUtil::SEPARATOR)) +strlen(JsonUtil::SEPARATOR)), array_unique(array_merge($this->default_keys , $this->getExtraKeys())) , true))

                    ) {
                        $parsed = $this->getParser()->parseText($value);
                        array_push($paths, array( "key" => $k , "parsed" => $parsed));
                    }
                }
            }
        }

    }
}
