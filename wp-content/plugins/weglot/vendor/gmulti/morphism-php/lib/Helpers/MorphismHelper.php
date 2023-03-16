<?php

namespace Morphism\Helpers;

trait MorphismHelper {

    /**
     * Source : https://stackoverflow.com/questions/5458241/php-dynamic-array-index-name
     * 
     * @static
     * @param array $array
     * @param array $indexes
     */
    protected static function getArrayValue(array $array, array $indexes)
    {
        if (count($array) == 0 || count($indexes) == 0) {
            return false;
        }

        $index = array_shift($indexes);
        if(!array_key_exists($index, $array)){
            return false;
        }

        $value = $array[$index];
        if (count($indexes) == 0) {
            return $value;
        }

        if(!is_array($value)) {
            return false;
        }

        return self::getArrayValue($value, $indexes);
    }

    /** 
     * @static
     * @param array $paths
     * @param array $object
     * @return string
     */
    protected static function agregator($paths, $object){
        return array_reduce($paths, function($delta, $path) use ($object) {
            $searchPath = $path;
            if(!is_array($path)){
                $searchPath = array($path);
            }

            return trim(sprintf("%s %s", $delta, self::getArrayValue($object, $searchPath) ));
        });
    }

    /** 
     * @static
     * @param array $object
     * @param array $schema
     * @param array $data
     * @return object
     */
    protected static function transformValuesFromObject($object, $schema, $data){
        foreach($schema as $key => $target){ // iterate on every action of the schema

            if(is_string($target)){ // Target<String>: string path => [ target: 'source' ]
                $indexes = explode(".", $target);
                $object->{$key} = self::getArrayValue($data, $indexes);

            }
            else if(is_callable($target)){
                $object->{$key} = call_user_func($target, $data); 
            }
            else if (is_array($target)){
                $object->{$key} = self::agregator($target, $data);
            }
            else if(is_object($target) ) {
                $searchPath = $target->path;
                if(is_array($target->path)){
                    $value = self::agregator($target->path, $data);
                }
                else{
                    $indexes =  explode(".", $target->path);
                    $value   =  self::getArrayValue($data, $indexes);
                }

                $object->{$key} = call_user_func($target->fn, $value);
            }
        }

        return $object;
    }
}