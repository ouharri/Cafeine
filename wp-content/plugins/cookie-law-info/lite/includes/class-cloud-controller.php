<?php
/**
 * WordPress file sytstem API.
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 *
 * @package    CookieYes\Lite\Includes
 */

namespace CookieYes\Lite\Includes;

use CookieYes\Lite\Integrations\Cookieyes\Cookieyes;

use CookieYes\Lite\Includes\Cache;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Controller Class
 *
 * @package CookieYes
 * @version  3.0.0
 */
abstract class Cloud_Controller extends CookieYes {
	/**
	 * Cache group.
	 *
	 * @var string
	 */
	protected $cache_group = '';

	/**
	 * Item unique identifier
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Load items from cache if any.
	 *
	 * @param boolean|integer $id Item id.
	 * @return array
	 */
	protected function get_cache( $id = false ) {
		$items  = array();
		$cached = Cache::get( 'all', $this->cache_group );
		if ( false === $cached ) {
			return false;
		}
		if ( ! empty( $cached ) ) {
			foreach ( $cached as $data ) {
				$item = $this->prepare_item( $data );
				if ( ! empty( $item ) ) {
					$items[ $item->{$this->id} ] = $item;
				}
			}
		}
		return isset( $id ) && isset( $items[ $id ] ) ? $items[ $id ] : $items;
	}
	/**
	 * Set items to the cache.
	 *
	 * @param array $data Data.
	 * @return void
	 */
	protected function set_cache( $data = array() ) {
		Cache::set( 'all', $this->cache_group, $data );
	}

	/**
	 * Delete the cache.
	 *
	 * @return void
	 */
	public function delete_cache() {
		Cache::delete( $this->cache_group );
	}

	/**
	 * Reset the cache on page load.
	 *
	 * @return void
	 */
	public function reset_cache() {
		if ( cky_is_admin_request() && cky_is_admin_page() ) {
			Cache::delete( $this->cache_group );
		}
	}

	/**
	 *  Get multiple items.
	 *
	 * @param array $args Arguments.
	 * @return array
	 */
	public function get_items( $args = array() ) {
		$cached = $this->get_cache();
		if ( false !== $cached ) {
			return $cached;
		}
		$items = $this->get_item_from_db( $args );
		$this->set_cache( $items );
		return $items;
	}

	/**
	 * Get a single item.
	 *
	 * @param integer $id Item ID.
	 * @return array|object
	 */
	public function get_item( $id ) {
		$cached = $this->get_cache( $id );
		if ( false !== $cached ) {
			return $cached;
		}
		$item = $this->get_item_from_db( array( 'id' => $id ) );
		return $item;
	}

	/**
	 * Load data directly from database.
	 *
	 * @param array $args Array of arguments.
	 * @return array|object
	 */
	abstract protected function get_item_from_db( $args = array() );

	/**
	 * Create an item.
	 *
	 * @param object $object Item object.
	 * @return void
	 */
	abstract public function create_item( $object );

	/**
	 * Update an item.
	 *
	 * @param object $object Item object.
	 * @return void
	 */
	abstract public function update_item( $object );

	/**
	 * Delete an item.
	 *
	 * @param object $object Item object.
	 * @return void
	 */
	abstract public function delete_item( $object );

	/**
	 * Delete an item.
	 *
	 * @param object $object Item object.
	 * @return array|object
	 */
	abstract public function prepare_item( $object );


}
