<?php

namespace WeglotWP\Actions\Front;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Button_Service_Weglot;

use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Option_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;

/**
 *
 * @since 2.0
 *
 */
class Front_Menu_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Button_Service_Weglot
	 */
	private $button_services;
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;
	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;

	/**
	 * @since 2.4.0
	 */
	public function __construct() {
		$this->option_services      = weglot_get_service( 'Option_Service_Weglot' );
		$this->button_services      = weglot_get_service( 'Button_Service_Weglot' );
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
		$this->language_services    = weglot_get_service( 'Language_Service_Weglot' );
	}

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.4.0
	 * @return void
	 */
	public function hooks() {
		if ( is_admin() ) {
			return;
		}

		if ( ! $this->option_services->get_option( 'api_key' ) ) {
			return;
		}


		add_filter( 'wp_get_nav_menu_items', array( $this, 'weglot_wp_get_nav_menu_items' ), 20 );
		add_filter( 'nav_menu_link_attributes', array( $this, 'add_nav_menu_link_attributes_atts' ), 10, 3 );
		add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ) );
	}

	/**
	 * @since 2.4.0
	 * @param array $items
	 * @return array
	 */
	public function weglot_wp_get_nav_menu_items( $items ) {

		// Prevent customizer.
		if ( doing_action( 'customize_register' ) ) {
			return $items;
		}

		$new_items = array();
		$offset    = 0;

		foreach ( $items as $key => $item ) {

			if ( strpos( $item->post_name, 'weglot-switcher' ) === false ) {
				$item->menu_order += $offset;
				$new_items[]       = $item;
				continue;
			}
			$id = $item->ID;
			$i  = 0;

			$classes    = array( 'weglot-lang', 'menu-item-weglot', 'weglot-language' );
			$options    = $this->option_services->get_option( 'menu_switcher' );
			$with_flags = $this->option_services->get_option_button( 'with_flags' );
			$dropdown   = 0;
			if ( isset( $options[ 'menu-item-' . $id ] ) && isset( $options[ 'menu-item-' . $id ]['dropdown'] ) ) {
				$dropdown = $options[ 'menu-item-' . $id ]['dropdown'];
			}
			$hide_current = 0;
			if ( isset( $options[ 'menu-item-' . $id ] ) && isset( $options[ 'menu-item-' . $id ]['hide_current'] ) ) {
				$hide_current = $options[ 'menu-item-' . $id ]['hide_current'];
			}

			if ( ! $hide_current && $with_flags ) {
				$classes = array_merge( $classes, explode( ' ', $this->button_services->get_flag_class() ) );
			}

			$current_language   = $this->request_url_services->get_current_language();
			$hide_all_languages = true;
			$show_all_languages = true;
			$array_excluded     = array();
			foreach ( $this->language_services->get_original_and_destination_languages( $this->request_url_services->is_allowed_private() ) as $key => $language ) {
				if ( $this->request_url_services->get_weglot_url()->getExcludeOption( $language, 'language_button_displayed' ) ) {
					$hide_all_languages = false;
				} else {
					$show_all_languages = false;
				}
				$array_excluded[ $language->getInternalCode() ] = $this->request_url_services->get_weglot_url()->getExcludeOption( $language, 'language_button_displayed' );
			}

			if ( $dropdown && ! $hide_all_languages ) {
				$title = __( 'Choose your language', 'weglot' );
				if ( ! $hide_current ) {
					$title = $this->button_services->get_name_with_language_entry( $current_language );
				}
				$item->title      = apply_filters( 'weglot_menu_parent_menu_item_title', $title );
				$item->attr_title = $current_language->getLocalName();
				$item->classes    = array_merge( array( 'weglot-parent-menu-item' ), $classes, array( $current_language->getInternalCode() ) );
				$new_items[]      = $item;
				$offset ++;
			}

			foreach ( $this->language_services->get_original_and_destination_languages( $this->request_url_services->is_allowed_private() ) as $language ) {

				// check if for this button we ant to exclude the button from switcher.
				$language_button_displayed = $this->request_url_services->get_weglot_url()->getExcludeOption( $language, 'language_button_displayed' );
				$link_button               = $this->request_url_services->get_weglot_url()->getForLanguage( $language, true );

				if ( $dropdown && ! $show_all_languages && $current_language->getInternalCode() === $language->getInternalCode() ||
				     ( $dropdown && $show_all_languages && $current_language->getInternalCode() === $language->getInternalCode() ) ||
				     ( $hide_current && $current_language->getInternalCode() === $language->getInternalCode() )) {
					continue;
				}

				if ( ! $language_button_displayed ) {
					$link_button = $this->request_url_services->get_weglot_url()->getForLanguage( $language, false );
				}

				if ( ! $dropdown && ! $hide_all_languages && $current_language->getInternalCode() === $language->getInternalCode() && ! $array_excluded[ $current_language->getInternalCode() ] ) {
					$link_button = $this->request_url_services->get_weglot_url()->getForLanguage( $language, true );
				}

				if ( ! $link_button || $hide_all_languages ) {
					continue;
				}

				$add_classes = array();
				// Just for children without flag classes.
				if ( $hide_current && $with_flags ) {
					$classes = array_merge( $classes, explode( ' ', $this->button_services->get_flag_class() ) );
				}

				$add_classes[] = 'weglot-' . $language->getInternalCode();
				if ( $with_flags ) {
					$add_classes[] = $language->getInternalCode();
				}

				if ( $this->option_services->get_option( 'auto_redirect' )
				) {
					$is_orig = $language === $this->language_services->get_original_language() ? 'true' : 'false';
					if ( strpos( $link_button, '?' ) !== false ) {
						$link_button = str_replace( '?', "?wg-choose-original=$is_orig&", $link_button );
					} else {
						$link_button .= "?wg-choose-original=$is_orig";
					}
				}

				$language_item              = clone $item;
				$language_item->ID          = 'weglot-' . $item->ID . '-' . $language->getInternalCode();
				$language_item->title       = $this->button_services->get_name_with_language_entry( $language );
				$language_item->attr_title  = $language->getLocalName();
				$language_item->url         = $link_button;
				$language_item->lang        = $language->getInternalCode();
				$language_item->classes     = array_merge( $classes, $add_classes );
				$language_item->menu_order += $offset + $i++;
				if ( $dropdown ) {
					$language_item->menu_item_parent = $item->db_id;
					$language_item->db_id            = 0;
				}

				$new_items[] = $language_item;
			}
			$offset += $i - 1;
		}

		return $new_items;
	}

	/**
	 * @since 2.7.0
	 * @param object $item
	 * @return array
	 */
	public function get_ancestors( $item ) {
		$ids     = array();
		$_anc_id = (int) $item->db_id;
		$_anc_id = get_post_meta( $_anc_id, '_menu_item_menu_item_parent', true );
		while ( isset( $_anc_id ) && ! in_array( $_anc_id, $ids, true ) ) {
			$ids[]   = $_anc_id;
			$_anc_id = get_post_meta( $_anc_id, '_menu_item_menu_item_parent', true );
		}
		return $ids;
	}

	/**
	 * @since 2.7.0
	 * @param array $items
	 * @return array
	 */
	public function wp_nav_menu_objects( $items ) {
		$r_ids = array();
		$k_ids = array();

		foreach ( $items as $item ) {
			if ( ! empty( $item->classes ) && is_array( $item->classes ) ) {
				if ( in_array( 'menu-item-weglot', $item->classes, true ) ) {
					$item->current = false;
					$item->classes = array_diff( $item->classes, array( 'current-menu-item' ) );
					$r_ids         = array_merge( $r_ids, $this->get_ancestors( $item ) ); // Remove the classes for these ancestors.
				} elseif ( in_array( 'current-menu-item', $item->classes, true ) ) {
					$k_ids = array_merge( $k_ids, $this->get_ancestors( $item ) ); // Keep the classes for these ancestors.
				}
			}
		}

		$r_ids = array_diff( $r_ids, $k_ids );

		foreach ( $items as $item ) {
			if ( ! empty( $item->db_id ) && in_array( $item->db_id, $r_ids, true ) ) {
				$item->classes = array_diff( $item->classes, array( 'current-menu-ancestor', 'current-menu-parent', 'current_page_parent', 'current_page_ancestor' ) );
			}
		}

		if ( apply_filters( 'weglot_active_current_menu_item', false ) ) {
			$current_language = $this->request_url_services->get_current_language()->getInternalCode();
			foreach ( $items as $item ) {
				if ( ! empty( $item->classes ) && is_array( $item->classes ) ) {
					if ( in_array( 'menu-item-weglot', $item->classes, true ) && in_array( 'weglot-' . $current_language, $item->classes, true ) ) {
						$item->classes[] = 'current-menu-item';
					}
				}
			}
		}

		return $items;
	}


	/**
	 * @since 2.0
	 * @version 2.4.0
	 * @see nav_menu_link_attributes_atts
	 * @param array $attrs
	 * @param object $item
	 * @return array
	 */
	public function add_nav_menu_link_attributes_atts( $attrs, $item, $args ) {
		$str = 'weglot-switcher';
		if ( strpos( $item->post_name, $str ) !== false ) {
			$attrs['data-wg-notranslate'] = 'true';
		}
		return $attrs;
	}
}

