<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options_available = [
	'active_wc_reload' => [
		'key'         => 'active_wc_reload',
		'label'       => __( '[WooCommerce] : Prevent reload cart', 'weglot' ),
		'description' => __( 'You should only enable this option if you have translation errors on your cart widget.', 'weglot' ),
	],
];


?>

<h3><?php esc_html_e( 'Options for support', 'weglot' ); ?> </h3>
<hr>

<table class="form-table">
	<tbody>
		<?php if ( $this->wc_active_services->is_active() ) : ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $options_available['active_wc_reload']['key'] ); ?>">
						<?php echo esc_html( $options_available['active_wc_reload']['label'] ); ?>
					</label>
				</th>
				<td class="forminp forminp-text">
					<input
						name="<?php echo esc_attr( sprintf( '%s[%s]', WEGLOT_SLUG, $options_available['active_wc_reload']['key'] ) ); ?>"
						id="<?php echo esc_attr( $options_available['active_wc_reload']['key'] ); ?>"
						type="checkbox"
						<?php checked( $this->options[ $options_available['active_wc_reload']['key'] ], 1 ); ?>
					>
					<p class="description"><?php echo esc_html( $options_available['active_wc_reload']['description'] ); ?></p>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
