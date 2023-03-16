<?php
$monsterlinks_upsell = empty( $data['canMonsterlink'] );
if ( $monsterlinks_upsell ) {
	echo '<p id="om-monsterlink-upgrade">';
	printf(
		wp_kses(
			/* translators: %s - OptinMonster Templates page. */
			__( 'Unlock access to the OptinMonster click-to-load feature called <a href="%s" target="_blank" rel="noopener">MonsterLinks</a> by upgrading your subscription.', 'optin-monster-api' ),
			array(
				'a' => array(
					'href'   => array(),
					'target' => array(
						'_blank',
					),
					'rel'    => array(
						'noopener',
					),
				),
			)
		),
		esc_url( $data['upgradeUri'] )
	);
	echo '</p>';
	echo '<div style="display:none;">';
}

if ( ! empty( $data['campaigns']['other'] ) ) {
	printf( '<p><label for="optin-monster-modal-select-campaign">%s</label></p>', esc_html__( 'Select a Click to Load Campaign to link.', 'optin-monster-api' ) );
	echo '<select id="optin-monster-modal-select-campaign">';
	foreach ( $data['campaigns']['other'] as $slug => $name ) {
		printf( '<option value="%s">%s</option>', esc_attr( $slug ), esc_html( $name ) );
	}
	echo '</select>';
	echo '<p class="optin-monster-modal-notice">';
	printf(
		wp_kses( /* translators: %s - OptinMonster documentation URL. */
			__( 'Or <a href="%s" target="_blank" rel="noopener noreferrer">create a new Click to Load Campaign</a>.', 'optin-monster-api' ),
			array(
				'a' => array(
					'href'   => array(),
					'rel'    => array(),
					'target' => array(),
				),
			)
		),
		esc_url( $data['templatesUri'] . '&type=popup' )
	);
	echo '</p>';
} else {
	echo '<p>';
	printf(
		wp_kses(
			/* translators: %s - OptinMonster Templates page. */
			__( 'Whoops, you haven\'t created a popup campaign yet. Want to <a href="%s">give it a go</a>?', 'optin-monster-api' ),
			array(
				'a' => array(
					'href' => array(),
				),
			)
		),
		esc_url( $data['templatesUri'] . '&type=popup' )
	);
	echo '</p>';
}

if ( $monsterlinks_upsell ) {
	echo '</div>';
}
