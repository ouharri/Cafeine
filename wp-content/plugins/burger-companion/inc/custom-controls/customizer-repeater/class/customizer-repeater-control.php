<?php if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class Burger_Companion_Repeater extends WP_Customize_Control {

	public $id;
	private $boxtitle = array();
	private $add_field_label = array();
	private $customizer_repeater_title_control = false;
	private $customizer_repeater_subtitle_control = false;
	private $customizer_repeater_button_text_control = false;
	private $customizer_repeater_link_control = false;
	private $customizer_repeater_slide_align = false;
	private $customizer_repeater_video_url_control = false;
	private $customizer_repeater_image_control = false;
	private $customizer_repeater_icon_control = false;
	private $customizer_repeater_color_control = false;
	private $customizer_repeater_text_control = false;
	 public $customizer_repeater_text2_control = false;
	 public $customizer_repeater_link2_control = false;
	private $customizer_repeater_designation_control = false;
	private $customizer_repeater_shortcode_control = false;
	private $customizer_repeater_repeater_control = false;
	private $customizer_repeater_checkbox_control = false;
	
    private $customizer_icon_container = '';
    private $allowed_html = array();


	/*Class constructor*/
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		/*Get options from customizer.php*/
		$this->add_field_label = esc_html__( 'Add new field', 'burger-companion' );
		if ( ! empty( $args['add_field_label'] ) ) {
			$this->add_field_label = $args['add_field_label'];
		}

		$this->boxtitle = esc_html__( 'Customizer Repeater', 'burger-companion' );
		if ( ! empty ( $args['item_name'] ) ) {
			$this->boxtitle = $args['item_name'];
		} elseif ( ! empty( $this->label ) ) {
			$this->boxtitle = $this->label;
		}

		if ( ! empty( $args['customizer_repeater_image_control'] ) ) {
			$this->customizer_repeater_image_control = $args['customizer_repeater_image_control'];
		}
	

		if ( ! empty( $args['customizer_repeater_icon_control'] ) ) {
			$this->customizer_repeater_icon_control = $args['customizer_repeater_icon_control'];
		}

		if ( ! empty( $args['customizer_repeater_color_control'] ) ) {
			$this->customizer_repeater_color_control = $args['customizer_repeater_color_control'];
		}

		if ( ! empty( $args['customizer_repeater_title_control'] ) ) {
			$this->customizer_repeater_title_control = $args['customizer_repeater_title_control'];
		}
		

		if ( ! empty( $args['customizer_repeater_subtitle_control'] ) ) {
			$this->customizer_repeater_subtitle_control = $args['customizer_repeater_subtitle_control'];
		}

		if ( ! empty( $args['customizer_repeater_text_control'] ) ) {
			$this->customizer_repeater_text_control = $args['customizer_repeater_text_control'];
		}
		if ( ! empty( $args['customizer_repeater_text2_control'] ) ) {
            $this->customizer_repeater_text2_control = $args['customizer_repeater_text2_control'];
        }
		  if ( ! empty( $args['customizer_repeater_link2_control'] ) ) {
            $this->customizer_repeater_link2_control = $args['customizer_repeater_link2_control'];
        }
		if ( ! empty( $args['customizer_repeater_designation_control'] ) ) {
			$this->customizer_repeater_designation_control = $args['customizer_repeater_designation_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_button_text_control'] ) ) {
			$this->customizer_repeater_button_text_control = $args['customizer_repeater_button_text_control'];
		}

		if ( ! empty( $args['customizer_repeater_link_control'] ) ) {
			$this->customizer_repeater_link_control = $args['customizer_repeater_link_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_checkbox_control'] ) ) {
			$this->customizer_repeater_checkbox_control = $args['customizer_repeater_checkbox_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_video_url_control'] ) ) {
			$this->customizer_repeater_video_url_control = $args['customizer_repeater_video_url_control'];
		}
		
		if ( ! empty( $args['customizer_repeater_slide_align'] ) ) {
			$this->customizer_repeater_slide_align = $args['customizer_repeater_slide_align'];
		}

		if ( ! empty( $args['customizer_repeater_shortcode_control'] ) ) {
			$this->customizer_repeater_shortcode_control = $args['customizer_repeater_shortcode_control'];
		}

		if ( ! empty( $args['customizer_repeater_repeater_control'] ) ) {
			$this->customizer_repeater_repeater_control = $args['customizer_repeater_repeater_control'];
		}
		

		if ( ! empty( $id ) ) {
			$this->id = $id;
		}

		// if ( file_exists( get_template_directory() . '/inc/customizer-repeater/inc/icons.php' ) ) {
			// $this->customizer_icon_container =  'inc/customizer-repeater/inc/icons';
		// }
		if ( file_exists( BURGER_COMPANION_PLUGIN_DIR . '/inc/custom-controls/customizer-repeater/inc/icons.php' ) ) {
			$this->customizer_icon_container =   'inc/custom-controls/customizer-repeater/inc/icons';
		}
		

		$allowed_array1 = wp_kses_allowed_html( 'post' );
		$allowed_array2 = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'placeholder' => array()
			)
		);

		$this->allowed_html = array_merge( $allowed_array1, $allowed_array2 );
	}

	/*Enqueue resources for the control*/
	public function enqueue() {
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/fonts/font-awesome/css/font-awesome.min.css', array(), 999 );

		wp_enqueue_style( 'burger_companion_customizer-repeater-admin-stylesheet', BURGER_COMPANION_PLUGIN_URL . '/inc/custom-controls/customizer-repeater/css/admin-style.css', array(), 999 );

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'burger_companion_customizer-repeater-script', BURGER_COMPANION_PLUGIN_URL . '/inc/custom-controls/customizer-repeater/js/customizer_repeater.js', array('jquery', 'jquery-ui-draggable', 'wp-color-picker' ), 999, true  );

		wp_enqueue_script( 'burger_companion_customizer-repeater-fontawesome-iconpicker', BURGER_COMPANION_PLUGIN_URL . '/inc/custom-controls/customizer-repeater/js/fontawesome-iconpicker.js', array( 'jquery' ), 999, true );

		wp_enqueue_style( 'burger_companion_customizer-repeater-fontawesome-iconpicker-script', BURGER_COMPANION_PLUGIN_URL . '/inc/custom-controls/customizer-repeater/css/fontawesome-iconpicker.min.css', array(), 999 );
	}

	public function render_content() {

		/*Get default options*/
		$this_default = json_decode( $this->setting->default );

		/*Get values (json format)*/
		$values = $this->value();

		/*Decode values*/
		$json = json_decode( $values );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		} ?>

		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
			<?php
			if ( ( count( $json ) == 1 && '' === $json[0] ) || empty( $json ) ) {
				if ( ! empty( $this_default ) ) {
					$this->iterate_array( $this_default ); ?>
					<input type="hidden"
					       id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
					       class="customizer-repeater-colector"
					       value="<?php echo esc_textarea( json_encode( $this_default ) ); ?>"/>
					<?php
				} else {
					$this->iterate_array(); ?>
					<input type="hidden"
					       id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
					       class="customizer-repeater-colector"/>
					<?php
				}
			} else {
				$this->iterate_array( $json ); ?>
				<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
				       class="customizer-repeater-colector" value="<?php echo esc_textarea( $this->value() ); ?>"/>
				<?php
			} ?>
			</div>
		<button type="button" class="button add_field customizer-repeater-new-field">
			<?php echo esc_html( $this->add_field_label ); ?>
		</button>
		<?php
	}

	private function iterate_array($array = array()){
		/*Counter that helps checking if the box is first and should have the delete button disabled*/
		$it = 0;
		if(!empty($array)){
			$exist_service=count($array);
			
				//$burger_companion_del_btn_id=$this->boxtitle;
				//$burger_companion_del_btn_id=wp_get_theme()."_".$this->boxtitle;
				$theme = wp_get_theme(); // gets the current theme
				$theme_name = str_replace(' ', '_', $theme->name);
				$burger_companion_del_btn_id=$theme_name."_".$this->boxtitle;
				
			global $burger_companion_limit;
			global $burger_companion_type_with_id;
			echo sprintf("<input type='hidden' value='$exist_service' id='exist_burger_companion_$burger_companion_del_btn_id'/>");
			foreach($array as $icon){ 
			if($it<4)
			{
			$burger_companion_limit="burger_companion_limit";
			$burger_companion_type_with_id='';
			}
			else
			{
			$burger_companion_limit="burger_companion_overlimit";	
			$burger_companion_type_with_id=$burger_companion_del_btn_id."_".$it;
			}

		
		?>
				<div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
					<div class="customizer-repeater-customize-control-title">
						<?php echo esc_html( $this->boxtitle ) ?>
					</div>
					<div class="customizer-repeater-box-content-hidden">
						<?php
						$choice = $image_url = $icon_value = $title = $subtitle = $text = $text2 = $link2 = $link = $designation = $slide_align = $button = $open_new_tab = $shortcode = $repeater = $color = $video_url = '';
						if(!empty($icon->id)){
							$id = $icon->id;
						}
						if(!empty($icon->choice)){
							$choice = $icon->choice;
						}
						if(!empty($icon->image_url)){
							$image_url = $icon->image_url;
						}
						if(!empty($icon->icon_value)){
							$icon_value = $icon->icon_value;
						}
						if(!empty($icon->color)){
							$color = $icon->color;
						}
						if(!empty($icon->title)){
							$title = $icon->title;
						}
						
						if(!empty($icon->slide_align)){
							$slide_align = $icon->slide_align;
						}
						
						if(!empty($icon->designation)){
							$designation = $icon->designation;
						}
						
						if(!empty($icon->subtitle)){
							$subtitle =  $icon->subtitle;
						}
						if(!empty($icon->text)){
							$text = $icon->text;
						}
						if(!empty($icon->text2)){
                            $text2 = $icon->text2;
                        }
						 if(!empty($icon->link2)){
                            $link2 = $icon->link2;
                        }
						if(!empty($icon->video_url)){
							$video_url = $icon->video_url;
						}
						
						if(!empty($icon->button)){
							$button = $icon->button_text;
						}
						if(!empty($icon->link)){
							$link = $icon->link;
						}
						if(!empty($icon->shortcode)){
							$shortcode = $icon->shortcode;
						}

						if(!empty($icon->social_repeater)){
							$repeater = $icon->social_repeater;
						}
						
						if(!empty($icon->open_new_tab)){
							$open_new_tab = $icon->open_new_tab;
						}
						
						
						if($this->customizer_repeater_title_control==true){
							$this->input_control(array(
								'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Title','burger-companion' ), $this->id, 'customizer_repeater_title_control' ),
								'class' => 'customizer-repeater-title-control '."$burger_companion_limit".' '."$burger_companion_type_with_id".'',
                                'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
							), $title);
						}
						
						if($this->customizer_repeater_subtitle_control==true){
							$this->input_control(array(
								'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Subtitle','burger-companion' ), $this->id, 'customizer_repeater_subtitle_control' ),
								'class' => 'customizer-repeater-subtitle-control '."$burger_companion_limit".' '."$burger_companion_type_with_id".'',
								'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
							), $subtitle);
						}
						if($this->customizer_repeater_text_control==true){
							$this->input_control(array(
								'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Description','burger-companion' ), $this->id, 'customizer_repeater_text_control' ),
								'class' => 'customizer-repeater-text-control '."$burger_companion_limit".' '."$burger_companion_type_with_id".'',
								'type'  => apply_filters('burger_companion_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
							), $text);
						}
						if($this->customizer_repeater_text2_control==true){
                            $this->input_control(array(
                                'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Button Label','burger-companion' ), $this->id, 'customizer_repeater_text2_control' ),
                                'class' => 'customizer-repeater-text2-control '."$burger_companion_limit".'',
                                'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text2_control' ),
                            ), $text2);
                        }
						if($this->customizer_repeater_link2_control){
                            $this->input_control(array(
                                'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'skills','burger-companion' ), $this->id, 'customizer_repeater_link2_control' ),
                                'class' => 'customizer-repeater-link2-control '."$burger_companion_limit".' '."$burger_companion_type_with_id".'',
                                //'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
                            ), $link2);
                        }
						if($this->customizer_repeater_button_text_control){
							$this->input_control(array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__('Button Text',
							'burger-companion'), $this->id, 'customizer_repeater_button_text_control'),
							'class' => 'customizer-repeater-button-text-control '."$burger_companion_limit".'',
							'type' => apply_filters('burger_companion_repeater_input_types_filter', '' , $this->id,
							'customizer_repeater_button_text_control'),
							), $button);
						}
						
						
						if($this->customizer_repeater_link_control){
							$this->input_control(array(
								'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Link','burger-companion' ), $this->id, 'customizer_repeater_link_control' ),
								'class' => 'customizer-repeater-link-control '."$burger_companion_limit".' '."$burger_companion_type_with_id".'',
								'sanitize_callback' => 'esc_url_raw',
                                'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
							), $link);
						}
						
						if($this->customizer_repeater_checkbox_control == true){
							$this->testimonila_check($open_new_tab);
							
						}
					
						if($this->customizer_repeater_video_url_control){
							$this->input_control(array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__('Video Url',
							'burger-companion'), $this->id, 'customizer_repeater_video_url_control'),
							'class' => 'customizer-repeater-video-url-control',
							'type'  => apply_filters('burger_companion_customizer_repeater_video_url_control', 'textarea', $this->id, 'customizer_repeater_video_url_control' ),
							), $video_url);
						}
						
						if($this->customizer_repeater_slide_align == true){
							$this->slide_align($slide_align);
							
						}						
						
						if($this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true) {
							$this->icon_type_choice( $choice,$burger_companion_limit );
						}
						if($this->customizer_repeater_image_control == true){
							$this->image_control($image_url, $choice, $burger_companion_limit, $it+1, $burger_companion_del_btn_id);
						}
						if($this->customizer_repeater_icon_control == true){
							$this->icon_picker_control($icon_value, $choice);
						}
						
					
						
						
						if($this->customizer_repeater_color_control == true){
							$this->input_control(array(
								'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Color','burger-companion' ), $this->id, 'customizer_repeater_color_control' ),
								'class' => 'customizer-repeater-color-control',
								'type'  => apply_filters('burger_companion_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
								'sanitize_callback' => 'sanitize_hex_color'
							), $color);
						}
						
						
						if($this->customizer_repeater_shortcode_control==true){
							$this->input_control(array(
								'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Shortcode','burger-companion' ), $this->id, 'customizer_repeater_shortcode_control' ),
								'class' => 'customizer-repeater-shortcode-control',
                                'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
							), $shortcode);
						}
						
						if($this->customizer_repeater_designation_control==true){
							$this->input_control(array(
								'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Designation','burger-companion' ), $this->id, 'customizer_repeater_designation_control' ),
								'class' => 'customizer-repeater-designation-control',
								'type'  => apply_filters('burger_companion_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_designation_control' ),
							), $designation);
						}
						
						if($this->customizer_repeater_repeater_control==true){
							$this->repeater_control($repeater, $burger_companion_limit, $burger_companion_type_with_id);
						} ?>

						<input type="hidden" class="social-repeater-box-id" value="<?php if ( ! empty( $id ) ) {
							echo esc_attr( $id );
						} ?>">
						<button type="button" class="social-repeater-general-control-remove-field" <?php if ( $it == 0 ) {
							echo esc_attr('style=display:none;');
						} ?>>
							<?php printf( __( 'Delete %s', 'burger-companion' ), $this->boxtitle );	?>
						</button>

					</div>
				</div>

				<?php
				$it++;
			}
		} else { ?>
			<div class="customizer-repeater-general-control-repeater-container">
				<div class="customizer-repeater-customize-control-title">
					<?php echo esc_html( $this->boxtitle ) ?>
				</div>
				<div class="customizer-repeater-box-content-hidden">
					<?php
					if ( $this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true ) {
						$this->icon_type_choice();
					}
					if ( $this->customizer_repeater_image_control == true ) {
						$this->image_control();
					}
					if ( $this->customizer_repeater_icon_control == true ) {
						$this->icon_picker_control();
					}
					
					
						
					if($this->customizer_repeater_color_control==true){
						$this->input_control(array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Color','burger-companion' ), $this->id, 'customizer_repeater_color_control' ),
							'class' => 'customizer-repeater-color-control',
							'type'  => apply_filters('burger_companion_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
							'sanitize_callback' => 'sanitize_hex_color'
						) );
					}
					if ( $this->customizer_repeater_title_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Title','burger-companion' ), $this->id, 'customizer_repeater_title_control' ),
							'class' => 'customizer-repeater-title-control',
                            'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
						) );
					}
					
					if ( $this->customizer_repeater_subtitle_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Subtitle','burger-companion' ), $this->id, 'customizer_repeater_subtitle_control' ),
							'class' => 'customizer-repeater-subtitle-control',
                            'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
						) );
					}
					if ( $this->customizer_repeater_text_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Description','burger-companion' ), $this->id, 'customizer_repeater_text_control' ),
							'class' => 'customizer-repeater-text-control',
							'type'  => apply_filters('burger_companion_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
						) );
					}
					if ( $this->customizer_repeater_text2_control == true ) {
                        $this->input_control( array(
                            'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Button Label','burger-companion' ), $this->id, 'customizer_repeater_text2_control' ),
                            'class' => 'customizer-repeater-text2-control',
                            'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_text2_control' ),
                        ) );
                    }
					 if ( $this->customizer_repeater_link2_control == true ) {
                        $this->input_control( array(
                            'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'skill','burger-companion' ), $this->id, 'customizer_repeater_link2_control' ),
                            'class' => 'customizer-repeater-link2-control',
                            'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
                        ) );
                    }
					if($this->customizer_repeater_button_text_control){
							$this->input_control(array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__('Button Text',
							'burger-companion'), $this->id, 'customizer_repeater_button_text_control'),
							'class' => 'customizer-repeater-button-text-control',
							'type' => apply_filters('burger_companion_repeater_input_types_filter', '' , $this->id,
							'customizer_repeater_button_text_control'),
							));
						}
						
					if ( $this->customizer_repeater_link_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Link','burger-companion' ), $this->id, 'customizer_repeater_link_control' ),
							'class' => 'customizer-repeater-link-control',
                            'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
						) );
					}
					
					if($this->customizer_repeater_checkbox_control == true){
							$this->testimonila_check();
							
						}
			
					if($this->customizer_repeater_video_url_control){
							$this->input_control(array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__('Video Url',
							'burger-companion'), $this->id, 'customizer_repeater_video_url_control'),
							'class' => 'customizer-repeater-video-url-control',
							'type'  => apply_filters('burger_companion_customizer_repeater_video_url_control', 'textarea', $this->id, 'customizer_repeater_video_url_control' ),
							));
						}
					
					if($this->customizer_repeater_slide_align == true){
							$this->slide_align($slide_align);
							
						}
					
					
					if ( $this->customizer_repeater_shortcode_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Shortcode','burger-companion' ), $this->id, 'customizer_repeater_shortcode_control' ),
							'class' => 'customizer-repeater-shortcode-control',
                            'type'  => apply_filters('burger_companion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
						) );
					}
					
					
					if ( $this->customizer_repeater_designation_control == true ) {
						$this->input_control( array(
							'label' => apply_filters('burger_companion_repeater_input_labels_filter', esc_html__( 'Designation','burger-companion' ), $this->id, 'customizer_repeater_designation_control' ),
							'class' => 'customizer-repeater-designation-control',
							'type'  => apply_filters('burger_companion_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_designation_control' ),
						) );
					}
					
					if($this->customizer_repeater_repeater_control==true){
						$this->repeater_control();
					} ?>
					<input type="hidden" class="social-repeater-box-id">
					<button type="button" class="social-repeater-general-control-remove-field button" style="display:none;">
						<?php esc_html_e( 'Delete field', 'burger-companion' ); ?>
					</button>
				</div>
			</div>
			<?php
		}
	}

	private function input_control( $options, $value='' ){
//print_r($options);
	?>
		<span class="customize-control-title <?php echo esc_html( $options['label'] ); ?>" 
		<?php if($options['class']== 'customizer-repeater-video-url-control') {echo esc_attr('style="display:none;"'); }?>
		
		><?php echo esc_html( $options['label'] ); ?></span>
		<?php
		if( !empty($options['type']) ){
			switch ($options['type']) {
				case 'textarea':?>
					<textarea class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"><?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?></textarea>
					<?php
                    break;
				case 'color': ?>
					<input type="text" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" />
					<?php
					break;
			}
		} else { ?>
			<input type="text" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"/>
			<?php
		}
	}
	
	
	private function testimonila_check($value='no', $class='', $burger_companion_type_with_id=''){
		?>
	<div class="customize-control-title">
	<?php esc_html_e('Open link in new tab:','burger-companion'); ?>
	<span class="switch">
		<input type="checkbox" name="custom_checkbox" value="yes" <?php if($value=='yes'){echo esc_attr('checked');}?> class="customizer-repeater-checkbox <?php echo esc_attr($class);?> <?php echo esc_attr($burger_companion_type_with_id);?>">
	</span>
	</div>
	<?php
	}

	private function icon_picker_control($value = '', $show = '', $class=''){ ?>
		<div class="social-repeater-general-control-icon" <?php if( $show === 'customizer_repeater_image' || $show === 'customizer_repeater_none' ) { echo esc_attr('style="display:none;"'); } ?>>
            <span class="customize-control-title">
                <?php esc_html_e('Icon','burger-companion'); ?>
            </span>
			<span class="description customize-control-description">
                <?php
                echo sprintf(
	                esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at %1$s.', 'burger-companion' ),
	                sprintf( '<a href="http://fontawesome.io/icons/" rel="nofollow">%s</a>', esc_html__( 'http://fontawesome.io/icons/', 'burger-companion' ) )
                ); ?>
            </span>
			<div class="input-group icp-container">
				<input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value );} ?>" type="text">
				<span class="input-group-addon">
                    <i class="fa <?php echo esc_attr($value); ?>"></i>
                </span>
			</div>
            <?php require BURGER_COMPANION_PLUGIN_DIR . 'inc/custom-controls/customizer-repeater/inc/icons.php'; ?>
		</div>
		<?php
	}

		private function image_control($value = '', $show = '', $class='', $auto='', $sections=''){ 
		if($auto==1)
		{
			$auto="one";
		}

		if($auto==2)
		{
			$auto="two";
		}
		if($auto==3)
		{
			$auto="three";
		}
		if($auto==4)
		{
			$auto="four";
		}
		?>
		<div class="customizer-repeater-image-control" <?php if( $show === 'customizer_repeater_icon' || $show === 'customizer_repeater_none' ) { echo esc_attr('style="display:none;"'); } ?>>
            <span class="customize-control-title">
                <?php esc_html_e('Image','burger-companion')?>
            </span>
			<input type="text" class="widefat custom-media-url <?php if($class="burger_companion_overlimit") { echo esc_attr('burger_companion-uploading-img');}?> <?php echo esc_attr($auto);?>" value="<?php echo esc_attr( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button <?php if($class="burger_companion_overlimit") { echo esc_attr('burger_companion-uploading-img-btn');}?> <?php echo esc_attr($auto);?>" value="<?php esc_attr_e( 'Upload Image','burger-companion' ); ?>" />
		</div>
		<?php
	}

	private function slide_align($value='left'){?>
	
	<span class="customize-control-title">
	<?php esc_html_e('Slide Align','burger-companion'); ?>
	</span>
	<select class="customizer-repeater-slide-align">
		<option value="left" <?php selected($value,'left');?>>
		<?php esc_html_e('Left','burger-companion') ?>
		</option>
		
		<option value="right" <?php selected($value,'right');?>>
		<?php esc_html_e('Right','burger-companion') ?>
		</option>
		
		<option value="center" <?php selected($value,'center');?>>
		<?php esc_html_e('Center','burger-companion') ?>
		</option>
		
		
	</select>
		
	<?php
	}
	
	private function icon_type_choice($value='customizer_repeater_icon', $burger_companion_limit=''){ ?>
		<span class="customize-control-title">
            <?php esc_html_e('Image type','burger-companion');?>
        </span>
		<select class="customizer-repeater-image-choice  <?php echo esc_attr($burger_companion_limit);?>">
			<option value="customizer_repeater_icon" <?php selected($value,'customizer_repeater_icon');?>><?php esc_html_e('Icon','burger-companion'); ?></option>
			<option value="customizer_repeater_image" <?php selected($value,'customizer_repeater_image');?>><?php esc_html_e('Image','burger-companion'); ?></option>
			<option value="customizer_repeater_none" <?php selected($value,'customizer_repeater_none');?>><?php esc_html_e('None','burger-companion'); ?></option>
		</select>
		<?php
	}

	private function repeater_control($value = '', $burger_companion_limit='', $burger_companion_type_with_id=''){
		$social_repeater = array();
		$show_del        = 0; ?>
		<span class="customize-control-title"><?php esc_html_e( 'Social icons', 'burger-companion' ); ?></span>
		<?php
		if(!empty($value)) {
			$social_repeater = json_decode( html_entity_decode( $value ), true );
		}
		if ( ( count( $social_repeater ) == 1 && '' === $social_repeater[0] ) || empty( $social_repeater ) ) { ?>
			<div class="customizer-repeater-social-repeater">
				<div class="customizer-repeater-social-repeater-container">
					<div class="customizer-repeater-rc input-group icp-container">
						<input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value ); } ?>" type="text">
						<span class="input-group-addon"></span>
					</div>
					<?php require BURGER_COMPANION_PLUGIN_DIR . 'inc/custom-controls/customizer-repeater/inc/icons.php'; ?>
					<input type="text" class="customizer-repeater-social-repeater-link team_linkdata_<?php echo $burger_companion_limit;?> <?php echo esc_attr($burger_companion_type_with_id);?>"
					       placeholder="<?php esc_attr_e( 'Link', 'burger-companion' ); ?>">
					<input type="hidden" class="customizer-repeater-social-repeater-id" value="">
					<button class="social-repeater-remove-social-item" style="display:none">
						<?php esc_html_e( 'Remove Icon', 'burger-companion' ); ?>
					</button>
				</div>
				<input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value=""/>
			</div>
			<button class="social-repeater-add-social-item button-secondary "><?php esc_html_e( 'Add Icon', 'burger-companion' ); ?></button>
			<?php
		} else { ?>
			<div class="customizer-repeater-social-repeater">
				<?php
				foreach ( $social_repeater as $social_icon ) {
					$show_del ++; ?>
					<div class="customizer-repeater-social-repeater-container">
						<div class="customizer-repeater-rc input-group icp-container">
							<input data-placement="bottomRight" class="icp icp-auto team_data_<?php echo esc_attr($burger_companion_limit);?> <?php echo esc_attr($burger_companion_type_with_id);?>" value="<?php if( !empty($social_icon['icon']) ) { echo esc_attr( $social_icon['icon'] ); } ?>" type="text">
							<span class="input-group-addon"><i class="fa <?php echo esc_attr( $social_icon['icon'] ); ?>"></i></span>
						</div>
						<?php require BURGER_COMPANION_PLUGIN_DIR . 'inc/custom-controls/customizer-repeater/inc/icons.php'; ?>
						<input type="text" class="customizer-repeater-social-repeater-link"
						       placeholder="<?php esc_attr_e( 'Link', 'burger-companion' ); ?>"
						       value="<?php if ( ! empty( $social_icon['link'] ) ) {
							       echo esc_url( $social_icon['link'] );
						       } ?>">
						<input type="hidden" class="customizer-repeater-social-repeater-id"
						       value="<?php if ( ! empty( $social_icon['id'] ) ) {
							       echo esc_attr( $social_icon['id'] );
						       } ?>">
						<button class="social-repeater-remove-social-item"
						        style="<?php if ( $show_del == 1 ) {
							        echo esc_attr("display:none");
						        } ?>"><?php esc_html_e( 'Remove Icon', 'burger-companion' ); ?></button>
					</div>
					<?php
				} ?>
				<input type="hidden" id="social-repeater-socials-repeater-colector"
				       class="social-repeater-socials-repeater-colector"
				       value="<?php echo esc_textarea( html_entity_decode( $value ) ); ?>" />
			</div>
			<button class="social-repeater-add-social-item button-secondary <?php echo esc_attr($burger_companion_limit);?> <?php echo esc_attr($burger_companion_type_with_id);?>"><?php esc_html_e( 'Add Icon', 'burger-companion' ); ?></button>
			<?php
		}
	}
}


/**
  * Filter to modify input label in repeater control
  * You can filter by control id and input name.
  *
  * @param string $string Input label.
  * @param string $id Input id.
  * @param string $control Control name.
  * @modified 1.0
  *
  * @return string
  */
 function burger_repeater_labels( $string, $id, $control ) {
	$theme = wp_get_theme(); // gets the current theme
	if ( $id === 'slider' && 'Appetizer' == $theme->name) {
		if ( $control === 'customizer_repeater_designation_control' ) {
			return esc_html__( 'price','burger-companion' );
		}			
	}	
	
	if ( $id === 'slider' && ('OwlPress' == $theme->name || 'Crowl' == $theme->name)) {
		if ( $control === 'customizer_repeater_designation_control' ) {
			return esc_html__( 'Subtitle 2','burger-companion' );
		}			
	}	
	
	if ( $id === 'slider' && 'Setto' == $theme->name) {
		if ( $control === 'customizer_repeater_designation_control' ) {
			return esc_html__( 'Subtitle 2','burger-companion' );
		}			
	}	
	
	return $string;
 }
 add_filter( 'burger_companion_repeater_input_labels_filter','burger_repeater_labels', 10 , 3 );