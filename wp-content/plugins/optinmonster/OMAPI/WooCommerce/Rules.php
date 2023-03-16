<?php
/**
 * WooCommerce Rules class.
 *
 * @since 2.8.0
 *
 * @package OMAPI
 * @author  Gabriel Oliveira
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce_Rules class.
 *
 * @since 2.8.0
 */
class OMAPI_WooCommerce_Rules extends OMAPI_Rules_Base {

	/**
	 * Holds the meta fields used for checking output statuses.
	 *
	 * @since 2.8.0
	 *
	 * @var array
	 */
	protected $fields = array(
		'show_on_woocommerce',
		'is_wc_shop',
		'is_wc_product',
		'is_wc_cart',
		'is_wc_checkout',
		'is_wc_account',
		'is_wc_endpoint',
		'is_wc_endpoint_order_pay',
		'is_wc_endpoint_order_received',
		'is_wc_endpoint_view_order',
		'is_wc_endpoint_edit_account',
		'is_wc_endpoint_edit_address',
		'is_wc_endpoint_lost_password',
		'is_wc_endpoint_customer_logout',
		'is_wc_endpoint_add_payment_method',
		'is_wc_product_category',
		'is_wc_product_tag',
	);

	/**
	 * Check for woocommerce rules.
	 *
	 * @since 1.5.0
	 * @since 2.8.0 Migrated from OMAPI_Rules
	 *
	 * @throws OMAPI_Rules_False|OMAPI_Rules_True
	 * @return void
	 */
	public function run_checks() {

		// If WooCommerce is not connected we can ignore the WooCommerce specific settings.
		if ( ! OMAPI_WooCommerce::is_connected() ) {
			return;
		}

		if (
			! $this->rules->is_inline_check
			// Separate never checks for WooCommerce pages that don't ID match
			// No global check on purpose. Global is still true if only this setting is populated.
			&& $this->rules->item_in_field( wc_get_page_id( 'shop' ), 'never' )
			&& is_shop()
		) {
			throw new OMAPI_Rules_False( 'never on wc is_shop' );
		}

		try {
			$this->check_fields();
		} catch ( OMAPI_Rules_Exception $e ) {
			if ( $e instanceof OMAPI_Rules_True ) {
				throw new OMAPI_Rules_True( 'include woocommerce', 0, $e );
			}
			$this->rules->add_reason( $e );
		}
	}

	/**
	 * Check for woocommerce rule fields.
	 *
	 * @since 1.5.0
	 * @since 2.8.0 Migrated from OMAPI_Rules
	 *
	 * @throws OMAPI_Rules_True
	 * @return void
	 */
	protected function check_fields() {

		$wc_checks = array(
			'show_on_woocommerce'               => array( 'is_woocommerce' ), // is woocommerce anything
			'is_wc_shop'                        => array( 'is_shop' ),
			'is_wc_product'                     => array( 'is_product' ),
			'is_wc_cart'                        => array( 'is_cart' ),
			'is_wc_checkout'                    => array( 'is_checkout' ),
			'is_wc_account'                     => array( 'is_account_page' ),
			'is_wc_endpoint'                    => array( 'is_wc_endpoint_url' ),
			'is_wc_endpoint_order_pay'          => array( 'is_wc_endpoint_url', 'order-pay' ),
			'is_wc_endpoint_order_received'     => array( 'is_wc_endpoint_url', 'order-received' ),
			'is_wc_endpoint_view_order'         => array( 'is_wc_endpoint_url', 'view-order' ),
			'is_wc_endpoint_edit_account'       => array( 'is_wc_endpoint_url', 'edit-account' ),
			'is_wc_endpoint_edit_address'       => array( 'is_wc_endpoint_url', 'edit-address' ),
			'is_wc_endpoint_lost_password'      => array( 'is_wc_endpoint_url', 'lost-password' ),
			'is_wc_endpoint_customer_logout'    => array( 'is_wc_endpoint_url', 'customer-logout' ),
			'is_wc_endpoint_add_payment_method' => array( 'is_wc_endpoint_url', 'add-payment-method' ),
		);

		foreach ( $wc_checks as $field => $callback ) {
			$this->check_field( $field, $callback );
		}
	}

	/**
	 * Check for woocommerce rule field.
	 *
	 * @since 2.10.0
	 *
	 * @param  string $field    The field to check.
	 * @param  array  $callback The callback to check.
	 *
	 * @return void
	 * @throws OMAPI_Rules_True
	 */
	protected function check_field( $field, $callback ) {
		if ( $this->rules->field_empty( $field ) ) {
			return;
		}

		$this->rules
			->set_global_override( false )
			->set_advanced_settings_field( $field, $this->rules->get_field_value( $field ) );

		if ( call_user_func_array( array_shift( $callback ), $callback ) ) {
			// If it passes, send it back.
			throw new OMAPI_Rules_True( $field );
		}
	}
}
