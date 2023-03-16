<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class pizza_lite_Example_1_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function pizza_lite_get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->pizza_lite_setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function pizza_lite_setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'pizza_lite_sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'pizza_lite_enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function pizza_lite_sections( $manager ) {

		// Load custom sections.
		get_template_part( 'customize-pro/example-1/section', 'pro' );

		// Register custom section types.
		$manager->register_section_type( 'pizza_lite_Example_1_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(
			new pizza_lite_Example_1_Customize_Section_Pro(
				$manager,
				'example_1',
				array(
					'title'    => esc_html__( 'Upgrade to Pro', 'pizza-lite' ),
					'pro_text' => esc_html__( 'Upgrade Now', 'pizza-lite' ),
					'pro_url'  => 'https://www.sktthemes.org/shop/pizza-ordering-wordpress-theme/',
					'priority'   => 1,
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function pizza_lite_enqueue_control_scripts() {

		wp_enqueue_script( 'pizza_lite', trailingslashit( get_template_directory_uri() ) . 'customize-pro/example-1/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'pizza_lite', trailingslashit( get_template_directory_uri() ) . 'customize-pro/example-1/customize-controls.css' );
	}
}

// Doing this customizer thang!
pizza_lite_Example_1_Customize::pizza_lite_get_instance();