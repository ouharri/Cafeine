<?php
/**
 * Promos class.
 *
 * @since 2.10.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Promos class.
 *
 * @since 2.10.0
 */
class OMAPI_Promos {

	/**
	 * OMAPI_Promos_TrustPulse object (loaded only in the admin)
	 *
	 * @var OMAPI_Promos_TrustPulse
	 */
	public $trustpulse;

	/**
	 * OMAPI_Promos_SeedProd object (loaded only in the admin)
	 *
	 * @var OMAPI_Promos_SeedProd
	 */
	public $seedprod;

	/**
	 * OMAPI_ConstantContact object (loaded only in the admin)
	 *
	 * @var OMAPI_ConstantContact
	 */
	public $constant_contact;

	/**
	 * Constructor
	 *
	 * @since 2.10.0
	 */
	public function __construct() {
		add_action( 'optin_monster_api_admin_loaded', array( $this, 'init_promos' ) );
	}

	/**
	 * Initiate the promos objects.
	 *
	 * @since 2.10.0
	 *
	 * @return void
	 */
	public function init_promos() {
		$this->trustpulse = new OMAPI_Promos_TrustPulse();
		$this->seedprod   = new OMAPI_Promos_SeedProd();
		if ( OMAPI_Partners::has_partner_url() ) {
			$this->constant_contact = new OMAPI_ConstantContact();
		}
	}

}
