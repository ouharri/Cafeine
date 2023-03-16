<?php

namespace Weglot\Parser\Formatter;

use Weglot\Parser\Check\Dom\ImageSource;
use Weglot\Parser\Check\Dom\MetaContent;

if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}
/**
 * Class DomFormatter
 * @package Weglot\Parser\Formatter
 */
class DomFormatter extends AbstractFormatter
{
    /**
     * {@inheritdoc}
     */
    public function handle(array $nodes, &$index)
    {
        $translatable_attributes = $this->getTranslatableAttributes();

        $original_words     = array_column($this->getTranslated()->getInputWords()->jsonSerialize() , 'w');
        $translated_words   = array_column($this->getTranslated()->getOutputWords()->jsonSerialize() , 'w');

        for ($i = 0; $i < \count($nodes); ++$i) {
            $currentNode = $nodes[$i];

            if ($translated_words[$i+$index] !== null) {
                $currentTranslated = $translated_words[$i+$index];

                $this->metaContent($currentNode,  $currentTranslated, $translatable_attributes, $original_words, $translated_words);
                $this->imageSource($currentNode, $currentTranslated, $i);
            }
        }
        $index = $index + count($nodes);
    }

    /**
     * @param array $details
     * @param string $translated
     */
    protected function metaContent(array $details, $translated, $translatable_attributes, $original_words, $translated_words) {
        $property = $details['property'];

        if ($details['class']::ESCAPE_SPECIAL_CHAR) {
            $details['node']->$property = htmlspecialchars($translated);
        } else {
            $details['node']->$property = $translated;
        }

        if(array_key_exists('attributes' , $details)) {
            foreach ($details['attributes'] as $wg => $attributes) {
                $attributeString = "";
                foreach ($attributes as $key => $attribute) {
                    if(in_array($key, $translatable_attributes)) {
                        $pos = array_search($attribute, $original_words);
                        if($pos !== false) {
                            $attribute = $translated_words[$pos];
                        }
                    }
                    $attributeString .= $key."=\"".$attribute."\" ";
                }
                $attributeString = strlen($attributeString) > 0 ? " ".$attributeString:$attributeString;
                $details['node']->$property = str_replace(" ".$wg.'=""', rtrim($attributeString), $details['node']->$property);
                $details['node']->$property = str_replace(" ".$wg.'=\'\'', rtrim($attributeString), $details['node']->$property);
            }
        }

    }

    protected function imageSource(array $details, $translated, $index) {
        $words = $this->getTranslated()->getInputWords();

            if ($details['class'] === '\Weglot\Parser\Check\Dom\ImageSource') {
                if ($details['node']->hasAttribute('srcset') &&
                    $details['node']->srcset != '' &&
                    $translated != $words[$index]->getWord()) {
                    $details['node']->srcset = '';
                }
            }

        if ($details['class'] === '\Weglot\Parser\Check\Dom\ImageDataSource') {
            $dataSrcSet = "data-srcset";;
            if ($details['node']->hasAttribute('data-srcset') &&
                $details['node']->$dataSrcSet != '' &&
                $translated != $words[$index]->getWord()) {
                $details['node']->$dataSrcSet = '';
            }
        }
    }

    protected function getTranslatableAttributes() {
        $checkers = $this->getParser()->getDomCheckerProvider()->getCheckers();

        $attributes= [];
        foreach ($checkers as $class) {
            $attributes[] = $class::toArray()[1];
        }
        return $attributes;
    }
}
