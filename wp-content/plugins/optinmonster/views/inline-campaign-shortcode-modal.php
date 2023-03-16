<?php
if ( empty( $data['campaigns']['inline'] ) ) {
	echo '<p>';
	printf(
		wp_kses(
			/* translators: %s - OptinMonster Templates page. */
			__( 'Whoops, you haven\'t created an inline campaign yet. Want to <a href="%s">give it a go</a>?', 'optin-monster-api' ),
			array(
				'a' => array(
					'href' => array(),
				),
			)
		),
		esc_url( $data['templatesUri'] . '&type=inline' )
	);
	echo '</p>';

	return;
}

printf( '<p><label for="optin-monster-modal-select-inline-campaign">%s</label></p>', esc_html__( 'Select and display your email marketing form or smart call-to-action campaign', 'optin-monster-api' ) );
echo '<select id="optin-monster-modal-select-inline-campaign">';
foreach ( $data['campaigns']['inline'] as $slug => $name ) {
	printf( '<option value="%s">%s</option>', esc_attr( $slug ), esc_html( $name ) );
}
echo '</select>';
echo '<p class="optin-monster-modal-notice">';
printf(
	wp_kses( /* translators: %s - OptinMonster documentation URL. */
		__( 'Or <a href="%s" target="_blank" rel="noopener noreferrer">create a new inline campaign</a> to embed in this post', 'optin-monster-api' ),
		array(
			'a' => array(
				'href'   => array(),
				'rel'    => array(),
				'target' => array(),
			),
		)
	),
	esc_url( $data['templatesUri'] . '&type=inline' )
);
echo '</p>';
