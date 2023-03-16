<?php
/**
 * WooCommerce-specific auto-insert locations.
 *
 * @package WPCode
 */

/**
 * Class WPCode_Auto_Insert_WooCommerce_Lite.
 */
class WPCode_Auto_Insert_WooCommerce_Lite extends WPCode_Auto_Insert_Type {

	/**
	 * Not available to select.
	 *
	 * @var string
	 */
	public $code_type = 'pro';
	/**
	 * Text to display next to optgroup label.
	 *
	 * @var string
	 */
	public $label_pill = 'PRO';

	/**
	 * Load the available options and labels.
	 *
	 * @return void
	 */
	public function init() {
		$this->label     = 'WooCommerce';
		$this->locations = array(
			'wc_before_products_list'              => __( 'Before the List of Products', 'insert-headers-and-footers' ),
			'wc_after_products_list'               => __( 'After the List of Products', 'insert-headers-and-footers' ),
			'wc_before_single_product'             => __( 'Before the Single Product', 'insert-headers-and-footers' ),
			'wc_after_single_product'              => __( 'After the Single Product', 'insert-headers-and-footers' ),
			'wc_before_single_product_summary'     => __( 'Before the Single Product Summary', 'insert-headers-and-footers' ),
			'wc_after_single_product_summary'      => __( 'After the Single Product Summary', 'insert-headers-and-footers' ),
			'woocommerce_before_cart'              => __( 'Before the Cart', 'insert-headers-and-footers' ),
			'woocommerce_after_cart'               => __( 'After the Cart', 'insert-headers-and-footers' ),
			'woocommerce_before_checkout_form'     => __( 'Before the Checkout Form', 'insert-headers-and-footers' ),
			'woocommerce_after_checkout_form'      => __( 'After the Checkout Form', 'insert-headers-and-footers' ),
			'woocommerce_checkout_order_review_19' => __( 'Before Checkout Payment Methods', 'insert-headers-and-footers' ),
			'woocommerce_checkout_order_review_21' => __( 'After Checkout Payment Button', 'insert-headers-and-footers' ),
			'woocommerce_before_thankyou'          => __( 'Before the Thank You Page Content', 'insert-headers-and-footers' ),
		);
		$this->upgrade_title = __( 'WooCommerce Locations are a PRO feature', 'insert-headers-and-footers' );
		$this->upgrade_text  = __( 'Upgrade to PRO today and get access to advanced eCommerce auto-insert locations and conditional logic rules for your needs.', 'insert-headers-and-footers' );
		$this->upgrade_link  = wpcode_utm_url( 'https://wpcode.com/lite/', 'edit-snippet', 'auto-insert', 'woocommerce' );
	}
}

new WPCode_Auto_Insert_WooCommerce_Lite();
