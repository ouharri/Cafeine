<?php

namespace WeglotWP\Actions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Helpers\Helper_Pages_Weglot;

/**
 * Update links on plugin page
 *
 * @since 2.0
 */
class Plugin_Links_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.0
	 * @return void
	 */
	public function hooks() {
		add_filter( 'plugin_action_links_' . WEGLOT_BNAME, array( $this, 'weglot_plugin_action_links' ) );
	}

	/**
	 * Add links
	 *
	 * @see plugin_action_links_WEGLOT_BNAME
	 *
	 * @param array $links
	 * @return array
	 */
	public function weglot_plugin_action_links( $links ) {
		$url  = get_admin_url( null, sprintf( 'admin.php?page=%s', Helper_Pages_Weglot::SETTINGS ) );
		$text = __( 'Settings', 'weglot' );

		$links[] = sprintf( '<a href="%s">%s</a>', $url, $text );
		return $links;
	}
}

