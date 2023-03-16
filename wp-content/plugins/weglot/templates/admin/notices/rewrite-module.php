<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="error settings-error notice is-dismissible">
	<p>
		<?php
			// translators: 1 HTML Tag, 2 HTML Tag
			echo sprintf( esc_html__( 'Weglot Translate: You need to activate the mod_rewrite module. You can find more information here : %1$sUsing Permalinks%2$s. If you need help, just ask us directly at support@weglot.com.', 'weglot' ), '<a target="_blank" href="https://codex.wordpress.org/Using_Permalinks">', '</a>' );
		?>
	</p>
</div>
