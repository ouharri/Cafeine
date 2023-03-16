<?php
/**
 * Icon Text Widget
 *
 * @package BlossomThemes_Toolkit
 */

// register BlossomThemes_Toolkit_Icon_Text_Widget widget.
function bttk_register_icon_text_widget() {
	register_widget( 'BlossomThemes_Toolkit_Icon_Text_Widget' );
}
add_action( 'widgets_init', 'bttk_register_icon_text_widget' );

/**
 * Adds BlossomThemes_Toolkit_Icon_Text_Widget widget.
 */
class BlossomThemes_Toolkit_Icon_Text_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'bttk_icon_text_widget', // Base ID
			__( 'Blossom: Icon Text', 'blossomthemes-toolkit' ), // Name
			array( 'description' => __( 'An Icon Text Widget.', 'blossomthemes-toolkit' ) ) // Args
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

		$obj       = new BlossomThemes_Toolkit_Functions();
		$title     = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$content   = ! empty( $instance['content'] ) ? $instance['content'] : '';
		$icon      = ! empty( $instance['icon'] ) ? $instance['icon'] : '';
		$image     = ! empty( $instance['image'] ) ? $instance['image'] : '';
		$link      = ! empty( $instance['link'] ) ? $instance['link'] : '';
		$more_text = ! empty( $instance['more_text'] ) ? $instance['more_text'] : '';
		$target    = 'rel="noopener noexternal" target="_blank"';
		if ( isset( $instance['target'] ) && $instance['target'] != '' ) {
			$target = 'target="_self"';
		}

		if ( $image ) {
			/** Added to work for demo content compatible */
			$attachment_id = $image;
			if ( ! filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
				$attachment_id = $obj->bttk_get_attachment_id( $image );
			}
			$icon_img_size = apply_filters( 'bttk_icon_img_size', 'thumbnail' );
		}

		echo $args['before_widget'];
		ob_start();
		?>
			<div class="rtc-itw-holder">
				<div class="rtc-itw-inner-holder">
					<div class="text-holder">
					<?php
					if ( $title ) {
						echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
					}
					if ( $content ) {
						echo '<div class="content">' . wpautop( wp_kses_post( $content ) ) . '</div>';
					}
					if ( isset( $link ) && $link != '' && isset( $more_text ) && $more_text != '' ) {
						echo '<a ' . $target . ' class="btn-readmore" href="' . esc_url( $link ) . '">' . esc_attr( $more_text ) . '</a>';
					}
					?>
												  
					</div>
					<?php if ( $image ) { ?>
						<div class="icon-holder">
							<?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, array( 'alt' => esc_attr( $title ) ) ); ?>
						</div>
					<?php } elseif ( $icon ) { ?>
						<div class="icon-holder">
							<span class="<?php echo esc_attr( $icon ); ?>"></span>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php
		$html = ob_get_clean();
		echo apply_filters( 'blossom_icontext_widget_filter', $html, $args, $instance );
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

		$obj       = new BlossomThemes_Toolkit_Functions();
		$title     = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$content   = ! empty( $instance['content'] ) ? $instance['content'] : '';
		$icon      = ! empty( $instance['icon'] ) ? $instance['icon'] : '';
		$image     = ! empty( $instance['image'] ) ? $instance['image'] : '';
		$link      = ! empty( $instance['link'] ) ? $instance['link'] : '';
		$more_text = ! empty( $instance['more_text'] ) ? $instance['more_text'] : '';
		$target    = ! empty( $instance['target'] ) ? $instance['target'] : '';
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blossomthemes-toolkit' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php esc_html_e( 'Description', 'blossomthemes-toolkit' ); ?></label>
			<textarea name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php print $content; ?></textarea>
		</p>

		<?php $obj->bttk_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), $image, __( 'Upload Image', 'blossomthemes-toolkit' ) ); ?>
		
		<p><strong><?php esc_html_e( 'or', 'blossomthemes-toolkit' ); ?></strong></p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icons', 'blossomthemes-toolkit' ); ?></label><br />
			<?php
			$class = '';
			if ( isset( $icon ) && $icon != '' ) {
				$class = 'yes';
			}
			?>
			<span class="icon-receiver <?php echo $class; ?>"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
			<input class="hidden-icon-input" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" value="<?php echo esc_attr( $icon ); ?>" />            
		</p>
		
		<input class="search-itw-icons" placeholder="<?php _e( 'search icons here...', 'blossomthemes-toolkit' ); ?>" />        
		
		<?php $obj->bttk_get_icon_list(); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked( $target, 1 ); ?> /><?php esc_html_e( 'Open in Same Tab', 'blossomthemes-toolkit' ); ?> </label>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>"><?php esc_html_e( 'Read More Label', 'blossomthemes-toolkit' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $more_text ); ?>" />            
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Read More Link', 'blossomthemes-toolkit' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_url( $link ); ?>" />            
		</p>
						
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

		$instance['title']     = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['content']   = ! empty( $new_instance['content'] ) ? wp_kses_post( $new_instance['content'] ) : '';
		$instance['image']     = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';
		$instance['icon']      = ! empty( $new_instance['icon'] ) ? esc_attr( $new_instance['icon'] ) : '';
		$instance['link']      = ! empty( $new_instance['link'] ) ? esc_url( $new_instance['link'] ) : '';
		$instance['more_text'] = ! empty( $new_instance['more_text'] ) ? esc_attr( $new_instance['more_text'] ) : '';
		$instance['target']    = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';

		return $instance;
	}

}  // class BlossomThemes_Toolkit_Icon_Text_Widget / class BlossomThemes_Toolkit_Icon_Text_Widget
