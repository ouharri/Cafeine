<?php
/**
 * Snippet manager page - add/edit snippets.
 *
 * @package WPCode
 */

/**
 * WPCode_Admin_Page_Snippet_Manager class.
 */
class WPCode_Admin_Page_Snippet_Manager extends WPCode_Admin_Page {

	/**
	 * The page slug to be used when adding the submenu.
	 *
	 * @var string
	 */
	public $page_slug = 'wpcode-snippet-manager';
	/**
	 * The publish button text depending on the status.
	 *
	 * @var string
	 */
	public $publish_button_text;
	/**
	 * The header title text depending on the status.
	 *
	 * @var string
	 */
	public $header_title;
	/**
	 * The default code type for this page is HTML.
	 *
	 * @var string
	 */
	public $code_type = 'html';
	/**
	 * The action for the nonce when the current page is submitted.
	 *
	 * @var string
	 */
	protected $action = 'wpcode-save-snippet';

	/**
	 * The name of the nonce used for saving.
	 *
	 * @var string
	 */
	protected $nonce_name = 'wpcode-save-snippet-nonce';
	/**
	 * The snippet id.
	 *
	 * @var int
	 */
	protected $snippet_id;
	/**
	 * The snippet instance.
	 *
	 * @var WPCode_Snippet
	 */
	protected $snippet;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Translators: This adds the name of the plugin "WPCode".
		$this->page_title = sprintf( __( 'Add %s Snippet', 'insert-headers-and-footers' ), 'WPCode' );
		$this->menu_title = sprintf( '+ %s', __( 'Add Snippet', 'insert-headers-and-footers' ) );
		parent::__construct();
	}

	/**
	 * Page-specific hooks.
	 *
	 * @return void
	 */
	public function page_hooks() {
		$this->can_edit = current_user_can( 'wpcode_edit_snippets' ) && current_user_can( 'unfiltered_html' );
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['snippet_id'] ) ) {
			$snippet_post = get_post( absint( $_GET['snippet_id'] ) );
			if ( ! is_null( $snippet_post ) && 'wpcode' === $snippet_post->post_type ) {
				$this->snippet_id = $snippet_post->ID;
				$this->snippet    = new WPCode_Snippet( $snippet_post );
			}
			// If the post type does not match the page will act as an add new snippet page, the id will be ignored.
		} elseif ( ! isset( $_GET['custom'] ) ) {
			$this->show_library = apply_filters( 'wpcode_add_snippet_show_library', true );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		$this->publish_button_text = __( 'Save Snippet', 'insert-headers-and-footers' );
		$this->header_title        = __( 'Create Custom Snippet', 'insert-headers-and-footers' );
		if ( isset( $this->snippet ) ) {
			$this->header_title        = __( 'Edit Snippet', 'insert-headers-and-footers' );
			$this->publish_button_text = __( 'Update', 'insert-headers-and-footers' );
		}
		if ( $this->show_library ) {
			$this->header_title = __( 'Add Snippet', 'insert-headers-and-footers' );
		}
		$this->process_message();
		add_action( 'admin_init', array( $this, 'check_status' ) );
		add_filter( 'submenu_file', array( $this, 'change_current_menu' ) );
		add_filter( 'admin_title', array( $this, 'change_page_title' ), 15, 2 );
		add_action( 'admin_init', array( $this, 'submit_listener' ) );
		add_action( 'admin_init', array( $this, 'set_code_type' ) );
		add_filter( 'wpcode_admin_js_data', array( $this, 'add_conditional_rules_to_script' ) );
		add_filter( 'admin_body_class', array( $this, 'maybe_show_tinymce' ) );
		add_filter( 'admin_body_class', array( $this, 'maybe_editor_height_auto' ) );
		add_filter( 'admin_head', array( $this, 'maybe_editor_height' ) );
	}

	/**
	 * Make sure we can't edit a trashed snippet.
	 *
	 * @return void
	 */
	public function check_status() {
		if ( ! isset( $this->snippet ) ) {
			return;
		}
		$post_data = $this->snippet->get_post_data();
		if ( 'trash' === $post_data->post_status ) {
			wp_die( esc_html__( 'You cannot edit this snippet because it is in the Trash. Please restore it and try again.', 'insert-headers-and-footers' ) );
		}
	}

	/**
	 * Process messages specific to this page.
	 *
	 * @return void
	 */
	public function process_message() {
		// phpcs:disable WordPress.Security.NonceVerification
		if ( ! isset( $_GET['message'] ) ) {
			if ( ! current_user_can( 'unfiltered_html' ) ) {
				$this->set_error_message( __( 'Sorry, you only have read-only access to this page. Ask your administrator for assistance editing.', 'insert-headers-and-footers' ) );
			}

			return;
		}

		$error_details = '';
		if ( isset( $_GET['error'] ) ) {
			$error_details = sanitize_text_field( wp_unslash( $_GET['error'] ) );
		}

		$messages = array(
			1 => __( 'Snippet updated.', 'insert-headers-and-footers' ),
			2 => __( 'Snippet created & Saved.', 'insert-headers-and-footers' ),
			3 => __( 'We encountered an error activating your snippet, please check the syntax and try again.', 'insert-headers-and-footers' ),
			4 => __( 'Sorry, you are not allowed to change the status of the snippet.', 'insert-headers-and-footers' ),
		);
		$message  = absint( $_GET['message'] );
		// phpcs:enable WordPress.Security.NonceVerification

		if ( ! isset( $messages[ $message ] ) ) {
			return;
		}

		if ( 3 === $message && ! empty( $error_details ) ) {
			$error_message = sprintf(
			/* translators: %s: Error message. */
				esc_html__( 'Error message: %s', 'insert-headers-and-footers' ),
				'<code>' . $error_details . '</code>'
			);

			$messages[ $message ] .= ' ' . $error_message;
		}

		if ( $message > 2 ) {
			$this->set_error_message( $messages[ $message ] );
		} else {
			$this->set_success_message( $messages[ $message ] );
		}

	}

	/**
	 * If we're editing a snippet, change the active submenu like WP does.
	 *
	 * @param null|string $submenu_file The submenu file.
	 *
	 * @return null|string
	 */
	public function change_current_menu( $submenu_file ) {
		if ( ! isset( $this->snippet_id ) ) {
			// Only change this for when editing a snippet.
			return $submenu_file;
		}

		return 'wpcode';
	}

	/**
	 * Change the admin page title when editing a snippet.
	 *
	 * @param string $title The admin page title to be displayed.
	 * @param string $original_title The page title before adding the WP suffix.
	 *
	 * @return string
	 */
	public function change_page_title( $title, $original_title ) {
		if ( isset( $this->snippet ) ) {
			// If the snippet post is loaded (so we're editing) replace the original page title with our edit snippet one.
			// Translators: this changes the edit page title to show the snippet title.
			return str_replace( $original_title, sprintf( __( 'Edit snippet "%s"', 'insert-headers-and-footers' ), $this->snippet->get_title() ), $title );
		}

		return $title;
	}

	/**
	 * The main page content.
	 *
	 * @return void
	 */
	public function output_content() {
		if ( $this->show_library ) {
			$this->show_snippet_library();
		} else {
			$this->show_snippet_editor();
		}
	}

	/**
	 * Show the snippet editor markup.
	 *
	 * @return void
	 */
	public function show_snippet_editor() {
		$this->field_title();
		$this->field_code_editor();
		$this->field_insert_options();
		$this->field_device_type();
		$this->field_conditional_logic();
		$this->field_code_revisions();
		$this->field_basic_info();
		$this->hidden_fields();
		wp_nonce_field( $this->action, $this->nonce_name );
	}

	/**
	 * Show the snippet library markup.
	 *
	 * @return void
	 */
	public function show_snippet_library() {
		$library_data     = wpcode()->library->get_data();
		$categories       = $library_data['categories'];
		$snippets         = $library_data['snippets'];
		$default_category = isset( $categories[0]['slug'] ) ? $categories[0]['slug'] : '';

		// Add a new item to allow adding a custom snippet.
		array_unshift(
			$snippets,
			array(
				'library_id' => 0,
				'title'      => __( 'Add Your Custom Code (New Snippet)', 'insert-headers-and-footers' ),
				'note'       => __( 'Choose this blank snippet to start from scratch and paste any custom code or simply write your own.', 'insert-headers-and-footers' ),
				'categories' => array(
					$default_category,
				),
			)
		);

		?>
		<div class="wpcode-add-snippet-description">
			<?php
			$custom_url = add_query_arg(
				array(
					'page'   => 'wpcode-snippet-manager',
					'custom' => 1,
				),
				admin_url( 'admin.php' )
			);
			printf(
			// Translators: The placeholders add links to create a new custom snippet or the suggest-a-snippet form.
				esc_html__( 'To speed up the process you can select from one of our pre-made library, or you can start with a %1$sblank snippet%2$s and %1$screate your own%2$s. Have a suggestion for new snippet? %3$sWe’d love to hear it!%4$s', 'insert-headers-and-footers' ),
				'<a href="' . esc_url( $custom_url ) . '">',
				'</a>',
				'<a href="' . esc_url( wpcode_utm_url( 'https://wpcode.com/suggestions/?wpf78_8=Snippet Request', 'add-new', 'suggestions' ) ) . '" target="_blank">',
				'</a>'
			);
			?>
		</div>
		<?php
		$this->get_library_markup( $categories, $snippets );
		$this->library_preview_modal_content();
		$this->library_connect_banner_template();
	}

	/**
	 * Output the snippet title field.
	 *
	 * @return void
	 */
	public function field_title() {
		$value = isset( $this->snippet ) ? $this->snippet->get_title() : '';
		?>
		<div class="wpcode-input-title">
			<input type="text" class="widefat wpcode-input-text" value="<?php echo esc_attr( $value ); ?>" name="wpcode_snippet_title" placeholder="<?php esc_attr_e( 'Add title for snippet', 'insert-headers-and-footers' ); ?>"/>
		</div>
		<?php
	}

	/**
	 * The main code editor field.
	 *
	 * @return void
	 */
	public function field_code_editor() {
		$value = isset( $this->snippet ) ? $this->snippet->get_code() : '';
		?>
		<div class="wpcode-code-textarea" data-code-type="<?php echo esc_attr( $this->code_type ); ?>">
			<div class="wpcode-flex">
				<div class="wpcode-column">
					<h2>
						<label for="wpcode_snippet_code"><?php esc_html_e( 'Code Preview', 'insert-headers-and-footers' ); ?></label>
					</h2>
				</div>
				<div class="wpcode-column">
					<?php $this->field_code_type(); ?>
				</div>
			</div>
			<textarea name="wpcode_snippet_code" id="wpcode_snippet_code" class="widefat" rows="8" <?php disabled( ! current_user_can( 'unfiltered_html' ) ); ?>><?php echo esc_textarea( $value ); ?></textarea>
			<?php
			wp_editor(
				$value,
				'wpcode_snippet_text',
				array(
					'wpautop'        => false,
					'default_editor' => 'tinymce',
					'tinymce'        => array(
						'height' => 330,
					),
				)
			);
			?>
		</div>
		<?php
	}

	/**
	 * Snippet type field.
	 *
	 * @return void
	 */
	public function field_code_type() {
		$snippet_types = wpcode()->execute->get_options();
		?>
		<div class="wpcode-input-select">
			<label for="wpcode_snippet_type"><?php esc_html_e( 'Code Type', 'insert-headers-and-footers' ); ?></label>
			<select name="wpcode_snippet_type" id="wpcode_snippet_type">
				<?php foreach ( $snippet_types as $key => $label ) { ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->code_type, $key ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php } ?>
			</select>
		</div>
		<?php
	}

	/**
	 * The insert options - using a metabox-style layout to output the options.
	 *
	 * @return void
	 */
	public function field_insert_options() {
		$title               = __( 'Insertion', 'insert-headers-and-footers' );
		$insert_toggle       = $this->get_input_insert_toggle();
		$auto_insert_options = $this->get_input_auto_insert_options();
		$shortcode_field     = $this->get_input_shortcode();
		// Build the field markup here.
		ob_start();
		?>
		<p><?php esc_html_e( 'Choose "Auto Insert" if you want the snippet to be automatically executed in one of the locations available. In "Shortcode" mode, the snippet will only be executed where the shortcode is inserted.', 'insert-headers-and-footers' ); ?></p>
		<div class="wpcode-separator"></div>
		<div class="wpcode-metabox-form">
			<?php $this->metabox_row( __( 'Insert Method', 'insert-headers-and-footers' ), $insert_toggle ); ?>
			<div class="wpcode-auto-insert-form-fields" data-show-if-id="#wpcode_auto_insert" data-show-if-value="1">
				<?php
				$this->metabox_row( __( 'Location', 'insert-headers-and-footers' ), $auto_insert_options, 'wpcode_auto_insert_location' );
				$this->metabox_row(
					__( 'Insert Number', 'insert-headers-and-footers' ),
					$this->get_input_number(
						'wpcode_auto_insert_number',
						$this->get_auto_insert_number_value(),
						'',
						1
					) . $this->get_insert_number_descriptions(),
					'wpcode_auto_insert_number',
					'#wpcode_auto_insert_location',
					implode( ',', wpcode_get_auto_insert_locations_with_number() )
				);
				?>
			</div>
			<div class="wpcode-shortcode-form-fields" data-show-if-id="#wpcode_auto_insert" data-show-if-value="0">
				<?php
				$this->metabox_row( __( 'Shortcode', 'insert-headers-and-footers' ), $shortcode_field, 'wpcode_shortcode' );
				$this->get_input_row_custom_shortcode();
				?>
			</div>
			<?php $this->get_input_row_schedule(); ?>
		</div>
		<?php
		$content = ob_get_clean();

		$this->metabox(
			$title,
			$content,
			__( 'Your snippet can be either automatically executed or only used as a shortcode. When using the "Auto Insert" option you can choose the location where your snippet will be placed automatically.', 'insert-headers-and-footers' )
		);
	}

	/**
	 * Get all the descriptions for the insert number input with conditional rules.
	 *
	 * @return string
	 */
	public function get_insert_number_descriptions() {
		$descriptions = array(
			'before_paragraph'    => __( 'Number of paragraphs before which to insert the snippet.', 'insert-headers-and-footers' ),
			'after_paragraph'     => __( 'Number of paragraphs after which to insert the snippet.', 'insert-headers-and-footers' ),
			'archive_before_post' => __( 'Number of posts before which to insert the snippet.', 'insert-headers-and-footers' ),
			'archive_after_post'  => __( 'Number of posts after which to insert the snippet.', 'insert-headers-and-footers' ),
		);
		$markup       = '';
		foreach ( $descriptions as $value => $description ) {
			$markup .= sprintf( '<p data-show-if-id="#wpcode_auto_insert_location" data-show-if-value="%1$s" style="display:none;">%2$s</p>', $value, esc_html( $description ) );
		}

		return $markup;
	}

	/**
	 * Get the input insert toggle markup.
	 *
	 * @return string
	 */
	public function get_input_insert_toggle() {
		ob_start();
		?>
		<div class="wpcode-button-toggle">
			<button class="wpcode-button wpcode-button-large wpcode-button-secondary <?php echo esc_attr( $this->get_active_toggle_class( 1 ) ); ?>" type="button" value="1">
				<?php wpcode_icon( 'auto', 18, 23 ); ?>
				<span><?php esc_html_e( 'Auto&nbsp;Insert', 'insert-headers-and-footers' ); ?></span>
			</button>
			<button class="wpcode-button wpcode-button-large wpcode-button-secondary <?php echo esc_attr( $this->get_active_toggle_class( 0 ) ); ?>" type="button" value="0">
				<?php wpcode_icon( 'shortcode', 24, 17 ); ?>
				<span><?php esc_html_e( 'Shortcode', 'insert-headers-and-footers' ); ?></span>
			</button>
			<input type="hidden" name="wpcode_auto_insert" class="wpcode-button-toggle-input" id="wpcode_auto_insert" value="<?php echo absint( $this->get_auto_insert_value() ); ?>"/>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get the active toggle class based on the auto-insert value.
	 *
	 * @param string|int $value The value of the button.
	 *
	 * @return string
	 */
	private function get_active_toggle_class( $value ) {
		$current_value = $this->get_auto_insert_value();
		if ( absint( $value ) !== $current_value ) {
			return 'wpcode-button-secondary-inactive';
		}

		return '';
	}

	/**
	 * Get the auto-insert value consistently.
	 *
	 * @return int
	 */
	private function get_auto_insert_value() {
		return isset( $this->snippet ) ? $this->snippet->get_auto_insert() : 1;
	}

	/**
	 * Renders the dropdown with the auto-insert options.
	 * This uses the auto-insert class that loads all the available types.
	 * Each type has some specific options.
	 *
	 * @return string
	 * @see WPCode_Auto_Insert
	 */
	public function get_input_auto_insert_options() {
		$location       = '';
		$location_terms = wp_get_post_terms(
			$this->snippet_id,
			'wpcode_location',
			array(
				'fields' => 'slugs',
				'number' => 1, // A snippet can only have 1 type.
			)
		);
		if ( ! empty( $location_terms ) ) {
			$location = $location_terms[0];
		}

		return wpcode_get_auto_insert_location_picker( $location, $this->code_type );
	}

	/**
	 * Get the shortcode field.
	 *
	 * @return string
	 */
	public function get_input_shortcode() {
		$shortcode = __( 'Please save the snippet first', 'insert-headers-and-footers' );
		if ( isset( $this->snippet_id ) ) {
			$shortcode = sprintf( '[wpcode id="%d"]', $this->snippet_id );
		}
		$input  = sprintf(
			'<input type="text" value=\'%1$s\' id="wpcode-shortcode" class="wpcode-input-text" readonly />',
			$shortcode
		);
		$button = wpcode_get_copy_target_button( 'wpcode-shortcode' );

		return sprintf( '<div class="wpcode-input-with-button">%1$s %2$s</div>', $input, $button );
	}

	/**
	 * Generic input number function.
	 *
	 * @param string     $id The id of the input field.
	 * @param string|int $value The value of the input.
	 * @param string     $description The description to display under the field.
	 * @param int        $min The minimum value.
	 *
	 * @return string
	 */
	public function get_input_number( $id, $value = '', $description = '', $min = 0 ) {
		$input = '<input type="number" class="wpcode-input-number" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '" min="' . absint( $min ) . '" />';
		if ( ! empty( $description ) ) {
			$input .= '<p>' . $description . '</p>';
		}

		return $input;
	}

	/**
	 * Get a simple textarea field.
	 *
	 * @param string $id The id of the input field.
	 * @param string $value The value of the input.
	 * @param string $description The description to display under the field.
	 *
	 * @return string
	 */
	public function get_input_textarea( $id, $value = '', $description = '' ) {
		$input = '<textarea class="wpcode-input-textarea" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" rows="3">' . esc_html( $value ) . '</textarea>';
		if ( ! empty( $description ) ) {
			$input .= '<p>' . $description . '</p>';
		}

		return $input;
	}

	/**
	 * Get the auto-insert value consistently.
	 *
	 * @return int
	 */
	private function get_auto_insert_number_value() {
		return isset( $this->snippet ) ? $this->snippet->get_auto_insert_number() : 1;
	}

	/**
	 * Markup for the basic info metabox.
	 *
	 * @return void
	 */
	public function field_basic_info() {
		$priority = isset( $this->snippet ) ? $this->snippet->get_priority() : 10;
		$note     = isset( $this->snippet ) ? $this->snippet->get_note() : '';

		ob_start();
		$this->metabox_row( __( 'Tag', 'insert-headers-and-footers' ), $this->get_input_tag_picker() );
		$this->metabox_row( __( 'Priority', 'insert-headers-and-footers' ), $this->get_input_number( 'wpcode_priority', $priority ), 'wpcode_priority' );
		$this->metabox_row( __( 'Note', 'insert-headers-and-footers' ), $this->get_input_textarea( 'wpcode_note', $note ), 'wpcode_note' );

		if ( isset( $this->snippet ) && $this->snippet->is_generated() ) {
			$this->metabox_row( __( 'Generator', 'insert-headers-and-footers' ), $this->get_input_generator() );
		}

		$this->metabox(
			__( 'Basic info', 'insert-headers-and-footers' ),
			ob_get_clean(),
			__( 'Tags: Use tags to make it easier to group similar snippets together. <br />Priority: A lower priority will result in the snippet being executed before others with a higher priority. <br />Note: Add a private note related to this snippet.', 'insert-headers-and-footers' )
		);
	}

	/**
	 * The conditional logic field.
	 *
	 * @return void
	 */
	public function field_conditional_logic() {
		$enable_logic = isset( $this->snippet ) && $this->snippet->conditional_rules_enabled();

		$content = '<p>' . __( 'Using conditional logic you can limit the pages where you want the snippet to be auto-inserted.', 'insert-headers-and-footers' ) . '</p>';

		$content .= '<div class="wpcode-separator"></div>';
		ob_start();
		$this->metabox_row( __( 'Enable Logic', 'insert-headers-and-footers' ), $this->get_checkbox_toggle( $enable_logic, 'wpcode_conditional_logic_enable' ), 'wpcode_conditional_logic_enable' );
		$this->metabox_row( __( 'Conditions', 'insert-headers-and-footers' ), $this->get_conditional_logic_input(), 'wpcode_contional_logic_conditions', '#wpcode_conditional_logic_enable', '1' );

		$content .= ob_get_clean();

		$this->metabox(
			__( 'Smart Conditional Logic', 'insert-headers-and-footers' ),
			$content,
			__( 'Enable logic to add rules and limit where your snippets are inserted automatically. Use multiple groups for different sets of rules.', 'insert-headers-and-footers' )
		);
	}

	/**
	 * Get the tag picker markup.
	 *
	 * @return string
	 */
	public function get_input_tag_picker() {
		$tags        = isset( $this->snippet ) ? $this->snippet->get_tags() : array();
		$tags_string = isset( $this->snippet ) ? implode( ',', $this->snippet->get_tags() ) : '';
		$markup      = '<select multiple="multiple" class="wpcode-tags-picker" data-target="#wpcode-tags">';
		foreach ( $tags as $tag ) {
			$markup .= '<option value="' . esc_attr( $tag ) . '" selected="selected">' . esc_html( $tag ) . '</option>';
		}
		$markup .= '</select>';
		$markup .= '<input type="hidden" name="wpcode_tags" id="wpcode-tags" value="' . esc_attr( $tags_string ) . '" />';

		return $markup;
	}

	/**
	 * Get the link to the generator page for the current snippet.
	 *
	 * @return string
	 */
	public function get_input_generator() {
		$generator = $this->snippet->get_generator();

		return sprintf(
			'<a href="%1$s" class="wpcode-button wpcode-button-secondary">%2$s</a>',
			add_query_arg(
				array(
					'generator' => $generator,
					'page'      => 'wpcode-generator',
					'snippet'   => $this->snippet->get_id(),
				),
				admin_url( 'admin.php' )
			),
			esc_html__( 'Update Generated Snippet', 'insert-headers-and-footers' )
		);
	}

	/**
	 * The hidden fields needed to identify the form submission.
	 *
	 * @return void
	 */
	public function hidden_fields() {
		if ( ! isset( $this->snippet_id ) ) {
			return;
		}
		?>
		<input type="hidden" name="id" value="<?php echo esc_attr( $this->snippet_id ); ?>"/>
		<?php
	}

	/**
	 * Output of the page wrapped in a form.
	 *
	 * @return void
	 */
	public function output() {
		if ( $this->show_library ) {
			// Don't wrap with form when showing library.
			parent::output();

			return;
		}
		?>
		<form action="<?php echo esc_url( $this->get_page_action_url() ); ?>" method="post" id="wpcode-snippet-manager-form">
			<?php parent::output(); ?>
		</form>
		<?php
	}

	/**
	 * The bottom of the header part.
	 *
	 * @return void
	 */
	public function output_header_bottom() {
		?>
		<div class="wpcode-column">
			<h1><?php echo esc_html( $this->header_title ); ?></h1>
		</div>
		<?php
		// If we're displaying the libray screen, return early and hide right-side buttons.
		if ( $this->show_library ) {
			return;
		}
		?>
		<div class="wpcode-column">
			<?php $this->header_buttons(); ?>
		</div>
		<?php
	}

	/**
	 * Add header buttons on the right side of the header.
	 *
	 * @return void
	 */
	public function header_buttons() {
		$active = isset( $this->snippet ) && $this->snippet->is_active();
		$this->save_to_library_button();
		?>
		<label class="wpcode-status-text" for="wpcode_active">
			<span class="screen-reader-text">
				<?php esc_html_e( 'Snippet Status:', 'insert-headers-and-footers' ); ?>
			</span>
			<span data-show-if-id="#wpcode_active" data-show-if-value="1" style="display: none">
				<?php esc_html_e( 'Active', 'insert-headers-and-footers' ); ?>
			</span>
			<span data-show-if-id="#wpcode_active" data-show-if-value="0" style="display:none;">
				<?php esc_html_e( 'Inactive', 'insert-headers-and-footers' ); ?>
			</span>
		</label>
		<?php echo $this->get_checkbox_toggle( $active, 'wpcode_active' ); ?>
		<button class="wpcode-button" type="submit" value="publish" name="button"><?php echo esc_html( $this->publish_button_text ); ?></button>
		<?php
	}

	/**
	 * Markup for the save to library button.
	 *
	 * @return void
	 */
	public function save_to_library_button() {
		?>
		<button
				class="wpcode-button wpcode-button-text wpcode-button-save-to-library"
				id="wpcode_save_to_library"
				type="button">
			<?php
			wpcode_icon( 'cloud', 16, 12 );
			esc_html_e( 'Save to Library', 'wpcode-premium' );
			?>
		</button>
		<?php
	}

	/**
	 * Handle a form submit here.
	 *
	 * @return void
	 */
	public function submit_listener() {
		if ( ! isset( $_REQUEST[ $this->nonce_name ] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST[ $this->nonce_name ] ), $this->action ) ) {
			// Nonce is missing, so we're not even going to try.
			return;
		}
		if ( ! $this->can_edit ) {
			return;
		}

		$code_type    = isset( $_POST['wpcode_snippet_type'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcode_snippet_type'] ) ) : 'html';
		$snippet_code = isset( $_POST['wpcode_snippet_code'] ) ? $_POST['wpcode_snippet_code'] : '';
		if ( 'text' === $code_type ) {
			$snippet_code = wpautop( $snippet_code );
		}

		$tags = array();

		if ( isset( $_POST['wpcode_tags'] ) ) {
			$tags = trim( sanitize_text_field( wp_unslash( $_POST['wpcode_tags'] ) ) );
			if ( ! empty( $tags ) ) {
				$tags = explode( ',', $tags );
			}
		}

		if ( 'php' === $code_type ) {
			$snippet_code = preg_replace( '|^\s*<\?(php)?|', '', $snippet_code );
		}

		if ( 'js' === $code_type && apply_filters( 'wpcode_strip_script_tags_for_js', true ) ) {
			$snippet_code = preg_replace( '|^\s*<script[^>]*>|', '', $snippet_code );
			$snippet_code = preg_replace( '|</(script)>\s*$|', '', $snippet_code );
		}

		$rules = isset( $_POST['wpcode_cl_rules'] ) ? json_decode( sanitize_textarea_field( wp_unslash( $_POST['wpcode_cl_rules'] ) ), true ) : array();

		$snippet = new WPCode_Snippet(
			array(
				'id'            => empty( $_REQUEST['id'] ) ? 0 : absint( $_REQUEST['id'] ),
				'title'         => isset( $_POST['wpcode_snippet_title'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcode_snippet_title'] ) ) : '',
				'code'          => $snippet_code,
				'active'        => isset( $_REQUEST['wpcode_active'] ),
				'code_type'     => $code_type,
				'location'      => isset( $_POST['wpcode_auto_insert_location'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcode_auto_insert_location'] ) ) : '',
				'insert_number' => isset( $_POST['wpcode_auto_insert_number'] ) ? absint( $_POST['wpcode_auto_insert_number'] ) : 0,
				'auto_insert'   => isset( $_POST['wpcode_auto_insert'] ) ? absint( $_POST['wpcode_auto_insert'] ) : 0,
				'tags'          => $tags,
				'use_rules'     => isset( $_POST['wpcode_conditional_logic_enable'] ),
				'rules'         => $rules,
				'priority'      => isset( $_POST['wpcode_priority'] ) ? intval( $_POST['wpcode_priority'] ) : 10,
				'note'          => isset( $_POST['wpcode_note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wpcode_note'] ) ) : '',
			)
		);

		if ( empty( $snippet->title ) ) {
			$snippet->title = $snippet->get_untitled_title();
		}

		$message_number = 1;
		$active_wanted  = $snippet->active;

		if ( 0 === $snippet->id ) {
			// If it's a new snippet display a different message.
			$message_number = 2;
		}

		$this->add_extra_snippet_data( $snippet );

		$id = $snippet->save();

		if ( $active_wanted !== $snippet->is_active() ) {
			// If the snippet failed to change status display an error message.
			$message_number = 3;
			// If the current user is not allowed to change snippet status, display a different message.
			if ( ! current_user_can( 'wpcode_activate_snippets' ) ) {
				$message_number = 4;
			}
		}

		if ( $id ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'snippet_id' => $id,
						'message'    => $message_number,
						'error'      => wpcode()->error->get_last_error_message(),
					),
					$this->get_page_action_url()
				)
			);
			exit;
		}
	}

	/**
	 * Load page-specific scripts.
	 *
	 * @return void
	 */
	public function page_scripts() {
		if ( $this->show_library ) {
			return;
		}

		$editor = new WPCode_Code_Editor( $this->code_type );
		$editor->load_hint_scripts();
		$editor->set_setting( 'autoCloseTags', true );
		$editor->set_setting( 'matchTags', array( 'bothTags' => true ) );

		$editor->register_editor( 'wpcode_snippet_code' );
		$editor->init_editor();
	}

	/**
	 * Get the snippet type based on the context.
	 *
	 * @return void
	 */
	public function set_code_type() {
		if ( isset( $this->snippet ) ) {
			$this->code_type = $this->snippet->get_code_type();
		} else {
			$this->code_type = apply_filters( 'wpcode_default_code_type', $this->code_type );
		}
	}

	/**
	 * Get the conditional logic options input markup.
	 *
	 * @return string
	 */
	public function get_conditional_logic_input() {

		$conditional_rules = isset( $this->snippet ) ? wp_json_encode( $this->snippet->get_conditional_rules() ) : '';

		$markup = $this->get_conditional_select_show_hide();

		$markup .= sprintf( '<div id="wpcode-conditions-holder">%s</div>', $this->build_conditional_rules_form() );
		$markup .= sprintf( '<button type="button" class="wpcode-button" id="wpcode-cl-add-group">%s</button>', __( '+ Add new group', 'insert-headers-and-footers' ) );
		$markup .= sprintf( '<script type="text/template" id="wpcode-conditions-group-markup">%s</script>', $this->get_conditions_group_markup() );
		$markup .= sprintf( '<script type="text/template" id="wpcode-conditions-group-row-markup">%s</script>', $this->get_conditions_group_row_markup() );
		$markup .= sprintf( '<input type="hidden" name="wpcode_cl_rules" id="wpcode-cl-rules" value="%s" />', esc_attr( $conditional_rules ) );

		return $markup;
	}

	/**
	 * Markup for the show/hide select input.
	 *
	 * @return string
	 */
	public function get_conditional_select_show_hide() {
		$rules    = isset( $this->snippet ) ? $this->snippet->get_conditional_rules() : array();
		$selected = empty( $rules ) ? 'show' : $rules['show'];
		$options  = array(
			'show' => __( 'Show', 'insert-headers-and-footers' ),
			'hide' => __( 'Hide', 'insert-headers-and-footers' ),
		);

		$markup = '<div class="wpcode-inline-select">';

		$markup .= '<select id="wpcode-cl-show-hide">';
		foreach ( $options as $value => $label ) {
			$markup .= sprintf(
				'<option value="%1$s" %2$s>%3$s</option>',
				esc_attr( $value ),
				selected( $value, $selected, false ),
				esc_html( $label )
			);
		}
		$markup .= '</select>';
		$markup .= '<label for="wpcode-cl-show-hide">';
		$markup .= sprintf( '<span>%s</span>', __( 'This code snippet if', 'insert-headers-and-footers' ) );
		$markup .= '</label>';
		$markup .= '</div>';

		return $markup;
	}

	/**
	 * Build back the form markup from the stored conditions.
	 *
	 * @return string|void
	 */
	public function build_conditional_rules_form() {
		if ( ! isset( $this->snippet ) ) {
			return;
		}
		$options = wpcode()->conditional_logic->get_all_admin_options();
		$rules   = $this->snippet->get_conditional_rules();
		if ( empty( $rules ) || empty( $rules['groups'] ) ) {
			return;
		}
		$form_groups = array();
		foreach ( $rules['groups'] as $group_rows ) {
			$rows = array();
			foreach ( $group_rows as $row ) {
				$type_options = $options[ $row['type'] ];
				$value_option = $type_options['options'][ $row['option'] ];

				$rows[] = $this->get_conditions_group_row_markup( $row['option'], $row['relation'], $this->get_input_markup_by_type( $value_option, $row['value'] ) );
			}

			$form_groups[] = $this->get_conditions_group_markup( implode( '', $rows ) );
		}

		return implode( $form_groups );

	}

	/**
	 * Process the type options and return the input markup.
	 *
	 * @param array  $data The data with the settings/options.
	 * @param string $value The value currently selected.
	 *
	 * @return string
	 */
	private function get_input_markup_by_type( $data, $value ) {
		$markup = '';
		switch ( $data['type'] ) {
			case 'select':
				$multiple = isset( $data['multiple'] ) && $data['multiple'] ? 'multiple' : '';
				$class    = isset( $data['multiple'] ) && $data['multiple'] ? 'wpcode-select2' : '';
				$markup   = '<select class=' . esc_attr( $class ) . '  ' . $multiple . '>';
				foreach ( $data['options'] as $option ) {
					$selected = ! is_array( $value ) ? array( $value ) : $value;
					$markup   .= '<option value="' . esc_attr( $option['value'] ) . '" ' . selected( in_array( $option['value'], $selected, true ), true, false ) . ' ' . disabled( isset( $option['disabled'] ) && $option['disabled'], true, false ) . '>' . esc_html( $option['label'] ) . '</option>';
				}
				$markup .= '</select>';
				break;
			case 'text':
				$markup = sprintf( '<input type="text" class="wpcode-input-text" value="%s" />', esc_attr( $value ) );
				break;
			case 'time':
				$markup = sprintf( '<input type="text" class="wpcode-input-text wpcode-input-time" value="%s" />', esc_attr( $value ) );
				break;
			case 'date':
				$value  = ! empty( $value ) ? date_i18n( 'Y-m-d', strtotime( $value ) ) : '';
				$markup = sprintf( '<input type="text" class="wpcode-input-text wpcode-input-date" value="%s" />', esc_attr( $value ) );
				break;
			case 'datetime':
				$value  = ! empty( $value ) ? date_i18n( 'Y-m-d H:i', strtotime( $value ) ) : '';
				$markup = sprintf( '<input type="text" class="wpcode-input-text wpcode-input-datetime" value="%s" />', esc_attr( $value ) );
				break;
			case 'ajax':
				$options = isset( $data['labels_callback'] ) ? $data['labels_callback']( $value ) : array();
				$markup  = '<select class="wpcode-select2-ajax" data-action="' . esc_attr( $data['options'] ) . '" multiple>';
				foreach ( $options as $option ) {
					$markup .= '<option value="' . esc_attr( $option['value'] ) . '" ' . selected( true, true, false ) . '>' . esc_html( $option['label'] ) . '</option>';
				}
				$markup .= '</select>';
				break;
		}

		return $markup;
	}

	/**
	 * Build the markup for an empty conditional logic group.
	 *
	 * @param string $rows Optional, already-built rows markup.
	 *
	 * @return string
	 */
	private function get_conditions_group_markup( $rows = '' ) {
		$markup = '<div class="wpcode-cl-group">';

		$markup .= $this->get_conditions_group_or_markup();
		$markup .= '<div class="wpcode-cl-group-rules">' . $rows . '</div>';
		$markup .= sprintf(
			'<button class="wpcode-button wpcode-cl-add-row" type="button" title="%2$s">%1$s</button>',
			_x( 'AND', 'Conditional logic add another "and" rules row.', 'insert-headers-and-footers' ),
			esc_attr__( 'Add another "AND" rules row.', 'insert-headers-and-footers' )
		);
		$markup .= '</div>';

		return $markup;
	}

	/**
	 * Build the markup for a conditional logic row. All parameters are optional and if
	 * left empty it will return the template to be used in JS.
	 *
	 * @param string $type The value for the type input.
	 * @param string $relation The value for the relation field.
	 * @param string $value The value selected for this row.
	 *
	 * @return string
	 */
	private function get_conditions_group_row_markup( $type = '', $relation = '', $value = '' ) {
		$options = wpcode()->conditional_logic->get_all_admin_options();

		$markup = '<div class="wpcode-cl-rules-row">';

		$markup .= '<div class="wpcode-cl-rules-row-options">';
		$markup .= '<select class="wpcode-cl-rule-type">';
		foreach ( $options as $opt_group ) {
			$markup .= '<optgroup label="' . esc_attr( $opt_group['label'] ) . '" data-type="' . esc_attr( $opt_group['name'] ) . '">';
			foreach ( $opt_group['options'] as $key => $option ) {
				$data_attrs = '';
				if ( isset( $option['upgrade'] ) && is_array( $option['upgrade'] ) ) {
					if ( isset( $option['upgrade']['title'] ) ) {
						$data_attrs = 'data-upgrade-title= "' . esc_attr( $option['upgrade']['title'] ) . '"';
					}
					if ( isset( $option['upgrade']['text'] ) ) {
						$data_attrs .= ' data-upgrade-text="' . esc_attr( $option['upgrade']['text'] ) . '"';
					}
					if ( isset( $option['upgrade']['link'] ) ) {
						$data_attrs .= ' data-upgrade-link="' . esc_attr( $option['upgrade']['link'] ) . '"';
					}
					if ( isset( $option['upgrade']['button'] ) ) {
						$data_attrs .= ' data-upgrade-button="' . esc_attr( $option['upgrade']['button'] ) . '"';
					}
				}
				$markup .= '<option value="' . esc_attr( $key ) . '" ' . selected( $type, $key, false ) . ' ' . disabled( isset( $option['disabled'] ) && $option['disabled'], true, false ) . $data_attrs . '>' . esc_html( $option['label'] ) . '</option>';
			}
			$markup .= '</optgroup>';
		}
		$markup .= '</select>';
		$markup .= $this->get_conditions_relation_select( $relation );
		$markup .= '<div class="wpcode-cl-rule-value">' . $value . '</div>';// This should be automatically populated based on the selected type.
		$markup .= '</div>'; // rules-row-options.
		$markup .= '<button class="wpcode-button-just-icon wpcode-cl-remove-row" type="button" title="' . esc_attr__( 'Remove Row', 'insert-headers-and-footers' ) . '">' . get_wpcode_icon( 'remove' ) . '</button>'; // rules-row-options.
		$markup .= '</div>'; // rules-row.

		return $markup;
	}

	/**
	 * Get the markup for the relation field.
	 *
	 * @param string $relation Optional selected relation.
	 *
	 * @return string
	 */
	private function get_conditions_relation_select( $relation = '' ) {
		$options = wpcode_get_conditions_relation_labels();
		$markup  = '<select class="wpcode-cl-rule-relation">';
		foreach ( $options as $value => $label ) {
			$markup .= '<option value="' . esc_attr( $value ) . '" ' . selected( $relation, $value, false ) . '>' . esc_html( $label ) . '</option>';
		}
		$markup .= '</select>';

		return $markup;
	}

	/**
	 * The markup for the "or" displayed between groups.
	 *
	 * @return string
	 */
	private function get_conditions_group_or_markup() {
		$markup = '<div class="wpcode-cl-group-or">';

		$markup .= '<div class="wpcode-cl-group-or-line"></div>';
		$markup .= '<div class="wpcode-cl-group-or-text">' . _x( 'OR', 'Conditional logic "or" another rule', 'insert-headers-and-footers' ) . '</div>';
		$markup .= '</div>';

		return $markup;
	}

	/**
	 * Add conditions to the admin script when on this page.
	 *
	 * @param array $data The localized data used in wp_localize_script.
	 *
	 * @return array
	 * @see wpcode_admin_scripts
	 */
	public function add_conditional_rules_to_script( $data ) {
		if ( ! isset( $data['conditions'] ) ) {
			$data['conditions'] = wpcode()->conditional_logic->get_all_admin_options();
		}

		$data['save_to_library_url']    = wpcode_utm_url( 'https://wpcode.com/lite/', 'snippet-editor', 'save-to-library', 'upgrade-to-pro' );
		$data['save_to_library_title']  = __( 'Save to Library is a Pro Feature', 'insert-headers-and-footers' );
		$data['save_to_library_text']   = __( 'Upgrade to PRO today and save your private snippets to the WPCode library for easy access. You can also share your snippets with other users or load them on other sites.', 'insert-headers-and-footers' );
		$data['save_to_library_button'] = __( 'Upgrade to PRO', 'insert-headers-and-footers' );
		$data['shortcode_title']        = __( 'Custom Shortcode is a Pro Feature', 'insert-headers-and-footers' );
		$data['shortcode_text']         = __( 'Upgrade today to use a custom shortcode and nerver worry about changing snippet ids again, even when importing your snippets to another site. You\'ll also get access to a private library that makes setting up new sites a lot easier.', 'insert-headers-and-footers' );
		$data['shortcode_url']          = wpcode_utm_url( 'https://wpcode.com/lite/', 'snippet-editor', 'custom-shortcode', 'modal' );
		$data['device_title']           = __( 'Device Type is a Pro Feature', 'insert-headers-and-footers' );
		$data['device_text']            = __( 'Upgrade to PRO today and unlock one-click device targeting for your snippets.', 'insert-headers-and-footers' );
		$data['device_url']             = wpcode_utm_url( 'https://wpcode.com/lite/', 'snippet-editor', 'device-type', 'modal' );
		$data['datetime_title']         = __( 'Scheduling snippets is a Pro Feature', 'insert-headers-and-footers' );
		$data['datetime_text']          = __( 'Upgrade to PRO today and unlock powerful scheduling options to limit when your snippet is active on the site.', 'insert-headers-and-footers' );
		$data['datetime_url']           = wpcode_utm_url( 'https://wpcode.com/lite/', 'snippet-editor', 'schedule', 'modal' );
		$data['php_cl_location_notice'] = sprintf(
		// Translators: %1$s Opening anchor tag. %2$s Closing anchor tag.
			__( 'For better results using conditional logic with PHP snippets we automatically switched the auto-insert location to "Frontend Conditional Logic" that runs later. If you want to run the snippet earlier please switch back to "Run Everywhere" but note not all conditional logic options will be available. %1$sRead more%2$s', 'insert-headers-and-footers' ),
			'<a href="' . wpcode_utm_url( 'https://wpcode.com/docs/conditional-logic-php-snippets/', 'snippet-editor', 'php-conditional-logic' ) . '" target="_blank">',
			'</a>'
		);

		return $data;
	}

	/**
	 * If we're showing a "text" code type let's display TinyMCE by default.
	 *
	 * @param string $body_class The body class.
	 *
	 * @return string
	 */
	public function maybe_show_tinymce( $body_class ) {
		if ( 'text' === $this->code_type ) {
			$body_class .= ' wpcode-show-tinymce';
		}

		return $body_class;
	}

	/**
	 * If the editor should grow with the code, add a body class.
	 *
	 * @param string $body_class The body class.
	 *
	 * @return string
	 */
	public function maybe_editor_height_auto( $body_class ) {
		$height_auto = wpcode()->settings->get_option( 'editor_height_auto' );
		if ( false !== $height_auto ) {
			$body_class .= ' wpcode-editor-auto ';
		}

		return $body_class;
	}

	/**
	 * If we have a custom height set, output the styles to change that.
	 * Also, check if the auto-height is set.
	 *
	 * @return void
	 */
	public function maybe_editor_height() {
		// Let's check if the auto-height is not enabled.
		$height_auto = wpcode()->settings->get_option( 'editor_height_auto' );
		if ( false !== $height_auto ) {
			return;
		}

		$height = wpcode()->settings->get_option( 'editor_height' );
		if ( ! $height ) {
			return;
		}

		echo '<style>.CodeMirror {height: ' . absint( $height ) . 'px;}</style>';
	}

	/**
	 * Get the markup of the custom shortcode row.
	 *
	 * @return void
	 */
	public function get_input_row_custom_shortcode() {
		$this->metabox_row(
			__( 'Custom Shortcode', 'insert-headers-and-footers' ),
			sprintf(
				'<input type="text" placeholder="%s" class="wpcode-input-text" id="wpcode-custom-shortcode-lite" readonly />',
				__( 'Shortcode name', 'insert-headers-and-footers' )
			),
			'',
			'',
			'',
			__( 'Use this field to define a custom shortcode name instead of the id-based one.', 'insert-headers-and-footers' ),
			true
		);
	}

	/**
	 * Get the markup of the schedule main dates inputs.
	 *
	 * @return void
	 */
	public function get_input_row_schedule() {
		?>
		<div class="wpcode-schedule-form-fields">
			<?php
			$schedule_label = __( 'Schedule snippet', 'insert-headers-and-footers' );
			$this->metabox_row(
				$schedule_label,
				$this->get_input_row_schedule_contents( '', '', true ),
				'wpcode_schedule',
				'',
				'',
				'',
				true
			);
			?>
		</div>
		<?php
	}

	/**
	 * Get the markup of the schedule inputs.
	 *
	 * @param string $start Start date.
	 * @param string $end End date.
	 *
	 * @return string
	 */
	public function get_input_row_schedule_contents( $start = '', $end = '', $read_only = false ) {
		$markup = '<div class="wpcode-input-row wpcode-input-row-schedule">';

		$markup .= $this->get_input_datetime(
			'wpcode-schedule-start',
			$start,
			esc_html__( 'Start Date', 'insert-headers-and-footers' ),
			esc_html__( 'Start Date', 'insert-headers-and-footers' ),
			esc_html__( 'Clear start date', 'insert-headers-and-footers' ),
			$read_only
		);

		$markup .= $this->get_input_datetime(
			'wpcode-schedule-end',
			$end,
			esc_html__( 'End Date', 'insert-headers-and-footers' ),
			esc_html__( 'End Date', 'insert-headers-and-footers' ),
			esc_html__( 'Clear end date', 'insert-headers-and-footers' ),
			$read_only
		);

		$markup .= '</div>';

		$markup .= $this->help_icon(
			sprintf(
			// Translators: %1$s and %2$s are HTML tags for a link to the documentation article.
				__( 'Looking for more scheduling options? %1$sClick here%2$s to read more about all the available options.', 'insert-headers-and-footers' ),
				'<a href="' . wpcode_utm_url( 'https://wpcode.com/docs/how-to-create-advanced-scheduling-rules/', 'snippet-editor', 'schedule-more' ) . '" target="_blank">',
				'</a>'
			),
			false
		);

		return $markup;
	}

	/**
	 * Get the markup of the schedule main dates inputs.
	 *
	 * @param string $id The id of the input.
	 * @param string $value The value of the input.
	 * @param string $label The label of the input.
	 * @param string $placeholder The placeholder of the input.
	 * @param string $clear_text The text of the clear button.
	 *
	 * @return string
	 */
	public function get_input_datetime( $id, $value = '', $label = '', $placeholder = '', $clear_text = '', $readonly = false ) {

		$markup = '';
		if ( $label ) {
			$markup .= sprintf(
				'<div class="wpcode-input-row-label screen-reader-text">
					<label for="%1$s">%2$s</label>
				</div>',
				esc_attr( $id ),
				esc_html( $label )
			);
		}
		$markup .= '<div class="wpcode-input-row-input">';
		$markup .= sprintf(
			'<input type="text" class="wpcode-input-text wpcode-input-datetime" id="%1$s" name="%1$s" value="%2$s" placeholder="%3$s" %4$s />',
			esc_attr( $id ),
			esc_attr( $value ),
			esc_attr( $placeholder ),
			$readonly ? 'readonly' : ''
		);
		if ( ! empty( $clear_text ) ) {
			$markup .= sprintf(
				'<button type="button" class="wpcode-input-button wpcode-input-button-clear" title="%1$s" style="%3$s">%2$s</button>',
				esc_attr( $clear_text ),
				get_wpcode_icon( 'close', 16, 16 ),
				! empty( $value ) ? '' : 'display:none;'
			);
		}
		$markup .= '</div>';

		return $markup;
	}

	/**
	 * Add extra snippet data.
	 *
	 * @param WPCode_Snippet $snippet Snippet about to be saved, passed by reference.
	 *
	 * @return void
	 */
	public function add_extra_snippet_data( &$snippet ) {
	}

	/**
	 * Markup for the code revisions metabox.
	 *
	 * @return void
	 */
	public function field_code_revisions() {
		$html = sprintf(
			'<p>%s</p><hr />',
			esc_html__( 'As you make changes to your snippet and save, you will get a list of previous versions with all the changes made in each revision. You can compare revisions to the current version or see changes as they have been saved by going through each revision. Any of the revisions can then be restored as needed.', 'wpcode-premium' )
		);

		$html .= $this->code_revisions_list();
		$this->metabox(
			__( 'Code Revisions', 'insert-headers-and-footers' ),
			$html,
			__( 'Easily switch back to a previous version of your snippet.', 'insert-headers-and-footers' )
		);
	}

	/**
	 * Markup for the device type metabox.
	 *
	 * @return void
	 */
	public function field_device_type() {
		$html = sprintf(
			'<p>%s</p>',
			esc_html__( 'Limit where you want this snippet to be loaded by device type. By default, snippets are loaded on all devices.', 'wpcode-premium' )
		);

		$html .= '<div class="wpcode-separator"></div>';
		$html .= $this->device_type_picker();
		$this->metabox(
			__( 'Device Type', 'insert-headers-and-footers' ),
			$html
		);
	}

	/**
	 * This method returns the markup for the device type radio input picker, the
	 * three options available are Any device type, Desktop only and Mobile only.
	 * By default, any device type is selected.
	 *
	 * @return string
	 */
	public function device_type_picker() {
		$html = '<div class="wpcode-device-type-picker wpcode-device-type-picker-lite">';
		$html .= $this->get_radio_field_icon( 'devices', __( 'Any device type', 'insert-headers-and-footers' ), 'any', '', true );
		$html .= $this->get_radio_field_icon( 'desktop', __( 'Desktop only', 'insert-headers-and-footers' ), 'desktop', '', false, '', true );
		$html .= $this->get_radio_field_icon( 'mobile', __( 'Mobile only', 'insert-headers-and-footers' ), 'mobile', '', false, '', true );
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get a styled radio input with an icon.
	 *
	 * @param string $icon Icon to use for label, @see get_wpcode_icon
	 * @param string $label The text of the label to display.
	 * @param string $value The value of the radio input.
	 * @param string $name The input name (for PHP).
	 * @param bool   $checked Whether the input is checked or not.
	 * @param string $id Unique id for the input, by default name + value will be used.
	 * @param bool   $disabled Whether the input should be disabled.
	 *
	 * @return string
	 */
	public function get_radio_field_icon( $icon = '', $label = '', $value = '', $name = '', $checked = false, $id = '', $disabled = false ) {

		$id   = empty( $id ) ? $name . '-' . $value : $id;
		$html = '<div class="wpcode-input-radio">';
		$html .= sprintf(
			'<input type="radio" name="%1$s" id="%2$s" value="%3$s" %4$s %5$s />',
			esc_attr( $name ),
			esc_attr( $id ),
			esc_attr( $value ),
			checked( $checked, true, false ),
			disabled( $disabled, true, false )
		);
		$html .= sprintf(
			'<label for="%1$s"><span class="wpcode-input-radio-icon">%2$s</span><span class="wpcode-input-radio-label">%3$s</span></label>',
			esc_attr( $id ),
			get_wpcode_icon( $icon, 48, 48 ),
			wp_kses_post( $label )
		);
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get a list of code revisions to use behind the notice.
	 *
	 * @return string
	 */
	public function get_code_revisions_empty_list() {
		$list           = array();
		$post_modified  = isset( $this->snippet ) ? strtotime( $this->snippet->get_post_data()->post_modified ) : time();
		$snippet_author = isset( $this->snippet ) ? $this->snippet->get_snippet_author() : get_current_user_id();
		$revisions_data = array(
			$post_modified,
			$post_modified - DAY_IN_SECONDS,
			$post_modified - WEEK_IN_SECONDS,
			$post_modified - 2 * WEEK_IN_SECONDS,
			$post_modified - MONTH_IN_SECONDS,
			$post_modified - 2 * MONTH_IN_SECONDS,
		);

		$compare = sprintf(
			'<span>%s</span>',
			esc_html__( 'Compare', 'wpcode-premium' )
		);
		$view    = sprintf(
			'<span>%s</a>',
			get_wpcode_icon( 'eye', 16, 11, '0 0 16 11' )
		);

		foreach ( $revisions_data as $revisions_date ) {
			$updated = sprintf(
			// Translators: time since the revision has been updated.
				esc_html__( 'Updated %s ago', 'wpcode-premium' ),
				human_time_diff( $revisions_date )
			);

			$list[] = $this->get_revision_item(
				$snippet_author,
				$updated,
				array(
					$compare,
					$view,
				)
			);
		}

		$html = '<div class="wpcode-blur-area">';

		$html .= sprintf(
			'<ul class="wpcode-revisions-list">%s</ul>',
			implode( '', $list )
		);

		$button_text = sprintf(
		// Translators: The placeholder gets replaced with the extra number of revisions available.
			esc_html__( '%d Other Revisions', 'wpcode-premium' ),
			3
		);

		$html .= sprintf(
			'<button type="button" class="wpcode-button wpcode-button-secondary wpcode-button-icon" id="wpcode-show-all-snippets">%1$s %2$s</button>',
			get_wpcode_icon( 'rewind', 16, 14 ),
			$button_text
		);

		$html .= '</div>';// .wpcode-blur-area.

		return $html;
	}

	/**
	 * List of code revision items.
	 *
	 * @return string
	 */
	public function code_revisions_list() {
		return $this->code_revisions_list_with_notice(
			esc_html__( 'Code Revisions is a Pro Feature', 'insert-headers-and-footers' ),
			sprintf(
				'<p>%s</p>',
				esc_html__( 'Upgrade to WPCode Pro today and start tracking revisions and see exactly who, when and which changes were made to your snippet.', 'insert-headers-and-footers' )
			),
			array(
				'text' => esc_html__( 'Upgrade to Pro and Unlock Revisions', 'insert-headers-and-footers' ),
				'url'  => wpcode_utm_url( 'https://wpcode.com/lite/', 'snippet-editor', 'revisions', 'upgrade-to-pro' ),
			),
			array(
				'text' => esc_html__( 'Learn more about all the features', 'insert-headers-and-footers' ),
				'url'  => wpcode_utm_url( 'https://wpcode.com/lite/', 'snippet-editor', 'revisions', 'features' ),
			)
		);
	}

	/**
	 * Get a code revisions list with a notice on top.
	 *
	 * @param string $title The title for the notice.
	 * @param string $description Description or text below the title.
	 * @param array  $button_1 Button 1 params for the get_upsell_box method.
	 * @param array  $button_2 Button 2 params for the get_upsell_box method.
	 *
	 * @return string
	 */
	public function code_revisions_list_with_notice( $title, $description = '', $button_1 = array(), $button_2 = array() ) {
		$html = '<div class="wpcode-revisions-list-area">';

		$html .= $this->get_code_revisions_empty_list();
		$html .= WPCode_Admin_Page::get_upsell_box(
			$title,
			$description,
			$button_1,
			$button_2
		);
		$html .= '</div>';// .wpcode-revisions-list-area.

		return $html;
	}

	/**
	 * Get the markup for a revision item in the list of revisions.
	 *
	 * @param int    $author_id The author id to display the avatar and name for.
	 * @param string $date The date used to display time passed.
	 * @param array  $actions Links specific to this row.
	 *
	 * @return string
	 */
	public function get_revision_item( $author_id, $date, $actions = array() ) {
		$list_item = '<li class="wpcode-revision-list-item">';

		$list_item .= get_avatar( $author_id, 30 );
		$list_item .= sprintf(
			'<span class="wpcode-revision-list-author">%s</span>',
			get_the_author_meta( 'display_name', $author_id )
		);
		$list_item .= sprintf(
			'<span class="wpcode-revision-list-date">%s</span>',
			$date
		);
		if ( ! empty( $actions ) ) {
			$list_item .= sprintf(
				'<span class="wpcode-revision-list-item-actions">%s</span>',
				implode( '', $actions )
			);
		}
		$list_item .= '</li>';

		return $list_item;
	}
}
