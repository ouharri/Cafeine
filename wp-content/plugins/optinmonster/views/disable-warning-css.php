<?php
/**
 * CSS to override default message for
 *
 * @since 2.2.0
 */

if ( empty( $data->labels->singular_name ) ) {
	return;
}

$message = sprintf(
	/* translators: %s - The name of the post-type being edited. */
	esc_attr__( 'OptinMonster campaigns have been disabled for this %s', 'optin-monster-api' ),
	$data->labels->singular_name
);

$message2 = sprintf(
	/* translators: %s - The name of the post-type being edited. */
	esc_attr__( 'Campaigns disabled for this %s', 'optin-monster-api' ),
	$data->labels->singular_name
);

/*
 * Double selectors added for extra specificity (instead of using !important)
 */
?>
body.om-campaigns-disabled.om-campaigns-disabled [data-type="optinmonster/campaign-selector"]:before {
	content: '<?php echo esc_attr( $message ); ?>';
}
body.om-campaigns-disabled.om-campaigns-disabled .om-format-popover .components-popover__content:after {
	content: '<?php echo esc_attr( $message2 ); ?>';
}
