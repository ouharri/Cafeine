<?php

namespace WeglotWP\Actions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Menu_Options_Service_Weglot;
use WeglotWP\Services\Option_Service_Weglot;

/**
 *
 * @since 2.0
 *
 */
class Customize_Menu_Weglot implements Hooks_Interface_Weglot {

	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Menu_Options_Service_Weglot
	 */
	private $menu_options_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services       = weglot_get_service( 'Option_Service_Weglot' );
		$this->menu_options_services = weglot_get_service( 'Menu_Options_Service_Weglot' );
		return $this;
	}

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.0
	 * @version 2.4.0
	 * @return void
	 */
	public function hooks() {

		add_action( 'admin_head-nav-menus.php', array( $this, 'add_nav_menu_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'nav_admin_enqueue_scripts' ) );
		add_action( 'wp_update_nav_menu_item', array( $this, 'custom_wp_update_nav_menu_item' ), 10, 2 );
	}



	/**
	 * @since 2.4.0
	 * @version 3.0.0
	 * @param int $menu_id
	 * @param int $menu_item_db_id
	 * @return void
	 */
	public function custom_wp_update_nav_menu_item( $menu_id = 0, $menu_item_db_id = 0 ) {

		if ( empty( $_POST['menu-item-url'][ $menu_item_db_id ] ) || '#weglot_switcher' !== $_POST[ 'menu-item-url' ][ $menu_item_db_id ] ) { //phpcs:ignore
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		$options_menu = $this->option_services->get_option( 'menu_switcher' );
		if ( ! $options_menu ) {
			$options_menu = array();
		}

		$is_dropdown = $this->option_services->get_option_button( 'is_dropdown' );
		$options_menu[ 'menu-item-' . $menu_item_db_id ]['dropdown'] = $is_dropdown;

		if ( array_key_exists( 'menu-item-weglot-hide_current', $_POST ) ) {
			$options_menu[ 'menu-item-' . $menu_item_db_id ]['hide_current'] = empty( $_POST[ 'menu-item-weglot-hide_current' ][ $menu_item_db_id ] ) ? 0 : 1; //phpcs:ignore
		} else {
			$options_menu[ 'menu-item-' . $menu_item_db_id ]['hide_current'] = 0;
		}

		delete_transient( 'weglot_cache_cdn' );
		$this->option_services->set_option_by_key( 'menu_switcher', $options_menu );
	}

	/**
	 * @since 2.0
	 * @return void
	 */
	public function nav_admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( 'nav-menus' !== $screen->base ) {
			return;
		}

		wp_enqueue_script( 'weglot_nav_menu', WEGLOT_URL_DIST . '/nav-js.js', array( 'jquery' ), WEGLOT_VERSION );

		$data['title']        = 'Weglot switcher'; // No translate this!
		$data['options']      = $this->option_services->get_option_by_key_v3( 'menu_switcher' );
		$data['list_options'] = $this->menu_options_services->get_list_options_menu_switcher();

		wp_localize_script( 'weglot_nav_menu', 'weglot_data', $data );
	}



	/**
	 * @since 2.0
	 *
	 * @return void
	 */
	public function add_nav_menu_meta_boxes() {
		add_meta_box( 'weglot_nav_link', __( 'Weglot switcher', 'weglot' ), array( $this, 'nav_menu_links' ), 'nav-menus', 'side', 'low' );
	}

	/**
	 * Output menu links.
	 * @since 2.0
	 * @see add_meta_box weglot_nav_link
	 */
	public function nav_menu_links() {
		global $_nav_menu_placeholder, $nav_menu_selected_id; ?>
		<div id="posttype-weglot-languages" class="posttypediv">
			<div id="tabs-panel-weglot-endpoints" class="tabs-panel tabs-panel-active">
				<ul id="weglot-endpoints-checklist" class="categorychecklist form-no-clear">
					<li>
						<label class="menu-item-title">
							<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $_nav_menu_placeholder ); ?>" /> <?php esc_html_e( 'Weglot Switcher', 'weglot' ); ?>
						</label>
						<input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-type]" value="custom" />
						<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-title]" value="Weglot Switcher" /> <!-- // No translate this! -->
						<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-url]" value="#weglot_switcher" />
						<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-classes]" />
					</li>
				</ul>
			</div>
			<p class="button-controls">
				<span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'weglot' ); ?>" name="add-post-type-menu-item" id="submit-posttype-weglot-languages"><?php esc_html_e( 'Add to Menu' ); ?></button>
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}
}

