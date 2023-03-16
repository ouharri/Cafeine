<?php
wp_enqueue_script( 'om-plugin-install-js', $this->url . 'assets/dist/js/plugininstall.min.js', array( 'jquery' ), $this->asset_version() );
OMAPI_Utils::add_inline_script(
	'om-plugin-install-js',
	'OMAPI_Plugins',
	array(
		'restUrl'     => rest_url(),
		'pluginData'  => $data['plugin'],
		'actionNonce' => wp_create_nonce( 'om_plugin_action_nonce' ),
		'restNonce'   => wp_create_nonce( 'wp_rest' ),
	)
);
?>
<style>
	#om-plugin-alerts {
		display: none;
		margin-bottom: 15px !important;
		padding: 10px !important;
	}
	#om-plugin-alerts p {
		margin-bottom: 10px !important;
	}
</style>
<div class="notice notice-error" id="om-plugin-alerts"></div>
<form class="install-plugin-form" action="<?php echo esc_url( $data['plugin_search_url'] ); ?>" method="post" >
	<?php if ( ! empty( $data['plugin']['installed'] ) ) : ?>
		<button type="submit" id="activateButton" class="button button-primary button-activate" data-actiontext="<?php esc_attr_e( 'Activating...', 'optin-monster-api' ); ?>">
			<?php echo esc_html( $data['button_activate'] ); ?>
		</button>
	<?php else : ?>
		<button type="submit" id="installButton" class="button button-primary button-install" data-actiontext="<?php esc_attr_e( 'Installing...', 'optin-monster-api' ); ?>">
			<?php echo esc_html( $data['button_install'] ); ?>
		</button>
	<?php endif; ?>
</form>
