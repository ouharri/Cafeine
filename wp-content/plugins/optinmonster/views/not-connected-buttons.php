<?php
/**
 * Buttons used when site not connected.
 *
 * @since 2.3.0
 */
?>
<a href="<?php echo esc_url( OMAPI_Urls::wizard() ); ?>" class="button button-primary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'Create a Free Account', 'optin-monster-api' ); ?></a>
<a href="<?php echo esc_url( OMAPI_Urls::settings() ); ?>" class="button button-secondary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'Connect an Existing Account', 'optin-monster-api' ); ?></a>
