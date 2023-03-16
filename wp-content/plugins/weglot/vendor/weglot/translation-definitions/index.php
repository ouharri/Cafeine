<?php

namespace Weglot;

class TranslationDefinitions
{
  public static $languages;
  public static $cases;
  public static $mergeNodesList;
  private static $labelKey = "english_name";

  private static function loadJSON($key)
  {
    return json_decode(file_get_contents(__DIR__ . "/data/" . $key . ".json"), true);
  }

  private static function sortedJSONArray($pathKey, $sortKey, $sortOrder = SORT_ASC)
  {
    $array = self::loadJSON($pathKey);
    array_multisort(array_map(function ($el) use ($sortKey) {
      return $el[$sortKey];
    }, $array), $sortOrder, $array);
    return $array;
  }

  static function init()
  {
    self::$languages = array_map(function ($language) {
      return array(
        'label' => $language[self::$labelKey],
        'value' => $language['code']
      );
    }, self::sortedJSONArray("available-languages", self::$labelKey));

    self::$cases = array(
      'v1' => self::loadJSON("cases/cases-v1"),
      'v2' => self::loadJSON("cases/cases-v2-php"),
      'v3' => self::loadJSON("cases/cases-v3")
    );

    self::$mergeNodesList = self::loadJSON("merge-nodes");
  }
}

TranslationDefinitions::init();
