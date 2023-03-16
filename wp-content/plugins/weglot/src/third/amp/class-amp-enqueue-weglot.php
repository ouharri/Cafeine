<?php

namespace WeglotWP\Third\Amp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Helpers\Helper_Flag_Type;
use WeglotWP\Services\Button_Service_Weglot;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Option_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;


/**
 * Amp_Enqueue_Weglot
 *
 * @since 2.0
 */
class Amp_Enqueue_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services = weglot_get_service( 'Option_Service_Weglot' );
	}

	/**
	 * @return void
	 * @throws Exception
	 * @since 2.0
	 * @see Hooks_Interface_Weglot
	 *
	 */
	public function hooks() {
		if ( ! defined( 'AMPFORWP_PLUGIN_DIR' ) && ! defined( 'AMP__VERSION' ) ) {
			return;
		}

		$translate_amp = $this->option_services->get_option( 'translate_amp' );

		if ( empty( $translate_amp ) ) {
			return;
		}

		add_action( 'weglot_render_dom', array( $this, 'weglot_amp_css' ) );
	}


	/**
	 * @param $html
	 * @return string
	 * @throws Exception
	 * @since 3.1.7
	 */
	public function weglot_amp_css( $html ) {

		/** @var Request_Url_Service_Weglot $request_url_service */
		$request_url_service = weglot_get_service( 'Request_Url_Service_Weglot' );
		$weglot_url          = $request_url_service->get_weglot_url();

		/** @var Amp_Service_Weglot $amp_service */
		$amp_service = weglot_get_service( 'Amp_Service_Weglot' );
		$amp_regex   = $amp_service->get_regex( true );

		if ( ! $this->option_services->get_option_custom_settings( 'translate_amp' ) || ! preg_match( '#' . $amp_regex . '#', $weglot_url->getUrl() ) === 1 ) {
			return $html;
		}

		/** @var Language_Service_Weglot $language_service */
		$language_service = weglot_get_service( 'Language_Service_Weglot' );

		$languages_configured = $language_service->get_original_and_destination_languages( $request_url_service->is_allowed_private() );
		$flags_positions      = $this->weglot_get_flags_positions();
		$type_flags           = weglot_get_option( 'type_flags' );
		$type_flags           = Helper_Flag_Type::get_flag_number_with_type( $type_flags );
		$with_flags           = weglot_get_option( 'with_flags' );
		$css                  = str_replace( '../images/', WEGLOT_URL_DIST . '/images/', file_get_contents( WEGLOT_DIR_DIST . '/css/front-amp-css.css' ) );

		$s = array(
			'!important',
			'.weglot-flags',
		);

		$r = array(
			'',
			'.weglot-flags.weglot-lang',
		);

		$css .= str_replace($s, $r, $this->option_services->get_flag_css()); //phpcs:ignore

		if ( $with_flags ) {
			foreach ( $languages_configured as $lang ) {

				$lang = $lang->getInternalCode();
				if ( ! empty( $flags_positions[ $type_flags ][ $lang ] ) ) {
					$css .= '.weglot-flags.flag-' . $type_flags . '.' . $lang . ' > a:before, .weglot-flags.flag-' . $type_flags . '.' . $lang . ' > span:before { background-position: ' . $flags_positions[ $type_flags ][ $lang ] . 'px 0; }';
				}
			}
		}

		$html = preg_replace( '#<style amp-custom(.*?)>#', '<style amp-custom$1>' . $css, $html );

		return $html;
	}

	public function weglot_get_flags_positions() {
		return array(
			array(
				'hw' => -3570,
				'af' => -6570,
				'fl' => -3060,
				'sq' => -2580,
				'am' => -5130,
				'ar' => -510,
				'hy' => -1800,
				'az' => -6840,
				'ba' => -2040,
				'eu' => -7260,
				'be' => -5310,
				'bn' => -5400,
				'bs' => -6390,
				'bg' => -2730,
				'my' => -3299,
				'ca' => -7230,
				'zh' => -3690,
				'tw' => -2970,
				'km' => -6930,
				'ny' => -1140,
				'co' => -2520,
				'hr' => -5910,
				'cs' => -2700,
				'da' => -2670,
				'nl' => -2100,
				'en' => -1920,
				'eo' => -1920,
				'et' => -2640,
				'fj' => -1710,
				'fi' => -2550,
				'fr' => -2520,
				'gl' => -7290,
				'ka' => -5040,
				'de' => -2490,
				'el' => -2460,
				'gu' => -1170,
				'ht' => -4650,
				'ha' => -900,
				'he' => -1050,
				'hi' => -1170,
				'hu' => -2430,
				'is' => -2400,
				'ig' => -870,
				'id' => -3510,
				'ga' => -2340,
				'it' => -2310,
				'ja' => -3480,
				'jv' => -3360,
				'kn' => -1170,
				'kk' => -3150,
				'ko' => -6990,
				'ku' => -2430,
				'ky' => -3420,
				'lo' => -3450,
				'la' => -2310,
				'lv' => -2280,
				'lt' => -2250,
				'lb' => -2220,
				'mk' => -2190,
				'mg' => -1200,
				'ms' => -3360,
				'ml' => -1170,
				'mt' => -2130,
				'mi' => -3240,
				'mr' => -1170,
				'mn' => -6000,
				'ne' => -3270,
				'no' => -5850,
				'ps' => -5189,
				'fa' => -6690,
				'pl' => -2160,
				'pt' => -1740,
				'pa' => -3180,
				'ro' => -2070,
				'ru' => -2040,
				'sm' => -4620,
				'gd' => -30,
				'sr' => -4290,
				'sn' => -540,
				'sd' => -3180,
				'si' => -2820,
				'sk' => -6810,
				'sl' => -2010,
				'so' => -4560,
				'st' => -4830,
				'es' => -480,
				'su' => -4530,
				'sw' => -1290,
				'sv' => -1980,
				'tl' => -3060,
				'ty' => -6270,
				'tg' => -2940,
				'ta' => -1170,
				'tt' => -2040,
				'te' => -1170,
				'th' => -2910,
				'to' => -6540,
				'tr' => -1950,
				'uk' => -1890,
				'ur' => -3180,
				'uz' => -2880,
				'vi' => -2850,
				'cy' => -6420,
				'fy' => -2100,
				'xh' => -6570,
				'yi' => -1050,
				'yo' => -870,
				'zu' => -6570,
				'br' => -6630,
			),
			array(
				'hw' => -7840,
				'fl' => 2560,
				'af' => -6848,
				'sq' => -97,
				'am' => -2369,
				'ar' => -6465,
				'hy' => -385,
				'az' => -513,
				'ba' => -6113,
				'eu' => -8353,
				'be' => -705,
				'bn' => -609,
				'bs' => -929,
				'bg' => -1121,
				'my' => -4929,
				'ca' => -8321,
				'zh' => -1505,
				'tw' => -6369,
				'km' => -1217,
				'ny' => -4289,
				'co' => -2561,
				'hr' => -1793,
				'cs' => -1921,
				'da' => -1985,
				'nl' => -5121,
				'en' => -7777,
				'eo' => -7777,
				'et' => -2337,
				'fj' => -2497,
				'fi' => -2529,
				'fr' => -2561,
				'gl' => -8383,
				'ka' => -2721,
				'de' => -2753,
				'el' => -2881,
				'gu' => -3329,
				'ht' => -3169,
				'ha' => -5281,
				'he' => -3521,
				'hi' => -3329,
				'hu' => -3265,
				'is' => -3297,
				'ig' => -5313,
				'id' => -3361,
				'ga' => -3457,
				'it' => -3553,
				'ja' => -3617,
				'jv' => -4321,
				'kn' => -3329,
				'kk' => -3713,
				'ko' => -6913,
				'ku' => -3265,
				'ky' => -3873,
				'lo' => -3904,
				'la' => -3553,
				'lv' => -3937,
				'lt' => -4129,
				'lb' => -4161,
				'mk' => -4225,
				'mg' => -4257,
				'ms' => -4321,
				'ml' => -3329,
				'mt' => -4417,
				'mi' => -5217,
				'mr' => -3329,
				'mn' => -4769,
				'ne' => -5091,
				'no' => -5505,
				'ps' => -33,
				'fa' => -3393,
				'pl' => -5889,
				'pt' => -5921,
				'pa' => -3329,
				'ro' => -6081,
				'ru' => -6113,
				'sm' => -6369,
				'gd' => -6497,
				'sr' => -6561,
				'sn' => -8287,
				'sd' => -5601,
				'si' => -7039,
				'sk' => -6689,
				'sl' => -6721,
				'so' => -6785,
				'st' => -4001,
				'es' => -7009,
				'su' => -7073,
				'sw' => -3745,
				'sv' => -7169,
				'tl' => -5823,
				'ty' => -2593,
				'tg' => -7297,
				'ta' => -3329,
				'tt' => -6113,
				'te' => -3329,
				'th' => -7361,
				'to' => -7456,
				'tr' => -7553,
				'uk' => -7713,
				'ur' => -5600,
				'uz' => -7969,
				'vi' => -8097,
				'cy' => -8129,
				'fy' => -5121,
				'xh' => -6848,
				'yi' => -3521,
				'yo' => -5313,
				'zu' => -6848,
				'br' => -993,
			),
			array(
				'hw' => -5448,
				'fl' => -1008,
				'af' => -4968,
				'sq' => -2976,
				'am' => -3816,
				'ar' => -768,
				'hy' => 0,
				'az' => -5136,
				'ba' => -936,
				'eu' => -5376,
				'be' => -4224,
				'bn' => -4056,
				'bs' => -3984,
				'bg' => -5040,
				'my' => -1248,
				'ca' => -5352,
				'zh' => -2592,
				'tw' => -3408,
				'km' => -5160,
				'ny' => -1392,
				'co' => -2304,
				'hr' => -4416,
				'cs' => -2472,
				'da' => -2448,
				'nl' => -1296,
				'en' => -312,
				'eo' => -312,
				'et' => -2424,
				'fj' => -576,
				'fi' => -2328,
				'fr' => -2304,
				'gl' => -5400,
				'ka' => -3744,
				'de' => -2256,
				'el' => -2208,
				'gu' => -1728,
				'ht' => -3528,
				'ha' => -1176,
				'he' => -1992,
				'hi' => -1728,
				'hu' => -2088,
				'is' => -2064,
				'ig' => -1103,
				'id' => -2040,
				'ga' => -2016,
				'it' => -1968,
				'ja' => -1920,
				'jv' => -1536,
				'kn' => -1728,
				'kk' => -1704,
				'ko' => -1848,
				'ku' => -2088,
				'ky' => -1800,
				'lo' => -1776,
				'la' => -1968,
				'lv' => -1752,
				'lt' => -1656,
				'lb' => -1632,
				'mk' => -1440,
				'mg' => -1560,
				'ms' => -1536,
				'ml' => -1728,
				'mt' => -1200,
				'mi' => -1224,
				'mr' => -1728,
				'mn' => -4800,
				'ne' => -1320,
				'no' => -4776,
				'ps' => -4008,
				'fa' => -5088,
				'pl' => -984,
				'pt' => -528,
				'pa' => -1728,
				'ro' => -960,
				'ru' => -936,
				'sm' => -3408,
				'gd' => -4872,
				'sr' => -3120,
				'sn' => -72,
				'sd' => -1128,
				'si' => -480,
				'sk' => -4152,
				'sl' => -696,
				'so' => -3336,
				'st' => -3552,
				'es' => -96,
				'su' => -3312,
				'sw' => -1872,
				'sv' => -552,
				'tl' => -1008,
				'ty' => -4512,
				'tg' => -264,
				'ta' => -1728,
				'tt' => -936,
				'te' => -1728,
				'th' => -456,
				'to' => -3264,
				'tr' => -360,
				'uk' => -288,
				'ur' => -1128,
				'uz' => -240,
				'vi' => -144,
				'cy' => -4848,
				'fy' => -1296,
				'xh' => -4968,
				'yi' => -1992,
				'yo' => -1103,
				'zu' => -4968,
				'br' => -2784,
			),
			array(
				'hw' => -2711,
				'fl' => -5232,
				'af' => -5496,
				'sq' => -4776,
				'am' => -192,
				'ar' => -3336,
				'hy' => -4632,
				'az' => -4536,
				'ba' => -2664,
				'eu' => -5808,
				'be' => -144,
				'bn' => -4488,
				'bs' => -4392,
				'bg' => -4296,
				'my' => -3769,
				'ca' => -5784,
				'zh' => -3240,
				'tw' => -4008,
				'km' => -4201,
				'ny' => -384,
				'co' => -2760,
				'hr' => -3048,
				'cs' => -5280,
				'da' => -3024,
				'nl' => -3360,
				'en' => -2520,
				'eo' => -2520,
				'et' => -2856,
				'fj' => -0,
				'fi' => -2784,
				'fr' => -2760,
				'gl' => -5832,
				'ka' => -1536,
				'de' => -1488,
				'el' => -1416,
				'gu' => -2304,
				'ht' => -5160,
				'ha' => -361,
				'he' => -1608,
				'hi' => -2304,
				'hu' => -1920,
				'is' => -840,
				'ig' => -3457,
				'id' => -4992,
				'ga' => -2016,
				'it' => -336,
				'ja' => -2448,
				'jv' => -864,
				'kn' => -2304,
				'kk' => -3912,
				'ko' => -2256,
				'ku' => -1920,
				'ky' => -744,
				'lo' => -3816,
				'la' => -336,
				'lv' => -216,
				'lt' => -1776,
				'lb' => -1945,
				'mk' => -2208,
				'mg' => -5064,
				'ms' => -864,
				'ml' => -2304,
				'mt' => -4920,
				'mi' => -2113,
				'mr' => -2304,
				'mn' => -24,
				'ne' => -5642,
				'no' => -984,
				'ps' => -4753,
				'fa' => -816,
				'pl' => -4944,
				'pt' => -3504,
				'pa' => -2304,
				'ro' => -3744,
				'ru' => -2664,
				'sm' => -1248,
				'gd' => -3841,
				'sr' => -3312,
				'sn' => -5521,
				'sd' => -1993,
				'si' => -2833,
				'sk' => -552,
				'sl' => -936,
				'so' => -4032,
				'st' => -3961,
				'es' => -3576,
				'su' => -3985,
				'sw' => -912,
				'sv' => -264,
				'tl' => -5232,
				'ty' => -1512,
				'tg' => -3720,
				'ta' => -2304,
				'tt' => -2664,
				'te' => -2304,
				'th' => -4848,
				'to' => -1680,
				'tr' => -432,
				'uk' => -5736,
				'ur' => -1992,
				'uz' => -2160,
				'vi' => -3384,
				'cy' => -5040,
				'fy' => -3360,
				'xh' => -5496,
				'yi' => -1608,
				'yo' => -3457,
				'zu' => -5496,
				'br' => -4344,
			),
		);
	}

}
