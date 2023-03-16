<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Tabs_Admin_Weglot;

$url_form = wp_nonce_url(
	add_query_arg(
		[
			'action' => 'weglot_save_settings',
			'tab'    => $this->tab_active,
		],
		admin_url( 'admin-post.php' )
	),
	'weglot_save_settings'
);

?>

<div id="wrap-weglot">
	<div class="wrap">
		<form method="post" id="mainform" action="<?php echo esc_url( $url_form ); ?>">
			<?php


			switch ( $this->tab_active ) {
				case Helper_Tabs_Admin_Weglot::SETTINGS:
				default:
					include_once WEGLOT_TEMPLATES_ADMIN_PAGES . '/tabs/settings.php';
					if ( ! $this->options['has_first_settings'] ) {
						include_once WEGLOT_TEMPLATES_ADMIN_PAGES . '/tabs/appearance.php';
						include_once WEGLOT_TEMPLATES_ADMIN_PAGES . '/tabs/advanced.php';
					}

					break;
				case Helper_Tabs_Admin_Weglot::STATUS:
					include_once WEGLOT_TEMPLATES_ADMIN_PAGES . '/tabs/status.php';
					break;
				case Helper_Tabs_Admin_Weglot::SUPPORT:
					include_once WEGLOT_TEMPLATES_ADMIN_PAGES . '/tabs/support.php';
					break;
				case Helper_Tabs_Admin_Weglot::CUSTOM_URLS:
					include_once WEGLOT_TEMPLATES_ADMIN_PAGES . '/tabs/custom-urls.php';
					break;
			}

			if ( ! in_array( $this->tab_active, [ Helper_Tabs_Admin_Weglot::STATUS ], true ) ) {
				submit_button();
			}
			?>
			<input type="hidden" name="tab" value="<?php echo esc_attr( $this->tab_active ); ?>">
		</form>
		<?php if ( ! $this->options['has_first_settings'] ) : ?>
		<hr>
		<span class="dashicons dashicons-heart"></span>&nbsp;
		<a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/weglot?rate=5#postform">
		<?php esc_html_e( 'Love Weglot? Give us 5 stars on WordPress.org', 'weglot' ); ?>
		</a>
		<p class="weglot-five-stars">
			<?php
				// translators: 1 HTML Tag, 2 HTML Tag
				echo sprintf( esc_html__( 'If you need any help, you can contact us via email us at %1$ssupport@weglot.com%2$s.', 'weglot' ), '<a href="mailto:support@weglot.com?subject=Need help from WP plugin admin" target="_blank">', '</a>' );
				echo  ' ';
				// translators: 1 HTML Tag, 2 HTML Tag
				echo sprintf( esc_html__( 'You can also check our %1$sFAQ%2$s.', 'weglot' ), '<a href="http://support.weglot.com/" target="_blank">', '</a>' ); ?>
		</p>
		<hr>
        <?php endif; ?>
	</div>
	<?php
	if ( ! $this->options['has_first_settings'] ) :
		?>
		<div class="weglot-infobox">
			<h3><?php esc_html_e( 'Where are my translations?', 'weglot' ); ?></h3>
			<div>
				<p><?php esc_html_e( 'You can find all your translations in your Weglot account:', 'weglot' ); ?></p>
				<a href="<?php echo esc_url( 'https://dashboard.weglot.com/translations/', 'weglot' ); ?>" target="_blank" class="weglot-editbtn">
					<?php esc_html_e( 'Edit my translations', 'weglot' ); ?>
				</a>
				<p><span class="wp-menu-image dashicons-before dashicons-welcome-comments"></span><?php esc_html_e( 'When you edit your translations in Weglot, remember to clear your cache (if you have a cache plugin) to make sure you see the latest version of your page)', 'weglot' ); ?></p>


			</div>
		</div>
		<?php
	endif;
	?>
</div>

