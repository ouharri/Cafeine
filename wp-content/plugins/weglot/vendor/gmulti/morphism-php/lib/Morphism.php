<?php

namespace Morphism;

use Morphism\Helpers\MorphismHelper;

abstract class Morphism {

    use MorphismHelper;

    protected static $registries = array();

    public static function register($type, $schema) {
            
    }

    /** 
     * @static
     * @param string $type
     * @return bool
     */
    public static function exists($type){
        return array_key_exists($type, self::$registries);
    }

    /**
     * @static
     * @param string $type
     * @return array
     */
    public static function getMapper($type){
        return self::$registries[$type];
    }

    /**
     * @static
     * @param string $type
     * @param array $schema
     */
    public static function setMapper($type, $schema){
        if (!$type) {
            throw new \Exception('type paramater is required when register a mapping');
        }
        if (!$schema) {
            throw new \Exception('schema paramater is required when register a mapping');
        }

        self::$registries[$type] = $schema;
    }

    /**
     * @static
     * @param string $type
     */
    public static function deleteMapper($type){
        unset(self::$registries[$type]);
    }

    /**
     * @static
     * @param string $type
     * @param array $data
     */
    public static function map($type, $data){
        if(!Morphism::exists($type)){
            throw new \Exception(sprintf("Mapper for %s not exist", $type));
        }

        $reflectedClass = new \ReflectionClass($type);

        if(!$reflectedClass->isInstantiable()){
            throw new \Exception($type . " is not an instantiable class.");
        }

        if(isset($data[0])){
            return array_map(function($arr) use($reflectedClass, $type){
                $instance = $reflectedClass->newInstance();
                return self::transformValuesFromObject($instance, Morphism::getMapper($type), $arr);
            }, $data);
        }
        else{
            $instance = $reflectedClass->newInstance();
            return self::transformValuesFromObject($instance, Morphism::getMapper($type), $data);
        }
    }
}