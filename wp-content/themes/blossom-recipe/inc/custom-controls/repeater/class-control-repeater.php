<?php
/**
 * Blossom Recipe Customizer Repeater Control.
 * 
 * @package Blossom_Recipe
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'Blossom_Recipe_Control_Repeater' ) ) {
    /**
     * Repeater control
     */
    class Blossom_Recipe_Control_Repeater extends WP_Customize_Control {
    
    	/**
    	 * The control type.
    	 *
    	 * @access public
    	 * @var string
    	 */
    	public $type = 'blossom-recipe-repeater';
    
    	/**
    	 * Data type
    	 *
    	 * @access public
    	 * @var string
    	 */
    	public $option_type = 'theme_mod';
    
    	/**
    	 * The fields that each container row will contain.
    	 *
    	 * @access public
    	 * @var array
    	 */
    	public $fields = array();
    
    	/**
    	 * Will store a filtered version of value for advenced fields (like images).
    	 *
    	 * @access protected
    	 * @var array
    	 */
    	protected $filtered_value = array();
    
    	/**
    	 * The row label
    	 *
    	 * @access public
    	 * @var array
    	 */
    	public $row_label = array();
    
    	/**
    	 * Constructor.
    	 * Supplied `$args` override class property defaults.
    	 * If `$args['settings']` is not defined, use the $id as the setting ID.
    	 *
    	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
    	 * @param string               $id      Control ID.
    	 * @param array                $args    {@see WP_Customize_Control::__construct}.
    	 */
    	public function __construct( $manager, $id, $args = array() ) {
    
    		parent::__construct( $manager, $id, $args );
    
    		// Set up defaults for row labels.
    		$this->row_label = array(
    			'type' => 'text',
    			'value' => esc_attr__( 'row', 'blossom-recipe' ),
    			'field' => false,
    		);
    
    		// Validate row-labels.
    		$this->row_label( $args );
    
    		if ( empty( $this->button_label ) ) {
    			$this->button_label = sprintf( esc_attr__( 'Add new %s', 'blossom-recipe' ), $this->row_label['value'] );
    		}
    
    		if ( empty( $args['fields'] ) || ! is_array( $args['fields'] ) ) {
    			$args['fields'] = array();
    		}
    
    		// An array to store keys of fields that need to be filtered.
    		$media_fields_to_filter = array();
    
    		foreach ( $args['fields'] as $key => $value ) {
    			$args['fields'][ $key ]['default'] = ( isset( $value['default'] ) ) ? $value['default'] : '';
    			$args['fields'][ $key ]['id']      = $key;
    
    			// We check if the filed is an uploaded media ( image , file, video, etc.. ).
    			if ( isset( $value['type'] ) ) {
    				switch ( $value['type'] ) {
    					case 'image':
    					case 'cropped_image':
    					case 'upload':
    						// We add it to the list of fields that need some extra filtering/processing.
    						$media_fields_to_filter[ $key ] = true;
    						break;
    				}
    			}
    		}
    
    		$this->fields = $args['fields'];
    
    		// Now we are going to filter the fields.
    		// First we create a copy of the value that would be used otherwise.
    		$this->filtered_value = $this->value();
    
    		if ( is_array( $this->filtered_value ) && ! empty( $this->filtered_value ) ) {
    
    			// We iterate over the list of fields.
    			foreach ( $this->filtered_value as &$filtered_value_field ) {
    
    				if ( is_array( $filtered_value_field ) && ! empty( $filtered_value_field ) ) {
    
    					// We iterate over the list of properties for this field.
    					foreach ( $filtered_value_field as $key => &$value ) {
    
    						// We check if this field was marked as requiring extra filtering (in this case image, cropped_images, upload).
    						if ( array_key_exists( $key, $media_fields_to_filter ) ) {
    
    							// What follows was made this way to preserve backward compatibility.
    							// The repeater control use to store the URL for images instead of the attachment ID.
    							// We check if the value look like an ID (otherwise it's probably a URL so don't filter it).
    							if ( is_numeric( $value ) ) {
    
    								// "sanitize" the value.
    								$attachment_id = (int) $value;
    
    								// Try to get the attachment_url.
    								$url = wp_get_attachment_url( $attachment_id );
    
    								$filename = basename( get_attached_file( $attachment_id ) );
    
    								// If we got a URL.
    								if ( $url ) {
    
    									// 'id' is needed for form hidden value, URL is needed to display the image.
    									$value = array(
    										'id'  => $attachment_id,
    										'url' => $url,
    										'filename' => $filename,
    									);
    								}
    							}
    						}
    					}
    				}
    			}
    		}
    	}
    
    	/**
    	 * Refresh the parameters passed to the JavaScript via JSON.
    	 *
    	 * @access public
    	 */
    	public function to_json() {
    		parent::to_json();
    
    		$this->json['default'] = ( isset( $this->default ) ) ? $this->default : $this->setting->default;
    		$this->json['value']   = $this->value();
    		$this->json['choices'] = $this->choices;
    		$this->json['link']    = $this->get_link();
    		$this->json['id']      = $this->id;
    
    		if ( 'user_meta' === $this->option_type ) {
    			$this->json['value'] = get_user_meta( get_current_user_id(), $this->id, true );
    		}
    
    		$this->json['inputAttrs'] = '';
    		foreach ( $this->input_attrs as $attr => $value ) {
    			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
    		}
    
    		$fields = $this->fields;
    
    		$this->json['fields'] = $fields;
    		$this->json['row_label'] = $this->row_label;
    
    		// If filtered_value has been set and is not empty we use it instead of the actual value.
    		if ( is_array( $this->filtered_value ) && ! empty( $this->filtered_value ) ) {
    			$this->json['value'] = $this->filtered_value;
    		}
    	}
    
    	/**
    	 * Enqueue control related scripts/styles.
    	 *
    	 * @access public
    	 */
    	public function enqueue() {    
    		// If we have a color picker field we need to enqueue the WordPress Color Picker style and script.
    		if ( is_array( $this->fields ) && ! empty( $this->fields ) ) {
    			foreach ( $this->fields as $field ) {
    				if ( isset( $field['type'] ) ){
    					if( 'color' === $field['type'] ){ 
                            wp_enqueue_script( 'wp-color-picker' );
                            wp_enqueue_style( 'wp-color-picker' );
    					}elseif( 'font' === $field['type'] ){
                            wp_enqueue_script( 'all', get_template_directory_uri() . '/js/all.min.js', array( 'jquery' ), '5.6.3', true );
                            wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js/v4-shims.min.js', array( 'jquery', 'all' ), '5.6.3', true );
    					}
    				}
    			}
    		}
            wp_enqueue_script( 'blossom-recipe-repeater', get_template_directory_uri() . '/inc/custom-controls/repeater/repeater.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), false, true );
    		wp_enqueue_style( 'blossom-recipe-repeater', get_template_directory_uri() . '/inc/custom-controls/repeater/repeater.css', null );
    	}
    
    	/**
    	 * Render the control's content.
    	 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
    	 *
    	 * @access protected
    	 */
    	protected function render_content() {
    		?>
    		<label>
    			<?php if ( ! empty( $this->label ) ) : ?>
    				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
    			<?php endif; ?>
    			<?php if ( ! empty( $this->description ) ) : ?>
    				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
    			<?php endif; ?>
    			<input type="hidden" {{{ data.inputAttrs }}} value="" <?php echo wp_kses_post( $this->get_link() ); ?> />
    		</label>
    
    		<ul class="repeater-fields"></ul>
    
    		<?php if ( isset( $this->choices['limit'] ) ) : ?>
    			<p class="limit"><?php printf( esc_html__( 'Limit: %s rows', 'blossom-recipe' ), esc_html( $this->choices['limit'] ) ); ?></p>
    		<?php endif; ?>
    		<button class="button-secondary repeater-add"><?php echo esc_html( $this->button_label ); ?></button>
    
    		<?php
    
    		$this->repeater_js_template();
    	}
    
    	/**
    	 * An Underscore (JS) template for this control's content (but not its container).
    	 * Class variables for this control class are available in the `data` JS object.
    	 *
    	 * @access public
    	 */
    	public function repeater_js_template() {
    		?>
    		<script type="text/html" class="customize-control-repeater-content">
    			<# var field; var index = data.index; #>
    
    			<li class="repeater-row minimized" data-row="{{{ index }}}">
    
    				<div class="repeater-row-header">
    					<span class="repeater-row-label"></span>
    					<i class="dashicons dashicons-arrow-down repeater-minimize"></i>
    				</div>
    				<div class="repeater-row-content">
    					<# _.each( data, function( field, i ) { #>
    
    						<div class="repeater-field repeater-field-{{{ field.type }}}">
    
    							<# if ( 'text' === field.type || 'font' === field.type || 'url' === field.type || 'link' === field.type || 'email' === field.type || 'tel' === field.type || 'date' === field.type ) { #>
    
    								<# if ( 'link' === field.type ) { #>
    									<# field.type = 'url' #>
    								<# } #>
    
    								<label>
    									<# if ( field.label ) { #>
    										<span class="customize-control-title">{{ field.label }}</span>
    									<# } #>
    									<# if ( field.description ) { #>
    										<span class="description customize-control-description">{{ field.description }}</span>
    									<# } #>
    									<input type="{{field.type}}" name="" value="{{{ field.default }}}" data-field="{{{ field.id }}}">
    								</label>
    
    							<# } else if ( 'hidden' === field.type ) { #>
    
    								<input type="hidden" data-field="{{{ field.id }}}" <# if ( field.default ) { #> value="{{{ field.default }}}" <# } #> />
    
    							<# } else if ( 'checkbox' === field.type ) { #>
    
    								<label>
    									<input type="checkbox" value="true" data-field="{{{ field.id }}}" <# if ( field.default ) { #> checked="checked" <# } #> /> {{ field.label }}
    									<# if ( field.description ) { #>
    										{{ field.description }}
    									<# } #>
    								</label>
    
    							<# } else if ( 'select' === field.type ) { #>
    
    								<label>
    									<# if ( field.label ) { #>
    										<span class="customize-control-title">{{ field.label }}</span>
    									<# } #>
    									<# if ( field.description ) { #>
    										<span class="description customize-control-description">{{ field.description }}</span>
    									<# } #>
    									<select data-field="{{{ field.id }}}">
    										<# _.each( field.choices, function( choice, i ) { #>
    											<option value="{{{ i }}}" <# if ( field.default == i ) { #> selected="selected" <# } #>>{{ choice }}</option>
    										<# }); #>
    									</select>
    								</label>
    
    							<# } else if ( 'radio' === field.type ) { #>
    
    								<label>
    									<# if ( field.label ) { #>
    										<span class="customize-control-title">{{ field.label }}</span>
    									<# } #>
    									<# if ( field.description ) { #>
    										<span class="description customize-control-description">{{ field.description }}</span>
    									<# } #>
    
    									<# _.each( field.choices, function( choice, i ) { #>
    										<label>
    											<input type="radio" name="{{{ field.id }}}{{ index }}" data-field="{{{ field.id }}}" value="{{{ i }}}" <# if ( field.default == i ) { #> checked="checked" <# } #>> {{ choice }} <br/>
    										</label>
    									<# }); #>
    								</label>
    
    							<# } else if ( 'radio-image' === field.type ) { #>
    
    								<label>
    									<# if ( field.label ) { #>
    										<span class="customize-control-title">{{ field.label }}</span>
    									<# } #>
    									<# if ( field.description ) { #>
    										<span class="description customize-control-description">{{ field.description }}</span>
    									<# } #>
    
    									<# _.each( field.choices, function( choice, i ) { #>
    										<input type="radio" id="{{{ field.id }}}_{{ index }}_{{{ i }}}" name="{{{ field.id }}}{{ index }}" data-field="{{{ field.id }}}" value="{{{ i }}}" <# if ( field.default == i ) { #> checked="checked" <# } #>>
    											<label for="{{{ field.id }}}_{{ index }}_{{{ i }}}">
    												<img src="{{ choice }}">
    											</label>
    										</input>
    									<# }); #>
    								</label>
    
    							<# } else if ( 'color' === field.type ) { #>
    
    								<# var defaultValue = '';
    						        if ( field.default ) {
    						            if ( '#' !== field.default.substring( 0, 1 ) ) {
    						                defaultValue = '#' + field.default;
    						            } else {
    						                defaultValue = field.default;
    						            }
    						            defaultValue = ' data-default-color=' + defaultValue; // Quotes added automatically.
    						        } #>
    						        <label>
    						            <# if ( field.label ) { #>
    						                <span class="customize-control-title">{{{ field.label }}}</span>
    						            <# } #>
    						            <# if ( field.description ) { #>
    						                <span class="description customize-control-description">{{{ field.description }}}</span>
    						            <# } #>
    						            <input class="color-picker-hex" type="text" maxlength="7" placeholder="<?php echo esc_attr__( 'Hex Value', 'blossom-recipe' ); ?>"  value="{{{ field.default }}}" data-field="{{{ field.id }}}" {{ defaultValue }} />
    
    						        </label>
    
    							<# } else if ( 'textarea' === field.type ) { #>
    
    								<# if ( field.label ) { #>
    									<span class="customize-control-title">{{ field.label }}</span>
    								<# } #>
    								<# if ( field.description ) { #>
    									<span class="description customize-control-description">{{ field.description }}</span>
    								<# } #>
    								<textarea rows="5" data-field="{{{ field.id }}}">{{ field.default }}</textarea>
    
    							<# } else if ( field.type === 'image' || field.type === 'cropped_image' ) { #>
    
    								<label>
    									<# if ( field.label ) { #>
    										<span class="customize-control-title">{{ field.label }}</span>
    									<# } #>
    									<# if ( field.description ) { #>
    										<span class="description customize-control-description">{{ field.description }}</span>
    									<# } #>
    								</label>
    
    								<figure class="blossom-recipe-image-attachment" data-placeholder="<?php esc_attr_e( 'No Image Selected', 'blossom-recipe' ); ?>" >
    									<# if ( field.default ) { #>
    										<# var defaultImageURL = ( field.default.url ) ? field.default.url : field.default; #>
    										<img src="{{{ defaultImageURL }}}">
    									<# } else { #>
    										<?php esc_attr_e( 'No Image Selected', 'blossom-recipe' ); ?>
    									<# } #>
    								</figure>
    
    								<div class="actions">
    									<button type="button" class="button remove-button<# if ( ! field.default ) { #> hidden<# } #>"><?php esc_html_e( 'Remove', 'blossom-recipe' ); ?></button>
    									<button type="button" class="button upload-button" data-label=" <?php esc_attr_e( 'Add Image', 'blossom-recipe' ); ?>" data-alt-label="<?php esc_attr_e( 'Change Image', 'blossom-recipe' ); ?>" >
    										<# if ( field.default ) { #>
    											<?php esc_attr_e( 'Change Image', 'blossom-recipe' ); ?>
    										<# } else { #>
    											<?php esc_attr_e( 'Add Image', 'blossom-recipe' ); ?>
    										<# } #>
    									</button>
    									<# if ( field.default.id ) { #>
    										<input type="hidden" class="hidden-field" value="{{{ field.default.id }}}" data-field="{{{ field.id }}}" >
    									<# } else { #>
    										<input type="hidden" class="hidden-field" value="{{{ field.default }}}" data-field="{{{ field.id }}}" >
    									<# } #>
    								</div>
    
    							<# } else if ( field.type === 'upload' ) { #>
    
    								<label>
    									<# if ( field.label ) { #>
    										<span class="customize-control-title">{{ field.label }}</span>
    									<# } #>
    									<# if ( field.description ) { #>
    										<span class="description customize-control-description">{{ field.description }}</span>
    									<# } #>
    								</label>
    
    								<figure class="blossom-recipe-file-attachment" data-placeholder="<?php esc_attr_e( 'No File Selected', 'blossom-recipe' ); ?>" >
    									<# if ( field.default ) { #>
    										<# var defaultFilename = ( field.default.filename ) ? field.default.filename : field.default; #>
    										<span class="file"><span class="dashicons dashicons-media-default"></span> {{ defaultFilename }}</span>
    									<# } else { #>
    										<?php esc_attr_e( 'No File Selected', 'blossom-recipe' ); ?>
    									<# } #>
    								</figure>
    
    								<div class="actions">
    									<button type="button" class="button remove-button<# if ( ! field.default ) { #> hidden<# } #>"></button>
    									<button type="button" class="button upload-button" data-label="<?php esc_attr_e( 'Add File', 'blossom-recipe' ); ?>" data-alt-label="<?php esc_attr_e( 'Change File', 'blossom-recipe' ); ?>" >
    										<# if ( field.default ) { #>
    											<?php esc_attr_e( 'Change File', 'blossom-recipe' ); ?>
    										<# } else { #>
    											<?php esc_attr_e( 'Add File', 'blossom-recipe' ); ?>
    										<# } #>
    									</button>
    									<# if ( field.default.id ) { #>
    										<input type="hidden" class="hidden-field" value="{{{ field.default.id }}}" data-field="{{{ field.id }}}" >
    									<# } else { #>
    										<input type="hidden" class="hidden-field" value="{{{ field.default }}}" data-field="{{{ field.id }}}" >
    									<# } #>
    								</div>
    
    							<# } else if ( 'custom' === field.type ) { #>
    
    								<# if ( field.label ) { #>
    									<span class="customize-control-title">{{ field.label }}</span>
    								<# } #>
    								<# if ( field.description ) { #>
    									<span class="description customize-control-description">{{ field.description }}</span>
    								<# } #>
    								<div data-field="{{{ field.id }}}">{{{ field.default }}}</div>
    
    							<# } #>
    
    						</div>
    					<# }); #>
    					<button type="button" class="button-link repeater-row-remove"><?php esc_html_e( 'Remove', 'blossom-recipe' ); ?></button>
    				</div>
    			</li>
    		</script>
    		<?php
    	}
    
    	/**
    	 * Validate row-labels.
    	 *
    	 * @access protected
    	 * @since 2.4.0
    	 * @param array $args {@see WP_Customize_Control::__construct}.
    	 */
    	protected function row_label( $args ) {
    
    		// Validating args for row labels.
    		if ( isset( $args['row_label'] ) && is_array( $args['row_label'] ) && ! empty( $args['row_label'] ) ) {
    
    			// Validating row label type.
    			if ( isset( $args['row_label']['type'] ) && ( 'text' === $args['row_label']['type'] || 'field' === $args['row_label']['type'] ) ) {
    				$this->row_label['type'] = $args['row_label']['type'];
    			}
    
    			// Validating row label type.
    			if ( isset( $args['row_label']['value'] ) && ! empty( $args['row_label']['value'] ) ) {
    				$this->row_label['value'] = esc_attr( $args['row_label']['value'] );
    			}
    
    			// Validating row label field.
    			if ( isset( $args['row_label']['field'] ) && ! empty( $args['row_label']['field'] ) && isset( $args['fields'][ esc_attr( $args['row_label']['field'] ) ] ) ) {
    				$this->row_label['field'] = esc_attr( $args['row_label']['field'] );
    			} else {
    				// If from field is not set correctly, making sure standard is set as the type.
    				$this->row_label['type'] = 'text';
    			}
    		}
    	}
    }
}