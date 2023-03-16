<?php

namespace Weglot\Parser\Formatter;

use Weglot\Client\Api\TranslateEntry;
use Weglot\Parser\Parser;
use Weglot\Util\JsonUtil;
use Weglot\Util\SourceType;

/**
 * Class JsonFormatter
 * @package Weglot\Parser\Formatter
 */
class JsonFormatter extends AbstractFormatter
{

    /**
     * @var string
     */
    protected $source;

    /**
     * JsonLdFormatter constructor.
     * @param Parser $parser
     * @param string $source
     * @param TranslateEntry $translated
     * @param int $nodesCount
     */
    public function __construct(Parser $parser, $source, TranslateEntry $translated)
    {
        $this->setSource($source);
        parent::__construct($parser, $translated);
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(array $tree, &$index)
    {
        $translated_words = $this->getTranslated()->getOutputWords();

        $jsonString = $tree['source'];
        $jsonArray = $tree['jsonArray'];
        $paths = $tree['paths'];

        foreach ($paths as $path) {
           $key = $path['key'];
           $parsed = $path['parsed'];


           if($parsed['type'] === SourceType::SOURCE_TEXT) {
               $jsonArray = JsonUtil::set($translated_words, $jsonArray, $key, $index);
           }
           if($parsed['type'] === SourceType::SOURCE_JSON) {
               $source = $this->getParser()->formatters($parsed['source'], $this->getTranslated(), $parsed, $index);
               $jsonArray = JsonUtil::setJSONString($source, $jsonArray, $key);
           }
            if($parsed['type'] === SourceType::SOURCE_HTML) {
               if($parsed['nodes']) {
                   $formatter = new DomFormatter($this->getParser(),  $this->getTranslated());
                   $formatter->handle($parsed['nodes'], $index);
                   $jsonArray= JsonUtil::setHTML($parsed['dom']->save(), $jsonArray, $key);

                   foreach ($parsed['regexes'] as $regex) {
                       $translatedRegex = $this->getParser()->formatters($regex['source'], $this->getTranslated(), $regex, $index);
                       $source = str_replace($regex['source'] , $translatedRegex, $parsed['source']);
                   }
               }
           }
        }
        $this->setSource(str_replace($jsonString, json_encode($jsonArray ), $this->getSource()));


        return $this->getSource();
    }
}
