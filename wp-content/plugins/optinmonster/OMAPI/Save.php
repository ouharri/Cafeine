<?php
/**
 * Save class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Save class.
 *
 * @since 1.0.0
 */
class OMAPI_Save {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds save error.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed
	 */
	public $error = null;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Store the optin data locally on the site.
	 *
	 * @since 1.0.0
	 *
	 * @param array $optins  Array of optin objects to store.
	 * @param bool  $enabled Whether newly-added campaigns are auto-enabled. Default is true.
	 */
	public function store_optins( $optins, $enabled = true ) {
		/**
		 * Allows the filtering of what campaigns are stored locally.
		 *
		 * @since 1.6.3
		 *
		 * @param array  $optins An array of `WP_Post` objects.
		 * @param object $this   The OMAPI object.
		 *
		 * @return array The filtered `WP_Post` objects array.
		 */
		$optins = apply_filters( 'optin_monster_pre_store_options', $optins, $this );

		// Do nothing if this is just a success message.
		if ( isset( $optins->success ) ) {
			return;
		}

		// Loop through all of the local optins so we can try to match and update.
		$local_optins = $this->base->get_optins( array( 'post_status' => 'any' ) );
		if ( ! empty( $local_optins ) ) {
			$this->sync_optins( $local_optins, $optins, $enabled );
		} else {
			$this->add_optins( $optins, $enabled );
		}

	}

	/**
	 * Add the retrieved optins as new optin post objects in the DB.
	 *
	 * @since 1.3.5
	 *
	 * @param array $optins  Array of optin objects to store.
	 * @param bool  $enabled Whether newly-added campaigns are auto-enabled. Default is true.
	 */
	public function add_optins( $optins, $enabled = true ) {
		foreach ( (array) $optins as $slug => $optin ) {
			// Maybe update an optin rather than add a new one.
			$local = $this->base->get_optin_by_slug( $slug );
			if ( $local ) {
				$this->update_optin( $local, $optin );
			} else {
				$this->new_optin( $slug, $optin, $enabled );
			}
		}
	}

	/**
	 * Sync the retrieved optins with our stored optins.
	 *
	 * @since 1.3.5
	 *
	 * @param array $local_optins  Array of local optin objects to sync.
	 * @param array $remote_optins Array of optin objects to store.
	 * @param bool  $enabled       Whether newly-added campaigns are auto-enabled. Default is true.
	 */
	public function sync_optins( $local_optins, $remote_optins, $enabled = true ) {
		foreach ( $local_optins as $local ) {

			if ( isset( $remote_optins[ $local->post_name ] ) ) {

				$this->update_optin( $local, $remote_optins[ $local->post_name ] );

				unset( $remote_optins[ $local->post_name ] );
			} else {

				// Delete the local optin. It does not exist remotely.
				$this->delete_optin( $local );
				unset( $remote_optins[ $local->post_name ] );
			}
		}

		// If we still have optins, they are new and we need to add them.
		if ( ! empty( $remote_optins ) ) {
			foreach ( (array) $remote_optins as $slug => $optin ) {
				$local = $this->base->get_optin_by_slug( $slug );
				if ( $local ) {
					$this->update_optin( $local, $optin );
				} else {
					$this->new_optin( $slug, $optin, $enabled );
				}
			}
		}
	}

	/**
	 * Update an existing optin post object in the DB with the one fetched from the API.
	 *
	 * @since  1.3.5
	 *
	 * @param  object $local The local optin post object.
	 * @param  object $optin The optin object.
	 *
	 * @return void
	 */
	public function update_optin( $local, $optin ) {
		$status = 'publish';
		if ( ! empty( $optin->status ) && 'active' !== $optin->status ) {
			$status = 'draft';
		}

		if (
			$optin->title !== $local->post_title
			|| $optin->output !== $local->post_content
			|| $status !== $local->post_status
		) {
			$this->optin_to_db(
				array(
					'ID'           => $local->ID, // Existing ID.
					'post_title'   => $optin->title,
					'post_content' => $optin->output,
					'post_status'  => $status,
				)
			);
		}

		$this->update_optin_meta( $local->ID, $optin );
	}

	/**
	 * Generate a new optin post object in the DB.
	 *
	 * @since  1.3.5
	 *
	 * @param  string $slug    The campaign slug.
	 * @param  object $optin   The optin object.
	 * @param  bool   $enabled Whether the new campaigns are auto-enabled. Default is true.
	 *
	 * @return void
	 */
	public function new_optin( $slug, $optin, $enabled = true ) {
		$status = 'publish';
		if ( ! empty( $optin->status ) && 'active' !== $optin->status ) {
			$status = 'draft';
		}

		$post_id = $this->optin_to_db(
			array(
				'post_name'    => $slug,
				'post_title'   => $optin->title,
				'post_excerpt' => $optin->id,
				'post_content' => $optin->output,
				'post_status'  => $status,
				'post_type'    => OMAPI_Type::SLUG,
			)
		);

		if ( 'post' === $optin->type ) {
			update_post_meta( $post_id, '_omapi_automatic', 1 );
		}

		$enabled = apply_filters( 'optin_monster_auto_enable_campaign', $enabled );
		if ( $enabled ) {
			update_post_meta( $post_id, '_omapi_enabled', true );
		}

		$this->update_optin_meta( $post_id, $optin );
	}

	/**
	 * Adds/updates the optin post-object in the DB.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $args Array of args for post object.
	 *
	 * @return mixed Result
	 */
	protected function optin_to_db( $args ) {
		$priority = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
		if ( false !== $priority ) {
			remove_filter( 'content_save_pre', 'wp_filter_post_kses', $priority );
		}

		if ( ! empty( $args['ID'] ) ) {
			$result = wp_update_post( $args );
		} else {
			$result = wp_insert_post( $args );
		}

		if ( false !== $priority ) {
			add_filter( 'content_save_pre', 'wp_filter_post_kses', $priority );
		}

		return $result;
	}

