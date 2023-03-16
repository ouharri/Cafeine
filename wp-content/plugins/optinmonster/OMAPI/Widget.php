<?php
/**
 * Widget class.
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
 * Widget class.
 *
 * @since 1.0.0
 */
class OMAPI_Widget extends WP_Widget {

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
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Constructor. Sets up and creates the widget with appropriate settings.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Load the base class object.
		$this->base = OMAPI::get_instance();

		$widget_ops = apply_filters(
			'optin_monster_api_widget_ops',
			array(
				'classname'   => 'optin-monster-api',
				'description' => esc_html__( 'Place an OptinMonster campaign into a widgetized area.', 'optin-monster-api' ),
			)
		);

		$control_ops = apply_filters(
			'optin_monster_api_widget_control_ops',
			array(
				'id_base' => 'optin-monster-api',
				'height'  => 350,
				'width'   => 225,
			)
		);

		parent::__construct(
			'optin-monster-api',
			apply_filters( 'optin_monster_api_widget_name', esc_html__( 'OptinMonster', 'optin-monster-api' ) ),
			$widget_ops,
			$control_ops
		);

	}

	/**
	 * Outputs the widget within the widgetized area.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     The default widget arguments.
	 * @param array $instance The input settings for the current widget instance.
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {

		$title    = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$optin_id = isset( $instance['optin_monster_id'] ) ? $instance['optin_monster_id'] : 0;

		do_action( 'optin_monster_api_widget_before_output', $args, $instance );

		echo $args['before_widget'];

		do_action( 'optin_monster_api_widget_before_title', $args, $instance );

		// If a title exists, output it.
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		do_action( 'optin_monster_api_widget_before_optin', $args, $instance );

		// If a optin has been selected, output it.
		if ( $optin_id ) {
			// Grab the optin object. If it does not exist, return early.
			$optin = absint( $optin_id ) ? $this->base->get_optin( $optin_id ) : $this->base->get_optin_by_slug( $optin_id );
			if ( ! $optin ) {
				return;
			}

			// If in test mode but not logged in, skip over the optin.
			$test = (bool) get_post_meta( $optin->ID, '_omapi_test', true );
			if ( $test && ! is_user_logged_in() ) {
				return;
			}

			// Load the optin.
			optin_monster(
				$optin->ID,
				'id',
				array(
					'followrules' => ! empty( $instance['followrules'] ) ? 'true' : 'false',
				)
			);
		}

		do_action( 'optin_monster_api_widget_after_optin', $args, $instance );

		echo $args['after_widget'];

		do_action( 'optin_monster_api_widget_after_output', $args, $instance );

	}

	/**
	 * Sanitizes and updates the widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance The new input settings for the current widget instance.
	 * @param array $old_instance The old input settings for the current widget instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		// Set $instance to the old instance in case no new settings have been updated for a particular field.
		$instance = $old_instance;

		// Sanitize user inputs.
		$instance['title']            = trim( $new_instance['title'] );
		$instance['followrules']      = ! empty( $new_instance['followrules'] );
		$instance['optin_monster_id'] = absint( $new_instance['optin_monster_id'] );

		return apply_filters( 'optin_monster_api_widget_update_instance', $instance, $new_instance );

	}

	/**
	 * Outputs the widget form where the user can specify settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance The input settings for the current widget instance.
	 *
	 * @return void
	 */
	public function form( $instance ) {

		// Get all available optins and widget properties.
		$optins      = $this->base->get_optins();
		$title       = isset( $instance['title'] ) ? $instance['title'] : '';
		$followrules = ! empty( $instance['followrules'] );
		$optin_id    = isset( $instance['optin_monster_id'] ) ? $instance['optin_monster_id'] : false;

		do_action( 'optin_monster_api_widget_before_form', $instance );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'optin-monster-api' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%;" />
		</p>
		<?php do_action( 'optin_monster_api_widget_middle_form', $instance ); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'optin_monster_id' ) ); ?>"><?php esc_html_e( 'Campaign', 'optin-monster-api' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'optin_monster_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'optin_monster_id' ) ); ?>" style="width: 100%;">
				<?php if ( ! empty( $optins ) ) {
					foreach ( $optins as $optin ) {
						$type    = get_post_meta( $optin->ID, '_omapi_type', true );
						$enabled = (bool) get_post_meta( $optin->ID, '_omapi_enabled', true );

						// Only allow sidebar types.
						if ( 'sidebar' !== $type && 'inline' !== $type ) {
							continue;
						}

						// Display disabled or enabled selection.
						if ( $enabled ) {
							echo '<option value="' . esc_attr( $optin->ID ) . '"' . selected( $optin->ID, $optin_id, false ) . '>' . esc_html( $optin->post_title ) . '</option>';
						} else {
							echo '<option value="' . esc_attr( $optin->ID ) . '" disabled="disabled"' . selected( $optin->ID, $optin_id, false ) . '>' . esc_html( $optin->post_title ) . ' (' . esc_html__( 'Not Enabled', 'optin-monster-api' ) . ')</option>';
						}
					}
				}
				?>
			</select>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'followrules' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'followrules' ) ); ?>" type="checkbox" value="1" <?php checked( $followrules ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'followrules' ) ); ?>"><?php esc_html_e( 'Apply Advanced Output Settings?', 'optin-monster-api' ); ?></label>
		</p>
		<?php

		do_action( 'optin_monster_api_widget_after_form', $instance );

	}

}
