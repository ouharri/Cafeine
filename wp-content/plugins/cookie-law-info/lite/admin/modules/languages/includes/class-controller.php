<?php
/**
 * Language controller file
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 * @package    CookieYes\Lite\Admin\Modules\Banners\Includes
 */

namespace CookieYes\Lite\Admin\Modules\Languages\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles Cookies Operation
 *
 * @class       Controller
 * @version     3.0.0
 * @package     CookieYes
 */
class Controller {

	/**
	 * Instance of the current class
	 *
	 * @var object
	 */
	private static $instance;
	/**
	 * Cookie items
	 *
	 * @var array
	 */
	public $languages;

	/**
	 * Return the current instance of the class
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get the available languages
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */
	public function get_languages() {
		if ( ! $this->languages ) {
			$this->languages = array(
				'Abkhazian'             => 'ab',
				'Afar'                  => 'aa',
				'Afrikaans'             => 'af',
				'Akan'                  => 'ak',
				'Albanian'              => 'sq',
				'Amharic'               => 'am',
				'Arabic'                => 'ar',
				'Armenian'              => 'hy',
				'Assamese'              => 'as',
				'Avar'                  => 'av',
				'Avestan'               => 'ae',
				'Aymara'                => 'ay',
				'Azerbaijani'           => 'az',
				'Bambara'               => 'bm',
				'Bashkir'               => 'ba',
				'Basque'                => 'eu',
				'Belarusian'            => 'be',
				'Bengali'               => 'bn',
				'Bhutani'               => 'dz',
				'Bihari'                => 'bh',
				'Bislama'               => 'bi',
				'Bosnian'               => 'bs',
				'Breton'                => 'br',
				'Bulgarian'             => 'bg',
				'Burmese'               => 'my',
				'Cambodian'             => 'km',
				'Catalan'               => 'ca',
				'Chamorro'              => 'ch',
				'Chechen'               => 'ce',
				'Chichewa'              => 'ny',
				'Chinese (Simplified)'  => 'zh-hans',
				'Chinese (Traditional)' => 'zh-hant',
				'Chuvash'               => 'cv',
				'Cornish'               => 'kw',
				'Corsican'              => 'co',
				'Cree'                  => 'cr',
				'Croatian'              => 'hr',
				'Czech'                 => 'cs',
				'Danish'                => 'da',
				'English'               => 'en',
				'Esperanto'             => 'eo',
				'Estonian'              => 'et',
				'Ewe'                   => 'ee',
				'Faeroese'              => 'fo',
				'Fiji'                  => 'fj',
				'Finnish'               => 'fi',
				'French'                => 'fr',
				'Frisian'               => 'fy',
				'Fulah'                 => 'ff',
				'Galician'              => 'gl',
				'Georgian'              => 'ka',
				'German'                => 'de',
				'Greek'                 => 'el',
				'Greenlandic'           => 'kl',
				'Guarani'               => 'gn',
				'Gujarati'              => 'gu',
				'Hausa'                 => 'ha',
				'Hebrew'                => 'he',
				'Herero'                => 'hz',
				'Hindi'                 => 'hi',
				'Hiri Motu'             => 'ho',
				'Hungarian'             => 'hu',
				'Icelandic'             => 'is',
				'Igbo'                  => 'ig',
				'Indonesian'            => 'id',
				'Interlingua'           => 'ia',
				'Interlingue'           => 'ie',
				'Inuktitut'             => 'iu',
				'Inupiak'               => 'ik',
				'Irish'                 => 'ga',
				'Italian'               => 'it',
				'Japanese'              => 'ja',
				'Javanese'              => 'jv',
				'Kannada'               => 'kn',
				'Kanuri'                => 'kr',
				'Kashmiri'              => 'ks',
				'Kazakh'                => 'kk',
				'Kikuyu'                => 'ki',
				'Kinyarwanda'           => 'rw',
				'Kirghiz'               => 'ky',
				'Kirundi'               => 'rn',
				'Komi'                  => 'kv',
				'Kongo'                 => 'kg',
				'Korean'                => 'ko',
				'Kurdish'               => 'ku',
				'Kwanyama'              => 'kj',
				'Laothian'              => 'lo',
				'Latvian'               => 'lv',
				'Lingala'               => 'ln',
				'Lithuanian'            => 'lt',
				'Luganda'               => 'lg',
				'Luxembourgish'         => 'lb',
				'Macedonian'            => 'mk',
				'Malagasy'              => 'mg',
				'Malay'                 => 'ms',
				'Malayalam'             => 'ml',
				'Maldivian'             => 'dv',
				'Maltese'               => 'mt',
				'Manx'                  => 'gv',
				'Maori'                 => 'mi',
				'Marathi'               => 'mr',
				'Marshallese'           => 'mh',
				'Moldavian'             => 'mo',
				'Mongolian'             => 'mn',
				'Nauru'                 => 'na',
				'Navajo'                => 'nv',
				'Ndonga'                => 'ng',
				'Nepali'                => 'ne',
				'North Ndebele'         => 'nd',
				'Northern Sami'         => 'se',
				'Dutch'                 => 'nl',
				'Norwegian Bokmål'      => 'no',
				'Norwegian Nynorsk'     => 'nn',
				'Occitan'               => 'oc',
				'Old Slavonic'          => 'cu',
				'Oriya'                 => 'or',
				'Oromo'                 => 'om',
				'Ossetian'              => 'os',
				'Pali'                  => 'pi',
				'Pashto'                => 'ps',
				'Persian'               => 'fa',
				'Polish'                => 'pl',
				'Portuguese, Brazil'    => 'pt-br',
				'Portuguese, Portugal'  => 'pt',
				'Punjabi'               => 'pa',
				'Quechua'               => 'qu',
				'Rhaeto-Romance'        => 'rm',
				'Romanian'              => 'ro',
				'Russian'               => 'ru',
				'Samoan'                => 'sm',
				'Sango'                 => 'sg',
				'Sanskrit'              => 'sa',
				'Sardinian'             => 'sc',
				'Scots Gaelic'          => 'gd',
				'Serbian'               => 'sr',
				'Serbo-Croatian'        => 'sh',
				'Sesotho'               => 'st',
				'Setswana'              => 'tn',
				'Shona'                 => 'sn',
				'Sindhi'                => 'sd',
				'Singhalese'            => 'si',
				'Siswati'               => 'ss',
				'Slavic'                => 'sla',
				'Slovak'                => 'sk',
				'Slovenian'             => 'sl',
				'Somali'                => 'so',
				'South Ndebele'         => 'nr',
				'Spanish'               => 'es',
				'Sudanese'              => 'su',
				'Swahili'               => 'sw',
				'Swedish'               => 'sv',
				'Tagalog'               => 'tl',
				'Tahitian'              => 'ty',
				'Tajik'                 => 'tg',
				'Tamil'                 => 'ta',
				'Tatar'                 => 'tt',
				'Telugu'                => 'te',
				'Thai'                  => 'th',
				'Tibetan'               => 'bo',
				'Tigrinya'              => 'ti',
				'Tonga'                 => 'to',
				'Tsonga'                => 'ts',
				'Turkish'               => 'tr',
				'Turkmen'               => 'tk',
				'Twi'                   => 'tw',
				'Uighur'                => 'ug',
				'Ukrainian'             => 'uk',
				'Urdu'                  => 'ur',
				'Uzbek'                 => 'uz',
				'Venda'                 => 've',
				'Vietnamese'            => 'vi',
				'Welsh'                 => 'cy',
				'Wolof'                 => 'wo',
				'Xhosa'                 => 'xh',
				'Yiddish'               => 'yi',
				'Yoruba'                => 'yo',
				'Zhuang'                => 'za',
				'Zulu'                  => 'zu',
			);

		}
		return $this->languages;
	}

	/**
	 * Localize list of languages.
	 *
	 * @return array
	 */
	public function load_config() {
		$data = array();
		foreach ( $this->get_languages() as $language => $code ) {
			$data[] = array(
				'code' => $code,
				'name' => $language,
			);
		}
		return $data;
	}
}
