<?php
/**
 * MemberPress Rules class.
 *
 * @since 2.13.0
 *
 * @package OMAPI
 * @author  Eduardo Nakatsuka
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MemberPress_Rules class.
 *
 * @since 2.13.0
 */
class OMAPI_MemberPress_Rules extends OMAPI_Rules_Base {

	/**
	 * Holds the meta fields used for checking output statuses.
	 *
	 * @since 2.13.0
	 *
	 * @var array
	 */
	protected $fields = array(
		'show_to_mbp_membership_member',
		'show_not_to_mbp_membership_member',
		'show_to_mbp_group_member',
		'show_not_to_mbp_group_member',
		'show_on_mbp_group_pages',
		'show_on_mbp_membership_pages',
		'show_on_mbp_register_pages',
		'show_on_mbp_checkout_pages',
		'is_mbp_register',
		'is_mbp_checkout',
		'is_mbp_thank_you',
		'show_on_mbp_course_pages',
		'show_on_mbp_lesson_pages',
		'show_on_mbp_quiz_pages',
	);

	/**
	 * MeprUser object
	 *
	 * @since 2.13.0
	 *
	 * @var MeprUser
	 */
	protected $mp_user;

	/**
	 * Whether user is on the checkout page.
	 *
	 * @since 2.13.0
	 *
	 * @var bool
	 */
	protected $is_checkout = false;

	/**
	 * Check for MP rules.
	 *
	 * @since 2.13.0
	 *
	 * @throws OMAPI_Rules_True  If rule matches.
	 * @return void
	 */
	public function run_checks() {

		// If MP is not active we can ignore the MP specific settings.
		if ( ! OMAPI_MemberPress::is_active() ) {
			return;
		}

		if( ! empty( $_GET['action'] ) && 'checkout' === $_GET['action'] ) {
			$this->set_is_checkout( true );
		}

		if ( is_user_logged_in() ) {
			$this->mp_user = new MeprUser( get_current_user_id() );
			$this->exclude_if_member_not_allowed();
		}

		$mp_checks = array(
			'show_on_mbp_group_pages'      => array( $this, 'is_on_page' ),
			'show_on_mbp_membership_pages' => array( $this, 'is_on_page' ),
			'show_on_mbp_register_pages'   => array( $this, 'is_on_membership_register_page' ),
			'show_on_mbp_checkout_pages'   => array( $this, 'is_on_membership_checkout_page' ),
			'is_mbp_register'              => array( $this, 'is_on_register_page' ),
			'is_mbp_checkout'              => array( $this, 'is_on_checkout_page' ),
			'is_mbp_thank_you'             => array( $this, 'is_on_thank_you_page' ),
			'show_on_mbp_course_pages'     => array( $this, 'is_on_page' ),
			'show_on_mbp_lesson_pages'     => array( $this, 'is_on_page' ),
			'show_on_mbp_quiz_pages'       => array( $this, 'is_on_page' ),
		);

		foreach ( $mp_checks as $field => $callback ) {
			// If field is empty, then we don't need to check this.
			if ( $this->rules->field_empty( $field ) ) {
				continue;
			}

			$rule_value = $this->rules->get_field_value( $field );
			$this->rules
				->set_global_override( false )
				->set_advanced_settings_field( $field, $rule_value );

			if ( call_user_func( $callback, $rule_value ) ) {
				throw new OMAPI_Rules_True( $field );
			}
		}
	}

	/**
	 * Check if campaign prevented from showing based on applicable membership/groups.
	 *
	 * @since 2.13.0
	 *
	 * @throws OMAPI_Rules_False
	 * @return void
	 */
	public function exclude_if_member_not_allowed() {

		// * Does user exist? If no, Nothing to do.
		if ( empty( $this->mp_user->ID ) ) {
			return false;
		}

		// * Are there selected memberships?
		$required_memberships = $this->rules->field_not_empty_array( 'show_to_mbp_membership_member' );
		if ( $required_memberships ) {
			// * Is user NOT ACTIVE in one of the selected memberships?
			if ( ! $this->is_active_on_memberships( $required_memberships ) ) {
				// * then fail!
				throw new OMAPI_Rules_False( 'show_to_mbp_membership_member' );
			}
		}

		// * Are there selected excluded memberships?
		$excluded_memberships = $this->rules->field_not_empty_array( 'show_not_to_mbp_membership_member' );
		if ( $excluded_memberships ) {
			// * Is user ACTIVE in one of the selected excluded memberships?
			if ( $this->is_active_on_memberships( $excluded_memberships ) ) {
				// * then fail!
				throw new OMAPI_Rules_False( 'show_not_to_mbp_membership_member' );
			}
		}

		// * Are there selected groups?
		$required_groups = $this->rules->field_not_empty_array( 'show_to_mbp_group_member' );
		if ( $required_groups ) {
			// * Is user NOT ACTIVE in one of the selected groups?
			if ( ! $this->is_active_on_groups( $required_groups ) ) {
				// * then fail!
				throw new OMAPI_Rules_False( 'show_to_mbp_group_member' );
			}
		}

		// * Are there selected excluded groups?
		$excluded_groups = $this->rules->field_not_empty_array( 'show_not_to_mbp_group_member' );
		if ( $excluded_groups ) {
			// * Is user ACTIVE in one of the selected excluded groups?
			if ( $this->is_active_on_groups( $excluded_groups ) ) {
				// * then fail!
				throw new OMAPI_Rules_False( 'show_not_to_mbp_group_member' );
			}
		}
	}

