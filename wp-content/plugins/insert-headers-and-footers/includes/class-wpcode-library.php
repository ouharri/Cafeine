<?php
/**
 * Load snippets from the wpcode.com snippet library.
 *
 * @package WPCode
 */

/**
 * Class WPCode_Library.
 */
class WPCode_Library {

	/**
	 * Key for storing snippets in the cache.
	 *
	 * @var string
	 */
	protected $cache_key = 'snippets';

	/**
	 * Library endpoint for loading all data.
	 *
	 * @var string
	 */
	protected $all_snippets_endpoint = 'get';

	/**
	 * The key for storing individual snippets.
	 *
	 * @var string
	 */
	protected $snippet_key = 'snippets/snippet';

	/**
	 * The base cache folder for this class.
	 *
	 * @var string
	 */
	protected $cache_folder = 'library';

	/**
	 * The data.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * The default time to live for libary items that are cached.
	 *
	 * @var int
	 */
	protected $ttl = DAY_IN_SECONDS;

	/**
	 * Key for transient used to store already installed snippets.
	 *
	 * @var string
	 */
	protected $used_snippets_transient_key = 'wpcode_used_library_snippets';

	/**
	 * Array of snippet ids that were already loaded from the library.
	 *
	 * @var array
	 */
	protected $library_snippets;

	/**
	 * Meta Key used for storing the library id.
	 *
	 * @var string
	 */
	protected $snippet_library_id_meta_key = '_wpcode_library_id';

	/**
	 * Total number of snippets in the library atm.
	 *
	 * @var int
	 */
	protected $snippets_count;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Class-specific hooks.
	 *
	 * @return void
	 */
	protected function hooks() {
		add_action( 'trash_wpcode', array( $this, 'clear_used_snippets' ) );
		add_action( 'transition_post_status', array( $this, 'clear_used_snippets_untrash' ), 10, 3 );
		add_action( 'wpcode_library_api_auth_connected', array( $this, 'delete_cache' ) );
		add_action( 'wpcode_library_api_auth_connected', array( $this, 'get_data_delayed' ), 15 );
		add_action( 'wpcode_library_api_auth_deleted', array( $this, 'delete_cache' ) );
	}

	/**
	 * Wait for the file cache to be cleared before loading the data.
	 *
	 * @return void
	 */
	public function get_data_delayed() {

		// Wait for the cache to be cleared.
		add_action( 'shutdown', array( $this, 'get_data' ) );
	}

	/**
	 * Grab all the available categories from the library.
	 *
	 * @return array
	 */
	public function get_data() {
		if ( ! isset( $this->data ) ) {
			$this->data = $this->load_data();
		}

		return $this->data;
	}

	/**
	 * Get the number of snippets in the library.
	 *
	 * @return int
	 */
	public function get_snippets_count() {
		if ( ! isset( $this->snippets_count ) ) {
			$this->snippets_count = 0;
			$data                 = $this->get_data();
			if ( ! empty( $data['snippets'] ) ) {
				$this->snippets_count = count( $data['snippets'] );
			}
		}

		return $this->snippets_count;
	}

	/**
	 * Grab data from the cache.
	 *
	 * @param string $key The key used to grab from cache.
	 * @param int    $ttl The time to live for cached data, defaults to class ttl.
	 *
	 * @return array|false
	 */
	public function get_from_cache( $key, $ttl = 0 ) {
		if ( empty( $ttl ) ) {
			$ttl = $this->ttl;
		}

		$data = wpcode()->file_cache->get( $this->cache_folder . '/' . $key, $ttl );

		if ( isset( $data['error'] ) && isset( $data['time'] ) ) {
			if ( $data['time'] + 10 * MINUTE_IN_SECONDS < time() ) {
				return false;
			} else {
				return $this->get_empty_array();
			}
		}

		return $data;
	}

	/**
	 * Load the library data either from the server or from cache.
	 *
	 * @return array
	 */
	public function load_data() {
		$this->data = $this->get_from_cache( $this->cache_key );

		if ( false === $this->data ) {
			$this->data = $this->get_from_server();
		}

		return $this->data;
	}


