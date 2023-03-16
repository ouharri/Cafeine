<?php
/**
 * CookieYes Web app storage class.
 *
 * @link       https://www.cookieyes.com/
 * @since      3.0.0
 *
 * @package    CookieYes\Lite\Integrations\Cookieyes\Includes
 */

namespace CookieYes\Lite\Integrations\Cookieyes\Includes;

use Exception;
use WP_Error;
use CookieYes\Lite\Integrations\Cookieyes\Cookieyes;
use CookieYes\Lite\Admin\Modules\Settings\Includes\Settings;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Request
 */
abstract class Cloud extends Cookieyes {

	/**
	 * Array of allowed methods
	 *
	 * @since 3.0.0
	 * @var array
	 */
	private static $allowed_methods = array(
		'bulk_read',
		'read',
		'create',
		'update',
		'remove',
		'get_template',
	);

	/**
	 * Perform a request based on the license status
	 *
	 * @since 3.0.0
	 * @param string  $request Request type. Allowed types read, write, update & delete.
	 * @param mixed   $value Request value eg: Id or slug.
	 * @param boolean $return Check if function returns any value.
	 * @return array
	 */
	public function prepare_request( $request = 'read', $value = false, $return = true ) {
		try {
			$cloud = false;
			if ( ! in_array( $request, self::$allowed_methods, true ) ) {
				return new WP_Error( 'invalid-method', sprintf( __( 'Ivalid method.', 'cookie-law-info' ), __METHOD__ ), array( 'status' => 405 ) );
			}
			if ( true === $this->is_connected() ) {
				$cloud = true;
			}
			if ( is_callable( array( $this, $request ) ) ) {
				if ( false === $value ) {
					$data = $this->{$request}( $cloud );
				} else {
					$data = $this->{$request}( $value, $cloud );

				}
				if ( true === $return ) {
					return $data;
				}
			}
		} catch ( Exception $e ) {
			return new WP_Error( 'invalid-method', sprintf( __( 'Ivalid method.', 'cookie-law-info' ), __METHOD__ ), array( 'status' => 405 ) );
		}
	}
	/**
	 * Read data from the local database or cloud
	 *
	 * @since 3.0.0
	 * @param integer $id Id of the corresponding object.
	 * @param boolean $cloud Decides whether to read data from cloud or local database.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function read( $id, $cloud ) {
		// translators: %s: Class method name.
		return new WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'cookie-law-info' ), __METHOD__ ), array( 'status' => 405 ) );
	}

	/**
	 * Add data to the local database or from cloud
	 *
	 * @since 3.0.0
	 * @param integer $object Corresponding object.
	 * @param boolean $cloud Decides whether to read data from cloud or local database.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function create( $object, $cloud ) {
		// translators: %s: Class method name.
		return new WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'cookie-law-info' ), __METHOD__ ), array( 'status' => 405 ) );
	}

	/**
	 * Delete data from the local database or from cloud
	 *
	 * @since 3.0.0
	 * @param integer $id Id of the corresponding object.
	 * @param boolean $cloud Decides whether to read data from cloud or local database.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function update( $id, $cloud ) {
		// translators: %s: Class method name.
		return new WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'cookie-law-info' ), __METHOD__ ), array( 'status' => 405 ) );
	}

	/**
	 * Add data to the local database or from cloud
	 *
	 * @since 3.0.0
	 * @param integer $object Corresponding object.
	 * @param boolean $cloud Decides whether to read data from cloud or local database.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function remove( $object, $cloud ) {
		// translators: %s: Class method name.
		return new WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'cookie-law-info' ), __METHOD__ ), array( 'status' => 405 ) );
	}

	/**
	 * Get template of a banner, only supported for Banner class
	 *
	 * @since 3.0.0
	 * @param object  $object Object of the corresponding class.
	 * @param boolean $cloud Decides whether to read data from cloud or local database.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function get_template( $object, $cloud ) {
		// translators: %s: Class method name.
		return new WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'cookie-law-info' ), __METHOD__ ), array( 'status' => 405 ) );
	}

	/**
	 * Check if the plugin is connected to the web app.
	 *
	 * @return boolean
	 */
	public function is_connected() {
		$settings = new Settings();
		return $settings->is_connected();
	}

}
