<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Cookie_Law_Info_Policy_Generator_Ajax extends Cookie_Law_Info_Cli_Policy_Generator {


	public function __construct() {
		add_action( 'wp_ajax_cli_policy_generator', array( $this, 'ajax_policy_generator' ) );
	}

	/*
	*
	* Main Ajax hook for processing requests
	*/
	public function ajax_policy_generator() {
		check_ajax_referer( 'cli_policy_generator', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
		}
		$out               = array(
			'response' => false,
			'message'  => __( 'Unable to handle your request.', 'cookie-law-info' ),
		);
		$non_json_response = array();
		if ( isset( $_POST['cli_policy_generator_action'] ) ) {
			$allowed_actions             = array( 'autosave_contant_data', 'save_contentdata', 'get_policy_pageid' );
			$action                      = isset( $_POST['cli_policy_generator_action'] ) ? sanitize_text_field( wp_unslash( $_POST['cli_policy_generator_action'] ) ) : '';
			$cli_policy_generator_action = in_array( $action, $allowed_actions ) ? $action : '';
			if ( in_array( $cli_policy_generator_action, $allowed_actions ) && method_exists( $this, $cli_policy_generator_action ) ) {
				$out = $this->{$cli_policy_generator_action}();
			}
		}
		if ( in_array( $cli_policy_generator_action, $non_json_response ) ) {
			echo esc_html( is_array( $out ) ? $out['message'] : $out );
		} else {
			echo json_encode( $out );
		}
		exit();
	}

	/*
	*	@since 1.7.4
	*	Get current policy page ID (Ajax-main)
	*	This is used to update the hidden field for policy page id. (In some case user press back button)
	*/
	public function get_policy_pageid() {
		$page_id            = Cookie_Law_Info_Cli_Policy_Generator::get_cookie_policy_pageid();
		$policy_page_status = get_post_status( $page_id );
		if ( $policy_page_status && $policy_page_status != 'trash' ) {

		} else {
			$page_id = 0;
		}
		return array(
			'response' => true,
			'page_id'  => $page_id,
		);
	}

	/*
	*	@since 1.7.4
	*	Save current data (Ajax-main)
	*/
	public function save_contentdata() {
		check_ajax_referer( 'cli_policy_generator', 'security' );
		$out          = array(
			'response' => true,
			'er'       => '',
		);
		$content_data = array();
		$count        = isset( $_POST['content_data'] ) ? count( $_POST['content_data'] ) : 0;
		for ( $i = 0; $i < $count; $i++ ) {
			$data           = array(
				'ord'     => isset( $_POST['content_data'][ $i ]['ord'] ) ? absint( $_POST['content_data'][ $i ]['ord'] ) : 0,
				'hd'      => isset( $_POST['content_data'][ $i ]['hd'] ) ? sanitize_text_field( wp_unslash( $_POST['content_data'][ $i ]['hd'] ) ) : '',
				'content' => isset( $_POST['content_data'][ $i ]['content'] ) ? wp_kses_post( wp_unslash( $_POST['content_data'][ $i ]['content'] ) ) : '',
			);
			$content_data[] = $data;
		}
		$page_id                    = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;
		$enable_webtofee_powered_by = isset( $_POST['enable_webtofee_powered_by'] ) ? absint( $_POST['enable_webtofee_powered_by'] ) : 0;
		$id                         = wp_insert_post(
			array(
				'ID'           => $page_id, // if ID is zero it will create new page otherwise update
				'post_title'   => 'Cookie Policy',
				'post_type'    => 'page',
				'post_content' => Cookie_Law_Info_Cli_Policy_Generator::generate_page_content( $enable_webtofee_powered_by, $content_data, 0 ),
				'post_status'  => 'draft', // default is draft
			)
		);
		if ( is_wp_error( $id ) ) {
			$out = array(
				'response' => false,
				'er'       => __( 'Error', 'cookie-law-info' ),
			);
		} else {
			Cookie_Law_Info_Cli_Policy_Generator::set_cookie_policy_pageid( $id );
			$out['url'] = get_edit_post_link( $id );
		}
		return $out;
	}

	/*
	*	@since 1.7.4
	*	Autosave Current content to session (Ajax-main)
	*/
	public function autosave_contant_data() {
		check_ajax_referer( 'cli_policy_generator', 'security' );
		$out          = array(
			'response' => true,
			'er'       => '',
		);
		$content_data = array();
		$count        = isset( $_POST['content_data'] ) ? count( $_POST['content_data'] ) : 0;
		for ( $i = 0; $i < $count; $i++ ) {
			$data           = array(
				'ord'     => isset( $_POST['content_data'][ $i ]['ord'] ) ? absint( $_POST['content_data'][ $i ]['ord'] ) : 0,
				'hd'      => isset( $_POST['content_data'][ $i ]['hd'] ) ? sanitize_text_field( wp_unslash( $_POST['content_data'][ $i ]['hd'] ) ) : '',
				'content' => isset( $_POST['content_data'][ $i ]['content'] ) ? wp_kses_post( wp_unslash( $_POST['content_data'][ $i ]['content'] ) ) : '',
			);
			$content_data[] = $data;
		}
		$page_id                    = isset( $_POST['page_id'] ) ? intval( $_POST['page_id'] ) : 0;
		$enable_webtofee_powered_by = isset( $_POST['enable_webtofee_powered_by'] ) ? intval( $_POST['enable_webtofee_powered_by'] ) : 0;
		if ( is_array( $content_data ) ) {
			$content_html = Cookie_Law_Info_Cli_Policy_Generator::generate_page_content( $enable_webtofee_powered_by, $content_data );
			update_option( 'cli_pg_content_data', $content_html );
		} else {
			$out = array(
				'response' => false,
				'er'       => __( 'Error', 'cookie-law-info' ),
			);
		}
		return $out;
	}
}
new Cookie_Law_Info_Policy_Generator_Ajax();
