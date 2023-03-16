<?php

namespace Weglot\Client\Endpoint;

use Weglot\Client\Api\LanguageCollection;
use Weglot\Client\Factory\Languages as LanguagesFactory;
use Languages\Languages;
/**
 * Class Languages
 * @package Weglot\Client\Endpoint
 */
class LanguagesList extends Endpoint
{
    const METHOD = 'GET';
    const ENDPOINT = '/languages';

    public function getLanguages(){
        $data = Languages::$defaultLanguages;
        return $data;
    }

    /**
     * @return LanguageCollection
     */
    public function handle()
    {
        $languageCollection = new LanguageCollection();
        $data = Languages::$defaultLanguages;

        $data = array_map(function($data) {

            $external_code = $data['code'];
            if($external_code == 'tw') {
                $external_code = 'zh-tw';
            }
            if($external_code == 'br') {
                $external_code = 'pt-br';
            }

            return array(
                'internal_code' => $data['code'],
                'english' => $data['english'],
                'local' => $data['local'],
                'rtl' => $data['rtl'],
                'external_code' => $external_code
            );
        }, $data);

        foreach ($data as $language) {
            if($language['internal_code'] != 'fc'){
                $factory = new LanguagesFactory($language);
                $languageCollection->addOne($factory->handle());
            }
        }

        return $languageCollection;
    }
}
