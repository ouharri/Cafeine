<?php

namespace WeglotWP\Third\Woocommercepdf;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Dompdf\Dompdf;
use Dompdf\Options;
use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Pdf_Translate_Service_Weglot;
use WeglotWP\Services\Request_Url_Service_Weglot;
use WeglotWP\Third\Woocommercepdf\Wcpdf_Active;


/**
 * WC_Mail_Weglot
 *
 * @since 3.7
 */
class WCPDF_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @var Wcpdf_Active
	 */
	private $wcpdf_active_services;
	/**
	 * @var Pdf_Translate_Service_Weglot
	 */
	private $pdf_translate_services;
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
	 * @since 3.7
	 */
	public function __construct() {
		$this->wcpdf_active_services  = weglot_get_service( 'Wcpdf_Active' );
		$this->request_url_services   = weglot_get_service( 'Request_Url_Service_Weglot' );
		$this->language_services      = weglot_get_service( 'Language_Service_Weglot' );
		$this->pdf_translate_services = weglot_get_service( 'Pdf_Translate_Service_Weglot' );
	}

	/**
	 * @return void
	 * @since 3.7
	 */
	public function hooks() {
		if ( ! $this->wcpdf_active_services->is_active() ) {
			return;
		}
		add_filter( 'wpo_wcpdf_before_dompdf_render', array( $this, 'translate_invoice_pdf' ), 10, 2 );
	}

	/**
	 * @param $args
	 * @param $html
	 *
	 * @return string
	 * @since 3.7
	 */
	public function translate_invoice_pdf( $dompdf, $html ) {

		$translate_pdf = apply_filters( 'weglot_translate_pdf', false );

		if ( ! $translate_pdf ) {
			return $dompdf;
		}

		if ( ! empty( sanitize_key( $_GET['order_ids'] ) ) ) { // phpcs:ignore
			$order_id = sanitize_key( $_GET['order_ids'] ); // phpcs:ignore
		}

		if ( empty( $order_id ) ) {
			return $dompdf;
		}

		$woocommerce_order_language = get_post_meta( $order_id, 'weglot_language', true ); // phpcs:ignore

		if ( $woocommerce_order_language == weglot_get_original_language() ) {
			return $dompdf;
		}

		$_dompdf = clone $dompdf;
		unset( $dompdf );
		$_dompdf_options           = $_dompdf->getOptions();
		$_dompdf_paper_size        = $_dompdf->getPaperSize();
		$_dompdf_paper_orientation = $_dompdf->getPaperOrientation();

		$_dompdf->loadHtml( $html );
		$_dompdf->render();
		unset( $_dompdf );

		$dompdf         = new \Dompdf\Dompdf( $_dompdf_options );
		$translated_pdf = $this->pdf_translate_services->translate_pdf( $html, $woocommerce_order_language );

		$dompdf->loadHtml( $translated_pdf['content'] );
		$dompdf->setPaper( $_dompdf_paper_size, $_dompdf_paper_orientation );

		return $dompdf;
	}

}
