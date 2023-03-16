<?php
function bttk_pinterest_widget_enqueue_scripts() {
	wp_deregister_script( 'pinit' );
	wp_register_script( 'pinit', '//assets.pinterest.com/js/pinit.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'bttk_pinterest_widget_enqueue_scripts');

function bttk_pinterest_widget_widgets_init() {
	register_widget('Bttk_Pinterest_Widget');
}
add_action('widgets_init', 'bttk_pinterest_widget_widgets_init');

class Bttk_Pinterest_Widget extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'description' => __('Add your latest pins form Pinterest.','blossomthemes-toolkit') );
		parent::__construct( 'bttk_pinterest_widget', __('Blossom: Pinterest','blossomthemes-toolkit'), $widget_ops );
	}

	function widget($args, $instance) {
		wp_enqueue_script( 'pinit' );

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		ob_start();

		if ( !empty($instance['title']) )
			echo $args['before_title'] .  $instance['title'] . $args['after_title'];

		if ( ! empty( $instance['height'] ) && is_numeric( $instance['height'] ) )
			$height = (int) $instance['height'];
		else
			$height = 400;

		if ( ! empty( $instance['url'] ) ) {
			$pin_do = 'embedUser';
			$parsed = parse_url( $instance['url'] );
			if ( isset( $parsed['path'] ) && ! empty ( $parsed['path'] ) ) {
				$path = trim( $parsed['path'], '/' );
				$p = explode( '/', $path );
				if ( isset( $p[0] ) && 'pin' == $p[0] ) {
					$pin_do = 'embedPin';
				}
				else if ( sizeof( $p ) > 1 ) {
					$pin_do = 'embedBoard';
				}

				echo '<a data-pin-do="'.$pin_do.'" href="'.esc_url( $instance['url'] ).'/" data-pin-scale-height="' . $height . '"></a>';
			}
		}
		$html = ob_get_clean();
        echo apply_filters( 'blossom_pinterest_widget_filter', $html, $args, $instance );
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['url'] = esc_url_raw($new_instance['url']);
		$instance['height'] = (int) strip_tags( stripslashes($new_instance['height']) );
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : 'Latest Pins!';
		$url = isset( $instance['url'] ) ? $instance['url'] : '';
		$height = isset( $instance['height'] ) ? $instance['height'] : 400;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','blossomthemes-toolkit') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Pinterest URL:','blossomthemes-toolkit') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" value="<?php echo $url; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height: (px)','blossomthemes-toolkit') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $height; ?>" />
		</p>
		<?php
	}
}
