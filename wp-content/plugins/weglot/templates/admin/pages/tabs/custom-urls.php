<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\WeglotContext;
?>

<h2><?php esc_html_e( 'Custom URLs', 'weglot' ); ?></h2>
<hr>

<?php
if ( ! empty( $_GET['reset-all-custom-urls'] ) && 'true' === $_GET['reset-all-custom-urls'] ) { // phpcs:ignore
	// Reset all customs URLs
	$option_service_weglot = weglot_get_service( 'Option_Service_Weglot' );
	$option_service_weglot->set_option_by_key( 'custom_urls', array() );
	$options = array();
	?>
	<div class="updated notice">
		<p><?php esc_html_e( 'All customs URLs was reseted.', 'weglot' ); ?></p>
	</div>
	<?php
} else {
	$options = weglot_get_options();
}

?>

<div class="wrap">

	<?php
	if ( ! empty( $options['custom_urls'] ) ) :
		foreach ( $options['custom_urls'] as $lang => $weglot_urls ) :
			?>

			<h3><?php esc_html_e( 'Lang : ', 'weglot' ); ?><?php echo esc_html( $lang ); ?></h3>

			<div style="display:flex;">
				<div style="flex:5; margin-right:10px;">
					<?php esc_html_e( 'Base URL :', 'weglot' ); ?>
				</div>
				<div style="flex:5;">
					<?php esc_html_e( 'Custom URL :', 'weglot' ); ?>
				</div>
				<div style="flex:1;"></div>
			</div>
			<?php
			if ( ! empty( $weglot_urls ) ) :
				foreach ( $weglot_urls as $key => $value ) :
					$key_generate = sprintf( '%s-%s-%s', $lang, $key, $value );
					?>
					<div style="display:flex;" id="<?php echo esc_attr( $key_generate ); ?>">
						<div style="margin-right:10px; flex:5;">
							<input style="max-width:100%;" type="text" value="<?php echo esc_attr( $value ); ?>" class="base-url base-url-<?php echo esc_attr( $key_generate ); ?>" data-key="<?php echo esc_attr( $key_generate ); ?>" name="<?php echo esc_attr( sprintf( '%s[%s][%s][%s]', WEGLOT_SLUG, 'custom_urls', $lang, $key ) ); ?>" data-lang="<?php echo esc_attr( $lang ); ?>" />
						</div>
						<div style="flex:5;">
							<input style="max-width:100%;"  type="text" value="<?php echo esc_attr( $key ); ?>" data-key="<?php echo esc_attr( $key_generate ); ?>" class="custom-url custom-<?php echo esc_attr( $key_generate ); ?>" data-lang="<?php echo esc_attr( $lang ); ?>" />
						</div>
						<div style="align-self:flex-end; flex:1; text-align: center; height: 32px;">
							<button class="js-btn-remove" data-key="<?php echo esc_attr( $key_generate ); ?>">
								<span class="dashicons dashicons-minus"></span>
							</button>
						</div>
					</div>
					<?php
				endforeach;
				endif;
			?>

			<script type="text/javascript">
				document.addEventListener('DOMContentLoaded', function(){
					const $ = jQuery

					$('.custom-url').on('keyup', function(e){
						const key = $(this).data('key')
						const lang = $(this).data('lang')
						$('.base-url-' + key).attr('name', 'weglot-translate[custom_urls][' + lang + '][' + e.target.value + ']')
					})

					$('.js-btn-remove').on('click', function(e){
						e.preventDefault();

						$('#' + $(this).data('key')).remove()
					})
				})
			</script>

			<?php
		endforeach;

		$url_reset_all_custom_urls = add_query_arg(
			array(
				'page'                  => 'weglot-settings',
				'tab'                   => 'custom-urls',
				'reset-all-custom-urls' => 'true',
			),
			admin_url() . 'admin.php'
		);
		?>
		<br />
		<hr />
		<p><span class="dashicons dashicons-trash"></span> <a href="<?php echo esc_url( $url_reset_all_custom_urls ); ?>" class="reset-all-custom-urls" style="color: #dc3232;"><?php esc_html_e( 'Reset all Weglot custom URLs', 'weglot' ); ?></a></p>
		<hr />
	<?php elseif ( empty( $_GET['reset-all-custom-urls'] ) ) : // phpcs:ignore ?>
	<div class="error notice">
		<p><?php esc_html_e( 'No custom URL found.', 'weglot' ); ?></p>
	</div>
	<?php endif; ?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		history.replaceState(null, null, 'admin.php?page=weglot-settings&tab=custom-urls');
		$(document).on("click", ".reset-all-custom-urls", function(e) {
			e.preventDefault();
			if (confirm( "<?php esc_html_e( 'Are you sure to reset all custom URLs?', 'weglot' ); ?>")) {
				window.location.href = $(this).attr("href");
			}
		});
	});
</script>
