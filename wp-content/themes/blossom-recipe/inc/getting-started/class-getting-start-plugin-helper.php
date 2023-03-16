<?php
/**
 * Plugin install helper.
 *
 * @package Blossom_Recipe
 */

/**
 * Class Blossom_Recipe_Getting_Started_Page_Plugin_Helper
 *
 * @package Blossom_Recipe_Getting_Started_Page
 */
class Blossom_Recipe_Getting_Started_Page_Plugin_Helper {
	/**
	 * Instance of class.
	 *
	 * @var bool $instance instance variable.
	 */
	private static $instance;

	/**
	 * Check if instance already exists.
	 *
	 * @return Blossom_Recipe_Getting_Started_Page_Plugin_Helper;
	 */
	public static function instance(){
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Blossom_Recipe_Getting_Started_Page_Plugin_Helper ) ) {
			self::$instance = new Blossom_Recipe_Getting_Started_Page_Plugin_Helper();
		}                            
		return self::$instance;
	}

	/**
	 * Get plugin path based on plugin slug.
	 *
	 * @param string $slug - plugin slug.
	 *
	 * @return string
	 */
	public static function get_plugin_path( $slug ){
		switch ( $slug ) {
			case 'contact-form-7':
				return $slug . '/' . 'wp-' . $slug . '.php';
				break;
			default:
				return $slug . '/' . $slug . '.php';
		}
	}

	/**
	 * Generate action button html.
	 *
	 * @param string $slug     plugin slug.
	 * @param array  $settings button settings.
	 *
	 * @return string
	 */
	public function get_button_html( $slug, $settings = array() ) {
		$button   = '';
		$redirect = '';
		if ( ! empty( $settings ) && array_key_exists( 'redirect', $settings ) ) {
			$redirect = $settings['redirect'];
		}
		$state = $this->check_plugin_state( $slug );
		if ( empty( $slug ) ) {
			return '';
		}

		$additional = '';

		if ( $state === 'deactivate' ) {
			$additional = ' action_button active';
		}

		$button .= '<div class=" plugin-card-' . esc_attr( $slug ) . esc_attr( $additional ) . '" style="padding: 8px 0 5px;">';

		$plugin_link_suffix = self::get_plugin_path( $slug );

		$nonce = add_query_arg(
			array(
				'action'        => 'activate',
				'plugin'        => rawurlencode( $plugin_link_suffix ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_link_suffix ),
			),
			network_admin_url( 'plugins.php' )
		);
		switch ( $state ) {
			case 'install':
				$button .= '<a data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="install-now button  " href="' . esc_url( $nonce ) . '" data-name="' . esc_attr( $slug ) . '" aria-label="' . esc_attr__( 'Install ', 'blossom-recipe' ) . esc_attr( $slug ) . '">' . __( 'Install and activate', 'blossom-recipe' ) . '</a>';
				break;

			case 'activate':
				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="activate-now button button-primary" href="' . esc_url( $nonce ) . '" aria-label="' . esc_attr__( 'Activate ', 'blossom-recipe' ) . esc_attr( $slug ) . '">' . esc_html__( 'Activate', 'blossom-recipe' ) . '</a>';
				break;

			case 'deactivate':
				$nonce = add_query_arg(
					array(
						'action'        => 'deactivate',
						'plugin'        => rawurlencode( $plugin_link_suffix ),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $plugin_link_suffix ),
					),
					network_admin_url( 'plugins.php' )
				);

				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="deactivate-now button" href="' . esc_url( $nonce ) . '" data-name="' . esc_attr( $slug ) . '" aria-label="' . esc_attr__( 'Deactivate ', 'blossom-recipe' ) . esc_attr( $slug ) . '">' . esc_html__( 'Deactivate', 'blossom-recipe' ) . '</a>';
				break;

			case 'enable_cpt':
				$url     = admin_url( 'admin.php?page=jetpack#/settings' );
				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Activate', 'blossom-recipe' ) . ' ' . esc_html__( 'Jetpack Portfolio', 'blossom-recipe' ) . '</a>';
				break;
		}// End switch().
		$button .= '</div>';

		return $button;
	}

	/**
	 * Check plugin state.
	 *
	 * @param string $slug - plugin slug.
	 *
	 * @return bool
	 */
	public function check_plugin_state( $slug ){

		$plugin_link_suffix = self::get_plugin_path( $slug );

		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $plugin_link_suffix ) ) {
			$needs = is_plugin_active( $plugin_link_suffix ) ? 'deactivate' : 'activate';
			if ( $needs === 'deactivate' && ! post_type_exists( 'portfolio' ) && $slug === 'jetpack' ) {
				return 'enable_cpt';
			}
			return $needs;
		} else {
			return 'install';
		}
	}
}
