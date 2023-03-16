<?php
/**
 * Scanner API
 *
 * @package Cookie_Law_Info_Cookie_Scaner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Scanner API classes
 */
class Cookie_Law_Info_Cookie_Scanner_Api extends Cookie_Law_Info_Cookie_Scaner {

	/**
	 * Cookie serve API URL
	 *
	 * @var string
	 */
	public $api_url = 'https://wp-scanner.cookieyes.com/api/v2/';
	/**
	 * Cookie serve API alternate URL
	 *
	 * @var string
	 */
	public $api_url_alternate = 'https://scanner.cookieyes.com/api/';
	/**
	 * Cookie serve API base path
	 *
	 * @var string
	 */
	public $api_path = 'scan';

	/**
	 * Append a query parameter to URLs to bypass script blocking
	 *
	 * @param array $url URL array.
	 * @return array
	 */
	public function append_bypasspath( $url ) {
		$url_arr = explode( '?', $url );
		if ( count( $url_arr ) > 1 ) {
			if ( trim( $url_arr[1] ) != '' ) {
				parse_str( $url_arr[1], $params );
				$params['cli_bypass'] = 1;
				$url_arr[1]           = http_build_query( $params );
			} else {
				$url_arr[1] = 'cli_bypass=1';
			}
		} else {
			$url_arr[] = 'cli_bypass=1';
		}
		return implode( '?', $url_arr );
	}
	/**
	 * Remove added query parameter from the URL before inserting to the table
	 *
	 * @param array $url URL array.
	 * @return array
	 */
	public function remove_bypasspath( $url ) {
		$url_arr = explode( '?', $url );
		if ( count( $url_arr ) > 1 ) {
			if ( trim( $url_arr[1] ) != '' ) {
				parse_str( $url_arr[1], $params );
				if ( isset( $params['cli_bypass'] ) ) {
					unset( $params['cli_bypass'] );
				}
				if ( count( $params ) > 0 ) {
					$url_arr[1] = http_build_query( $params );
					$url        = implode( '?', $url_arr );
				} else {
					$url = $url_arr[0];
				}
			}
		}
		return $url;
	}
	/**
	 * Returns the API URL
	 *
	 * @return string
	 */
	private function get_api_url() {
		$url = $this->api_url;
		// if ( get_option( 'cli_scanner_api' ) == 2 ) { // wt_cli_temp_fix.
		// $url = $this->api_url_alternate;
		// }
		return apply_filters( 'wt_cli_cookie_scanner_api_url', $url );
	}
	/**
	 * Bulk scanner API.
	 *
	 * @param array $urls Array of URLs.
	 * @return array
	 */
	public function fetch_all_cookies( $urls ) {

		$endpoint = $this->api_url . $this->api_path;
		$token    = $this->get_ckyes_scan_token();
		$scan_id  = $this->get_ckyes_scan_id();

		foreach ( $urls as $key => $url ) {
			$urls[ $key ] = $this->append_bypasspath( $url );
		}
		$request_body = array(
			'domain' => home_url(),
			'urls'   => $urls,
			'scanId' => intval( $scan_id ),
		);

		$raw_response  = wp_remote_post(
			$endpoint,
			array(
				'body'    => wp_json_encode( $request_body ),
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $token,
				),
				'timeout' => 60,
			)
		);
		$response_code = wp_remote_retrieve_response_code( $raw_response );

		if ( 200 !== $response_code ) {
			return false;
		}
		$response = wp_remote_retrieve_body( $raw_response );

		if ( $response ) {
			return $response;
		}
		return false;
	}
}
