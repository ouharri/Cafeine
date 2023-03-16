<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<?php
	if ( $scan_results ) :
		?>
		<div class="wt-cli-cookie-scan-results-container">
			<div class="wt-cli-scan-result-header">
				<div class="wt-cli-row wt-cli-align-center">
					<div class="wt-cli-col-6">
						<h2>
							<?php
							echo esc_html__( 'Cookie scan result for your website', 'cookie-law-info' );
							?>
						</h2>
					</div>
					<div class="wt-cli-col-6">
						<div class="wt-cli-scan-result-actions">
						<?php echo wp_kses_post( $this->get_scan_btn( true ) ); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="wt-cli-scan-results-body">
			<div class="wt-cli-scan-result-summary">
				<ul class="wt-cli-scan-result-summary-list">
					<li>
						<b><?php echo esc_html__( 'Total URLs', 'cookie-law-info' ); ?></b>: <span class="wt-cli-cookie-scan-count"> <?php echo esc_html( $scan_results['total_urls'] ); ?></span><br />
					</li>
					<li>
						<b><?php echo esc_html__( 'Total cookies', 'cookie-law-info' ); ?></b>: <span class="wt-cli-cookie-scan-count"> <?php echo esc_html( $scan_results['total_cookies'] ); ?></span><br />
					</li>
				</ul>
			</div>
			<?php if ( $scan_results['total_cookies'] > 0 ) : ?>
			<div class="wt-cli-scan-result-import-section">
				<p>
				<?php

					echo sprintf(
						wp_kses(
							__( 'Clicking “Add to cookie list” will import the discovered cookies to the <a href="%s" target="_blank">Cookie List</a> and thus display them in the cookie declaration section of your consent banner.', 'cookie-law-info' ),
							array(
								'a' => array(
									'href'   => array(),
									'target' => array(),
								),
							)
						),
						esc_url( $cookie_list_page )
					);
				?>
					</p>
				<a class="button-primary cli_import" data-scan-id="<?php echo esc_attr( $scan_results['scan_id'] ); ?>" style="margin-left:5px;"><?php echo esc_html__( 'Add to cookie list', 'cookie-law-info' ); ?></a>
			</div>
			<?php endif; ?>
			<?php
			$count   = 1;
			$cookies = isset( $scan_results['cookies'] ) ? $scan_results['cookies'] : array();
			?>
			<div class="wt-cli-scan-result-cookie-container">
				<div class="wt-cli-row">
					<div class="wt-cli-col-12">
						<div class="wt-cli-scan-result-cookies">
							<table class="wt-cli-table">
								<thead>
									<th style="width:6%;"><?php echo esc_html__( 'Sl.No:', 'cookie-law-info' ); ?></th>
									<th><?php echo esc_html__( 'Cookie Name', 'cookie-law-info' ); ?></th>
									<th style="width:15%;" ><?php echo esc_html__( 'Duration', 'cookie-law-info' ); ?></th>
									<th style="width:15%;" ><?php echo esc_html__( 'Category', 'cookie-law-info' ); ?></th>
									<th style="width:40%;" ><?php echo esc_html__( 'Description', 'cookie-law-info' ); ?></th>
								</thead>
								<tbody>
									<?php if ( isset( $cookies ) && is_array( $cookies ) && count( $cookies ) > 0 ) : ?>
										<?php foreach ( $cookies as $cookie ) : ?>
											<tr>
												<td><?php echo esc_html( $count ); ?></td>
												<td><?php echo esc_html( $cookie['id'] ); ?></td>
												<td><?php echo esc_html( $cookie['expiry'] ); ?></td>
												<td><?php echo esc_html( $cookie['category'] ); ?></td>
												<td><?php echo wp_kses_post( wp_unslash( $cookie['description'] ) ); ?></td>
											</tr>
											<?php
											$count ++;
											endforeach;
										?>
									<?php else : ?>
										<tr><td class="colspanchange" colspan="5" style="text-align:center"><?php echo esc_html__( 'Your cookie list is empty', 'cookie-law-info' ); ?></td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	<?php else : ?>
	<?php endif; ?>
