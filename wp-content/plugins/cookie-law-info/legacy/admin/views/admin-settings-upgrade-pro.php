<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cookie-law-info-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
<div class="wt-free-pro-table" style="margin-top:30px"> 
  <table>
	<thead style="text-transform:uppercase;">
	  <tr>
		<th width="40%"><?php echo esc_html( __( 'Features', 'cookie-law-info' ) ); ?></th>
		<th width="30%"><?php echo esc_html( __( 'Free', 'cookie-law-info' ) ); ?></th>
		<th width="30%"><?php echo esc_html( __( 'Premium', 'cookie-law-info' ) ); ?></th>
	  </tr>
	</thead>
	<tbody>
	  <tr>
		<td><?php echo esc_html( __( 'Supported regulations:', 'cookie-law-info' ) ); ?>
				  <p class="light"> 
				  <?php
					echo esc_html( __( 'GDPR (RGPD, DSGVO), CCPA, CNIL, LGPD', 'cookie-law-info' ) );
					?>
					</p>
		</td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>

	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Show cookie notice', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td>
		<?php
		echo esc_html(
			__(
				'Display ‘Do Not Sell My
		  Personal Information’ link.',
				'cookie-law-info'
			)
		);
		?>

		  <br />
		  <p class="light"> <?php echo esc_html( __( 'In regards to CCPA compliance', 'cookie-law-info' ) ); ?></p>
		</td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Cookie notice customization', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Set up cookie notice for multilingual websites', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Pre-built templates for cookie notice', 'cookie-law-info' ) ); ?></td>
		<td><?php echo esc_html( __( 'Standard template', 'cookie-law-info' ) ); ?></td>
		<td><?php echo esc_html( __( '26 Template', 'cookie-law-info' ) ); ?></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Cookie scanner', 'cookie-law-info' ) ); ?></td>
		<td><?php echo esc_html( __( 'Up to 100 URLs', 'cookie-law-info' ) ); ?></td>
		<td><?php echo esc_html( __( 'Up to 2000 URLs', 'cookie-law-info' ) ); ?></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Auto-blocking of third-party cookies', 'cookie-law-info' ) ); ?></td>
		<td><?php echo esc_html( __( 'From plugins', 'cookie-law-info' ) ); ?></td>
		<td><?php echo esc_html( __( 'From plugins & services', 'cookie-law-info' ) ); ?><br /><a
			href=" https://www.webtoffee.com/how-to-automatically-block-cookies-using-the-gdpr-cookie-consent-plugin/"
			target="_blank"><?php echo esc_html( __( 'See list', 'cookie-law-info' ) ); ?></a></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Revisit consent widget', 'cookie-law-info' ) ); ?>

		  <br />
		  <p class="light"> <?php echo esc_html( __( 'Let users revoke their consent', 'cookie-law-info' ) ); ?></p>
		</td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Cookie-audit table', 'cookie-law-info' ) ); ?>

		  <br />
		  <p class="light"> <?php echo esc_html( __( 'Display your website’s cookie list for your site visitors using a shortcode', 'cookie-law-info' ) ); ?></p>
		</td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Cookie category based consent', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Privacy/cookie policy generator', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Record user consent for cookies', 'cookie-law-info' ) ); ?>

		  <br />
		  <p class="light"> <?php echo esc_html( __( 'Anonymized IP, cookie categories, user ID, timestamp, etc.', 'cookie-law-info' ) ); ?></p>
		</td>
		<td><span class="wt-cli-badge wt-cli-error"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Option to show cookie notice only to users from the EU', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-error"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Option to show ‘Do Not Sell My Personal Information’ link only to visitors from California', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-error"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Disable ‘Powered by CookieYes’ branding', 'cookie-law-info' ) ); ?>
		  <br />
		  <p class="light">
			<?php echo esc_html( __( 'From cookie notices', 'cookie-law-info' ) ); ?></p>
		</td>
		<td><span class="wt-cli-badge wt-cli-error"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Renew user consent', 'cookie-law-info' ) ); ?></td>
		<td><span class="wt-cli-badge wt-cli-error"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Categorize personal data collecting cookies', 'cookie-law-info' ) ); ?>
		  <br />
		  <p class="light"><?php echo esc_html( __( 'In regards to CCPA compliance', 'cookie-law-info' ) ); ?></p>
		</td>
		<td><span class="wt-cli-badge wt-cli-error"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	  <tr>
		<td><?php echo esc_html( __( 'Cookie notice live preview', 'cookie-law-info' ) ); ?>
		  <br />
		  <p class="light"><?php echo esc_html( __( 'During cookie notice customization', 'cookie-law-info' ) ); ?></p>
		</td>
		<td><span class="wt-cli-badge wt-cli-error"></span></td>
		<td><span class="wt-cli-badge wt-cli-success"></span></td>
	  </tr>
	</tbody>
  </table>
</div>
<p class="text-right" style="margin-top: 25px;"><a
	href="https://www.webtoffee.com/product/gdpr-cookie-consent/?utm_source=free_pro-comparison&utm_medium=gdpr_basic&utm_campaign=GDPR&utm_content=<?php echo esc_attr( CLI_VERSION ); ?>"
	class="wt-primary-btn crown-icon" target="_blank" style="text-transform:uppercase;margin-bottom:0px"><?php echo esc_html( __( 'Upgrade to premium', 'cookie-law-info' ) ); ?></a></p>

<!------ stop copying -- (frame 10 : table)-------->
</div>