	/**
	 * Deletes the optin post-type object from the DB.
	 *
	 * @since  1.9.10
	 *
	 * @param  mixed   $id      WP_Post object, or post ID, or campaign slug (post_name).
	 * @param  boolean $by_slug Whether id passed in was the campaign slug.
	 *
	 * @return mixed            Result of wp_delete_post.
	 */
	public function delete_optin( $id, $by_slug = false ) {
		if ( $by_slug ) {
			$id = $this->base->get_optin_by_slug( $id );
		}

		return wp_delete_post( absint( ! empty( $id->ID ) ? $id->ID : $id ), true );
	}

	/**
	 * Update the optin post object's post-meta with an API object's values.
	 *
	 * @since  1.3.5
	 *
	 * @param  int    $post_id The post (optin) ID.
	 * @param  object $optin   The optin object.
	 *
	 * @return void
	 */
	public function update_optin_meta( $post_id, $optin ) {
		update_post_meta( $post_id, '_omapi_type', $optin->type );
		update_post_meta( $post_id, '_omapi_ids', $optin->ids );

		$shortcodes = ! empty( $optin->shortcodes ) ? $optin->shortcodes : null;

		$this->update_shortcodes_meta( $post_id, $shortcodes );
	}

	/**
	 * Store the raw shortcodes to the optin's meta for later retrieval/parsing.
	 *
	 * @since  1.3.5
	 *
	 * @param  int               $post_id    The post (optin) ID.
	 * @param  string|array|null $shortcodes The shortcodes to store to meta, or delete from meta if null.
	 *
	 * @return void
	 */
	protected function update_shortcodes_meta( $post_id, $shortcodes = null ) {
		if ( ! empty( $shortcodes ) ) {
			update_post_meta( $post_id, '_omapi_shortcode_output', self::get_shortcodes_string( $shortcodes ) );
			update_post_meta( $post_id, '_omapi_shortcode', true );
		} else {
			delete_post_meta( $post_id, '_omapi_shortcode_output' );
			delete_post_meta( $post_id, '_omapi_shortcode' );
		}
	}

	/**
	 * Updated the `optin_monster_api` option in the database.
	 *
	 * @since 1.9.8
	 *
	 * @param array $option The full `optin_monster_api` option array.
	 * @param array $data   Optional. The parameters passed in via POST request.
	 *
	 * @return mixed The results of update_option.
	 */
	public function update_option( $option, $data = array() ) {

		// Allow storing the timestamp of when the API is connected for "first time".
		// We are not changing it if the user disconnects and reconnects.
		$connected = $this->base->get_option( 'connected' );
		if ( ! empty( $connected ) ) {
			unset( $option['connected'] );
		}

		/**
		 * Filters the `optin_monster_api` option before being saved to the database.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $option The full `optin_monster_api` option array.
		 * @param array  $data   The parameters passed in via POST request.
		 */
		$option = apply_filters( 'optin_monster_api_save', $option, $data );

		// Save the option.
		return update_option( 'optin_monster_api', $option );
	}

	/**
	 * Handles auto-generating WooCommerce API keys for use with OM.
	 *
	 * @since 1.7.0
	 * @since 2.8.0 All the logic was moved to OMAPI_WooCommerce_Save class.
	 *
	 * @deprecated 2.8.0 Use `OMAPI_WooCommerce_Save->autogenerate()` instead.
	 *
	 * @return array
	 */
	public function woocommerce_autogenerate() {
		_deprecated_function( __FUNCTION__, '2.8.0', 'OMAPI_WooCommerce_Save->autogenerate()' );

		return $this->base->woocommerce->save->autogenerate();
	}

	/**
	 * Handles connecting WooCommerce when the connect button is clicked.
	 *
	 * @since 1.7.0
	 * @since 2.8.0 All the logic was moved to OMAPI_WooCommerce_Save class.
	 *
	 * @deprecated 2.8.0 Use `OMAPI_WooCommerce_Save->connect()` instead.
	 *
	 * @param array $data The data passed in via POST request.
	 *
	 * @return void
	 */
	public function woocommerce_connect( $data ) {
		_deprecated_function( __FUNCTION__, '2.8.0', 'OMAPI_WooCommerce_Save->connect()' );

		return $this->base->woocommerce->save->connect( $data );
	}

	/**
	 * Handles disconnecting WooCommerce when the disconnect button is clicked.
	 *
	 * @since 1.7.0
	 * @since 2.8.0 All the logic was moved to OMAPI_WooCommerce_Save class.
	 *
	 * @deprecated 2.8.0 Use `OMAPI_WooCommerce_Save->disconnect()` instead.
	 *
	 * @param array $data The data passed in via POST request.
	 *
	 * @return void
	 */
	public function woocommerce_disconnect( $data ) {
		_deprecated_function( __FUNCTION__, '2.8.0', 'OMAPI_WooCommerce_Save->disconnect()' );

		return $this->base->woocommerce->save->disconnect( $data );
	}

	/**
	 * Parse shortcodes into a string.
	 *
	 * @since 2.2.0
	 *
	 * @param  mixed $shortcodes Convert shorcodes array to a concatenated string.
	 *
	 * @return string
	 */
	public static function get_shortcodes_string( $shortcodes ) {
		return is_array( $shortcodes )
			? '|||' . implode( '|||', array_map( 'htmlentities', $shortcodes ) )
			: '|||' . htmlentities( $shortcodes, ENT_COMPAT, 'UTF-8' );
	}

}
