<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Tabs_Admin_Weglot;


$tab_settings = Helper_Tabs_Admin_Weglot::get_full_tabs()[ Helper_Tabs_Admin_Weglot::SETTINGS ];

?>
<div class="error settings-error notice is-dismissible">
	<p>
		<?php
			// translators: 1 HTML Tag, 2 HTML Tag
			echo sprintf( esc_html__( 'Weglot Translate is installed but not yet configured, you need to configure Weglot here : %1$sWeglot configuration page%2$s. The configuration takes only 1 minute! ', 'weglot' ), '<a href="' . esc_url( $tab_settings['url'] ) . '">', '</a>' );
		?>
	</p>
</div>
