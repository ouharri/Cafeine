<?php

namespace WeglotWP\Third\Woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;


/**
 * WC_Mail_Weglot
 *
 * @since 3.1.6
 */
class WC_Mail_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @var Wc_Active
	 */
	private $wc_active_services;
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;
	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;

	/**
	 * @return void
	 * @since 3.1.6
	 */
	public function __construct() {
		$this->wc_active_services   = weglot_get_service( 'Wc_Active' );
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
		$this->language_services    = weglot_get_service( 'Language_Service_Weglot' );
	}

	/**
	 * @return void
	 * @since 3.1.6
	 */
	public function hooks() {
		if ( ! $this->wc_active_services->is_active() || ! apply_filters( 'weglot_woocommerce_translate_following_mail', true ) ) {
			return;
		}

		add_action( 'woocommerce_new_order', array( $this, 'save_language' ), 10, 1 );
		add_action( 'woocommerce_mail_callback_params', array( $this, 'translate_following_mail' ), 10, 2 );
	}

	/**
	 * @param $args
	 * @param $mail
	 *
	 * @return array
	 * @since 3.1.6
	 */
	public function translate_following_mail( $args, $mail ) {

		$translate_email = apply_filters( 'weglot_translate_email', weglot_get_option( 'email_translate' ), $args );

		if (
			$translate_email
			&& (
				is_a( $mail->object, 'Automattic\WooCommerce\Admin\Overrides\Order' )
				|| is_a( $mail->object, 'WC_Order' )
			)
		) {

			if ( $mail->is_customer_email() ) { // If mail is for customer
				$woocommerce_order_language = get_post_meta( $mail->object->get_id(), 'weglot_language', true );
				if ( ! empty( $woocommerce_order_language ) ) {

					$current_and_original_language            = array(
						'original' => $this->language_services->get_original_language()->getInternalCode(),
						'current'  => $this->request_url_services->get_current_language()->getInternalCode(),
					);
					$current_and_original_language['current'] = $this->language_services->get_language_from_external( $woocommerce_order_language )->getInternalCode();

					add_filter(
						'weglot_translate_email_languages_forced',
						function () use ( $current_and_original_language ) {
							return $current_and_original_language;
						}
					);
				}
			} else { // If mail is for admin.
				//check if send is send to customer to.
				if ( $mail->object->get_billing_email() === $mail->get_recipient() ) {
					$woocommerce_order_language               = get_post_meta( $mail->object->get_id(), 'weglot_language', true );
					$current_and_original_language            = array(
						'original' => $this->language_services->get_original_language()->getInternalCode(),
						'current'  => $this->request_url_services->get_current_language()->getInternalCode(),
					);
					$current_and_original_language['current'] = $this->language_services->get_language_from_external( $woocommerce_order_language )->getInternalCode();

				} else {
					$current_and_original_language['original'] = $this->language_services->get_original_language()->getInternalCode();
					$current_and_original_language['current']  = $current_and_original_language['original'];
				}

				add_filter(
					'weglot_translate_email_languages_forced',
					function () use ( $current_and_original_language ) {
						return $current_and_original_language;
					}
				);
			}
		}

		return $args;
	}


	/**
	 * @return int
	 * @since 3.1.6
	 */
	public function save_language( $order_id ) {
		if ( Helper_Is_Admin::is_wp_admin() ) {
			return;
		}

		$woocommerce_order_language = get_post_meta( $order_id, 'weglot_language', true );

		if ( ! $woocommerce_order_language ) {
			$current_language = $this->request_url_services->get_current_language()->getExternalCode();
			add_post_meta( $order_id, 'weglot_language', $current_language );
		}

		return $order_id;
	}

}