	/**
	 * Check if the user is logged in and subscribed to any product in the group.
	 *
	 * @since 2.13.0
	 *
	 * @param array $group_ids Array of group IDs.
	 *
	 * @return bool
	 */
	public function is_active_on_groups( $group_ids ) {
		// Stash our checks in a static variable
		// So we only do the fetch/checks once per page-load.
		static $cached = array();

		foreach ( $group_ids as $group_id ) {
			if ( isset( $cached[ $group_id ] ) ) {
				if ( $cached[ $group_id ] ) {
					return true;
				}

				continue;
			}

			$cached[ $group_id ] = false;
			$group               = new MeprGroup( $group_id );

			// If we can't find this group, then it's not considered active.
			if ( ! empty( $group->ID ) ) {
				// Check if user is subscribed to any products of a group.
				foreach ( $group->products() as $group_product ) {
					if ( $this->mp_user->is_already_subscribed_to( $group_product->ID ) ) {
						$cached[ $group_id ] = true;
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Check if the If the page is a Membership.
	 *
	 * @since 2.13.0
	 *
	 * @return bool
	 */
	public function is_on_register_page() {
		return $this->is_product( $this->rules->post_id )
			? ! $this->is_checkout
			: false;
	}

	/**
	 * Check if the current user is active on any of the given memberships.
	 *
	 * @since 2.13.0
	 *
	 * @param array $membership_ids Array of membership IDs.
	 *
	 * @return bool
	 */
	public function is_active_on_memberships( $membership_ids ) {
		foreach ( $membership_ids as $membership ) {
			if ( $this->mp_user->is_active_on_membership( $membership ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the current user is on a thank you page.
	 *
	 * @since 2.13.0
	 *
	 * @return bool
	 */
	public function is_on_thank_you_page() {
		$options = MeprOptions::fetch();

		if (
			! empty( $options->thankyou_page_id )
			&& $this->is_current_post_id( $options->thankyou_page_id )
		) {
			return true;
		}

		$product               = new MeprProduct();
		$all_memberpress_posts = $product->get_all();

		foreach ( $all_memberpress_posts as $post ) {
			if (
				! empty( $post->thankyou_page_id )
				&& $this->is_current_post_id( $post->thankyou_page_id )
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the current user is on a checkout page.
	 *
	 * @since 2.13.0
	 *
	 * @return bool
	 */
	public function is_on_checkout_page() {
		return $this->is_product( $this->rules->post_id )
			? $this->is_checkout
			: false;
	}

	/**
	 * Check if the current user is on a membership register page.
	 *
	 * @since 2.13.0
	 *
	 * @param array $memberships Array of membership IDs.
	 *
	 * @return bool
	 */
	public function is_on_membership_register_page( $memberships ) {
		foreach ( $memberships as $membership ) {

			if ( ! $this->is_current_post_id( $membership ) ) {
				continue;
			}

			// If page is not a Membership, go to the next loop.
			if ( ! $this->is_product( $this->rules->post_id ) ) {
				continue;
			}

			return ! $this->is_checkout;
		}

		return false;
	}

	/**
	 * Check if the current user is on a membership checkout page.
	 *
	 * @since 2.13.0
	 *
	 * @param array $memberships Array of membership IDs.
	 *
	 * @return bool
	 */
	public function is_on_membership_checkout_page( $memberships ) {
		foreach ( $memberships as $membership ) {
			if ( ! $this->is_current_post_id( $membership ) ) {
				continue;
			}

			// If page is not a Membership, go to the next loop.
			if ( ! $this->is_product( $membership ) ) {
				continue;
			}

			return $this->is_checkout;
		}

		return false;
	}

	/**
	 * Check if the current user is on a page of given IDs.
	 *
	 * @param array $page_ids Array of page IDs.
	 *
	 * @return bool
	 */
	public function is_on_page( $page_ids ) {
		foreach ( $page_ids as $page_id ) {
			if ( $this->is_current_post_id( $page_id ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if given id is a product.
	 *
	 * @since 2.13.0
	 *
	 * @param  int $product_id Product/post ID.
	 *
	 * @return bool Whether given id is a product.
	 */
	public function is_product( $product_id ) {
		$product = $this->get_product( $product_id );

		return ! empty( $product->ID );

	}

	/**
	 * Get memberpress product.
	 *
	 * @since 2.13.0
	 *
	 * @param  int $product_id Product/post ID.
	 *
	 * @return MeprProduct Memberpress product object.
	 */
	public function get_product( $product_id ) {
		static $products = array();
		if ( empty( $products[ $product_id ] ) ) {
			$products[ $product_id ] = new MeprProduct( $product_id );
		}

		return $products[ $product_id ];
	}

	/**
	 * Determines if current post is the same one being passed in.
	 *
	 * @since 2.13.0
	 *
	 * @param  int $post_id Current post id.
	 *
	 * @return boolean Whether current post is the same one being passed in.
	 */
	public function is_current_post_id( $post_id ) {
		return intval( $this->rules->post_id ) === intval( $post_id );
	}

	/**
	 * Set the is_checkout property.
	 *
	 * @since 2.13.0
	 *
	 * @param  boolean $is_checkout The property value.
	 *
	 * @return void
	 */
	public function set_is_checkout( $is_checkout ) {
		$this->is_checkout = (bool) $is_checkout;
	}
}
