<?php
/**
 * EasyDigitalDownloads Rules class.
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
 * EasyDigitalDownloads_Rules class.
 *
 * @since 2.8.0
 */
class OMAPI_EasyDigitalDownloads_Rules extends OMAPI_Rules_Base {

	/**
	 * Holds the meta fields used for checking output statuses.
	 *
	 * @since 2.8.0
	 *
	 * @var array
	 */
	protected $fields = array(
		'show_on_edd',
		'is_edd_download',
		'is_edd_checkout',
		'is_edd_success_page',
		'is_edd_failed_transaction_page',
		'is_edd_purchase_history_page',
		'is_edd_download_category',
		'is_edd_download_tag',
	);

	/**
	 * Check for edd rules.
	 *
	 * @since 2.8.0
	 *
	 * @throws OMAPI_Rules_False If rule doesn't match.
	 * @throws OMAPI_Rules_True  If rule matches.
	 * @return void
	 */
	public function run_checks() {

		// If EDD is not connected we can ignore the EDD specific settings.
		if ( ! OMAPI_EasyDigitalDownloads::is_connected() ) {
			return;
		}

		try {
			$edd_checks = array(
				'is_edd_download'                => array( $this, 'is_edd_download' ),
				'is_edd_checkout'                => 'edd_is_checkout',
				'is_edd_success_page'            => 'edd_is_success_page',
				'is_edd_failed_transaction_page' => 'edd_is_failed_transaction_page',
				'is_edd_purchase_history_page'   => 'edd_is_purchase_history_page',
			);

			// If show_on_edd is selected, then we'll override the field_empty check for each page.
			$show_on_all_edd_pages = ! $this->rules->field_empty( 'show_on_edd' );

			if ( $show_on_all_edd_pages ) {
				$this->rules
					->set_global_override( false )
					->set_advanced_settings_field( 'show_on_edd', $this->rules->get_field_value( 'show_on_edd' ) );
			}

			foreach ( $edd_checks as $field => $callback ) {
				// If show on all edd pages is not selected, and field is empty, then we don't need to check this.
				if ( ! $show_on_all_edd_pages && $this->rules->field_empty( $field ) ) {
					continue;
				}

				$rule_value = $this->rules->get_field_value( $field );

				if ( $rule_value ) {
					$this->rules
						->set_global_override( false )
						->set_advanced_settings_field( $field, $rule_value );
				}

				if ( call_user_func( $callback ) ) {

					// If it passes, send it back.
					throw new OMAPI_Rules_True( $field );
				}
			}
		} catch ( OMAPI_Rules_Exception $e ) {
			if ( $e instanceof OMAPI_Rules_True ) {
				throw new OMAPI_Rules_True( 'include edd', 0, $e );
			}
			$this->rules->add_reason( $e );
		}
	}

	/**
	 * Check if the current page is a valid EDD Download page.
	 *
	 * @since 2.8.0
	 *
	 * @return boolean True if current page is EDD Download, false otherwise or if it was not able to determine.
	 */
	public function is_edd_download() {
		 // Get the current page/post id.
		$post_id = get_the_ID();

		if ( ! $post_id || ! function_exists( 'edd_get_download' ) ) {
			return false;
		}

		// This method only returns the download if the post id passed through
		// is a valid EDD download object. Null otherwhise.
		$download = edd_get_download( $post_id );

		return ! empty( $download );
	}
}
