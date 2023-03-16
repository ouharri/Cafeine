<?php
/**
 * Class SktThemes\FullWidthTemplates\Elementor
 *
 * @package     SktThemes\FullWidthTemplates\Elementor
 * @copyright   Copyright (c) 2017, Andrei Lupu
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace SktThemes\FullWidthTemplates;

class None {
	/**
	 * @var Elementor
	 */
	public static $instance = null;

	protected function init(){
		
		// for the gb template
		add_action( 'fwpt_gb_content', array( $this, 'render_content' ) );
		add_action( 'fwpt_gb_before_content', array( $this, 'render_std_before_content' ) );
		add_action( 'fwpt_gb_after_content', array( $this, 'render_std_after_content' ) );	
		
		// for the standard template
		add_action( 'fwpt_std_before_content', array( $this, 'render_std_before_content' ) );
		add_action( 'fwpt_std_after_content', array( $this, 'render_std_after_content' ) );

	}

	/**
	 * Display the WordPress loop
	 */
	public function render_content() {
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
	}


	/**
	 * Display the header of the standard template
	 */
	public function render_std_before_content() {
		get_header();
	}

	/**
	 * Display the footer of the standard template
	 */
	public function render_std_after_content() {
		get_footer();
	}


	/**
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Elementor
	 */
	public static function instance() {
		if ( null ===  self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html( 'Cheatin&#8217; huh?'), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html( 'Cheatin&#8217; huh?'), '1.0.0' );
	}
}