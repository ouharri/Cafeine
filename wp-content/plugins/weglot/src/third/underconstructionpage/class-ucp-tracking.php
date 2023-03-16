<?php

namespace WeglotWP\Third\UnderConstructionPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;


/**
 * UCP_Tracking
 *
 * @since 3.1.1
 */
class UCP_Tracking implements Hooks_Interface_Weglot {
	/**
	 * @var Ucp_Active
	 */
	private $ucp_active_services;

	/**
	 * @since 3.1.1
	 * @return void
	 */
	public function __construct() {
		$this->ucp_active_services = weglot_get_service( 'Ucp_Active' );
	}

	/**
	 * @since 3.1.1
	 * @see Hooks_Interface_Weglot
	 * @return void
	 */
	public function hooks() {
		if ( ! Helper_Is_Admin::is_wp_admin() ) {
			return;
		}

		if ( ! $this->ucp_active_services->is_active() ) {
			return;
		}

		add_filter( 'weglot_tabs_admin_options_available', array( $this, 'weglot_ucp_tracking' ) );
	}


	/**
	 * @param $options_available
	 * @return mixed
	 * @since 3.1.1
	 */
	public function weglot_ucp_tracking( $options_available ) {

		if ( isset( $options_available['api_key_private']['description'] ) ) {

			$register_link         = 'https://dashboard.weglot.com/register-wordpress';
			$register_link_tracked = 'https://weglot.com/ad-track?origin=UCP&redirectTo=https://dashboard.weglot.com/register-wordpress';

			$options_available['api_key_private']['description'] = \str_replace( $register_link, $register_link_tracked, $options_available['api_key_private']['description'] );
		}

		return $options_available;
	}

}
