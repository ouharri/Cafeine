<?php
/**
 * Mailpoet integration class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mailpoet integration class.
 *
 * @since 1.9.10
 */
class OMAPI_MailPoet {

	/**
	 * Check to see if Mailpoet is active.
	 *
	 * @since 1.2.3
	 *
	 * @return bool
	 */
	public static function is_active() {
		return class_exists( 'WYSIJA_object' ) || class_exists( 'MailPoet\\API\\API' );
	}

	/**
	 * Returns the available MailPoet lists.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of MailPoet lists.
	 */
	public static function get_lists() {

		// Prepare variables.
		$mailpoet    = null;
		$lists       = array();
		$ret         = array();
		$list_id_key = 'id';

		// Get lists. Check for MailPoet 3 first. Default to legacy.
		if ( class_exists( '\\MailPoet\\Config\\Initializer' ) ) {
			$lists = \MailPoet\API\API::MP( 'v1' )->getLists();
		} else {
			$mailpoet    = WYSIJA::get( 'list', 'model' );
			$lists       = $mailpoet->get( array( 'name', 'list_id' ), array( 'is_enabled' => 1 ) );
			$list_id_key = 'list_id';
		}

		// Add default option.
		$ret[] = array(
			'name'  => esc_html__( 'Select your MailPoet list...', 'optin-monster-api' ),
			'value' => 'none',
		);

		// Loop through the list data and add to array.
		foreach ( (array) $lists as $list ) {
			$ret[] = array(
				'name'  => $list['name'],
				'value' => $list[ $list_id_key ],
			);
		}

		/**
		 * Filters the MailPoet lists.
		 *
		 * @param array       $ret      The MailPoet lists array.
		 * @param array       $lists    The raw MailPoet lists array. Format differs by plugin verison.
		 * @param WYSIJA|null $mailpoet The MailPoet object if using legacy. Null otherwise.
		 */
		return apply_filters( 'optin_monster_api_mailpoet_lists', $ret, $lists, $mailpoet );
	}

	/**
	 * Returns the available MailPoet custom fields.
	 *
	 * @since 1.9.8
	 *
	 * @return array An array of MailPoet custom fields.
	 */
	public static function get_custom_fields() {

		// Prepare variables.
		$ret            = array();
		$default_fields = array( 'email', 'first_name', 'last_name' );

		// Get lists. Check for MailPoet 3.
		$custom_fields = class_exists( '\\MailPoet\\Config\\Initializer' )
			? \MailPoet\API\API::MP( 'v1' )->getSubscriberFields()
			: array();

		// Add default option.
		$ret[] = array(
			'name'  => esc_html__( 'Select the phone number field...', 'optin-monster-api' ),
			'value' => '',
		);

		// Loop through the list data and add to array.
		foreach ( (array) $custom_fields as $custom_field ) {
			if ( in_array( $custom_field['id'], $default_fields, true ) ) {
				continue;
			}

			$ret[] = array(
				'name'  => $custom_field['name'],
				'value' => $custom_field['id'],
			);
		}

		/**
		 * Filters the MailPoet custom fields.
		 *
		 * @param array. $ret           The MailPoet custom fields array, except
		 *                              first name, last name and email
		 * @param array. $custom_fields The raw MailPoet custom fields array.
		 *                              Format differs by plugin verison.
		 */
		return apply_filters( 'optin_monster_api_mailpoet_custom_fields', $ret, $custom_fields );
	}

	/**
	 * Opts the user into MailPoet.
	 *
	 * @since 1.0.0
	 */
	public function handle_ajax_call() {
		/*
		 * Check the nonce is correct first.
		 *
		 * As this is a front end form to store the visitor's data in a mailing
		 * list no capability check is required.
		 */
		check_ajax_referer( 'omapi', 'nonce' );

		// Prepare variables.
		$data = array_merge( $_REQUEST, $_REQUEST['optinData'] );
		unset( $data['optinData'] );

		$optin       = OMAPI::get_instance()->get_optin_by_slug( stripslashes( $data['optin'] ) );
		$list        = get_post_meta( $optin->ID, '_omapi_mailpoet_list', true );
		$phone_field = get_post_meta( $optin->ID, '_omapi_mailpoet_phone_field', true );
		$email       = ! empty( $data['email'] ) ? stripslashes( $data['email'] ) : false;
		$name        = ! empty( $data['name'] ) ? stripslashes( $data['name'] ) : false;
		$user        = array();

		// Possibly split name into first and last.
		if ( $name ) {
			$names = explode( ' ', $name );
			if ( isset( $names[0] ) ) {
				$user['firstname'] = $names[0];
			}

			if ( isset( $names[1] ) ) {
				$user['lastname'] = $names[1];
			}
		}

		// Save the email address.
		$user['email'] = $email;

		// Save the phone number.
		if ( ! empty( $phone_field ) && ! empty( $data['phone'] ) ) {
			$user[ $phone_field ] = stripslashes( $data['phone'] );
		}

		// Store the data.
		$data = array(
			'user'      => $user,
			'user_list' => array( 'list_ids' => array( $list ) ),
		);
		$data = apply_filters( 'optin_monster_pre_optin_mailpoet', $data, $_REQUEST, $list, null );

		// Save the subscriber. Check for MailPoet 3 first. Default to legacy.
		if ( class_exists( 'MailPoet\\API\\API' ) ) {
			// Customize the lead data for MailPoet 3.
			if ( isset( $user['firstname'] ) ) {
				$user['first_name'] = $user['firstname'];
				unset( $user['firstname'] );
			}

			if ( isset( $user['lastname'] ) ) {
				$user['last_name'] = $user['lastname'];
				unset( $user['lastname'] );
			}

			try {
				$subscriber = \MailPoet\API\API::MP( 'v1' )->getSubscriber( $user['email'] );
			} catch ( Exception $e ) {
				$subscriber = false;
			}

			try {
				if ( $subscriber ) {
					\MailPoet\API\API::MP( 'v1' )->subscribeToList( $subscriber['email'], array( $list ) );
				} else {
					\MailPoet\API\API::MP( 'v1' )->addSubscriber( $user, array( $list ) );
				}
			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage(), 400 );
			}
		} else {
			$user_helper = WYSIJA::get( 'user', 'helper' );
			$user_helper->addSubscriber( $data );
		}

		// Send back a response.
		wp_send_json_success();
	}
}
