<?php

/**
 * Cookies module to handle all the cookies related operations
 *
 * @version 1.9.6
 * @package CookieLawInfo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cookie_Law_Info_Cookies {



	protected $cookies;
	protected $non_necessary_options;
	protected $necessary_options;
	private static $instance;

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'add_meta_box' ) );
		add_action( 'admin_menu', array( $this, 'register_settings_page' ), 20 );
		add_action( 'create_cookielawinfo-category', array( $this, 'add_category_meta' ) );
		add_action( 'edited_cookielawinfo-category', array( $this, 'edit_category_meta' ) );
		add_action( 'cookielawinfo-category_add_form_fields', array( $this, 'add_category_form_fields' ) );
		add_action( 'cookielawinfo-category_edit_form_fields', array( $this, 'edit_category_form_fields' ), 1 );
		add_action( 'save_post', array( $this, 'save_custom_metaboxes' ) );
		add_action( 'manage_edit-cookielawinfo_columns', array( $this, 'manage_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'manage_posts_custom_columns' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'migrate' ) );

		add_action( 'wt_cli_before_cookie_scanner_header', array( $this, 'add_cookie_migration_notice' ) );
		add_action( 'wt_cli_initialize_plugin', array( $this, 'load_default_plugin_settings' ) );
		add_action( 'wt_cli_after_cookie_category_migration', array( $this, 'load_default_terms' ) );

	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init() {
		$this->register_custom_post_type();
		$this->create_taxonomy();
		add_filter( 'wt_cli_cookie_categories', array( $this, 'get_cookies' ) );

	}

	// The function update_term_meta() is only introduced in 4.4 so we have cloned this function locally
	public function update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
		if ( $this->wp_term_is_shared( $term_id ) ) {
			return new WP_Error( 'ambiguous_term_id', __( 'Term meta cannot be added to terms that are shared between taxonomies.', 'cookie-law-info' ), $term_id );
		}

		return update_metadata( 'term', $term_id, $meta_key, $meta_value, $prev_value );
	}

	public function wp_term_is_shared( $term_id ) {
		global $wpdb;
		if ( get_option( 'finished_splitting_shared_terms' ) ) {
			return false;
		}
		$tt_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE term_id = %d", $term_id ) );
		return $tt_count > 1;
	}

	public function get_term_meta( $term_id, $key = '', $single = false ) {
		return get_metadata( 'term', $term_id, $key, $single );
	}

	public function get_cookie_category_terms( $display_all = false ) {
		global $wp_version;
		$taxonomy = 'cookielawinfo-category';
		$terms    = array();
		if ( version_compare( $wp_version, '4.9', '>=' ) ) {
			$args  = array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			);
			$terms = get_terms( $args );
		} else {
			$terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );
		}
		return $terms;
	}
	public function enqueue_scripts( $hook ) {
		global $wp_version;
		if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'cookielawinfo-category' && isset( $_GET['tag_ID'] ) ) {
			if ( version_compare( $wp_version, '4.9', '>=' ) ) {
				$code_editor_js = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

				if ( $code_editor_js !== false ) {
					wp_add_inline_script(
						'code-editor',
						sprintf(
							'jQuery( function() {
								if (jQuery(".wt-cli-code-editor").length) { jQuery(".wt-cli-code-editor").each(function () { wp.codeEditor.initialize(this.id, %s); }); } 
								jQuery("#edittag > table.form-table > tbody").prepend(jQuery("tr.form-field.term-status-field"));
								jQuery("#addtag").prepend(jQuery(".term-status-field"));
							} );',
							wp_json_encode( $code_editor_js )
						)
					);
				}
			}
		}
	}
	public function register_custom_post_type() {
		$labels = array(
			'name'               => __( 'GDPR Cookie Consent', 'cookie-law-info' ),
			'all_items'          => __( 'Cookie List', 'cookie-law-info' ),
			'singular_name'      => __( 'Cookie', 'cookie-law-info' ),
			'add_new'            => __( 'Add New', 'cookie-law-info' ),
			'add_new_item'       => __( 'Add New Cookie Type', 'cookie-law-info' ),
			'edit_item'          => __( 'Edit Cookie Type', 'cookie-law-info' ),
			'new_item'           => __( 'New Cookie Type', 'cookie-law-info' ),
			'view_item'          => __( 'View Cookie Type', 'cookie-law-info' ),
			'search_items'       => __( 'Search Cookies', 'cookie-law-info' ),
			'not_found'          => __( 'Nothing found', 'cookie-law-info' ),
			'not_found_in_trash' => __( 'Nothing found in Trash', 'cookie-law-info' ),
			'parent_item_colon'  => '',
		);
		$args   = array(
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'query_var'           => true,
			'rewrite'             => true,
			'capabilities'        => array(
				'publish_posts'       => 'manage_options',
				'edit_posts'          => 'manage_options',
				'edit_others_posts'   => 'manage_options',
				'delete_posts'        => 'manage_options',
				'delete_others_posts' => 'manage_options',
				'read_private_posts'  => 'manage_options',
				'edit_post'           => 'manage_options',
				'delete_post'         => 'manage_options',
				'read_post'           => 'manage_options',
			),
			'menu_icon'           => plugin_dir_url( __FILE__ ) . 'images/cli_icon.png',
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor' ),
		);

		register_post_type( CLI_POST_TYPE, $args );
	}

	public function add_category_meta( $term_id ) {
		$this->cookie_save_defaultstate( $term_id );
		$this->save_scripts_meta( $term_id );
		$this->save_priority_meta( $term_id );
	}
	public function edit_category_meta( $term_id ) {
		$this->cookie_save_defaultstate( $term_id );
		$this->save_scripts_meta( $term_id );
	}
	public function add_category_form_fields( $term ) {
		$this->cookie_add_defaultstate( $term );
		$this->add_scripts_meta( $term );
	}
	public function edit_category_form_fields( $term ) {
		$this->cookie_edit_defaultstate( $term );
		$this->edit_scripts_meta( $term );
	}
	public function add_meta_box() {

		add_meta_box( '_cli_cookie_slugid', __( 'Cookie ID', 'cookie-law-info' ), array( $this, 'metabox_cookie_slugid' ), 'cookielawinfo', 'side', 'default' );
		add_meta_box( '_cli_cookie_type', __( 'Cookie Type', 'cookie-law-info' ), array( $this, 'metabox_cookie_type' ), 'cookielawinfo', 'side', 'default' );
		add_meta_box( '_cli_cookie_duration', __( 'Cookie Duration', 'cookie-law-info' ), array( $this, 'metabox_cookie_duration' ), 'cookielawinfo', 'side', 'default' );
		add_meta_box( '_cli_cookie_sensitivity', __( 'Cookie Sensitivity', 'cookie-law-info' ), array( $this, 'metabox_cookie_sensitivity' ), 'cookielawinfo', 'side', 'default' );
	}
	/** Display the custom meta box for cookie_slugid */
	public function metabox_cookie_slugid() {
		global $post;
		$custom        = get_post_custom( $post->ID );
		$cookie_slugid = ( isset( $custom['_cli_cookie_slugid'][0] ) ) ? $custom['_cli_cookie_slugid'][0] : '';
		?>
		<label><?php echo esc_html__( 'Cookie ID', 'cookie-law-info' ); ?></label>
		<input name="_cli_cookie_slugid" value="<?php echo esc_attr( sanitize_text_field( $cookie_slugid ) ); ?>" style="width:95%;" />
		<?php
	}

	/** Display the custom meta box for cookie_type */
	public function metabox_cookie_type() {
		 global $post;
		$custom      = get_post_custom( $post->ID );
		$cookie_type = ( isset( $custom['_cli_cookie_type'][0] ) ) ? $custom['_cli_cookie_type'][0] : '';
		?>
		<label><?php echo esc_html__( 'Cookie Type: (persistent, session, third party )', 'cookie-law-info' ); ?></label>
		<input name="_cli_cookie_type" value="<?php echo esc_attr( sanitize_text_field( $cookie_type ) ); ?>" style="width:95%;" />
		<?php
	}

	/** Display the custom meta box for cookie_duration */
	public function metabox_cookie_duration() {
		global $post;
		$custom          = get_post_custom( $post->ID );
		$cookie_duration = ( isset( $custom['_cli_cookie_duration'][0] ) ) ? $custom['_cli_cookie_duration'][0] : '';
		?>
		
		<label><?php echo esc_html__( 'Cookie Duration:', 'cookie-law-info' ); ?></label>
		<input name="_cli_cookie_duration" value="<?php echo esc_attr( sanitize_text_field( $cookie_duration ) ); ?>" style="width:95%;" />
		<?php
	}

	/** Display the custom meta box for cookie_sensitivity */
	public function metabox_cookie_sensitivity() {
		global $post;
		$custom             = get_post_custom( $post->ID );
		$cookie_sensitivity = ( isset( $custom['_cli_cookie_sensitivity'][0] ) ) ? $custom['_cli_cookie_sensitivity'][0] : '';
		?>
		<label><?php echo esc_html__( 'Cookie Sensitivity: ( necessary , non-necessary )', 'cookie-law-info' ); ?></label>
		<input name="_cli_cookie_sensitivity" value="<?php echo esc_attr( sanitize_text_field( $cookie_sensitivity ) ); ?>" style="width:95%;" />
		<?php
	}

	/** Saves all form data from custom post meta boxes, including saitisation of input */
	public function save_custom_metaboxes() {
		global $post;
		if ( isset( $_POST['_cli_cookie_type'] ) ) {
			update_post_meta( $post->ID, '_cli_cookie_type', sanitize_text_field( wp_unslash( $_POST['_cli_cookie_type'] ) ) );
		}
		if ( isset( $_POST['_cli_cookie_duration'] ) ) {
			update_post_meta( $post->ID, '_cli_cookie_duration', sanitize_text_field( wp_unslash( $_POST['_cli_cookie_duration'] ) ) );
		}
		if ( isset( $_POST['_cli_cookie_sensitivity'] ) ) {
			update_post_meta( $post->ID, '_cli_cookie_sensitivity', sanitize_text_field( wp_unslash( $_POST['_cli_cookie_sensitivity'] ) ) );
		}
		if ( isset( $_POST['_cli_cookie_slugid'] ) ) {
			update_post_meta( $post->ID, '_cli_cookie_slugid', sanitize_text_field( wp_unslash( $_POST['_cli_cookie_slugid'] ) ) );
		}
	}
	public function manage_edit_columns( $columns ) {
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'title'       => __( 'Cookie Name', 'cookie-law-info' ),
			'type'        => __( 'Type', 'cookie-law-info' ),
			'category'    => __( 'Category', 'cookie-law-info' ),
			'duration'    => __( 'Duration', 'cookie-law-info' ),
			'sensitivity' => __( 'Sensitivity', 'cookie-law-info' ),
			'slugid'      => __( 'ID', 'cookie-law-info' ),
			'description' => __( 'Description', 'cookie-law-info' ),
		);
		return $columns;
	}

	/** Add column data to custom post type table columns */
	public function manage_posts_custom_columns( $column, $post_id = 0 ) {
		global $post;

		switch ( $column ) {
			case 'description':
					$content_post = get_post( $post_id );
				if ( $content_post ) {
					echo wp_kses_post( wp_unslash( $content_post->post_content ) );
				} else {
					echo '---';
				}
				break;
			case 'type':
				$custom = get_post_custom();
				if ( isset( $custom['_cli_cookie_type'][0] ) ) {
					echo esc_html( wp_unslash( $custom['_cli_cookie_type'][0] ) );
				}
				break;
			case 'category':
				$term_list = wp_get_post_terms( $post->ID, 'cookielawinfo-category', array( 'fields' => 'names' ) );
				if ( ! empty( $term_list ) ) {
					echo esc_html( wp_unslash( $term_list[0] ) );
				} else {
					echo '<i>---</i>';
				}

				break;
			case 'duration':
				$custom = get_post_custom();
				if ( isset( $custom['_cli_cookie_duration'][0] ) ) {
					echo esc_html( wp_unslash( $custom['_cli_cookie_duration'][0] ) );
				}
				break;
			case 'sensitivity':
				$custom = get_post_custom();
				if ( isset( $custom['_cli_cookie_sensitivity'][0] ) ) {
					echo esc_html( wp_unslash( $custom['_cli_cookie_sensitivity'][0] ) );
				}
				break;
			case 'slugid':
				$custom = get_post_custom();
				if ( isset( $custom['_cli_cookie_slugid'][0] ) ) {
					echo esc_html( wp_unslash( $custom['_cli_cookie_slugid'][0] ) );
				}
				break;
		}
	}
	/**
	 * Register cutsom taxonomy
	 *
	 * @return void
	 */
	public function create_taxonomy() {
		register_taxonomy(
			'cookielawinfo-category',
			'cookielawinfo',
			array(
				'labels'              => array(
					'name'         => __( 'Cookie Category', 'cookie-law-info' ),
					'add_new_item' => __( 'Add cookie category', 'cookie-law-info' ),
					'edit_item'    => __( 'Edit cookie category', 'cookie-law-info' ),
				),
				'public'              => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'rewrite'             => true,
				'hierarchical'        => false,
				'show_in_menu'        => ( $this->check_if_old_category_table() === true ? false : true ),
			)
		);

	}
	public static function get_strictly_necessory_categories() {

		$strictly_necessary_categories = array( 'necessary', 'obligatoire' );
		return apply_filters( 'gdpr_strictly_enabled_category', $strictly_necessary_categories );
	}
	/**
	 * Returns necessary category id's
	 *
	 * @return array
	 */
	public function get_necessary_category_ids() {
		$necessory_categories   = self::get_strictly_necessory_categories();
		$necessory_category_ids = array();
		foreach ( $necessory_categories as $category ) {
			$term = $this->get_term_data_by_slug( $category );
			if ( false !== $term ) {
				$necessory_category_ids[] = $term->term_id;
			}
		}
		return $necessory_category_ids;
	}
	public function migrate_cookie_terms() {

		$cookie_categories      = $this->get_cookie_category_options_old();
		$non_necessary_defaults = $this->get_non_necessary_defaults();

		foreach ( $cookie_categories as $slug => $data ) {

			if ( 'non-necessary' === $slug ) {
				if ( false === $this->check_if_category_has_changed( $data, $non_necessary_defaults ) ) {
					continue;
				}
			}
			$existing = get_term_by( 'slug', $slug, 'cookielawinfo-category' );

			if ( $existing === false ) {

				$term = wp_insert_term(
					$data['title'],
					'cookielawinfo-category',
					array(
						'description' => $data['description'],
						'slug'        => $slug,
					)
				);

				if ( is_wp_error( $term ) ) {
					continue;
				}
				$term_id = ( isset( $term['term_id'] ) ? $term['term_id'] : false );
				if ( $term_id !== false ) {
					if ( $data['head_scripts'] !== '' ) {
						$this->update_term_meta( $term_id, '_cli_cookie_head_scripts', $data['head_scripts'] );
					}
					if ( $data['body_scripts'] !== '' ) {
						$this->update_term_meta( $term_id, '_cli_cookie_body_scripts', $data['body_scripts'] );
					}
					$default_state = ( isset( $data['default_state'] ) && $data['default_state'] === true ? 'enabled' : 'disabled' );
					$priority      = isset( $data['priority'] ) ? $data['priority'] : 0;
					$this->update_term_meta( $term_id, 'CLIdefaultstate', $default_state );
					$this->update_term_meta( $term_id, 'CLIpriority', $priority );
					Cookie_Law_Info_Languages::get_instance()->maybe_set_term_language( $term_id ); // In polylang plugin the default language will not be get assigned.
				}
			}
		}
	}

	public function register_settings_page() {
		if ( $this->check_if_old_category_table() === true || true === apply_filters( 'wt_cli_force_show_old_cookie_categories', false ) ) {
			add_submenu_page(
				'edit.php?post_type=' . CLI_POST_TYPE,
				__( 'Non-necessary', 'cookie-law-info' ),
				__( 'Non-necessary', 'cookie-law-info' ),
				'manage_options',
				'cookie-law-info-thirdparty',
				array( $this, 'admin_non_necessary_cookie_page' )
			);
			add_submenu_page(
				'edit.php?post_type=' . CLI_POST_TYPE,
				__( 'Necessary', 'cookie-law-info' ),
				__( 'Necessary', 'cookie-law-info' ),
				'manage_options',
				'cookie-law-info-necessary',
				array( $this, 'admin_necessary_cookie_page' )
			);
		}
	}
	public function get_non_necessary_defaults() {

		$defaults = array(
			'status'        => true,
			'default_state' => true,
			'title'         => 'Non-necessary',
			'description'   => 'Any cookies that may not be particularly necessary for the website to function and is used specifically to collect user personal data via analytics, ads, other embedded contents are termed as non-necessary cookies. It is mandatory to procure user consent prior to running these cookies on your website.',
			'head_scripts'  => '',
			'body_scripts'  => '',
			'cookies'       => '',
		);
		return $defaults;

	}
	public function get_necessary_defaults() {

		$settings = array(
			'status'        => true,
			'default_state' => true,
			'title'         => 'Necessary',
			'description'   => 'Necessary cookies are absolutely essential for the website to function properly. This category only includes cookies that ensures basic functionalities and security features of the website. These cookies do not store any personal information.',
		);
		return $settings;
	}
	public function get_non_necessary_cookie_settings() {
		if ( ! $this->non_necessary_options ) {
			$settings = array();
			$defaults = $this->get_non_necessary_defaults();
			$options  = get_option( 'cookielawinfo_thirdparty_settings' );

			$settings['status']          = isset( $options['thirdparty_on_field'] ) ? $options['thirdparty_on_field'] : $defaults['status'];
			$settings['default_state']   = isset( $options['third_party_default_state'] ) ? $options['third_party_default_state'] : $defaults['default_state'];
			$settings['title']           = isset( $options['thirdparty_title'] ) ? $options['thirdparty_title'] : $defaults['title'];
			$settings['description']     = isset( $options['thirdparty_description'] ) ? $options['thirdparty_description'] : $defaults['description'];
			$settings['head_scripts']    = isset( $options['thirdparty_head_section'] ) ? wp_unslash( $options['thirdparty_head_section'] ) : $defaults['head_scripts'];
			$settings['body_scripts']    = isset( $options['thirdparty_body_section'] ) ? wp_unslash( $options['thirdparty_body_section'] ) : $defaults['body_scripts'];
			$settings['strict']          = false;
			$settings['cookies']         = $this->get_cookies_by_meta( '_cli_cookie_sensitivity', 'non-necessary' );
			$settings['priority']        = 6;
			$this->non_necessary_options = $settings;
		}
		return $this->non_necessary_options;
	}

	public function get_necessary_cookie_settings() {

		if ( ! $this->necessary_options ) {
			$settings = array();
			$defaults = $this->get_necessary_defaults();
			$options  = get_option( 'cookielawinfo_necessary_settings' );

			$settings['status']        = $defaults['status'];
			$settings['default_state'] = $defaults['default_state'];
			$settings['title']         = isset( $options['necessary_title'] ) ? $options['necessary_title'] : $defaults['title'];
			$settings['description']   = isset( $options['necessary_description'] ) ? $options['necessary_description'] : $defaults['description'];
			$settings['head_scripts']  = '';
			$settings['body_scripts']  = '';
			$settings['strict']        = true;
			$settings['cookies']       = $this->get_cookies_by_meta( '_cli_cookie_sensitivity', 'necessary' );
			$this->necessary_options   = $settings;
		}
		return $this->necessary_options;

	}

	public function get_cookie_category_options_old() {

		$cookie_category_options = array(
			'necessary'     => $this->get_necessary_cookie_settings(),
			'non-necessary' => $this->get_non_necessary_cookie_settings(),
		);
		return $cookie_category_options;
	}
	public function get_cookie_category_options() {

		$cookie_data              = array();
		$necessary_categories     = array();
		$non_necessary_categories = array();

		$terms = $this->get_cookie_category_terms();
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( is_object( $term ) ) {

					$strict           = false;
					$term_id          = $term->term_id;
					$term_slug        = $term->slug;
					$term_name        = $term->name;
					$term_description = $term->description;

					$cli_cookie_head_scripts = $this->get_term_meta( $term_id, '_cli_cookie_head_scripts', true );
					$cli_cookie_body_scripts = $this->get_term_meta( $term_id, '_cli_cookie_body_scripts', true );

					$head_scripts = isset( $cli_cookie_head_scripts ) ? wp_unslash( $cli_cookie_head_scripts ) : '';
					$body_scripts = isset( $cli_cookie_body_scripts ) ? wp_unslash( $cli_cookie_body_scripts ) : '';

					if ( Cookie_Law_Info_Languages::get_instance()->is_multilanguage_plugin_active() === true ) {

						$default_language = Cookie_Law_Info_Languages::get_instance()->get_default_language_code();
						$current_language = Cookie_Law_Info_Languages::get_instance()->get_current_language_code();

						if ( $current_language !== $default_language ) {
							$default_term = Cookie_Law_Info_Languages::get_instance()->get_term_by_language( $term_id, $default_language );

							if ( $default_term && $default_term->term_id ) {
								$term_slug = $default_term->slug;
								$term_id   = $default_term->term_id;
							}
						}
					}
					$cookies           = $this->get_cookies_by_term( 'cookielawinfo-category', $term_slug );
					$cli_default_state = $this->get_meta_data_from_db( $term_id, 'CLIdefaultstate' );
					$priority          = $this->get_meta_data_from_db( $term_id, 'CLIpriority' );

					$default_state = isset( $cli_default_state ) && $cli_default_state === 'enabled' ? true : false;

					$category_data = array(
						'id'            => $term_id,
						'status'        => true,
						'priority'      => isset( $priority ) ? intval( $priority ) : 0,
						'default_state' => $default_state,
						'strict'        => $strict,
						'title'         => $term_name,
						'description'   => $term_description,
						'head_scripts'  => $head_scripts,
						'body_scripts'  => $body_scripts,
						'cookies'       => $cookies,
					);

					if ( $this->check_strictly_necessary_category( $term_id ) === true ) {
						$strict                             = true;
						$category_data['strict']            = $strict;
						$necessary_categories[ $term_slug ] = $category_data;
					} else {
						$non_necessary_categories[ $term_slug ] = $category_data;
					}
				}
			}
			$non_necessary_categories = $this->order_category_by_key( $non_necessary_categories, 'priority' );
			$cookie_data              = $necessary_categories + $non_necessary_categories;
		}
		return $cookie_data;

	}
	public function get_cookies() {

		if ( ! $this->cookies ) {
			if ( $this->check_if_old_category_table() === true ) {
				$this->cookies = $this->get_cookie_category_options_old();
			} else {
				$this->cookies = $this->get_cookie_category_options();
			}
		}
		return $this->cookies;
	}
	public function get_cookies_by_term( $taxonomy, $slug ) {
		$cookies = array();
		$args    = array(
			'posts_per_page' => -1,
			'post_type'      => 'cookielawinfo',
			'tax_query'      => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		);
		$posts   = get_posts( $args );
		if ( $posts ) {
			$cookies = $posts;
		}
		return $cookies;
	}
	/*
	* Category default state add form
	*/
	public function cookie_add_defaultstate( $term ) {
		?>
		<div class="form-field term-defaultstate-field">
			<label for="CLIdefaultstate"><?php echo esc_html__( 'Category default state', 'cookie-law-info' ); ?></label>
			<input type="radio" name="CLIdefaultstate" value="enabled"  /><?php echo esc_html__( 'Enabled', 'cookie-law-info' ); ?>
			<input type="radio" name="CLIdefaultstate" value="disabled" checked /><?php echo esc_html__( 'Disabled', 'cookie-law-info' ); ?>	
			<p class="description"><?php echo esc_html__( 'If you enable this option, the category toggle button will be in the active state for cookie consent.', 'cookie-law-info' ); ?></p>
		</div>
		<?php
	}
	public function check_strictly_necessary_category( $term_id ) {
		$strict_enabled = $this->get_necessary_category_ids();
		if ( in_array( $term_id, $strict_enabled ) ) {
			return true;
		}
		return false;
	}
	/*
	* Category Active State edit form
	*/
	public function cookie_edit_defaultstate( $term ) {
		// put the term ID into a variable
		$t_id               = $term->term_id;
		$term_default_state = $this->get_term_meta( $t_id, 'CLIdefaultstate', true );

		if ( $this->check_strictly_necessary_category( $t_id ) === false ) {
			?>
		<tr class="form-field term-defaultstate-field">
			<th><label for="CLIdefaultstate"><?php echo esc_html__( 'Category default state', 'cookie-law-info' ); ?></label></th>			 
			<td>
				<input type="radio" name="CLIdefaultstate" value="enabled" <?php checked( $term_default_state, 'enabled' ); ?>/><label><?php echo esc_html__( 'Enabled', 'cookie-law-info' ); ?></label>
				<input type="radio" name="CLIdefaultstate" value="disabled" <?php checked( $term_default_state, 'disabled' ); ?>/><label><?php echo esc_html__( 'Disabled', 'cookie-law-info' ); ?></label>		 
				<p class="description"><?php echo esc_html__( 'If you enable this option, the category toggle button will be in the active state for cookie consent.', 'cookie-law-info' ); ?></p>
			</td>
		</tr>
			<?php
		}
	}

	/*
	* Category Active State save form
	*/
	public function cookie_save_defaultstate( $term_id ) {
		if ( isset( $_POST['CLIdefaultstate'] ) ) {
			$term_default_state = sanitize_text_field( wp_unslash( $_POST['CLIdefaultstate'] ) );

			if ( $term_default_state ) {
				$this->update_term_meta( $term_id, 'CLIdefaultstate', $term_default_state );
			}
		} else {
			$this->update_term_meta( $term_id, 'CLIdefaultstate', 'disabled' );
		}

	}

	public function add_scripts_meta( $term ) {
		?>
		<div class="form-field term-head-scripts-field">
			<p>	
				<label><b><?php echo esc_html__( 'Head scripts', 'cookie-law-info' ); ?></b></label>
				<label>Script: eg:-  &lt;script&gt; enableGoogleAnalytics(); &lt;/script&gt; </label><br />
				<textarea id="_cli_cookie_head_scripts" rows=5 name="_cli_cookie_head_scripts" class="wt-cli-code-editor"></textarea>
			</p>
		</div>
		<div class="form-field term-body-scripts-field">
			<p>	
				<label><b><?php echo esc_html__( 'Body scripts', 'cookie-law-info' ); ?></b></label>
				<label>Script: eg:-  &lt;script&gt; enableGoogleAnalytics(); &lt;/script&gt; </label><br />
				<textarea id="_cli_cookie_body_scripts" rows="5" name="_cli_cookie_body_scripts" class="wt-cli-code-editor" ></textarea>
			</p>
		</div>
		<?php
	}

	public function edit_scripts_meta( $term ) {
		// put the term ID into a variable
		$term_id      = $term->term_id;
		$head_scripts = $this->get_term_meta( $term_id, '_cli_cookie_head_scripts', true );
		$body_scripts = $this->get_term_meta( $term_id, '_cli_cookie_body_scripts', true );
		?>
		<tr class="form-field term-body-scripts-field">
			<th>
				<label for="_cli_cookie_head_scripts"><?php echo esc_html__( 'Head scripts', 'cookie-law-info' ); ?></label>
			</th>			 
			<td>
				<textarea id="_cli_cookie_head_scripts" rows="5" name="_cli_cookie_head_scripts" class="wt-cli-code-editor"><?php echo wp_unslash( $head_scripts ); ?></textarea>
			</td>
		</tr>
		<tr class="form-field term-head-scripts-field">
			<th>
				<label for="_cli_cookie_body_scripts"><?php echo esc_html__( 'Body scripts', 'cookie-law-info' ); ?></label>
			</th>			 
			<td>
				<textarea  id="_cli_cookie_body_scripts" rows="5" name="_cli_cookie_body_scripts" class="wt-cli-code-editor"><?php echo wp_unslash( $body_scripts ); ?></textarea>
			</td>
		</tr>
		<?php
	}

	public function save_scripts_meta( $term_id ) {
		$head_scripts = ( isset( $_POST['_cli_cookie_head_scripts'] ) ? wp_unslash( $_POST['_cli_cookie_head_scripts'] ) : '' );
		$body_scripts = ( isset( $_POST['_cli_cookie_body_scripts'] ) ? wp_unslash( $_POST['_cli_cookie_body_scripts'] ) : '' );

		$this->update_term_meta( $term_id, '_cli_cookie_head_scripts', $head_scripts );
		$this->update_term_meta( $term_id, '_cli_cookie_body_scripts', $body_scripts );

	}

	public function migrate() {
		global $wp_version;
		if ( isset( $_GET['cat-migrate'] ) && $_GET['cat-migrate'] === 'yes' ) {

			if ( check_admin_referer( 'migrate', 'cookie_law_info_nonce' ) && current_user_can( 'manage_options' ) ) {
				if ( version_compare( $wp_version, '4.4', '<' ) ) {
					echo '<div class="fade error"><p><strong>';
					echo esc_html__( 'WordPress 4.4 or higher is the required version. Please consider upgrading the WordPress before migrating the cookie categories.', 'cookie-law-info' );
					echo '</strong></p></div>';
					if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) == 'xmlhttprequest' ) {
						exit();
					}
				} else {
					$this->migrate_cookie_terms();
					update_option( 'wt_cli_cookie_db_version', '2.0' );
					do_action( 'wt_cli_after_cookie_category_migration' );
					$redirect_url = admin_url( 'edit-tags.php?taxonomy=cookielawinfo-category&post_type=cookielawinfo' );
					wp_safe_redirect( $redirect_url );
				}
			}
		}
	}

	public function admin_non_necessary_cookie_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
		}
		if ( isset( $_POST['update_thirdparty_settings_form'] ) || isset( $_POST['cli_non-necessary_ajax_update'] ) ) {
			check_admin_referer( 'cookielawinfo-update-thirdparty' );
			$this->update_non_necessary_cookie_settings( $_POST );
			$this->finish_request();
		}

		$settings = $this->get_non_necessary_cookie_settings();
		require_once plugin_dir_path( __FILE__ ) . 'views/non-necessary-settings.php';
	}
	public function admin_necessary_cookie_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permission to perform this operation', 'cookie-law-info' ) );
		}
		if ( isset( $_POST['update_necessary_settings_form'] ) || isset( $_POST['cli_necessary_ajax_update'] ) ) {

			check_admin_referer( 'cookielawinfo-update-necessary' );
			$this->update_necessary_cookie_settings( $_POST );
			$this->finish_request();

		}
		$settings = $this->get_necessary_cookie_settings();
		require_once plugin_dir_path( __FILE__ ) . 'views/necessary-settings.php';
	}
	public function update_non_necessary_cookie_settings( $data ) {

		$options                              = array();
		$options['thirdparty_title']          = sanitize_text_field( isset( $data['wt_cli_non_necessary_title'] ) ? $data['wt_cli_non_necessary_title'] : '' );
		$options['thirdparty_on_field']       = (bool) ( isset( $data['thirdparty_on_field'] ) ? Cookie_Law_Info::sanitise_settings( 'thirdparty_on_field', $data['thirdparty_on_field'] ) : false );
		$options['third_party_default_state'] = (bool) ( isset( $data['third_party_default_state'] ) ? Cookie_Law_Info::sanitise_settings( 'third_party_default_state', $data['third_party_default_state'] ) : true );
		$options['thirdparty_description']    = wp_kses_post( isset( $data['thirdparty_description'] ) && $data['thirdparty_description'] !== '' ? $data['thirdparty_description'] : '' );
		$options['thirdparty_head_section']   = wp_unslash( isset( $data['thirdparty_head_section'] ) && $data['thirdparty_head_section'] !== '' ? $data['thirdparty_head_section'] : '' );
		$options['thirdparty_body_section']   = wp_unslash( isset( $data['thirdparty_body_section'] ) && $data['thirdparty_body_section'] !== '' ? $data['thirdparty_body_section'] : '' );

		update_option( 'cookielawinfo_thirdparty_settings', $options );
	}
	public function update_necessary_cookie_settings( $data ) {

		$options                          = array();
		$options['necessary_title']       = sanitize_text_field( isset( $data['wt_cli_necessary_title'] ) ? $data['wt_cli_necessary_title'] : '' );
		$options['necessary_description'] = wp_kses_post( isset( $data['necessary_description'] ) && $data['necessary_description'] !== '' ? $data['necessary_description'] : '' );

		update_option( 'cookielawinfo_necessary_settings', $options );
	}

	public function finish_request() {

		echo '<div class="updated"><p><strong>';
		echo esc_html__( 'Settings Updated.', 'cookie-law-info' );
		echo '</strong></p></div>';
		if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) == 'xmlhttprequest' ) {
			exit();
		}
	}

	public function get_cookie_db_version() {
		$current_db_version = get_option( 'wt_cli_cookie_db_version', '1.0' );
		return $current_db_version;
	}
	public function check_if_old_category_table() {
		return ! is_null( $this->get_cookie_db_version() ) && version_compare( $this->get_cookie_db_version(), '2.0', '<' ) === true;
	}
	public function add_cookie_migration_notice() {
		if ( $this->check_if_old_category_table() === true ) :
			$url = add_query_arg( 'cat-migrate', 'yes', admin_url( 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info-cookie-scaner' ) );

			?>

		 <div class="notice notice-warning">
				 <div class="wt-cli-admin-notice-wrapper">
					<div class="wt-cli-notice-content">
						<p style="font-weight:500;font-size:1.05em;"><?php echo esc_html__( 'Clicking “Migrate cookie categories” will auto migrate your existing cookie categories (Necessary and Non-necessary) to our new Cookie Category taxonomy. This action is required to enable the cookie scanner.', 'cookie-law-info' ); ?></p>
						<h3 style="font-size:1.05em;"><?php echo esc_html__( 'What happens after migration?', 'cookie-law-info' ); ?></h3>
						<ul>
							<li><?php echo esc_html__( 'You no longer need to manage static cookie categories. After the migration, new cookie categories (Necessary, Functional, Analytics, Performance, Advertisement, and Others) will be created automatically. Also, you can easily add custom cookie categories and edit/delete the existing categories including the custom categories.', 'cookie-law-info' ); ?></li>
							<li><?php echo esc_html__( 'If you have made any changes to the existing "Non-necessary" category we will migrate it to the newly created “Cookie Category” section. If not, we will delete the "Non-necessary" category automatically.', 'cookie-law-info' ); ?></li>
							<li><?php echo esc_html__( 'During the migration phase your existing cookie category translations will be lost. Hence we request you to add it manually soon after the migration. You can access the existing translations by navigating to the string translation settings of your translator plugin.', 'cookie-law-info' ); ?></li>
						</ul>
					</div>
					<div class="wt-cli-notice-actions">
						<a href="<?php echo esc_attr( wp_nonce_url( $url, 'migrate', 'cookie_law_info_nonce' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Migrate cookie categories', 'cookie-law-info' ); ?></a>
					</div>
				</div>
		 </div>
			<?php
		endif;
	}
	public function get_cookies_by_meta( $meta, $value ) {
		$cookies = array();
		$args    = array(
			'post_type'  => CLI_POST_TYPE,
			'meta_query' => array(
				array(
					'key'   => $meta,
					'value' => $value,
				),
			),

		);
		$posts = get_posts( $args );
		if ( $posts ) {
			$cookies = $posts;
		}
		return $cookies;
	}
	/**
	 * Load default cookie categories and cookies.
	 *
	 * @return void
	 */
	public function load_default_plugin_settings() {
		$this->load_default_terms();
		$this->load_default_cookies();
	}
	public function get_default_cookie_categories() {

		$cookie_categories = array(

			'necessary'     => array(
				'title'       => 'Necessary',
				'description' => 'Necessary cookies are absolutely essential for the website to function properly. These cookies ensure basic functionalities and security features of the website, anonymously.',
			),
			'functional'    => array(
				'title'       => 'Functional',
				'description' => 'Functional cookies help to perform certain functionalities like sharing the content of the website on social media platforms, collect feedbacks, and other third-party features.',
				'priority'    => 5,
			),
			'performance'   => array(
				'title'       => 'Performance',
				'description' => 'Performance cookies are used to understand and analyze the key performance indexes of the website which helps in delivering a better user experience for the visitors.',
				'priority'    => 4,
			),
			'analytics'     => array(
				'title'       => 'Analytics',
				'description' => 'Analytical cookies are used to understand how visitors interact with the website. These cookies help provide information on metrics the number of visitors, bounce rate, traffic source, etc.',
				'priority'    => 3,
			),
			'advertisement' => array(
				'title'       => 'Advertisement',
				'description' => 'Advertisement cookies are used to provide visitors with relevant ads and marketing campaigns. These cookies track visitors across websites and collect information to provide customized ads.',
				'priority'    => 2,
			),
			'others'        => array(
				'title'       => 'Others',
				'description' => 'Other uncategorized cookies are those that are being analyzed and have not been classified into a category as yet.',
				'priority'    => 1,
			),
		);
		return $cookie_categories;

	}
	public function get_default_cookies() {

		$default_cookies = array(
			'viewed_cookie_policy'               => array(
				'title'       => 'viewed_cookie_policy',
				'description' => 'The cookie is set by the GDPR Cookie Consent plugin and is used to store whether or not user has consented to the use of cookies. It does not store any personal data.',
				'category'    => 'necessary',
				'type'        => 0,
				'expiry'      => '11 months',
				'sensitivity' => 'necessary',
			),
			'cookielawinfo-checkbox-necessary'   => array(
				'title'       => 'cookielawinfo-checkbox-necessary',
				'description' => 'This cookie is set by GDPR Cookie Consent plugin. The cookies is used to store the user consent for the cookies in the category "Necessary".',
				'category'    => 'necessary',
				'type'        => 0,
				'expiry'      => '11 months',
				'sensitivity' => 'necessary',
			),
			'cookielawinfo-checkbox-functional'  => array(
				'title'       => 'cookielawinfo-checkbox-functional',
				'description' => 'The cookie is set by GDPR cookie consent to record the user consent for the cookies in the category "Functional".',
				'category'    => 'necessary',
				'type'        => 0,
				'expiry'      => '11 months',
				'sensitivity' => 'necessary',
			),
			'cookielawinfo-checkbox-performance' => array(
				'title'       => 'cookielawinfo-checkbox-performance',
				'description' => 'This cookie is set by GDPR Cookie Consent plugin. The cookie is used to store the user consent for the cookies in the category "Performance".',
				'category'    => 'necessary',
				'type'        => 0,
				'expiry'      => '11 months',
				'sensitivity' => 'necessary',
			),
			'cookielawinfo-checkbox-analytics'   => array(
				'title'       => 'cookielawinfo-checkbox-analytics',
				'description' => 'This cookie is set by GDPR Cookie Consent plugin. The cookie is used to store the user consent for the cookies in the category "Analytics".',
				'category'    => 'necessary',
				'type'        => 0,
				'expiry'      => '11 months',
				'sensitivity' => 'necessary',
			),
			'cookielawinfo-checkbox-others'      => array(
				'title'       => 'cookielawinfo-checkbox-others',
				'description' => 'This cookie is set by GDPR Cookie Consent plugin. The cookie is used to store the user consent for the cookies in the category "Other.',
				'category'    => 'necessary',
				'type'        => 0,
				'expiry'      => '11 months',
				'sensitivity' => 'necessary',
			),

		);
		return $default_cookies;
	}
	public function load_default_cookies() {

		$default_cookies = $this->get_default_cookies();

		foreach ( $default_cookies as $slug => $cookie_data ) {

			if ( false === $this->wt_cli_post_exists_by_slug( $slug ) ) {

				$category = get_term_by( 'slug', $cookie_data['category'], 'cookielawinfo-category' );
				if ( $category && is_object( $category ) ) {

					$category_id = $category->term_id;
					$cookie_data = array(
						'post_type'     => CLI_POST_TYPE,
						'post_title'    => $cookie_data['title'],
						'post_name'     => $slug,
						'post_content'  => $cookie_data['description'],
						'post_category' => array( $category_id ),
						'post_status'   => 'publish',
						'ping_status'   => 'closed',
						'post_author'   => 1,
						'meta_input'    => array(
							'_cli_cookie_type'        => $cookie_data['type'],
							'_cli_cookie_duration'    => $cookie_data['expiry'],
							'_cli_cookie_sensitivity' => $cookie_data['category'],
							'_cli_cookie_slugid'      => $slug,
						),
					);
					$post_id     = wp_insert_post( $cookie_data );
					wp_set_object_terms( $post_id, $cookie_data['post_category'], 'cookielawinfo-category' );
				}
			}
		}
	}
	private function wt_cli_post_exists_by_slug( $post_slug ) {
		$args_posts = array(
			'post_type'      => CLI_POST_TYPE,
			'post_status'    => 'any',
			'name'           => $post_slug,
			'posts_per_page' => 1,
		);
		$loop_posts = new WP_Query( $args_posts );
		if ( ! $loop_posts->have_posts() ) {
			return false;
		} else {
			$loop_posts->the_post();
			return $loop_posts->post->ID;
		}
	}
	public function load_default_terms() {

		// wt_cli_temp_fix.
		$cookie_categories = $this->get_default_cookie_categories();

		foreach ( $cookie_categories as $slug => $data ) {

			$existing = get_term_by( 'slug', $slug, 'cookielawinfo-category' );

			if ( $existing === false ) {

				$description            = $data['description'];
				$cookie_audit_shortcode = sprintf( '[cookie_audit category="%s" style="winter" columns="cookie,duration,description"]', $slug );
				$description           .= "\n";
				$description           .= $cookie_audit_shortcode;

				$term = wp_insert_term(
					$data['title'],
					'cookielawinfo-category',
					array(
						'description' => $description,
						'slug'        => $slug,
					)
				);

				if ( is_wp_error( $term ) ) {
					continue;
				}
				$term_id = ( isset( $term['term_id'] ) ? $term['term_id'] : false );
				if ( $term_id !== false ) {
					$priority = isset( $data['priority'] ) ? $data['priority'] : 0;
					$this->update_term_meta( $term_id, 'CLIpriority', $priority );
					Cookie_Law_Info_Languages::get_instance()->maybe_set_term_language( $term_id ); // In polylang plugin the default language will not be get assigned.
				}
			}
		}
		do_action( 'wt_cli_after_create_cookie_categories' );
	}
	public function check_if_category_has_changed( $cat_data, $cat_default_data ) {

		$changed        = false;
		$compare_values = array(
			'status',
			'title',
			'default_state',
			'description',
			'head_scripts',
			'body_scripts',
		);
		foreach ( $cat_default_data as $key => $data ) {
			if ( in_array( $key, $compare_values ) ) {
				$current_value = isset( $cat_data[ $key ] ) ? $cat_data[ $key ] : '';
				if ( $current_value !== $cat_default_data[ $key ] ) {
					$changed = true;
				}
			}
		}
		return $changed;
	}
	/**
	 * Internal ordering for cookie categories.
	 *
	 * @param int $term_id term ID.
	 * @return void
	 */
	public function save_priority_meta( $term_id ) {
		update_term_meta( $term_id, 'CLIpriority', 0 );
	}
	/**
	 * Get meta data directly from the DB
	 *
	 * @param int    $term_id Term id.
	 * @param string $meta_key Meta key name.
	 * @return string
	 */
	public function get_meta_data_from_db( $term_id, $meta_key ) {
		global $wpdb;
		$term_value = false;
		$term_meta  = $wpdb->get_row( $wpdb->prepare( "SELECT meta_value FROM $wpdb->termmeta WHERE term_id = %d AND meta_key = %s", $term_id, $meta_key ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( $term_meta ) {
			$term_value = $term_meta->meta_value;
		}
		return $term_value;
	}
	/**
	 * Get term data directly from DB to avoid hooks by other plugins.
	 *
	 * @param string $key using this key the row is fetched.
	 * @param string $value value of the corresponding key.
	 * @return array
	 */
	public function get_term_data_by_slug( $value ) {
		global $wpdb;
		$term_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->terms WHERE slug = %s", $value ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( $term_data && is_object( $term_data ) ) {
			return $term_data;
		}
		return false;
	}
	/**
	 * Re order the categories based on the key
	 *
	 * @param array  $categories Category array.
	 * @param string $key The key based on which an array is sorted.
	 * @param string $order The sort order default DESC.
	 * @return array
	 */
	public function order_category_by_key( $categories, $meta_key, $order = 'DESC' ) {
		$sort_order  = SORT_DESC;
		$meta_values = array();
		if ( 'ASC' === $order ) {
			$sort_order = SORT_ASC;
		}
		if ( ! empty( $categories ) && is_array( $categories ) ) {
			foreach ( $categories as $key => $category ) {
				if ( isset( $category[ $meta_key ] ) ) {
					$meta_values[] = $category[ $meta_key ];
				}
			}
			if ( ! empty( $meta_values ) && is_array( $meta_values ) ) {
				array_multisort( $meta_values, $sort_order, $categories );
			}
		}
		return $categories;
	}
	/**
	 * Get allowed HTML for the cookie scripts
	 *
	 * @return array
	 */
	public function get_allowed_html() {
		return apply_filters(
			'wt_cli_allowed_html',
			array_merge(
				wp_kses_allowed_html( 'post' ),
				array(
					'script'   => array(
						'type'    => array(),
						'src'     => array(),
						'charset' => array(),
						'async'   => array(),
						'defer'   => array(),
					),
					'noscript' => array(),
				)
			)
		);
	}
}
Cookie_Law_Info_Cookies::get_instance();
