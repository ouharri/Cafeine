<?php

namespace WeglotWP\Third\Woocommercepdf;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Third_Active_Interface_Weglot;


/**
 * Wcpdf_Active
 *
 * @since 2.0
 */
class Wcpdf_Active implements Third_Active_Interface_Weglot {

	/**
	 * WooCommerce PDF Invoices & Packing Slips  is active ?
	 * @since 2.0
	 *
	 * @return boolean
	 */
	public function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! is_plugin_active( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) ) {
			return false;
		}

		return true;
	}
}
