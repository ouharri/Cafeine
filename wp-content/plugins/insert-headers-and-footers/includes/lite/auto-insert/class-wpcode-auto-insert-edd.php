<?php
/**
 * Easy Digital Downloads specific auto-insert locations.
 *
 * @package WPCode
 */

/**
 * Class WPCode_Auto_Insert_EDD.
 */
class WPCode_Auto_Insert_EDD_Lite extends WPCode_Auto_Insert_Type {
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
		$this->label         = 'Easy Digital Downloads';
		$this->locations     = array(
			'edd_purchase_link_top'       => __( 'Before the Purchase Button', 'insert-headers-and-footers' ),
			'edd_purchase_link_end'       => __( 'After the Purchase Button', 'insert-headers-and-footers' ),
			'edd_before_download_content' => __( 'Before the Single Download', 'insert-headers-and-footers' ),
			'edd_after_download_content'  => __( 'After the Single Download', 'insert-headers-and-footers' ),
			'edd_before_cart'             => __( 'Before the Cart', 'insert-headers-and-footers' ),
			'edd_after_cart'              => __( 'After the Cart', 'insert-headers-and-footers' ),
			'edd_before_checkout_cart'    => __( 'Before the Checkout Cart', 'insert-headers-and-footers' ),
			'edd_after_checkout_cart'     => __( 'After the Checkout Cart', 'insert-headers-and-footers' ),
			'edd_before_purchase_form'    => __( 'Before the Checkout Form', 'insert-headers-and-footers' ),
			'edd_after_purchase_form'     => __( 'After the Checkout Form', 'insert-headers-and-footers' ),
		);
		$this->upgrade_title = __( 'Easy Digital Downloads Locations are a PRO feature', 'insert-headers-and-footers' );
		$this->upgrade_text  = __( 'Upgrade to PRO today and get access to advanced eCommerce auto-insert locations and conditional logic rules for your needs.', 'insert-headers-and-footers' );
		$this->upgrade_link  = wpcode_utm_url( 'https://wpcode.com/lite/', 'edit-snippet', 'auto-insert', 'edd' );
	}
}

new WPCode_Auto_Insert_EDD_Lite();
