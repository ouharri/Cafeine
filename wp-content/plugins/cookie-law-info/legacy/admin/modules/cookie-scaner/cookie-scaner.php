<?php
/**
 * Cookie_Law_Info_Cookie_Scaner class file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Main class for handling the cookie scanner functions
 *
 * @version  1.9.6
 */
class Cookie_Law_Info_Cookie_Scaner extends Cookie_Law_Info_Cookieyes {


	/**
	 * Table name for the san history table.
	 *
	 * @var string
	 */
	public $scan_table = 'cli_cookie_scan';
	/**
	 * Table name for the san URLs table.
	 *
	 * @var string
	 */
	public $url_table = 'cli_cookie_scan_url';
	/**
	 * Table name for the scanned cookies table.
	 *
	 * @var string
	 */
	public $cookies_table = 'cli_cookie_scan_cookies';
	/**
	 * Table name for the sannec cookie category table.
	 *
	 * @var string
	 */
	public $category_table = 'cli_cookie_scan_categories';
	/**
	 * Table name for the san history table.
	 *
	 * @var string
	 */
	public $status_labels;
	/**
	 * To keep the scan history or not
	 *
	 * @var boolean
	 */
	public $not_keep_records = true;
	/**
	 * Initialize the scanner
	 */

	public function __construct() {
		$this->status_labels = array(
			0 => '',
			1 => __( 'Incomplete', 'cookie-law-info' ),
			2 => __( 'Completed', 'cookie-law-info' ),
			3 => __( 'Stopped', 'cookie-law-info' ),
			4 => __( 'Failed', 'cookie-law-info' ),
		);
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ), 5 );
		add_action( 'wt_cli_cookie_scanner_body', array( $this, 'scanner_notices' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'wt_cli_cookie_scan_status', array( $this, 'check_scan_status' ) );
		register_activation_hook( CLI_PLUGIN_FILENAME, array( $this, 'activator' ) );
		add_action( 'wt_cli_initialize_plugin', array( $this, 'activator' ) );
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'cookieyes/v1',
					'/fetch_results',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'fetch_scan_result' ),
						'permission_callback' => '__return_true',
					)
				);
			}
		);
	}
	/**
	 * Include AJAX handler class
	 *
	 * @return void
	 */
	public function init() {
		include plugin_dir_path( __FILE__ ) . 'classes/class-cookie-law-info-cookie-scanner-ajax.php';

	}
	/**
	 * Plugin activation hook
	 *
	 * @return void
	 */
	public function activator() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		if ( is_multisite() ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->install_tables();
				restore_current_blog();
			}
		} else {
			$this->install_tables();
		}
	}
	/**
	 * Check whether table exist or not
	 *
	 * @param string $table_name table name.
	 * @return bool
	 */
	public function table_exists( $table_name ) {
		global $wpdb;

		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) ) === $table_name ) {
			return true;
		}
		return false;
	}
	/**
	 * Install necessary tables
	 *
	 * @return void
	 */
	public function install_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . $this->scan_table;

		if ( false === $this->table_exists( $table_name ) ) {
			$create_table_sql = "CREATE TABLE `$table_name`(
			    `id_cli_cookie_scan` INT NOT NULL AUTO_INCREMENT,
			    `status` INT NOT NULL DEFAULT '0',
			    `created_at` INT NOT NULL DEFAULT '0',
			    `total_url` INT NOT NULL DEFAULT '0',
			    `total_cookies` INT NOT NULL DEFAULT '0',
			    `current_action` VARCHAR(50) NOT NULL,
			    `current_offset` INT NOT NULL DEFAULT '0',
			    PRIMARY KEY(`id_cli_cookie_scan`)
            ) $charset_collate;";

			dbDelta( $create_table_sql );
		}

		// Creates a table to store all the URLs.
		$table_name = $wpdb->prefix . $this->url_table;

		if ( false === $this->table_exists( $table_name ) ) {
			$create_table_sql = "CREATE TABLE `$table_name`(
			    `id_cli_cookie_scan_url` INT NOT NULL AUTO_INCREMENT,
			    `id_cli_cookie_scan` INT NOT NULL DEFAULT '0',
			    `url` TEXT NOT NULL,
			    `scanned` INT NOT NULL DEFAULT '0',
			    `total_cookies` INT NOT NULL DEFAULT '0',
			    PRIMARY KEY(`id_cli_cookie_scan_url`)
            ) $charset_collate;";

			dbDelta( $create_table_sql );
		}

		// Creates a table to store all the categories of cookies.
		$table_name = $wpdb->prefix . $this->category_table;

		if ( false === $this->table_exists( $table_name ) ) {
			$create_table_sql = "CREATE TABLE `$table_name`(
                `id_cli_cookie_category` INT NOT NULL AUTO_INCREMENT,
                `cli_cookie_category_name` VARCHAR(100) NOT NULL,
                `cli_cookie_category_description` TEXT  NULL,
                PRIMARY KEY(`id_cli_cookie_category`),
                UNIQUE `cookie` (`cli_cookie_category_name`)
            )";
			$this->insert_scanner_tables( $create_table_sql, $charset_collate );
		}

		// Creates a table to store all the scanned cookies.
		$table_name = $wpdb->prefix . $this->cookies_table;

		if ( false === $this->table_exists( $table_name ) ) {
			$create_table_sql = "CREATE TABLE `$table_name`(
			    `id_cli_cookie_scan_cookies` INT NOT NULL AUTO_INCREMENT,
			    `id_cli_cookie_scan` INT NOT NULL DEFAULT '0',
			    `id_cli_cookie_scan_url` INT NOT NULL DEFAULT '0',
			    `cookie_id` VARCHAR(255) NOT NULL,
			    `expiry` VARCHAR(255) NOT NULL,
			    `type` VARCHAR(255) NOT NULL,
			    `category` VARCHAR(255) NOT NULL,
                `category_id` INT NOT NULL,
                `description` TEXT NULL DEFAULT '',
			    PRIMARY KEY(`id_cli_cookie_scan_cookies`),
			    UNIQUE `cookie` (`id_cli_cookie_scan`, `cookie_id`)
            )";

			$this->insert_scanner_tables( $create_table_sql, $charset_collate );
		}
		$this->update_tables();
	}
	/**
	 * Add foreign key constraint to cookie table
	 *
	 * @return void
	 */
	private function update_tables() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}cli_cookie_scan_cookies ADD CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES {$wpdb->prefix}cli_cookie_scan_categories (`id_cli_cookie_category`)" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}
	/**
	 * Recursice function to insert sanner tables no matter what error has occured
	 *
	 * @param string  $sql sql query.
	 * @param string  $prop property value.
	 * @param integer $status current status fail or success.
	 * @return boolean
	 */
	private function insert_scanner_tables( $sql, $prop = '', $status = 0 ) {

		global $wpdb;
		dbDelta( $sql . ' ' . $prop );
		if ( $wpdb->last_error ) {
			$status++;
			if ( 1 === $status ) {
				$prop = '';
			} elseif ( 2 === $status ) {
				$prop = 'ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci';
			} else {
				return true;
			}
			$this->insert_scanner_tables( $sql, $prop, $status );
		} else {
			return true;
		}
	}
	/**
	 * Add cookie scanner submenu
	 *
	 * @return void
	 */
	public function add_admin_pages() {
		add_submenu_page(
			'edit.php?post_type=' . CLI_POST_TYPE,
			__( 'Cookie Scanner', 'cookie-law-info' ),
			__( 'Cookie Scanner', 'cookie-law-info' ),
			'manage_options',
			'cookie-law-info-cookie-scaner',
			array( $this, 'cookie_scaner_page' )
		);
	}
	/**
	 * Check if all the tables are inserted
	 *
	 * @return bool
	 */
	protected function check_tables() {
		global $wpdb;

		$scanner_tables = array(
			$this->scan_table,
			$this->url_table,
			$this->cookies_table,
			$this->category_table,

		);
		foreach ( $scanner_tables as $table ) {
			$table_name = $wpdb->prefix . $table;
			if ( ! $wpdb->get_results( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ), ARRAY_N ) ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				return false;
			}
		}
		return true;
	}
	/**
	 * Main admin page of the cookie scanner,
	 *
	 * @return void
	 */
	public function cookie_scaner_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( __( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) ) );
		}
		if ( 'cookielawinfo_page_cookie-law-info-cookie-scaner' === get_current_screen()->id ) {
			$scan_page_url   = admin_url( 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info-cookie-scaner' );
			$export_page_url = $scan_page_url . '&cli_scan_export=';

			$scan_results = $this->get_last_scan_result();

			include plugin_dir_path( __FILE__ ) . 'views/settings.php';

			$params = array(
				'nonces'      => array(
					'cli_cookie_scaner' => wp_create_nonce( 'cli_cookie_scaner' ),
				),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'scan_status' => ( $this->check_scan_status() === 1 ? true : false ),
				'loading_gif' => plugin_dir_url( __FILE__ ) . 'assets/images/loading.gif',
				'labels'      => array(
					'scanned'             => __( 'Scanned', 'cookie-law-info' ),
					'finished'            => __( 'Scanning completed.', 'cookie-law-info' ),
					'import_finished'     => __( 'Added to cookie list.', 'cookie-law-info' ),
					'finding'             => __( 'Finding pages...', 'cookie-law-info' ),
					'scanning'            => __( 'Scanning pages...', 'cookie-law-info' ),
					'error'               => __( 'Error', 'cookie-law-info' ),
					'stop'                => __( 'Stop', 'cookie-law-info' ),
					'scan_again'          => __( 'Scan again', 'cookie-law-info' ),
					'export'              => __( 'Download cookies as CSV', 'cookie-law-info' ),
					'import'              => __( 'Add to cookie list', 'cookie-law-info' ),
					'view_result'         => __( 'View scan result', 'cookie-law-info' ),
					'import_options'      => __( 'Import options', 'cookie-law-info' ),
					'replace_old'         => __( 'Replace old', 'cookie-law-info' ),
					'merge'               => __( 'Merge', 'cookie-law-info' ),
					'recommended'         => __( 'Recommended', 'cookie-law-info' ),
					'append'              => __( 'Append', 'cookie-law-info' ),
					'not_recommended'     => __( 'Not recommended', 'cookie-law-info' ),
					'cancel'              => __( 'Cancel', 'cookie-law-info' ),
					'start_import'        => __( 'Start import', 'cookie-law-info' ),
					'importing'           => __( 'Importing....', 'cookie-law-info' ),
					'refreshing'          => __( 'Refreshing....', 'cookie-law-info' ),
					'reload_page'         => __( 'Error !!! Please reload the page to see cookie list.', 'cookie-law-info' ),
					'stoping'             => __( 'Stopping...', 'cookie-law-info' ),
					'scanning_stopped'    => __( 'Scanning stopped.', 'cookie-law-info' ),
					'ru_sure'             => __( 'Are you sure?', 'cookie-law-info' ),
					'success'             => __( 'Success', 'cookie-law-info' ),
					'thankyou'            => __( 'Thank you', 'cookie-law-info' ),
					'checking_api'        => __( 'Checking API', 'cookie-law-info' ),
					'sending'             => __( 'Sending...', 'cookie-law-info' ),
					'total_urls_scanned'  => __( 'Total URLs scanned', 'cookie-law-info' ),
					'total_cookies_found' => __( 'Total Cookies found', 'cookie-law-info' ),
					'page_fetch_error'    => __( 'Could not fetch the URLs, please try again', 'cookie-law-info' ),
					'abort'               => __( 'Aborting the scan...', 'cookie-law-info' ),
					'abort_failed'        => __( 'Could not abort the scan, please try again', 'cookie-law-info' ),
				),
			);
			wp_enqueue_script( 'cookielawinfo_cookie_scaner', plugin_dir_url( __FILE__ ) . 'assets/js/cookie-scaner.js', array(), CLI_VERSION, true );
			wp_localize_script( 'cookielawinfo_cookie_scaner', 'cookielawinfo_cookie_scaner', $params );
		}
	}
	/**
	 * Insert a new scan entry to the scanner table
	 *
	 * @param array $data_arr Array of data.
	 * @param int   $scan_id scan ID.
	 * @return bool
	 */
	protected function update_scan_entry( $data_arr, $scan_id ) {
		global $wpdb;
		$scan_table = $wpdb->prefix . $this->scan_table;
		if ( $wpdb->update( $scan_table, $data_arr, array( 'id_cli_cookie_scan' => esc_sql( $scan_id ) ) ) ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Insert the URL's
	 *
	 * @param int    $scan_id scan ID.
	 * @param string $permalink Permalink of a page or post.
	 * @return void
	 */
	protected function insert_url( $scan_id, $permalink ) {
		global $wpdb;
		$url_table = $wpdb->prefix . $this->url_table;
		$data_arr  = array(
			'id_cli_cookie_scan' => esc_sql( $scan_id ),
			'url'                => esc_sql( $permalink ),
			'scanned'            => 0,
			'total_cookies'      => 0,
		);
		$wpdb->insert( $url_table, $data_arr );
	}

	/**
	 * Get current scan status text
	 *
	 * @param [type] $status current status of the scan.
	 * @return string
	 */
	public function get_scan_status_text( $status ) {
		return isset( $this->status_labels[ $status ] ) ? $this->status_labels[ $status ] : __( 'Unknown', 'cookie-law-info' );
	}
	/**
	 * Return the last scan results
	 *
	 * @return array
	 */
	protected function get_last_scan() {
		global $wpdb;
		$scan_table = $wpdb->prefix . $this->scan_table;
		$data       = array();
		if ( true === $this->table_exists( $scan_table ) ) {
			$raw_data = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}cli_cookie_scan ORDER BY id_cli_cookie_scan DESC LIMIT 1", ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			if ( $raw_data ) {
				$data['id_cli_cookie_scan'] = isset( $raw_data['id_cli_cookie_scan'] ) ? absint( $raw_data['id_cli_cookie_scan'] ) : 0;
				$data['status']             = isset( $raw_data['status'] ) ? absint( $raw_data['status'] ) : 1;
				$data['created_at']         = isset( $raw_data['created_at'] ) ? sanitize_text_field( $raw_data['created_at'] ) : '';
				$data['total_url']          = isset( $raw_data['total_url'] ) ? absint( $raw_data['total_url'] ) : 0;
				$data['total_cookies']      = isset( $raw_data['total_cookies'] ) ? absint( $raw_data['total_cookies'] ) : 0;
				$data['current_action']     = isset( $raw_data['current_action'] ) ? sanitize_text_field( $raw_data['current_action'] ) : '';
				$data['current_offset']     = isset( $raw_data['current_offset'] ) ? (int) $raw_data['current_offset'] : -1;
				return $data;
			}
		}
		return false;

	}
	/**
	 * Return the current scan status progress, failed , or success.
	 *
	 * @return integer
	 */
	public function check_scan_status() {
		$last_scan = $this->get_last_scan();
		$status    = ( isset( $last_scan['status'] ) ? $last_scan['status'] : 0 );
		if ( $this->get_cookieyes_status() === 0 || $this->get_cookieyes_status() === false ) {
			$status = 0;
		}
		return intval( $status );
	}
	/**
	 * Display a notice if not connected to CookieYes
	 *
	 * @param boolean $existing if existing customer return different notice.
	 * @return string
	 */
	public function get_cookieyes_scan_notice( $existing = false ) {

		$ckyes_link             = 'https://www.cookieyes.com/';
		$ckyes_privacy_policy   = 'https://www.cookieyes.com/privacy-policy';
		$ckyes_terms_conditions = 'https://www.cookieyes.com/terms-and-conditions/';

		$notice = '<p>' . __( 'Scan your website with CookieYes, our scanning solution for high-speed, accurate cookie scanning', 'cookie-law-info' ) . '</p>';
		$notice = '<p style="font-weight:500;">' . sprintf(
			wp_kses(
				__( 'Clicking “Connect & scan” will let you connect with a free <a href="%1$s" target="_blank">CookieYes</a> account and initiate scanning of your website for cookies. These cookies along with their description will be listed under the cookie declaration popup. By continuing, you agree to CookieYes\'s <a href="%2$s" target="_blank">Privacy Policy</a> & <a href="%3$s" target="_blank">Terms of service</a>.', 'cookie-law-info' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_url( $ckyes_link ),
			esc_url( $ckyes_privacy_policy ),
			esc_url( $ckyes_terms_conditions )
		) . '</p>';

		$notice = Cookie_Law_Info_Admin::wt_cli_admin_notice( 'info', $notice );
		if ( false === $existing ) { // Existing user so show this notice.
			$notice .= '<div class="wt-cli-cookie-scanner-actions"><a id="wt-cli-ckyes-connect-scan" class="button-primary">' . __( 'Connect & scan', 'cookie-law-info' ) . '</a></div>';
		}
		return $notice;
	}
	/**
	 * Get last scan info
	 *
	 * @return string
	 */
	public function get_last_scan_info() {

		$last_scan = $this->get_last_scan();

		$scan_notice  = $this->get_scan_default_html();
		$show_results = false;

		if ( $last_scan ) {
			$scan_status = intval( ( isset( $last_scan['status'] ) ? $last_scan['status'] : 0 ) );
			if ( 2 === $scan_status ) {
				$scan_notice  = $this->get_scan_success_html( $last_scan );
				$show_results = true;
			} elseif ( 3 === $scan_status ) {
				$scan_notice = $this->get_scan_abort_html( $last_scan );
			} elseif ( 4 === $scan_status ) {
				$scan_notice = $this->get_scan_failed_html( $last_scan );
			}
		}
		$notice  = '<div class="wt-cli-cookie-scan-container">';
		$notice .= '<div class="wt-cli-cookie-scanner-actions">' . apply_filters( 'wt_cli_ckyes_account_widget', '' ) . '</div>';
		$notice .= $scan_notice;
		$notice .= '</div>';
		$notice .= '<div class="wt-cli-cookie-scanner-actions">' . $this->get_scan_btn() . '</div>';
		if ( true === $show_results ) {
			$notice .= $this->get_scan_result_table();
		}
		return $notice;

	}
	/**
	 * Default HTML content if no scanning has performed
	 *
	 * @return string
	 */
	public function get_scan_default_html() {
		$message = '<p>' . __( 'You haven\'t performed a site scan yet.', 'cookie-law-info' ) . '</p>';
		return Cookie_Law_Info_Admin::wt_cli_admin_notice( 'info', $message );
	}
	/**
	 * Scan success HTML
	 *
	 * @param [type] $scan_data scan data.
	 * @return string
	 */
	public function get_scan_success_html( $scan_data ) {

		$message        = '';
		$result_page    = admin_url( 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info-cookie-scaner&scan_result' );
		$last_scan_date = ( isset( $scan_data['created_at'] ) ? $scan_data['created_at'] : '' );

		if ( ! empty( ( $last_scan_date ) ) ) {
			$last_scan_date = date( 'F j, Y g:i a T', $last_scan_date );
		}

		$last_scan_text = sprintf( wp_kses( __( 'Last scan: %1$s', 'cookie-law-info' ), array( 'a' => array( 'href' => array() ) ) ), $last_scan_date );
		$message        = '<div class="wt-cli-scan-status"><p><b>' . __( 'Scan complete', 'cookie-law-info' ) . '</b></p></div>';
		$message       .= '<div class="wt-cli-scan-date"><p style="color: #80827f;">' . $last_scan_text . '</p></div>';

		return Cookie_Law_Info_Admin::wt_cli_admin_notice( 'success', $message, true );

	}
	/**
	 * Scan failed HTML
	 *
	 * @param array $scan_data scan result.
	 * @return string
	 */
	public function get_scan_failed_html( $scan_data ) {

		$last_scan_date = ( isset( $scan_data['created_at'] ) ? $scan_data['created_at'] : '' );

		if ( ! empty( ( $last_scan_date ) ) ) {
			$last_scan_date = date( 'F j, Y g:i a T', $last_scan_date );
		}

		$message  = '<div class="wt-cli-scan-status"><p><b>' . __( 'Scan failed', 'cookie-law-info' ) . '</b></p></div>';
		$message .= '<div class="wt-cli-scan-date"><p style="color: #80827f;">' . __( 'Last scan:', 'cookie-law-info' ) . ' ' . $last_scan_date . '</p></div>';

		return Cookie_Law_Info_Admin::wt_cli_admin_notice( 'warning', $message, true );
	}
	/**
	 * Scan abort HTML
	 *
	 * @param array $scan_data scan result.
	 * @return string
	 */
	public function get_scan_abort_html( $scan_data ) {
		$last_scan_date = ( isset( $scan_data['created_at'] ) ? $scan_data['created_at'] : '' );

		if ( ! empty( ( $last_scan_date ) ) ) {
			$last_scan_date = date( 'F j, Y g:i a T', $last_scan_date );
		}

		$message  = '<div class="wt-cli-scan-status"><p><b>' . __( 'Scan aborted', 'cookie-law-info' ) . '</b></p></div>';
		$message .= '<div class="wt-cli-scan-date"><p style="color: #80827f;">' . __( 'Last scan:', 'cookie-law-info' ) . ' ' . $last_scan_date . '</p></div>';
		return Cookie_Law_Info_Admin::wt_cli_admin_notice( 'alert', $message, true );
	}
	/**
	 * Scan progress HTML.
	 *
	 * @return string
	 */
	public function get_scan_progress_html() {

		$last_scan                = $this->get_last_scan();
		$total_urls               = ( isset( $last_scan['total_url'] ) ? $last_scan['total_url'] : 0 );
		$last_scan_timestamp      = ( isset( $last_scan['created_at'] ) ? $last_scan['created_at'] : '' );
		$scan_estimate_in_seconds = $this->get_ckyes_scan_estimate();
		$scan_estimate            = date( 'H:i:s', $scan_estimate_in_seconds );
		$last_scan_date           = '';
		if ( ! empty( ( $last_scan_timestamp ) ) ) {
			$last_scan_date = date( 'F j, Y g:i a T', $last_scan_timestamp );
		}
		// $offset_time = ( $scan_estimate_in_seconds > HOUR_IN_SECONDS ) ? $scan_estimate_in_seconds : HOUR_IN_SECONDS;
		// $show_abort  = ( time() > ( intval( $last_scan_timestamp ) + intval( $offset_time ) ) ) ? true : false;

		$html = '<div class="wt-cli-scan-status-container" style="">
						<div class="wt-cli-row">
							<div class="wt-cli-col-5">
								<div class="wt-cli-row">
									<div class="wt-cli-col-5">
										<div class="wt-cli-scan-status-bar" style="display:flex;align-items:center; color: #2fab10;">
											<span class="wt-cli-status-icon wt-cli-status-success"></span><span style="margin-left:10px">' . __( 'Scan initiated...', 'cookie-law-info' ) . '</span>
										</div>
									</div>
									<div class="wt-cli-col-7"><a id="wt-cli-cookie-scan-abort" href="#">' . __( 'Abort scan', 'cookie-law-info' ) . '</a></div>
								</div>
							</div>
						</div>
					</div>
					<div class="wt-scan-status-info">
						<div class="wt-cli-row">
							<div class="wt-cli-col-5">
								<div class="wt-scan-status-info-item">
									<div class="wt-cli-row">
										<div class="wt-cli-col-5">
											<b>' . __( 'Scan started at', 'cookie-law-info' ) . ':</b> 
										</div>
										<div class="wt-cli-col-7">' . $last_scan_date . '</div>
									</div>
								</div>
								<div class="wt-scan-status-info-item">
									<div class="wt-cli-row">
										<div class="wt-cli-col-5">
											<b>' . __( 'Total URLs', 'cookie-law-info' ) . ':</b> 
										</div>
										<div class="wt-cli-col-7">' . $total_urls . '</div>
									</div>
								</div>
								<div class="wt-scan-status-info-item">
									<div class="wt-cli-row">
										<div class="wt-cli-col-5">
											<b>' . __( 'Total estimated time (Approx)', 'cookie-law-info' ) . ':</b> 
										</div>
										<div class="wt-cli-col-7">' . $scan_estimate . '</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
					<div class="wt-cli-notice wt-cli-info">' . __( 'Your website is currently being scanned for cookies. This might take from a few minutes to a few hours, depending on your website speed and the number of pages to be scanned.', 'cookie-law-info' ) .

					'</br><b>' . __( 'Once the scanning is complete, we will notify you by email.', 'cookie-law-info' ) . '</b></div>
					';
		return $html;
	}
	/**
	 * Scanner notices
	 *
	 * @param boolean $return check whether to return or print the content.
	 * @return string
	 */
	public function scanner_notices( $return = false ) {

		$notice        = '';
		$cookies_table = '';
		$html          = '';

		if ( ! $this->check_tables() ) {
			$notice = $this->get_table_missing_notice();
		} elseif ( $this->check_if_local_server() === true ) {
			$message = __( 'Unable to load cookie scanner. Scanning will not work on local servers', 'cookie-law-info' );
			$notice  = Cookie_Law_Info_Admin::wt_cli_admin_notice( 'warning', $message );
		} elseif ( Cookie_Law_Info_Cookies::get_instance()->check_if_old_category_table() === true ) {
			$notice = $this->cookies_scan_features();
		} else {
			if ( $this->get_cookieyes_status() === false ) {

				$last_scan = $this->get_last_scan();
				if ( $last_scan ) {
					$notice  = $this->get_cookieyes_scan_notice( true ); // Existing customer so there should be no connect button.
					$notice .= $this->get_last_scan_info();
				} else {
					$notice  = $this->get_cookieyes_scan_notice();
					$notice .= $this->cookies_scan_features();
				}
			} else {
				if ( $this->check_scan_status() === 1 ) {
					$notice = $this->get_scan_progress_html();
				} else {
					$notice = $this->get_last_scan_info();
				}
			}
		}
			$html = $notice;

		if ( true === $return ) {
			return $html;
		} else {
			echo wp_kses_post( $html );

		}
	}
	/**
	 * Table missing notice.
	 *
	 * @return string
	 */
	public function get_table_missing_notice() {

		$message  = __( 'To scan cookies following tables should be present on your database, please check if tables do exist on your database. If not exist please try to deactivate and activate the plugin again.', 'cookie-law-info' );
		$message .= '<ul>';
		$message .= '<li>{$wpdb->prefix}cli_cookie_scan</li>';
		$message .= '<li>{$wpdb->prefix}cli_cookie_scan_url</li>';
		$message .= '<li>{$wpdb->prefix}cli_cookie_scan_cookies</li>';
		$message .= '<li>{$wpdb->prefix}cli_cookie_scan_categories</li>';
		$message .= '</ul>';

		$notice = Cookie_Law_Info_Admin::wt_cli_admin_notice( 'warning', $message );
		return $notice;

	}
	/**
	 * Detect whether the server is hosted locally or not
	 *
	 * @return bool
	 */
	public function check_if_local_server() {
		$localhost_arr = array(
			'127.0.0.1',
			'::1',
		);
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip_address = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		} else {
			$ip_address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		}
		$ip_address = apply_filters( 'wt_cli_change_ip_address', $ip_address );
		if ( in_array( $ip_address, $localhost_arr, true ) ) {
			return true;
		}
		return false;
	}
	/**
	 * Return current cookies present on the cookie posts
	 *
	 * @access public
	 * @return array
	 */
	public static function get_cookie_list() {
		$cookies = array();

		$args = array(
			'numberposts' => -1,
			'post_type'   => CLI_POST_TYPE,
			'orderby'     => 'ID',
			'order'       => 'DESC',
		);

		$posts = get_posts( $args );
		if ( isset( $posts ) && is_array( $posts ) && count( $posts ) > 0 ) {
			foreach ( $posts as $post ) {

				$post_meta = get_post_custom( $post->ID );
				$term_list = wp_get_post_terms( $post->ID, 'cookielawinfo-category', array( 'fields' => 'names' ) );
				$category  = isset( $term_list[0] ) ? $term_list[0] : '';

				$cookie_data = array(

					'id'          => $post->post_title,
					'type'        => isset( $post_meta['_cli_cookie_type'][0] ) ? $post_meta['_cli_cookie_type'][0] : '',
					'expiry'      => isset( $post_meta['_cli_cookie_duration'][0] ) ? $post_meta['_cli_cookie_duration'][0] : '',
					'category'    => $category,
					'description' => $post->post_content,
				);
				$cookies[]   = $cookie_data;
			}
		}

		return $cookies;
	}
	/**
	 * Return the last scan results
	 *
	 * @return array
	 */
	public function get_last_scan_result() {

		$last_scan    = $this->get_last_scan();
		$scan_results = array();

		if ( $last_scan && isset( $last_scan['id_cli_cookie_scan'] ) ) {
			$scan_results = $this->get_scan_results_by_id( $last_scan['id_cli_cookie_scan'] );
		}

		return $scan_results;
	}
	/**
	 * Return last scan history
	 *
	 * @param [type] $id Scan ID.
	 * @return array
	 */
	public function get_scan_history( $id ) {
		global $wpdb;
		$scan_table = $wpdb->prefix . $this->scan_table;
		$data       = array();
		$raw_data   = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}cli_cookie_scan WHERE `id_cli_cookie_scan` = %d",
				$id
			),
			ARRAY_A
		);

		if ( $raw_data ) {

			$data['id_cli_cookie_scan'] = isset( $raw_data['id_cli_cookie_scan'] ) ? absint( $raw_data['id_cli_cookie_scan'] ) : 0;
			$data['status']             = isset( $raw_data['status'] ) ? absint( $raw_data['status'] ) : 1;
			$data['created_at']         = isset( $raw_data['created_at'] ) ? sanitize_text_field( $raw_data['created_at'] ) : '';
			$data['total_url']          = isset( $raw_data['total_url'] ) ? absint( $raw_data['total_url'] ) : 0;
			$data['total_cookies']      = isset( $raw_data['total_cookies'] ) ? absint( $raw_data['total_cookies'] ) : 0;
			$data['current_action']     = isset( $raw_data['current_action'] ) ? sanitize_text_field( $raw_data['current_action'] ) : '';
			$data['current_offset']     = isset( $raw_data['current_offset'] ) ? (int) $raw_data['current_offset'] : -1;

			return $data;
		}
		return false;

	}
	/**
	 * Retuns scan results by ID
	 *
	 * @param [type] $id scan ID.
	 * @return array
	 */
	public function get_scan_results_by_id( $id ) {

		$data            = array();
		$scan_info       = $this->get_scan_history( $id );
		$scan_urls       = $this->get_scan_urls( $id );
		$scan_cookies    = $this->get_scan_cookies( $id );
		$data['scan_id'] = $id;
		$data['date']    = isset( $scan_info['created_at'] ) ? $scan_info['created_at'] : '';
		$data['status']  = isset( $scan_info['status'] ) ? $scan_info['status'] : '';

		$data['urls']       = isset( $scan_urls['urls'] ) ? $scan_urls['urls'] : '';
		$data['total_urls'] = isset( $scan_urls['total'] ) ? $scan_urls['total'] : '';

		$data['cookies']       = isset( $scan_cookies['cookies'] ) ? $this->process_cookies( $scan_cookies['cookies'] ) : '';
		$data['total_cookies'] = isset( $scan_cookies['total'] ) ? $scan_cookies['total'] : '';

		return $data;
	}
	/**
	 * Process cookies and re order by description.
	 *
	 * @param array $raw_cookie_data raw cookie data.
	 * @return array
	 */
	public function process_cookies( $raw_cookie_data ) {
		$cookies                   = array();
		$cookie_has_description    = array();
		$cookie_has_no_description = array();
		$count                     = 0;
		foreach ( $raw_cookie_data  as $key => $data ) {
			$cookie_data = array(

				'id'          => isset( $data['cookie_id'] ) ? $data['cookie_id'] : '',
				'type'        => isset( $data['type'] ) ? $data['type'] : '',
				'expiry'      => isset( $data['expiry'] ) ? $data['expiry'] : '',
				'category'    => isset( $data['category'] ) ? $data['category'] : '',
				'description' => isset( $data['description'] ) ? $data['description'] : '',
			);
			if ( '' === $cookie_data['description'] || 'No description' === $cookie_data['description'] ) {
				$cookie_has_no_description[ $count ] = $cookie_data;
			} else {
				$cookie_has_description[ $count ] = $cookie_data;
			}
			$count ++;
		}
		$cookies = $cookie_has_description + $cookie_has_no_description;
		return $cookies;
	}
	/**
	 * Return the scanner URLs
	 *
	 * @param [type]  $scan_id scan ID.
	 * @param integer $offset Offset number.
	 * @param integer $limit page limit if pagination is used.
	 * @return array
	 */
	public function get_scan_urls( $scan_id, $offset = 0, $limit = 100 ) {
		global $wpdb;
		$out = array(
			'total' => 0,
			'data'  => array(),
		);

		$count_arr = $wpdb->get_row( $wpdb->prepare( "SELECT COUNT( id_cli_cookie_scan_url ) AS ttnum FROM {$wpdb->prefix}cli_cookie_scan_url WHERE id_cli_cookie_scan = %d", $scan_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		if ( $count_arr ) {
			$out['total'] = $count_arr['ttnum'];
		}

		$data_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}cli_cookie_scan_url WHERE id_cli_cookie_scan = %d ORDER BY id_cli_cookie_scan_url ASC LIMIT %d,%d", $scan_id, $offset, $limit ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared

		if ( $data_arr ) {
			$out['urls'] = $data_arr;
		}
		return $out;
	}
	/**
	 * Return the identified cookies after the scanning
	 *
	 * @param integer $scan_id scan ID.
	 * @param integer $offset offset number.
	 * @param integer $limit page limit if pagination is used.
	 * @return array
	 */
	public function get_scan_cookies( $scan_id, $offset = 0, $limit = 100 ) {
		global $wpdb;
		$out            = array(
			'total'   => 0,
			'cookies' => array(),
		);
		$limits         = '';
		$cookies        = array();
		$cookies_table  = $wpdb->prefix . $this->cookies_table;
		$url_table      = $wpdb->prefix . $this->url_table;
		$category_table = $wpdb->prefix . $this->category_table;

		$count_arr = $wpdb->get_row( $wpdb->prepare( "SELECT COUNT( id_cli_cookie_scan_cookies ) AS ttnum FROM {$wpdb->prefix}cli_cookie_scan_cookies WHERE id_cli_cookie_scan = %d", $scan_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		if ( $count_arr ) {
			$out['total'] = $count_arr['ttnum'];
		}
		$offset = (int) $offset;
		$limit  = (int) $limit;

		if ( $limit > 0 ) {
			$db_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}cli_cookie_scan_cookies AS cookie INNER JOIN {$wpdb->prefix}cli_cookie_scan_categories as category ON category.id_cli_cookie_category = cookie.category_id INNER JOIN {$wpdb->prefix}cli_cookie_scan_url as urls ON cookie.id_cli_cookie_scan_url = urls.id_cli_cookie_scan_url WHERE cookie.id_cli_cookie_scan = %s ORDER BY id_cli_cookie_scan_cookies ASC LIMIT %d, %d", $scan_id, $offset, $limit ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		} else {
			$db_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}cli_cookie_scan_cookies AS cookie INNER JOIN {$wpdb->prefix}cli_cookie_scan_categories as category ON category.id_cli_cookie_category = cookie.category_id INNER JOIN {$wpdb->prefix}cli_cookie_scan_url as urls ON cookie.id_cli_cookie_scan_url = urls.id_cli_cookie_scan_url WHERE cookie.id_cli_cookie_scan = %s ORDER BY id_cli_cookie_scan_cookies ASC", $scan_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}

		if ( is_array( $db_data ) && ! empty( $db_data ) ) {
			foreach ( (array) $db_data as $raw_data ) {
				$data                                    = array();
				$data['id_cli_cookie_scan_cookies']      = isset( $raw_data['id_cli_cookie_scan_cookies'] ) ? absint( $raw_data['id_cli_cookie_scan_cookies'] ) : 1;
				$data['id_cli_cookie_scan']              = isset( $raw_data['id_cli_cookie_scan'] ) ? absint( $raw_data['id_cli_cookie_scan'] ) : 1;
				$data['id_cli_cookie_scan_url']          = isset( $raw_data['id_cli_cookie_scan_url'] ) ? absint( $raw_data['id_cli_cookie_scan_url'] ) : 1;
				$data['cookie_id']                       = isset( $raw_data['cookie_id'] ) ? sanitize_text_field( $raw_data['cookie_id'] ) : '';
				$data['expiry']                          = isset( $raw_data['expiry'] ) ? sanitize_text_field( $raw_data['expiry'] ) : '';
				$data['type']                            = isset( $raw_data['type'] ) ? sanitize_text_field( $raw_data['type'] ) : 1;
				$data['category']                        = isset( $raw_data['category'] ) ? sanitize_text_field( $raw_data['category'] ) : '';
				$data['category_id']                     = isset( $raw_data['category_id'] ) ? absint( $raw_data['category_id'] ) : '';
				$data['description']                     = isset( $raw_data['description'] ) ? sanitize_textarea_field( $raw_data['description'] ) : '';
				$data['id_cli_cookie_category']          = isset( $raw_data['id_cli_cookie_category'] ) ? absint( $raw_data['id_cli_cookie_category'] ) : '';
				$data['cli_cookie_category_name']        = isset( $raw_data['cli_cookie_category_name'] ) ? sanitize_text_field( $raw_data['cli_cookie_category_name'] ) : '';
				$data['cli_cookie_category_description'] = isset( $raw_data['cli_cookie_category_description'] ) ? sanitize_textarea_field( $raw_data['cli_cookie_category_description'] ) : '';
				$data['url']                             = isset( $raw_data['url'] ) ? esc_url( sanitize_text_field( $raw_data['url'] ) ) : '';
				$data['scanned']                         = isset( $raw_data['scanned'] ) ? absint( $raw_data['scanned'] ) : 0;
				$data['total_cookies']                   = isset( $raw_data['total_cookies'] ) ? absint( $raw_data['total_cookies'] ) : 0;
				$cookies[]                               = $data;
			}
		}
		if ( $cookies ) {
			$out['cookies'] = $cookies;
		}
		return $out;
	}
	/**
	 * Get the last scan ID
	 *
	 * @return int
	 */
	public function get_last_scan_id() {
		$last_scan = $this->get_last_scan();
		$scan_id   = ( isset( $last_scan['id_cli_cookie_scan'] ) ? $last_scan['id_cli_cookie_scan'] : false );
		return $scan_id;
	}
	/**
	 * Insert a new scan entry to the table
	 *
	 * @param integer $total_url Total URL count.
	 * @return int
	 */
	protected function create_scan_entry( $total_url = 0 ) {
		global $wpdb;

		// we are not planning to keep records of old scans.
		if ( $this->not_keep_records ) {
			$this->flush_scan_records();
		}

		$scan_table = $wpdb->prefix . $this->scan_table;
		$data_arr   = array(
			'created_at'     => time(),
			'total_url'      => absint( $total_url ),
			'total_cookies'  => 0,
			'current_action' => 'get_pages',
			'status'         => 1,
		);
		update_option( 'CLI_BYPASS', 1 );
		if ( $wpdb->insert( $scan_table, $data_arr ) ) {
			return $wpdb->insert_id;
		} else {
			return '0';
		}
	}
	/**
	 * Delete all the scan records
	 *
	 * @return void
	 */
	public function flush_scan_records() {
		global $wpdb;
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}cli_cookie_scan;" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}cli_cookie_scan_url;" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}cli_cookie_scan_cookies;" );
	}
	/**
	 * Fetch the current scan result
	 *
	 * @param array $request Request object.
	 * @return void
	 */
	public function fetch_scan_result( $request ) {

		if ( isset( $request ) && is_object( $request ) ) {
			$request_body = $request->get_body();
			if ( ! empty( $request_body ) ) {
				if ( is_wp_error( $this->save_cookie_data( json_decode( $request_body, true ) ) ) ) {
					wp_send_json_error( __( 'Token mismatch', 'cookie-law-info' ) );
				}
				wp_send_json_success( __( 'Successfully inserted', 'cookie-law-info' ) );
			}
		}
		wp_send_json_error( __( 'Failed to insert', 'cookie-law-info' ) );
	}
	/**
	 * Save cookie data to cookies table
	 *
	 * @param array $cookie_data Array of data.
	 * @return WP_Error|void
	 */
	public function save_cookie_data( $cookie_data ) {

		global $wpdb;
		$url_table = $wpdb->prefix . $this->url_table;
		$scan_id   = $this->get_last_scan_id();
		$scan_urls = array();

		if ( $cookie_data ) {
			if ( $scan_id !== false ) {

				$urls = $wpdb->get_results( $wpdb->prepare( "SELECT id_cli_cookie_scan_url,url FROM {$wpdb->prefix}cli_cookie_scan_url WHERE id_cli_cookie_scan = %s ORDER BY id_cli_cookie_scan_url ASC", $scan_id ), ARRAY_A );

				foreach ( $urls as $url_data ) {
					$url               = isset( $url_data['url'] ) ? sanitize_text_field( $url_data['url'] ) : '';
					$scan_urls[ $url ] = isset( $url_data['id_cli_cookie_scan_url'] ) ? absint( $url_data['id_cli_cookie_scan_url'] ) : 1;
				}
				$scan_data         = ( isset( $cookie_data['scan_result'] ) ? json_decode( $cookie_data['scan_result'], true ) : array() );
				$scan_result_token = ( isset( $cookie_data['scan_result_token'] ) ? $cookie_data['scan_result_token'] : array() );

				if ( $this->validate_scan_instance( $scan_result_token ) === false ) {
					return new WP_Error( 'invalid', __( 'Invalid scan token', 'cookie-law-info' ) );
				}
				$this->insert_categories( $scan_data );
				foreach ( $scan_data as $key => $data ) {
					$cookies  = ( isset( $data['cookies'] ) && is_array( $data['cookies'] ) ) ? $data['cookies'] : array();
					$category = ( isset( $data['category'] ) ? $data['category'] : '' );

					if ( ! empty( $cookies ) ) {
						$this->insert_cookies( $scan_id, $scan_urls, $cookies, $category );
					}
				}
			}
		}
		$this->finish_scan( $scan_id );
	}
	/**
	 * Validate the scan instance sent to the CookieYes for scanning purposes
	 *
	 * @param string $instance Created instance ID.
	 * @return bool
	 */
	public function validate_scan_instance( $instance ) {
		$last_instance = $this->get_ckyes_scan_instance();
		if ( ( 0 !== $instance ) && ! empty( $instance ) && ( $instance === $last_instance ) ) {
			return true;
		}
		return false;
	}
	/**
	 * Finish the scan
	 *
	 * @param int $scan_id Scan ID.
	 * @return void
	 */
	public function finish_scan( $scan_id ) {
		$scan_data = array(
			'current_action' => 'scan_pages',
			'current_offset' => -1,
			'status'         => 2,
		);
		$this->set_ckyes_scan_status( 2 );
		$this->update_scan_entry( $scan_data, $scan_id );
		$this->reset_scan_token();
	}
	/**
	 * Insert all the cookie categories.
	 *
	 * @param array $categories Array of cookie categories.
	 * @return void
	 */
	protected function insert_categories( $categories ) {
		global $wpdb;
		foreach ( $categories as $id => $category_data ) {
			$category    = ( isset( $category_data['category'] ) ? esc_sql( sanitize_text_field( $category_data['category'] ) ) : '' );
			$description = ( isset( $category_data['category_desc'] ) ? esc_sql( addslashes( wp_kses_post( $category_data['category_desc'] ) ) ) : '' );
			if ( ! empty( $category ) ) {
				$wpdb->insert(
					$wpdb->prefix . 'cli_cookie_scan_categories',
					array(
						'cli_cookie_category_name'        => $category,
						'cli_cookie_category_description' => $description,
					),
					array( '%s', '%s' )
				);
			}
		}
	}
	/**
	 * Insert the scanned Cookies to the corresponding table
	 *
	 * @param int    $scan_id scan Id.
	 * @param array  $urls scanned URLs.
	 * @param array  $cookie_data scanned cookies.
	 * @param string $category category.
	 * @return void
	 */
	protected function insert_cookies( $scan_id, $urls, $cookie_data, $category ) {
		global $wpdb;

		foreach ( $cookie_data as $cookies ) {
			if ( is_array( $cookies ) && ! empty( $cookies ) ) {
				$cookie_id   = isset( $cookies['cookie_id'] ) ? esc_sql( sanitize_text_field( $cookies['cookie_id'] ) ) : '';
				$description = isset( $cookies['description'] ) ? esc_sql( wp_kses_post( $cookies['description'] ) ) : '';
				$expiry      = isset( $cookies['duration'] ) ? esc_sql( sanitize_text_field( $cookies['duration'] ) ) : '';
				$type        = isset( $cookies['type'] ) ? esc_sql( sanitize_text_field( $cookies['type'] ) ) : '';
				$category    = esc_sql( sanitize_text_field( $category ) );
				$url_id      = ( isset( $cookies['frist_found_url'] ) ? esc_sql( sanitize_text_field( $cookies['frist_found_url'] ) ) : '' );
				$url_id      = ( isset( $urls[ $url_id ] ) ? esc_sql( $urls[ $url_id ] ) : 1 );
				$category_id = $wpdb->get_var( $wpdb->prepare( "SELECT `id_cli_cookie_category` FROM {$wpdb->prefix}cli_cookie_scan_categories WHERE `cli_cookie_category_name` = %s;", array( $category ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared
				$category_id = esc_sql( absint( $category_id ) );

				if ( ! empty( $cookie_id ) ) {
					$wpdb->insert(
						$wpdb->prefix . 'cli_cookie_scan_cookies',
						array(
							'id_cli_cookie_scan'     => $scan_id,
							'id_cli_cookie_scan_url' => $url_id,
							'cookie_id'              => $cookie_id,
							'expiry'                 => $expiry,
							'type'                   => $type,
							'category'               => $category,
							'category_id'            => $category_id,
							'description'            => $description,

						),
						array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s' )
					);
				}
			}
		}
	}
	/**
	 * List the cookie scan features
	 *
	 * @return string
	 */
	public function cookies_scan_features() {
		$html  = '';
		$html .= '<div class="wt-cli-cookie-scan-features">';
		$html .= '<h3>' . __( 'Why scan your website for cookies?', 'cookie-law-info' ) . '</h3>';
		$html .= '<p>' . __( 'Your website needs to obtain prior consent from your users before setting any cookies other than those required for the proper functioning of your website. Therefore, you need to identify and keep track of all the cookies used on your website.', 'cookie-law-info' ) . '</p>';
		$html .= '<p>' . __( 'Our cookie scanning solution lets you:', 'cookie-law-info' ) . '</p>';
		$html .= '<ul class="wt-cli-cookie-scan-feature-list">';
		$html .= '<li>' . __( 'Discover the first-party and third-party cookies that are being used on your website ( Limited upto 100 pages ).', 'cookie-law-info' ) . '</li>';
		$html .= '<li>' . __( 'Identify what personal data they collect and what are the other purposes they serve.', 'cookie-law-info' ) . '</li>';
		$html .= '<li>' . __( 'Determine whether you need to comply with the data protection laws governing cookies. Eg:- EU’s GDPR, ePrivacy Directive (EU Cookie Law), California’s CCPA, etc.', 'cookie-law-info' ) . '</li>';
		$html .= '</ul>';
		// $html .= '<a href="#" class="wt-cli-cookie-scan-preview-modal">' . __( 'Click here to preview sample cookie declaration', 'cookie-law-info' ) . '</a>';
		$html .= '<div class="wt-cli-modal" id="wt-cli-ckyes-modal-settings-preview">';
		$html .= '<span class="wt-cli-modal-js-close">×</span>';
		$html .= '<div class="wt-cli-modal-body">';
		$html .= '<img src="' . plugin_dir_url( __FILE__ ) . 'assets/images/screenshot-1.png">';
		$html .= '</div></div>';
		$html .= '<div class="wt-cli-cookie-scan-preview-image"><img style="width:318.5px;" src="' . plugin_dir_url( __FILE__ ) . 'assets/images/screenshot-1.png"></div>';
		$html .= '</div>';
		return '<div class="wt-cli-cookie-scan-features-section">' . Cookie_Law_Info_Admin::wt_cli_admin_notice( 'success', $html ) . '</div>';
	}
	public function get_scan_result_table() {

		ob_start();
		$scan_results     = $this->get_last_scan_result();
		$cookie_list_page = admin_url( 'edit.php?post_type=' . CLI_POST_TYPE );
		$scan_status      = intval( ( isset( $scan_results['status'] ) ? $scan_results['status'] : 0 ) );
		$scan_page_url    = admin_url( 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info-cookie-scaner' );

		if ( 2 === $scan_status ) {
			include plugin_dir_path( __FILE__ ) . 'views/scan-results.php';
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	public function get_scan_btn( $strict = false ) {

		$last_scan     = $this->get_last_scan();
		$scan_btn_id   = 'wt-cli-ckyes-scan';
		$scan_btn_text = __( 'Scan website for cookies', 'cookie-law-info' );
		$scan_status   = intval( ( isset( $last_scan['status'] ) ? $last_scan['status'] : 0 ) );
		$show_btn      = true;

		$scan_status = intval( ( isset( $last_scan['status'] ) ? $last_scan['status'] : 0 ) );
		if ( 2 === $scan_status ) {
			$show_btn = false;
		}
		if ( true === $strict ) {
			$scan_btn_text = __( 'Scan again', 'cookie-law-info' );
			$show_btn      = true; // Override the existing settings.
		}
		if ( $this->get_cookieyes_status() === 0 || $this->get_cookieyes_status() === false ) { // Disconnected with Cookieyes after registering account.
			$scan_btn_id   = 'wt-cli-ckyes-connect-scan';
			$scan_btn_text = __( 'Connect & scan', 'cookie-law-info' );
			$show_btn      = true;
			if ( true === $strict ) {
				$show_btn = false;
			}
		} elseif ( $this->get_cookieyes_status() === 2 ) {
			$show_btn = true;
		}

		return ( true === $show_btn ? '<a id="' . $scan_btn_id . '" class="button-primary pull-right">' . $scan_btn_text . '</a>' : '' );

	}
}
new Cookie_Law_Info_Cookie_Scaner();
