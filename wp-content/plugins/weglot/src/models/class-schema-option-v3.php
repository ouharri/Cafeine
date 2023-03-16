<?php

namespace WeglotWP\Models;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Flag_Type;


class Schema_Option_V3 {

	/**
	 * @since 3.0.0
	 * @return array
	 */
	public static function get_schema_options_v3_compatible() {
		$schema = array(
			'api_key'                   => 'api_key',
			'api_key_private'           => 'api_key_private',
			'allowed'                   => 'allowed',
			'original_language'         => 'language_from',
			'language_from_custom_flag' => 'language_from_custom_flag',
			'language_from_custom_name' => 'language_from_custom_name',
			'translation_engine'        => 'translation_engine',
			'destination_language'      => (object) array(
				'path' => 'languages',
				'fn'   => function( $languages ) {
					$destinations = array();
					if ( ! $languages ) {
						return $destinations;
					}
					foreach ( $languages as $item ) {
						$destinations[] = array(
							'language_to'       => $item['language_to'],
							'custom_code'       => $item['custom_code'],
							'custom_name'       => $item['custom_name'],
							'custom_local_name' => $item['custom_local_name'],
							'public'            => $item['enabled'],
						);
					}

					return $destinations;
				},
			),
			'private_mode'              => (object) array(
				'path' => 'languages',
				'fn'   => function( $languages ) {
					$private = array();
					foreach ( $languages as $item ) {
						if ( ! $item['enabled'] ) {
							$private[ $item['language_to'] ] = true;
						} else {
							$private[ $item['language_to'] ] = false;
						}
					}

					return $private;
				},
			),
			'auto_redirect'             => 'auto_switch',
			'autoswitch_fallback'       => 'auto_switch_fallback',
			'exclude_urls'              => 'excluded_paths',
			'exclude_blocks'            => (object) array(
				'path' => 'excluded_blocks',
				'fn'   => function( $excluded_blocks ) {
					$excluded = array();
					if ( ! $excluded_blocks ) {
						return $excluded;
					}
					foreach ( $excluded_blocks as $item ) {
						$excluded[] = $item['value'];
					}
					return $excluded;
				},
			),
			'custom_settings'           => 'custom_settings',
			'is_dropdown'               => 'custom_settings.button_style.is_dropdown',
			'is_fullname'               => 'custom_settings.button_style.full_name',
			'with_name'                 => 'custom_settings.button_style.with_name',
			'with_flags'                => 'custom_settings.button_style.with_flags',
			'type_flags'                => (object) array(
				'path' => 'custom_settings.button_style.flag_type',
				'fn'   => function( $flag_type ) {
					if ( $flag_type ) {
						return $flag_type;
					}

					return Helper_Flag_Type::RECTANGLE_MAT;
				},
			),
			'override_css'              => 'custom_settings.button_style.custom_css',
			'email_translate'           => 'custom_settings.translate_email',
			'active_search'             => 'custom_settings.translate_search',
			'translate_amp'             => 'custom_settings.translate_amp',
			'has_first_settings'        => 'has_first_settings',
			'show_box_first_settings'   => 'show_box_first_settings',
			'custom_urls'               => (object) array(
				'path' => 'custom_urls',
				'fn'   => function( $custom_urls ) {
					if ( $custom_urls ) {
						return $custom_urls;
					}

					return array();
				},
			),
			'page_views_enabled'        => 'page_views_enabled',
			'flag_css'                  => 'flag_css',
			'menu_switcher'             => 'menu_switcher',
			'active_wc_reload'          => 'active_wc_reload',
			'versions'                  => 'versions',
			'slugTranslation'           => 'versions.slugTranslation',
			'translation'               => 'versions.translation',
		);

		return $schema;
	}
}
