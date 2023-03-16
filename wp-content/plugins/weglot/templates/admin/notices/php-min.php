<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="error settings-error notice is-dismissible">
	<p>
		<?php
			/* translators: 1 is a plugin name, 2 is Weglot version, 3 is current php version. */
			echo sprintf( esc_html__( '%1$s  requires PHP %2$s minimum, your website is actually running version %3$s.', 'weglot' ), '<strong>Weglot translate</strong>', '<code>' . esc_attr( WEGLOT_PHP_MIN ) . '</code>', '<code>' . esc_attr( phpversion() ) . '</code>' );
		?>
	</p>
	<p>
		<?php
		echo esc_html__( 'If you are not able to upgrade, you can rollback to the previous version by using the button below.', 'weglot' );
		?>
	</p>
	<p>
		<a href="<?php echo wp_nonce_url( admin_url( 'admin-post.php?action=weglot_rollback' ), 'weglot_rollback' ); //phpcs:ignore ?>" class="button">
			<?php echo esc_html__( 'Re-install version 1.13.1', 'weglot' ); ?>
		</a>
	</p>
</div>
