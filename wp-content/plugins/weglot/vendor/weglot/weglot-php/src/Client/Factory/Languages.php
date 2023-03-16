<?php

namespace Weglot\Client\Factory;

use Weglot\Client\Api\LanguageEntry;

/**
 * Class Languages
 * @package Weglot\Client\Factory
 */
class Languages
{
    /**
     * @var array
     */
    protected $language;

    /**
     * Languages constructor.
     * @param array $language
     */
    public function __construct(array $language)
    {
        $this->language = $language;
    }

    /**
     * @param array $language
     * @return $this
     */
    public function setLanguage(array $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param null $key
     * @return array|string|bool|null
     */
    public function getLanguage($key = null)
    {
        if ($key !== null) {
            return isset($this->language[$key]) ? $this->language[$key] : null;
        }

        return $this->language;
    }

    /**
     * @return LanguageEntry
     */
    public function handle()
    {
        $language = new LanguageEntry(
            $this->getLanguage('internal_code'),
            $this->getLanguage('external_code'),
            $this->getLanguage('english'),
            $this->getLanguage('local'),
            $this->getLanguage('rtl')
        );

        return $language;
    }

    /**
     * Only used to replace API endpoint
     * We planned to make this endpoint available soon !
     *
     * @return array
     */
    public static function data()
    {
        return [
            'sq' => [
                'internal_code'  => 'sq',
                'english' => 'Albanian',
                'local' => 'Shqip',
                'rtl' => false,
            ],
            'en' => [
                'internal_code'  => 'en',
                'english' => 'English',
                'local' => 'English',
                'rtl' => false,
            ],
            'ar' => [
                'internal_code'  => 'ar',
                'english' => 'Arabic',
                'local' => 'العربية‏',
                'rtl' => true,
            ],
            'hy' => [
                'internal_code'  => 'hy',
                'english' => 'Armenian',
                'local' => 'հայերեն',
                'rtl' => false,
            ],
            'az' => [
                'internal_code'  => 'az',
                'english' => 'Azerbaijani',
                'local' => 'Azərbaycan dili',
                'rtl' => false,
            ],
            'af' => [
                'internal_code'  => 'af',
                'english' => 'Afrikaans',
                'local' => 'Afrikaans',
                'rtl' => false,
            ],
            'eu' => [
                'internal_code'  => 'eu',
                'english' => 'Basque',
                'local' => 'Euskara',
                'rtl' => false,
            ],
            'be' => [
                'internal_code'  => 'be',
                'english' => 'Belarusian',
                'local' => 'Беларуская',
                'rtl' => false,
            ],
            'bg' => [
                'internal_code'  => 'bg',
                'english' => 'Bulgarian',
                'local' => 'български',
                'rtl' => false,
            ],
            'bs' => [
                'internal_code'  => 'bs',
                'english' => 'Bosnian',
                'local' => 'Bosanski',
                'rtl' => false,
            ],
            'cy' => [
                'internal_code'  => 'cy',
                'english' => 'Welsh',
                'local' => 'Cymraeg',
                'rtl' => false,
            ],
            'vi' => [
                'internal_code'  => 'vi',
                'english' => 'Vietnamese',
                'local' => 'Tiếng Việt',
                'rtl' => false,
            ],
            'hu' => [
                'internal_code'  => 'hu',
                'english' => 'Hungarian',
                'local' => 'Magyar',
                'rtl' => false,
            ],
            'ht' => [
                'internal_code'  => 'ht',
                'english' => 'Haitian',
                'local' => 'Kreyòl ayisyen',
                'rtl' => false,
            ],
            'gl' => [
                'internal_code'  => 'gl',
                'english' => 'Galician',
                'local' => 'Galego',
                'rtl' => false,
            ],
            'nl' => [
                'internal_code'  => 'nl',
                'english' => 'Dutch',
                'local' => 'Nederlands',
                'rtl' => false,
            ],
            'el' => [
                'internal_code'  => 'el',
                'english' => 'Greek',
                'local' => 'Ελληνικά',
                'rtl' => false,
            ],
            'ka' => [
                'internal_code'  => 'ka',
                'english' => 'Georgian',
                'local' => 'ქართული',
                'rtl' => false,
            ],
            'da' => [
                'internal_code'  => 'da',
                'english' => 'Danish',
                'local' => 'Dansk',
                'rtl' => false,
            ],
            'he' => [
                'internal_code'  => 'he',
                'english' => 'Hebrew',
                'local' => 'עברית',
                'rtl' => true,
            ],
            'id' => [
                'internal_code'  => 'id',
                'english' => 'Indonesian',
                'local' => 'Bahasa Indonesia',
                'rtl' => false,
            ],
            'ga' => [
                'internal_code'  => 'ga',
                'english' => 'Irish',
                'local' => 'Gaeilge',
                'rtl' => false,
            ],
            'it' => [
                'internal_code'  => 'it',
                'english' => 'Italian',
                'local' => 'Italiano',
                'rtl' => false,
            ],
            'is' => [
                'internal_code'  => 'is',
                'english' => 'Icelandic',
                'local' => 'Íslenska',
                'rtl' => false,
            ],
            'es' => [
                'internal_code'  => 'es',
                'english' => 'Spanish',
                'local' => 'Español',
                'rtl' => false,
            ],
            'kk' => [
                'internal_code'  => 'kk',
                'english' => 'Kazakh',
                'local' => 'Қазақша',
                'rtl' => false,
            ],
            'ca' => [
                'internal_code'  => 'ca',
                'english' => 'Catalan',
                'local' => 'Català',
                'rtl' => false,
            ],
            'ky' => [
                'internal_code'  => 'ky',
                'english' => 'Kyrgyz',
                'local' => 'кыргызча',
                'rtl' => false,
            ],
            'zh' => [
                'internal_code'  => 'zh',
                'english' => 'Simplified Chinese',
                'local' => '中文 (简体)',
                'rtl' => false,
            ],
            'tw' => [
                'internal_code'  => 'tw',
                'english' => 'Traditional Chinese',
                'local' => '中文 (繁體)',
                'rtl' => false,
            ],
            'ko' => [
                'internal_code'  => 'ko',
                'english' => 'Korean',
                'local' => '한국어',
                'rtl' => false,
            ],
            'lv' => [
                'internal_code'  => 'lv',
                'english' => 'Latvian',
                'local' => 'Latviešu',
                'rtl' => false,
            ],
            'lt' => [
                'internal_code'  => 'lt',
                'english' => 'Lithuanian',
                'local' => 'Lietuvių',
                'rtl' => false,
            ],
            'mg' => [
                'internal_code'  => 'mg',
                'english' => 'Malagasy',
                'local' => 'Malagasy',
                'rtl' => false,
            ],
            'ms' => [
                'internal_code'  => 'ms',
                'english' => 'Malay',
                'local' => 'Bahasa Melayu',
                'rtl' => false,
            ],
            'mt' => [
                'internal_code'  => 'mt',
                'english' => 'Maltese',
                'local' => 'Malti',
                'rtl' => false,
            ],
            'mk' => [
                'internal_code'  => 'mk',
                'english' => 'Macedonian',
                'local' => 'Македонски',
                'rtl' => false,
            ],
            'mn' => [
                'internal_code'  => 'mn',
                'english' => 'Mongolian',
                'local' => 'Монгол',
                'rtl' => false,
            ],
            'de' => [
                'internal_code'  => 'de',
                'english' => 'German',
                'local' => 'Deutsch',
                'rtl' => false,
            ],
            'no' => [
                'internal_code'  => 'no',
                'english' => 'Norwegian',
                'local' => 'Norsk',
                'rtl' => false,
            ],
            'fa' => [
                'internal_code'  => 'fa',
                'english' => 'Persian',
                'local' => 'فارسی',
                'rtl' => true,
            ],
            'pl' => [
                'internal_code'  => 'pl',
                'english' => 'Polish',
                'local' => 'Polski',
                'rtl' => false,
            ],
            'pt' => [
                'internal_code'  => 'pt',
                'english' => 'Portuguese',
                'local' => 'Português',
                'rtl' => false,
            ],
            'ro' => [
                'internal_code'  => 'ro',
                'english' => 'Romanian',
                'local' => 'Română',
                'rtl' => false,
            ],
            'ru' => [
                'internal_code'  => 'ru',
                'english' => 'Russian',
                'local' => 'Русский',
                'rtl' => false,
            ],
            'sr' => [
                'internal_code'  => 'sr',
                'english' => 'Serbian',
                'local' => 'Српски',
                'rtl' => false,
            ],
            'sk' => [
                'internal_code'  => 'sk',
                'english' => 'Slovak',
                'local' => 'Slovenčina',
                'rtl' => false,
            ],
            'sl' => [
                'internal_code'  => 'sl',
                'english' => 'Slovenian',
                'local' => 'Slovenščina',
                'rtl' => false,
            ],
            'sw' => [
                'internal_code'  => 'sw',
                'english' => 'Swahili',
                'local' => 'Kiswahili',
                'rtl' => false,
            ],
            'tg' => [
                'internal_code'  => 'tg',
                'english' => 'Tajik',
                'local' => 'Тоҷикӣ',
                'rtl' => false,
            ],
            'th' => [
                'internal_code'  => 'th',
                'english' => 'Thai',
                'local' => 'ภาษาไทย',
                'rtl' => false,
            ],
            'tl' => [
                'internal_code'  => 'tl',
                'english' => 'Tagalog',
                'local' => 'Tagalog',
                'rtl' => false,
            ],
            'tt' => [
                'internal_code'  => 'tt',
                'english' => 'Tatar',
                'local' => 'Tatar',
                'rtl' => false,
            ],
            'tr' => [
                'internal_code'  => 'tr',
                'english' => 'Turkish',
                'local' => 'Türkçe',
                'rtl' => false,
            ],
            'uz' => [
                'internal_code'  => 'uz',
                'english' => 'Uzbek',
                'local' => 'O\'zbek',
                'rtl' => false,
            ],
            'uk' => [
                'internal_code'  => 'uk',
                'english' => 'Ukrainian',
                'local' => 'Українська',
                'rtl' => false,
            ],
            'fi' => [
                'internal_code'  => 'fi',
                'english' => 'Finnish',
                'local' => 'Suomi',
                'rtl' => false,
            ],
            'fr' => [
                'internal_code'  => 'fr',
                'english' => 'French',
                'local' => 'Français',
                'rtl' => false,
            ],
            'hr' => [
                'internal_code'  => 'hr',
                'english' => 'Croatian',
                'local' => 'Hrvatski',
                'rtl' => false,
            ],
            'cs' => [
                'internal_code'  => 'cs',
                'english' => 'Czech',
                'local' => 'Čeština',
                'rtl' => false,
            ],
            'sv' => [
                'internal_code'  => 'sv',
                'english' => 'Swedish',
                'local' => 'Svenska',
                'rtl' => false,
            ],
            'et' => [
                'internal_code'  => 'et',
                'english' => 'Estonian',
                'local' => 'Eesti',
                'rtl' => false,
            ],
            'ja' => [
                'internal_code'  => 'ja',
                'english' => 'Japanese',
                'local' => '日本語',
                'rtl' => false,
            ],
            'hi' => [
                'internal_code'  => 'hi',
                'english' => 'Hindi',
                'local' => 'हिंदी',
                'rtl' => false,
            ],
            'ur' => [
                'internal_code'  => 'ur',
                'english' => 'Urdu',
                'local' => 'اردو',
                'rtl' => false,
            ],
            'co' => [
                'internal_code'  => 'co',
                'english' => 'Corsican',
                'local' => 'Corsu',
                'rtl' => false,
            ],
            'fj' => [
                'internal_code'  => 'fj',
                'english' => 'Fijian',
                'local' => 'Vosa Vakaviti',
                'rtl' => false,
            ],
            'hw' => [
                'internal_code'  => 'hw',
                'english' => 'Hawaiian',
                'local' => '‘Ōlelo Hawai‘i',
                'rtl' => false,
            ],
            'ig' => [
                'internal_code'  => 'ig',
                'english' => 'Igbo',
                'local' => 'Igbo',
                'rtl' => false,
            ],
            'ny' => [
                'internal_code'  => 'ny',
                'english' => 'Chichewa',
                'local' => 'chiCheŵa',
                'rtl' => false,
            ],
            'ps' => [
                'internal_code'  => 'ps',
                'english' => 'Pashto',
                'local' => 'پښت',
                'rtl' => false,
            ],
            'sd' => [
                'internal_code'  => 'sd',
                'english' => 'Sindhi',
                'local' => 'سنڌي، سندھی, सिन्धी',
                'rtl' => false,
            ],
            'sn' => [
                'internal_code'  => 'sn',
                'english' => 'Shona',
                'local' => 'chiShona',
                'rtl' => false,
            ],
            'to' => [
                'internal_code'  => 'to',
                'english' => 'Tongan',
                'local' => 'faka-Tonga',
                'rtl' => false,
            ],
            'yo' => [
                'internal_code'  => 'yo',
                'english' => 'Yoruba',
                'local' => 'Yorùbá',
                'rtl' => false,
            ],
            'zu' => [
                'internal_code'  => 'zu',
                'english' => 'Zulu',
                'local' => 'isiZulu',
                'rtl' => false,
            ],
            'ty' => [
                'internal_code'  => 'ty',
                'english' => 'Tahitian',
                'local' => 'te reo Tahiti, te reo Māʼohi',
                'rtl' => false,
            ],
            'sm' => [
                'internal_code'  => 'sm',
                'english' => 'Samoan',
                'local' => 'gagana fa\'a Samoa',
                'rtl' => false,
            ],
            'ku' => [
                'internal_code'  => 'ku',
                'english' => 'Kurdish',
                'local' => 'كوردی',
                'rtl' => false,
            ],
            'ha' => [
                'internal_code'  => 'ha',
                'english' => 'Hausa',
                'local' => 'هَوُسَ',
                'rtl' => false,
            ],
            'bn' => [
                'internal_code'  => 'bn',
                'english' => 'Bengali',
                'local' => 'বাংলা',
                'rtl' => false,
            ],
            'st' => [
                'internal_code'  => 'st',
                'english' => 'Southern Sotho',
                'local' => 'seSotho',
                'rtl' => false,
            ],
            'ba' => [
                'internal_code'  => 'ba',
                'english' => 'Bashkir',
                'local' => 'башҡорт теле',
                'rtl' => false,
            ],
            'jv' => [
                'internal_code'  => 'jv',
                'english' => 'Javanese',
                'local' => 'Wong Jawa',
                'rtl' => false,
            ],
            'kn' => [
                'internal_code'  => 'kn',
                'english' => 'Kannada',
                'local' => 'ಕನ್ನಡ',
                'rtl' => false,
            ],
            'la' => [
                'internal_code'  => 'la',
                'english' => 'Latin',
                'local' => 'Latine',
                'rtl' => false,
            ],
            'lo' => [
                'internal_code'  => 'lo',
                'english' => 'Lao',
                'local' => 'ພາສາລາວ',
                'rtl' => false,
            ],
            'mi' => [
                'internal_code'  => 'mi',
                'english' => 'Māori',
                'local' => 'te reo Māori',
                'rtl' => false,
            ],
            'ml' => [
                'internal_code'  => 'ml',
                'english' => 'Malayalam',
                'local' => 'മലയാളം',
                'rtl' => false,
            ],
            'mr' => [
                'internal_code'  => 'mr',
                'english' => 'Marathi',
                'local' => 'मराठी',
                'rtl' => false,
            ],
            'ne' => [
                'internal_code'  => 'ne',
                'english' => 'Nepali',
                'local' => 'नेपाली',
                'rtl' => false,
            ],
            'pa' => [
                'internal_code'  => 'pa',
                'english' => 'Punjabi',
                'local' => 'ਪੰਜਾਬੀ',
                'rtl' => false,
            ],
            'so' => [
                'internal_code'  => 'so',
                'english' => 'Somali',
                'local' => 'Soomaaliga',
                'rtl' => false,
            ],
            'su' => [
                'internal_code'  => 'su',
                'english' => 'Sundanese',
                'local' => 'Sundanese',
                'rtl' => false,
            ],
            'te' => [
                'internal_code'  => 'te',
                'english' => 'Telugu',
                'local' => 'తెలుగు',
                'rtl' => false,
            ],
            'yi' => [
                'internal_code'  => 'yi',
                'english' => 'Yiddish',
                'local' => 'ייִדיש',
                'rtl' => false,
            ],
            'am' => [
                'internal_code'  => 'am',
                'english' => 'Amharic',
                'local' => 'አማርኛ',
                'rtl' => false,
            ],
            'eo' => [
                'internal_code'  => 'eo',
                'english' => 'Esperanto',
                'local' => 'Esperanto',
                'rtl' => false,
            ],
            'fy' => [
                'internal_code'  => 'fy',
                'english' => 'Western Frisian',
                'local' => 'frysk',
                'rtl' => false,
            ],
            'gd' => [
                'internal_code'  => 'gd',
                'english' => 'Scottish Gaelic',
                'local' => 'Gàidhlig',
                'rtl' => false,
            ],
            'gu' => [
                'internal_code'  => 'gu',
                'english' => 'Gujarati',
                'local' => 'ગુજરાતી',
                'rtl' => false,
            ],
            'km' => [
                'internal_code'  => 'km',
                'english' => 'Central Khmer',
                'local' => 'ភាសាខ្មែរ',
                'rtl' => false,
            ],
            'lb' => [
                'internal_code'  => 'lb',
                'english' => 'Luxembourgish',
                'local' => 'Lëtzebuergesch',
                'rtl' => false,
            ],
            'my' => [
                'internal_code'  => 'my',
                'english' => 'Burmese',
                'local' => 'မျန္မာစာ',
                'rtl' => false,
            ],
            'si' => [
                'internal_code'  => 'si',
                'english' => 'Sinhalese',
                'local' => 'සිංහල',
                'rtl' => false,
            ],
            'ta' => [
                'internal_code'  => 'ta',
                'english' => 'Tamil',
                'local' => 'தமிழ்',
                'rtl' => false,
            ],
            'xh' => [
                'internal_code'  => 'xh',
                'english' => 'Xhosa',
                'local' => 'isiXhosa',
                'rtl' => false,
            ],
            'fl' => [
                'internal_code'  => 'fl',
                'english' => 'Filipino',
                'local' => 'Pilipino',
                'rtl' => false,
            ],
            'br' => [
                'internal_code'  => 'br',
                'english' => 'Brazilian Portuguese',
                'local' => 'Português Brasileiro',
                'rtl' => false,
            ]
        ];
    }
}