	/**
	 * Get data from the server.
	 *
	 * @return array
	 */
	private function get_from_server() {
		$data = $this->process_response( $this->make_request( $this->all_snippets_endpoint ) );

		if ( empty( $data['snippets'] ) ) {
			return $this->save_temporary_response_fail( $this->cache_key );
		}

		$this->save_to_cache( $this->cache_key, $data );

		return $data;
	}

	/**
	 * Generic request handler with support for authentication.
	 *
	 * @param string $endpoint The API endpoint to load data from.
	 * @param string $method The method used for the request (GET, POST, etc).
	 * @param array  $data The data to pass in the body for POST-like requests.
	 *
	 * @return string
	 */
	public function make_request( $endpoint = '', $method = 'GET', $data = array() ) {
		$args = array(
			'method'    => $method,
		);
		if ( wpcode()->library_auth->has_auth() ) {
			$args['headers'] = $this->get_authenticated_headers();
		}
		if ( ! empty( $data ) ) {
			$args['body'] = $data;
		}

		$url = add_query_arg(
			array(
				'site'    => rawurlencode( site_url() ),
				'version' => WPCODE_VERSION,
			),
			wpcode()->library_auth->get_api_url( $endpoint )
		);

		$response = wp_remote_request( $url, $args );

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( $response_code > 299 ) {
			// Temporary error so cache for just 10 minutes and then try again.
			return '';
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Get the headers for making an authenticated request.
	 *
	 * @return array
	 */
	public function get_authenticated_headers() {
		// Build the headers of the request.
		return array(
			'Content-Type'    => 'application/x-www-form-urlencoded',
			'Cache-Control'   => 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0',
			'Pragma'          => 'no-cache',
			'Expires'         => 0,
			'Origin'          => site_url(),
			'WPCode-Referer'  => site_url(),
			'WPCode-Sender'   => 'WordPress',
			'WPCode-Site'     => esc_attr( get_option( 'blogname' ) ),
			'WPCode-Version'  => esc_attr( WPCODE_VERSION ),
			'X-WPCode-ApiKey' => wpcode()->library_auth->get_auth_key(),
		);
	}

	/**
	 * When we can't fetch from the server we save a temporary error => true file to avoid
	 * subsequent requests for a while. Returns a properly formatted array for frontend output.
	 *
	 * @param string $key The key used for storing the data in the cache.
	 *
	 * @return array
	 */
	public function save_temporary_response_fail( $key ) {
		$data = array(
			'error' => true,
			'time'  => time(),
		);
		$this->save_to_cache( $key, $data );

		return $this->get_empty_array();
	}

	/**
	 * Get an empty array for a consistent response.
	 *
	 * @return array[]
	 */
	public function get_empty_array() {
		return array(
			'categories' => array(),
			'snippets'   => array(),
		);
	}

	/**
	 * Save to cache.
	 *
	 * @param string      $key The key used to store the data in the cache.
	 * @param array|mixed $data The data that will be stored.
	 *
	 * @return void
	 */
	public function save_to_cache( $key, $data ) {
		wpcode()->file_cache->set( $this->cache_folder . '/' . $key, $data );
	}

	/**
	 * Generic handler for grabbing data by slug. Either all categories or the category slug.
	 *
	 * @param string $data Response body from server.
	 *
	 * @return array
	 */
	public function process_response( $data ) {
		$response = json_decode( $data, true );
		if ( ! isset( $response['status'] ) || 'success' !== $response['status'] ) {
			return $this->get_empty_array();
		}

		return $response['data'];
	}

	/**
	 * Get a cache key for a specific snippet id.
	 *
	 * @param int $id The snippet id.
	 *
	 * @return string
	 */
	public function get_snippet_cache_key( $id ) {
		return $this->snippet_key . '_' . $id;
	}

	/**
	 * Create a new snippet by the library id.
	 * This grabs the snippet by its id from the snippet library site and creates
	 * a new snippet on the current site using the response.
	 *
	 * @param int $library_id The id of the snippet on the library site.
	 *
	 * @return false|WPCode_Snippet
	 */
	public function create_new_snippet( $library_id ) {

		$snippet_data = $this->grab_snippet_from_api( $library_id );

		if ( ! $snippet_data ) {
			return false;
		}

		$snippet = new WPCode_Snippet( $snippet_data );

		$snippet->save();

		delete_transient( $this->used_snippets_transient_key );

		return $snippet;
	}

	/**
	 * Grab a snippet data from the API.
	 *
	 * @param int $library_id The id of the snippet in the Library api.
	 *
	 * @return array|array[]|false
	 */
	public function grab_snippet_from_api( $library_id ) {
		$snippet_request = $this->make_request( 'get/' . $library_id );
		$snippet_data    = $this->process_response( $snippet_request );

		if ( empty( $snippet_data ) ) {
			return false;
		}

		return $snippet_data;
	}

	/**
	 * Get all the snippets that were created from the library, by library ID.
	 * Results are cached in a transient.
	 *
	 * @return array
	 */
	public function get_used_library_snippets() {

		if ( isset( $this->library_snippets ) ) {
			return $this->library_snippets;
		}

		$snippets_from_library = get_transient( $this->used_snippets_transient_key );

		if ( false === $snippets_from_library ) {
			$snippets_from_library = array();

			$args     = array(
				'post_type'   => 'wpcode',
				'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => $this->snippet_library_id_meta_key,
						'compare' => 'EXISTS',
					),
				),
				'fields'      => 'ids',
				'post_status' => 'any',
				'nopaging'    => true,
			);
			$snippets = get_posts( $args );

			foreach ( $snippets as $snippet_id ) {
				$snippets_from_library[ $this->get_snippet_library_id( $snippet_id ) ] = $snippet_id;
			}

			set_transient( $this->used_snippets_transient_key, $snippets_from_library );
		}

		$this->library_snippets = $snippets_from_library;

		return $this->library_snippets;

	}

