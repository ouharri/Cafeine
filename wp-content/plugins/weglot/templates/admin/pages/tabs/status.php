<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\WeglotContext;

?>

<h2><?php esc_html_e( 'Status', 'weglot' ); ?></h2>


<?php

$php_min_54 = true;
if ( version_compare( phpversion(), '5.4', '<' ) ) {
	$php_min_54 = false;
}

$options = weglot_get_options();

?>


<div class="wrap">
	<table class="widefat" cellspacing="0" id="status">
		<thead>
			<tr>
				<th colspan="3" data-export-label="WordPress Environment"><h2>WordPress environment</h2></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php esc_html_e( 'Home URL', 'weglot' ); ?></td>
				<td><?php echo esc_attr( home_url() ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Site URL', 'weglot' ); ?></td>
				<td><?php echo esc_attr( site_url() ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Weglot version', 'weglot' ); ?></td>
				<td><?php echo esc_attr( WEGLOT_VERSION ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'WordPress version', 'weglot' ); ?></td>
				<td><?php echo esc_attr( get_bloginfo( 'version' ) ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Is multisite', 'weglot' ); ?></td>
				<td>
					<?php echo is_multisite() ? 'Yes' : '-'; ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'WordPress Debug mode', 'weglot' ); ?></td>
				<td>
					<?php if ( defined( WP_DEBUG ) && WP_DEBUG ): //phpcs:ignore ?>
						<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
					<?php endif; ?>
				</td>
			</tr>
				<tr>
					 <td><?php esc_html_e( 'Permalink Structure', 'weglot' ); ?></td>
					 <td><?php echo esc_attr( get_option( 'permalink_structure' ) ); ?></td>
				</tr>
				<tr>
					 <td><?php esc_html_e( 'Language', 'weglot' ); ?></td>
					 <td><?php echo esc_attr( get_locale() ); ?></td>
				</tr>
		  </tbody>
	 </table>
	 <br />
	 <table class="widefat" cellspacing="0">
		  <thead>
				<tr>
					<th colspan="3" data-export-label="Server Environment"><h2><?php esc_html_e( 'Server environment', 'weglot' ); ?></h2></th>
				</tr>
		  </thead>
		  <tbody>
				<tr>
					 <td><?php esc_html_e( 'Server info', 'weglot' ); ?></td>
					 <td><?php echo ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown' //phpcs:ignore ?></td>
				</tr>
				<tr>
					 <td><?php esc_html_e( 'PHP Version', 'weglot' ); ?></td>
					 <td>
						<?php echo phpversion(); //phpcs:ignore ?>
						<?php if ( ! $php_min_54 ) : ?>
							<mark class="error">
									<span class="dashicons dashicons-warning"></span> -
								<?php echo esc_html__( 'We need a minimum PHP version : 5.4.', 'weglot' ); ?>
							</mark>
						<?php endif; ?>
					 </td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Module mod_rewrite', 'weglot' ); ?></td>
					<td>
						<?php echo $apache_mod_rewrite; //phpcs:ignore ?>
					</td>
				</tr>
		  </tbody>
	 </table>
	 <br />
	 <table class="widefat" cellspacing="0">
		  <thead>
				<tr>
					 <th colspan="3" data-export-label="Server Environment"><h2><?php esc_html_e( 'Weglot environment', 'weglot' ); ?></h2></th>
				</tr>
		  </thead>
		  <tbody>
				<tr>
					 <td><?php esc_html_e( 'Original Language', 'weglot' ); ?></td>
					 <td><?php echo esc_attr( $this->language_services->get_original_language()->getInternalCode() ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Destination Language', 'weglot' ); ?></td>
					<td>
						<?php foreach ( $this->language_services->get_destination_languages_external( true ) as $language ) : ?>
							<?php echo esc_attr( sprintf( '%s - ', $language ) ); ?>
						<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					 <td><?php esc_html_e( 'Exclude URLs', 'weglot' ); ?></td>
					 <td>
						<?php
						if ( ! empty( $options['exclude_urls'] ) ) :
							foreach ( $options['exclude_urls'] as $t => $exclude_url ) :
								if ( empty( $exclude_url['type'] ) || empty( $exclude_url['value'] ) ) {
									continue;
								}

								echo esc_html( $exclude_url['type'] . ' - ' . $exclude_url['value'] ) . '<br/>';

							endforeach;
						else :
							esc_html_e( 'Empty', 'weglot' );
						endif;
						?>
					</td>

				</tr>
				<tr>
					 <td><?php esc_html_e( 'Exclude Blocks', 'weglot' ); ?></td>
					 <td><?php echo esc_attr( implode( $options['exclude_blocks'], ' - ' ) ); ?></td>
				</tr>
		  </tbody>
	 </table>
	 <br />
	<div class="widefat">
		<h2><?php esc_html_e( 'Custom URLS', 'weglot' ); ?></h2>
		<pre><?php var_export( $options['custom_urls'] ); // phpcs:ignore ?></pre>
	</div>
</div>
