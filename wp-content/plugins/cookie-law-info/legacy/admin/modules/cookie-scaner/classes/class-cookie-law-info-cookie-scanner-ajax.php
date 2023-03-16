<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The class which handles all the ajax calls made by the cookie scaner.
 */
class Cookie_Law_Info_Cookie_Scanner_Ajax extends Cookie_Law_Info_Cookie_Scaner {

	/**
	 * Initialise scanner ajax handlers function.
	 */
	public function __construct() {
		add_action( 'wp_ajax_cli_cookie_scaner', array( $this, 'ajax_cookie_scaner' ) );
		add_action( 'wt_cli_ckyes_abort_scan', array( $this, 'update_abort_status' ) );
	}
	/**
	 * Ajax callback which handles the ajax calls from scanner.
	 *
	 * @return void
	 */
	public function ajax_cookie_scaner() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( __( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) ) );
		}
		check_ajax_referer( 'cli_cookie_scaner', 'security' );
		$out = array(
			'response' => false,
			'message'  => __( 'Unable to handle your request.', 'cookie-law-info' ),
		);
		if ( isset( $_POST['cli_scaner_action'] ) ) {

			$cli_scan_action = sanitize_text_field( wp_unslash( $_POST['cli_scaner_action'] ) );
			$allowed_actions = array(
				'get_pages',
				'scan_pages',
				'stop_scan',
				'import_now',
				'connect_scan',
				'next_scan_id',
				'bulk_scan',
				'check_status',
				'fetch_result',
				'get_scan_html',
			);
			if ( in_array( $cli_scan_action, $allowed_actions, true ) && method_exists( $this, $cli_scan_action ) ) {
				$out = $this->{$cli_scan_action}();
			}
		}
		echo wp_json_encode( $out );
		exit();
	}
	/**
	 * Create an account with CookieYes and start scan
	 *
	 * @return void
	 */
	public function connect_scan() {

		$data         = array();
		$ckyes_status = $this->get_cookieyes_status();
		$status       = false;
		if ( false === $ckyes_status ) {

			// Firs time connection request.
			$data['status'] = false;
			$data['code']   = parent::EC_WT_CKYES_NOT_REGISTERED;

		} elseif ( 0 === $ckyes_status ) { // Not connected to CookieYes then connect and scan again.
			$response = $this->ckyes_connect();
			$status   = isset( $response['status'] ) ? $response['status'] : false;
		} elseif ( 2 === $ckyes_status ) {
			$status         = true;
			$data['status'] = $status;
		}
		if ( true === $status ) {
			wp_send_json_success( $data );
		}
		wp_send_json_error( $data );
	}
	/**
	 * Retreives the next scan ID from CookieYes before proceeding to scan.
	 *
	 * @return void
	 */
	public function next_scan_id() {

		$data       = array();
		$total_urls = $this->get_total_page_count();
		$total_urls ++; // Add home url to the count.
		$response    = $this->get_next_scan_id( $total_urls );
		$status      = ( isset( $response['status'] ) ? $response['status'] : false );
		$status_code = ( isset( $response['code'] ) ? $response['code'] : '' );
		if ( ! empty( $status_code ) ) {
			if ( self::EC_WT_CKYES_PENDING_VERIFICATION === $status_code ) {
				$data['html']      = $this->get_email_verification_html( true );
				$data['scan_html'] = $this->scanner_notices( true );
				$data['code']      = $status_code;
			} else {
				$data['code']    = $status_code;
				$data['message'] = $this->get_ckyes_message( $status_code );
			}
		}
		if ( true === $status ) {
			if ( isset( $response['scan_id'] ) && isset( $response['scan_token'] ) ) {
				$this->set_ckyes_scan_id( $response['scan_id'] );
				$this->set_ckyes_scan_token( $response['scan_token'] );
			}
			wp_send_json_success( $data );
		}
		wp_send_json_error( $data );

	}
	/**
	 * Retrieves pages from the site for scanning
	 *
	 * @return array
	 */
	public function get_pages() {

		global $wpdb;
		$page_limit = 100;

		$wt_cli_site_host = $this->wt_cli_get_host( get_site_url() );

		$out               = array(
			'log'     => array(),
			'total'   => 0,
			'limit'   => $page_limit,
			'scan_id' => 0,
			'status'  => false,
		);
		$post_types        = $this->get_exclude_post_types();

		$total_rows = $wpdb->get_row( $wpdb->prepare( "SELECT COUNT(ID) AS ttnum FROM {$wpdb->prefix}posts WHERE post_type IN( '" . implode( "','", array_map( 'esc_sql', array_keys( $post_types ) ) ) . "' ) AND post_status='publish' ORDER BY post_type='page' DESC LIMIT %d", $page_limit ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$total      = $total_rows ? $total_rows['ttnum'] + 1 : 1; // always add 1 becuase home url is there.

		$this->set_ckyes_scan_status( 0 );
		$scan_id = $this->create_scan_entry( $total );

		$out['scan_id'] = $scan_id;
		$out['total']   = $total;

		$this->insert_url( $scan_id, get_home_url() );

		$data = $wpdb->get_results( $wpdb->prepare( "SELECT post_name,post_title,post_type,ID FROM {$wpdb->prefix}posts WHERE post_type IN( '" . implode( "','", array_map( 'esc_sql', array_keys( $post_types ) ) ) . "' ) AND post_status='publish' ORDER BY post_type='page' DESC LIMIT %d", $page_limit ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		if ( ! empty( $data ) ) {
			foreach ( $data as $value ) {

				$permalink         = get_permalink( $value['ID'] );
				$currrent_url_host = $this->wt_cli_get_host( $permalink );

				if ( ( $this->filter_url( $permalink ) ) && ( $currrent_url_host === $wt_cli_site_host ) ) {
					$this->insert_url( $scan_id, $permalink );
				} else {
					$out['total'] = $out['total'] - 1;
				}
			}
		}
		$data_arr = array(
			'current_action' => 'get_pages',
			'status'         => 1,
			'total_url'      => $out['total'],
		);
		$this->update_scan_entry( $data_arr, $scan_id );
		$out['status'] = true;
		return $out;
	}
	/**
	 * Perform a bulk scan request.
	 *
	 * @return void
	 */
	public function bulk_scan() {
		check_ajax_referer( 'cli_cookie_scaner', 'security' );

		include plugin_dir_path( __FILE__ ) . 'class-cookie-law-info-cookie-scanner-api.php';

		$scan_id = isset( $_POST['scan_id'] ) ? absint( $_POST['scan_id'] ) : 0;
		$total   = isset( $_POST['total'] ) ? absint( $_POST['total'] ) : 0;

		$data_arr = array(
			'current_action' => 'bulk_scan',
			'current_offset' => -1,
			'status'         => 1,
		);

		$cookie_serve_api = new Cookie_Law_Info_Cookie_Scanner_Api();

		$urls            = $this->get_urls( $scan_id );
		$data            = array();
		$data['title']   = '';
		$data['message'] = __( 'Scanner API is temporarily down please try again later.', 'cookie-law-info' );

		$response = $cookie_serve_api->fetch_all_cookies( $urls );
		$response = json_decode( $response, true );

		if ( false !== $response ) {

			if ( isset( $response['status'] ) && 'initiated' === $response['status'] ) {

				$this->update_scan_entry( $data_arr, $scan_id );
				$this->set_ckyes_scan_status( 1 );
				$estimate = ( isset( $response['estimatedTimeInSeconds'] ) ? $response['estimatedTimeInSeconds'] : 0 );

				$this->set_ckyes_scan_estimate( $estimate );
				$data['title']   = __( 'Scanning initiated successfully', 'cookie-law-info' );
				$data['message'] = __( 'It might take a few minutes to a few hours to complete the scanning of your website. This depends on the number of pages to scan and the website speed. Once the scanning is complete, we will notify you by email.', 'cookie-law-info' );
				$data['html']    = $this->get_scan_progress_html();
				wp_send_json_success( $data );
			}
		}
		$this->update_failed_status();
		wp_send_json_error( $data );
	}
	/**
	 * Check the current status of a scan from the Cookieyes.
	 *
	 * @return void
	 */
	public function check_status() {

		$scan_id     = $this->get_ckyes_scan_id();
		$response    = $this->get_scan_status( $scan_id );
		$scan_status = isset( $response['scan_status'] ) ? $response['scan_status'] : '';

		$data = array(
			'status'      => false,
			'refresh'     => false,
			'scan_id'     => $scan_id,
			'scan_status' => $scan_status,
		);

		if ( isset( $response['status'] ) && 'error' === $response['status'] ) {
			if ( isset( $response['error_code'] ) && $response['error_code'] == 1008 ) {
				$data['refresh'] = true;
				$this->update_failed_status();
			}
		} else {
			if ( 'completed' === $scan_status ) {

				$data['refresh'] = true;
				$data['status']  = true;
				wp_send_json_success( $data );

			} elseif ( 'failed' === $scan_status || intval( $scan_id ) === 0 || $this->get_ckyes_scan_status() === 0 ) { // Scan id has expired or scan fails on Cookieyes.
				$data['refresh'] = true;
				$this->update_failed_status();
				wp_send_json_error( $data );
			}
		}
		wp_send_json_error( $data );
	}
	/**
	 * Fetch the result directly from the scanner.
	 *
	 * @return void
	 */
	public function fetch_result() {
		$scan_id      = $this->get_ckyes_scan_id();
		$scan_results = $this->get_scan_results( $scan_id );
		if ( is_wp_error( $this->save_cookie_data( $scan_results ) ) ) {
			$this->update_failed_status();
			wp_send_json_error( __( 'Token mismatch', 'cookie-law-info' ) );
		}
		wp_send_json_success();
	}
	/**
	 * Set last scan status to fail if the scan has failed from CookieYes.
	 *
	 * @return void
	 */
	public function update_failed_status() {
		$scan_id  = $this->get_last_scan_id();
		$data_arr = array( 'status' => 4 ); // Updating scan status to stopped.
		$this->update_scan_entry( $data_arr, $scan_id );
		update_option( 'CLI_BYPASS', 0 );
	}
	/**
	 * Return the total page count
	 *
	 * @return int
	 */
	public function get_total_page_count() {
		global $wpdb;
		$post_types = $this->get_exclude_post_types();
		$total_rows = $wpdb->get_row( "SELECT COUNT(ID) AS ttnum FROM {$wpdb->prefix}posts WHERE post_type IN( '" . implode( "','", array_map( 'esc_sql', array_keys( $post_types ) ) ) . "' ) AND post_status='publish'", ARRAY_A );
		$pages      = ( isset( $total_rows ) ? $total_rows : 0 );
		$page_count = intval( ( isset( $pages['ttnum'] ) ? $pages['ttnum'] : 0 ) );
		return $page_count;
	}
	/**
	 * Returns the current host
	 *
	 * @param string $url URL of a page or post.
	 * @return string
	 */
	private function wt_cli_get_host( $url ) {
		$site_host  = '';
		$parsed_url = wp_parse_url( $url );
		$site_host  = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';
		return $site_host;
	}
	/**
	 * Filters the URL
	 *
	 * @param string $permalink Permalink of a page or post.
	 * @return string
	 */
	private function filter_url( $permalink ) {
		$url_arr = explode( '/', $permalink );
		$end     = trim( end( $url_arr ) );
		if ( '' !== $end ) {
			$url_end_arr = explode( '.', $end );
			if ( count( $url_end_arr ) > 1 ) {
				$end_end = trim( end( $url_end_arr ) );
				if ( $end_end != '' ) {
					$allowed = array( 'html', 'htm', 'shtml', 'php' );
					if ( ! in_array( $end_end, $allowed, true ) ) {
						return false;
					}
				}
			}
		}
		return true;
	}
	/**
	 * Returns the query to get the pages to be scanned
	 *
	 * @return array
	 */
	public function get_exclude_post_types() {
		global $wpdb;
		$post_types = get_post_types(
			array(
				'public' => true,
			)
		);
		unset( $post_types['attachment'] );
		unset( $post_types['revision'] );
		unset( $post_types['custom_css'] );
		unset( $post_types['customize_changeset'] );
		unset( $post_types['user_request'] );

		return $post_types;
	}
	/**
	 * Returns the total URLs to be scanned.
	 *
	 * @param int $scan_id scan ID.
	 * @return array
	 */
	public function get_urls( $scan_id ) {
		global $wpdb;
		$urls         = array();
		$urls_from_db = $wpdb->get_results( $wpdb->prepare( "SELECT id_cli_cookie_scan_url,url FROM {$wpdb->prefix}cli_cookie_scan_url WHERE id_cli_cookie_scan=%d ORDER BY id_cli_cookie_scan_url ASC", $scan_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared

		if ( ! empty( $urls_from_db ) ) {
			foreach ( $urls_from_db as $data ) {
				if ( isset( $data['url'] ) ) {
					$urls[] = sanitize_text_field( $data['url'] );
				}
			}
		}
		return $urls;
	}
	/**
	 * API to abort the scanning
	 *
	 * @return void
	 */
	public function stop_scan() {
		$scan_id       = $this->get_last_scan_id();
		$ckyes_scan_id = $this->get_ckyes_scan_id();
		$response      = $this->ckyes_abort_scan( $ckyes_scan_id );
		$status        = isset( $response['status'] ) ? $response['status'] : false;

		$data = array(
			'status'  => $status,
			'refresh' => false,
			'message' => '',
		);
		if ( true === $status ) {

			$data_arr = array( 'status' => 3 ); // Updating scan status to stopped.
			$this->update_scan_entry( $data_arr, $scan_id );
			update_option( 'CLI_BYPASS', 0 );
			$data['refresh'] = true;
			$data['message'] = __( 'Abort successfull', 'cookie-law-info' );
			wp_send_json_success( $data );

		} else {
			$data['message'] = __( 'Abort failed', 'cookie-law-info' );
		}
		wp_send_json_error( $data );
	}
	/**
	 * Import cookies to the cookie list
	 *
	 * @return array
	 */
	public function import_now() {

		check_ajax_referer( 'cli_cookie_scaner', 'security' );
		
		$scan_id = isset( $_POST['scan_id'] ) ? absint( wp_unslash( $_POST['scan_id'] ) ) : 0;
		$out     = array(
			'response' => false,
			'scan_id'  => $scan_id,
			'message'  => __( 'Unable to handle your request', 'cookie-law-info' ),
		);
		if ( ! current_user_can( 'manage_options' ) ) {
			$out['message'] = __( 'You do not have sufficient permissions to access this page.', 'cookie-law-info' );
			return $out;
		}
		$deleted       = 0;
		$skipped       = 0;
		$added         = 0;
		$scan_id       = isset( $_POST['scan_id'] ) ? absint( wp_unslash( $_POST['scan_id'] ) ) : 0;
		$import_option = isset( $_POST['import_option'] ) ? absint( wp_unslash( $_POST['import_option'] ) ) : 2;

		if ( $scan_id > 0 ) {
			$cookies = $this->get_scan_cookies( $scan_id, 0, -1 ); // taking cookies.
			if ( $cookies['total'] > 0 ) {
				if ( 1 === $import_option ) {
					$all_cookies = get_posts(
						array(
							'post_type'   => CLI_POST_TYPE,
							'numberposts' => -1,
						)
					);
					foreach ( $all_cookies as $cookie ) {
						$deleted++;
						wp_delete_post( $cookie->ID, true );
					}
				}
				foreach ( $cookies['cookies'] as $cookie ) {

					$skip = false;
					if ( 2 === $import_option ) {
						$existing_cookie = get_posts(
							array(
								'name'      => $cookie['cookie_id'],
								'post_type' => CLI_POST_TYPE,
							)
						);
						if ( ! empty( $existing_cookie ) ) {
							$cli_post = $existing_cookie[0];
							if ( empty( $cli_post->post_content ) ) {
								$post_data = array(
									'ID'           => $cli_post->ID,
									'post_content' => wp_kses_post( trim( wp_unslash( $cookie['description'] ) ) ),
								);
								wp_update_post( $post_data );
							}
							$skipped++;
							$skip = true;
						}
					}
					if ( false === $skip ) {
						$added++;
						$cookie_data = array(
							'post_type'    => CLI_POST_TYPE,
							'post_title'   => sanitize_text_field( wp_unslash( $cookie['cookie_id'] ) ),
							'post_content' => wp_kses_post( trim( wp_unslash( $cookie['description'] ) ) ),
							'post_status'  => 'publish',
							'ping_status'  => 'closed',
							'post_excerpt' => sanitize_text_field( wp_unslash( $cookie['cookie_id'] ) ),
							'post_author'  => 1,
						);
						$post_id     = wp_insert_post( $cookie_data );

						update_post_meta( $post_id, '_cli_cookie_duration', sanitize_text_field( wp_unslash( $cookie['expiry'] ) ) );
						update_post_meta( $post_id, '_cli_cookie_sensitivity', 'non-necessary' );
						update_post_meta( $post_id, '_cli_cookie_slugid', sanitize_text_field( wp_unslash( $cookie['cookie_id'] ) ) );
						wp_set_object_terms( $post_id, array( sanitize_text_field( wp_unslash( $cookie['category'] ) ) ), 'cookielawinfo-category', true );

						// Import Categories.
						$category = get_term_by( 'name', $cookie['category'], 'cookielawinfo-category' );
						// Check if category exist.
						if ( $category && is_object( $category ) ) {

							$category_id          = $category->term_id;
							$category_description = $category->description;

							// Check if catgory has description.
							if ( empty( $category_description ) ) {
								$description            = wp_kses_post( trim( wp_unslash( $cookie['cli_cookie_category_description'] ) ) );
								$category_slug          = $category->slug;
								$cookie_audit_shortcode = sprintf( '[cookie_audit category="%s" style="winter" columns="cookie,duration,description"]', $category_slug );
								$description           .= "\n";
								$description           .= $cookie_audit_shortcode;
								wp_update_term(
									$category_id,
									'cookielawinfo-category',
									array(
										'description' => $description,
									)
								);
							}
						}
					}
				}

				// preparing response message based on choosed option.
				$out_message = $added . ' ' . __( 'cookies added.', 'cookie-law-info' );
				if ( $import_option == 2 ) {
					$out_message .= ' ' . $skipped . ' ' . __( 'cookies skipped.', 'cookie-law-info' );
				}
				if ( $import_option == 1 ) {
					$out_message .= ' ' . $deleted . ' ' . __( 'cookies deleted.', 'cookie-law-info' );
				}
				$out['response'] = true;
				$out['message']  = $out_message;
			} else {
				$out['response'] = false;
				$out['message']  = __( 'No cookies found', 'cookie-law-info' );
			}
		}
		return $out;
	}
	/**
	 * Get latest scan HTML
	 *
	 * @return void
	 */
	public function get_scan_html() {
		$data = array();
		if ( '' !== $this->scanner_notices( true ) ) {
			$data['scan_html'] = $this->scanner_notices( true );
			wp_send_json_success( $data );
		}
		wp_send_json_error( $data );
	}
	public function update_abort_status() {
		$scan_id  = $this->get_last_scan_id();
		$data_arr = array( 'status' => 3 ); // Updating scan status to stopped.
		$this->update_scan_entry( $data_arr, $scan_id );
		update_option( 'CLI_BYPASS', 0 );
	}
}

new Cookie_Law_Info_Cookie_Scanner_Ajax();