	/**
	 * Grab the library id from the snippet by snippet id.
	 *
	 * @param int $snippet_id The snippet id.
	 *
	 * @return int
	 */
	public function get_snippet_library_id( $snippet_id ) {
		return absint( get_post_meta( $snippet_id, '_wpcode_library_id', true ) );
	}

	/**
	 * When a snippet is trashed, clear the used snippets transients
	 * for this class instance to avoid confusion in the library.
	 *
	 * @return void
	 */
	public function clear_used_snippets() {
		delete_transient( $this->used_snippets_transient_key );
	}

	/**
	 * Clear used snippets also when a snippet is un-trashed.
	 *
	 * @param string  $new_status
	 * @param string  $old_status
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function clear_used_snippets_untrash( $new_status, $old_status, $post ) {
		if ( 'wpcode' !== $post->post_type || 'trash' !== $old_status ) {
			return;
		}

		$this->clear_used_snippets();
	}

	/**
	 * Delete the file cache for the snippets library.
	 *
	 * @return void
	 */
	public function delete_cache() {
		wpcode()->file_cache->delete( $this->cache_folder . '/' . $this->cache_key );
		if ( isset( $this->data ) ) {
			unset( $this->data );
		}
	}

	/**
	 * Makes a request to the snippet library API to grab a public snippet by its hash.
	 *
	 * @param string $hash The hash used to identify the snippet on the library server.
	 * @param string $auth The unique user hash used to authenticate the request on the library.
	 *
	 * @return array
	 */
	public function get_public_snippet( $hash, $auth ) {
		// Let's use transients for hashes to avoid unnecessary requests.
		$transient_key = 'wpcode_public_snippet_' . $hash . '_' . $auth;
		$snippet_data  = get_transient( $transient_key );
		if ( false === $snippet_data ) {
			$snippet_request = $this->make_request( 'public/' . $hash, 'POST', array(
				'auth' => $auth,
			) );
			$snippet_data    = json_decode( $snippet_request, true );
			// Transient for 1 minute if error otherwise 30 minutes.
			$timeout = ! isset( $snippet_data['status'] ) || 'error' === $snippet_data['status'] ? 60 : 30 * 60;
			set_transient( $transient_key, $snippet_data, $timeout );
		}

		return $snippet_data;
	}
}
