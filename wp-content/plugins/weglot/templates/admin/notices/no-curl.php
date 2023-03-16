<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="error settings-error notice is-dismissible">
	<p>
		<?php
			// translators: 1 HTML Tag, 2 HTML Tag
			echo esc_html__( 'Weglot Translate: You need to activate cURL. If you need help, just ask us directly at support@weglot.com.', 'weglot' );
		?>
	</p>
</div>
