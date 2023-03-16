<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$plugin_file = "gtranslate/gtranslate.php";
$deactivate_link = wp_nonce_url('plugins.php?action=deactivate&amp;plugin='.urlencode($plugin_file ).'&amp;plugin_status=all&amp;paged=1&amp;s=', 'deactivate-plugin_' . $plugin_file);

?>
<div class="error settings-error notice is-dismissible">
	<p>
		<?php
			/* translators: 1 is a plugin name, 2 is Weglot version, 3 is current php version. */
			echo sprintf( esc_html__( '%1$s %2$s.', 'weglot' ), '<strong>GTranslate</strong>', ' plugin is activated. It creates compatibility issues with Weglot, we recommend to deactivate it to use Weglot' );
		?>
	</p>

	<p>
		<a href="<?php echo $deactivate_link; //phpcs:ignore ?>" class="button">
			<?php echo esc_html__( 'Deactivate GTranslate plugin', 'weglot' ); ?>
		</a>
	</p>
</div>
