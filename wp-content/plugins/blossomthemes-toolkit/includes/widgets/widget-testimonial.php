<?php
/**
 * Testimonial Widget
 *
 * @package BlossomThemes_Toolkit
 */

// register BlossomThemes_Toolkit_Testimonial_Widget widget
function bttk_register_testimonial_widget() {
	register_widget( 'BlossomThemes_Toolkit_Testimonial_Widget' );
}
add_action( 'widgets_init', 'bttk_register_testimonial_widget' );

/**
 * Adds BlossomThemes_Toolkit_Testimonial_Widget widget.
 */
class BlossomThemes_Toolkit_Testimonial_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'bttk_testimonial_widget', // Base ID
			__( 'Blossom: Testimonial', 'blossomthemes-toolkit' ), // Name
			array( 'description' => __( 'A Testimonial Widget.', 'blossomthemes-toolkit' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$obj         = new BlossomThemes_Toolkit_Functions();
		$name        = ! empty( $instance['name'] ) ? $instance['name'] : '';
		$designation = ! empty( $instance['designation'] ) ? $instance['designation'] : '';
		$testimonial = ! empty( $instance['testimonial'] ) ? $instance['testimonial'] : '';
		$image       = ! empty( $instance['image'] ) ? $instance['image'] : '';

		if ( $image ) {
			/** Added to work for demo testimonial compatible */
			$attachment_id = $image;
			if ( ! filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
				$attachment_id = $obj->bttk_get_attachment_id( $image );
			}

			$icon_img_size = apply_filters( 'bttk_testimonial_icon_img_size', 'thumbnail' );
		}

		echo $args['before_widget'];
		ob_start();
		?>
		
			<div class="bttk-testimonial-holder">
				<div class="bttk-testimonial-inner-holder">
					<?php if ( $image ) { ?>
						<div class="img-holder">
							<?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, array( 'alt' => esc_attr( $name ) ) ); ?>
						</div>
					<?php } ?>
		
					<div class="text-holder">
						<div class="testimonial-meta">
							<?php
							if ( $name ) {
								echo '<span class="name">' . esc_html( $name ) . '</span>';
							}
							if ( isset( $designation ) && $designation != '' ) {
								echo '<span class="designation">' . esc_html( $designation ) . '</span>';
							}
							?>
						</div>                              
						<?php
						if ( $testimonial ) {
							echo '<div class="testimonial-content">' . wpautop( wp_kses_post( $testimonial ) ) . '</div>';}
						?>
					</div>
				</div>
			</div>
		<?php
		$html = ob_get_clean();
		echo apply_filters( 'blossom_testimonial_widget_filter', $html, $args, $instance );
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$obj         = new BlossomThemes_Toolkit_Functions();
		$name        = ! empty( $instance['name'] ) ? $instance['name'] : '';
		$testimonial = ! empty( $instance['testimonial'] ) ? $instance['testimonial'] : '';
		$image       = ! empty( $instance['image'] ) ? $instance['image'] : '';
		$designation = ! empty( $instance['designation'] ) ? $instance['designation'] : '';
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name', 'blossomthemes-toolkit' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />            
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>"><?php esc_html_e( 'Designation', 'blossomthemes-toolkit' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'designation' ) ); ?>" type="text" value="<?php echo esc_attr( $designation ); ?>" />            
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'testimonial' ) ); ?>"><?php esc_html_e( 'Testimonial', 'blossomthemes-toolkit' ); ?></label>
			<textarea name="<?php echo esc_attr( $this->get_field_name( 'testimonial' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'testimonial' ) ); ?>"><?php print $testimonial; ?></textarea>
		</p>
		
		
		<?php $obj->bttk_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), $image, __( 'Upload Image', 'blossomthemes-toolkit' ) ); ?>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['name']        = ! empty( $new_instance['name'] ) ? sanitize_text_field( $new_instance['name'] ) : '';
		$instance['testimonial'] = ! empty( $new_instance['testimonial'] ) ? wp_kses_post( $new_instance['testimonial'] ) : '';
		$instance['image']       = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';
		$instance['designation'] = ! empty( $new_instance['designation'] ) ? esc_attr( $new_instance['designation'] ) : '';

		return $instance;
	}

}  // class BlossomThemes_Toolkit_Testimonial_Widget / class BlossomThemes_Toolkit_Testimonial_Widget
