<?php
/**
 * Product Education Metabox for WooCommerce Product Pages
 *
 * @since 2.1.0
 */
$svgpath = plugin_dir_path( OMAPI_FILE ) . '/assets/images/icons/';
?>
<div class="omapi-metabox has-slides">
	<nav class="omapi-metabox__nav">
		<ul>
			<li>
				<a href="#increase-conversions" title="Sticky Bar" class="active">
					<?php include $svgpath . 'increase-conversions.svg'; ?>
					<?php esc_html_e( 'Increase Conversions', 'optin-monster-api' ); ?>
				</a>
			</li>
			<li>
				<a href="#reduce-abandonment" title="Popup">
					<?php include $svgpath . 'reduce-abandonment.svg'; ?>
					<?php esc_html_e( 'Reduce Abandonment', 'optin-monster-api' ); ?>
				</a>
			</li>
			<li>
				<a href="#cross-sell" title="Gamified">
					<?php include $svgpath . 'cross-sell.svg'; ?>
					<?php esc_html_e( 'Cross Sell Popup', 'optin-monster-api' ); ?>
				</a>
			</li>
		</ul>
	</nav>
	<div class="omapi-metabox__content">
		<div class="omapi-metabox__slides">
			<div class="omapi-metabox__slides-slide active" id="increase-conversions">
				<div class="omapi-metabox__tab">
					<div class="omapi-metabox__tab-icon omapi-metabox__tab-icon-product">
						<img src="<?php echo esc_url( $this->url . 'assets/images/metabox/increase-conversions.svg' ); ?>">
					</div>
					<div class="omapi-metabox__tab-content omapi-metabox__tab-content-product">

						<p><strong>
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Increase Conversions', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_title'] ); ?>
							<?php endif; ?>
						</strong></p>
						<p class="secondary">
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Boost your store sales with one of OptinMonster\'s high-converting popup campaigns.', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_message'] ); ?>
							<?php endif; ?>
						</p>
						<div class="omapi-button-wrap">
							<?php if ( $data['has_sites'] ) : ?>
								<a href="admin.php?page=optin-monster-templates&type=popup" class="button button-primary button-large omapi-metabox__arrow-after" target="_blank" rel="noopener"><?php esc_html_e( 'Create a Campaign', 'optin-monster-api' ); ?></a>
								<a href="admin.php?page=optin-monster-campaigns" class="button button-secondary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'View Existing Campaigns', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php $this->output_view( 'not-connected-buttons.php' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if ( $data['has_sites'] ) : ?>
					<div class="omapi-metabox__tab-case-studies">
						<hr>
						<p><strong><?php esc_html_e( 'See Case Studies', 'optin-monster-api' ); ?></strong></p>
						<p class="secondary"><?php esc_html_e( 'Learn how other stores just like yours found success with OptinMonster!', 'optin-monster-api' ); ?></p>
						<ul>
							<li>
								<a href="https://optinmonster.com/marketing-handbags-case-study/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
									title="<?php esc_attr_e( 'How Urban Southern Increased Sales 400% Using OptinMonster', 'optin-monster-api' ); ?>"
									class="omapi-metabox__link-style"
									target="_blank" rel="noopener"><?php esc_html_e( 'How Urban Southern Increased Sales 400% Using OptinMonster', 'optin-monster-api' ); ?></a>
							</li>
							<li>
								<a href="https://optinmonster.com/overcoming-sales-objections-with-popups/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
									title="<?php esc_attr_e( 'How Kennedy Blue Increased Sales 50% by Overcoming Sales Objections with Popups', 'optin-monster-api' ); ?>"
									class="omapi-metabox__link-style"
									target="_blank" rel="noopener"><?php esc_html_e( 'How Kennedy Blue Increased Sales 50% by Overcoming Sales Objections with Popups', 'optin-monster-api' ); ?></a>
							</li>
						</ul>
						<a href="https://optinmonster.com/category/case-studies/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
							title="See more case studies"
							class="omapi-metabox__arrow-after omapi-metabox__link-style"
							target="_blank" rel="noopener"><?php esc_html_e( 'See more case studies', 'optin-monster-api' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<div class="omapi-metabox__slides-slide" id="reduce-abandonment">
				<div class="omapi-metabox__tab">
					<div class="omapi-metabox__tab-icon omapi-metabox__tab-icon-product">
						<img src="<?php echo esc_url( $this->url . 'assets/images/metabox/reduce-abandonment.svg' ); ?>">
					</div>
					<div class="omapi-metabox__tab-content omapi-metabox__tab-content-product">

						<p><strong>
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Reduce Abandonment', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_title'] ); ?>
							<?php endif; ?>
						</strong></p>
						<p class="secondary">
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Grow your store revenue by getting more people to complete your checkout funnel with an OptinMonster Exit Intent® campaign.', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_message'] ); ?>
							<?php endif; ?>
						</p>
						<div class="omapi-button-wrap">
							<?php if ( $data['has_sites'] ) : ?>
								<a href="admin.php?page=optin-monster-templates&type=popup" class="button button-primary button-large omapi-metabox__arrow-after" target="_blank" rel="noopener"><?php esc_html_e( 'Create a Campaign', 'optin-monster-api' ); ?></a>
								<a href="admin.php?page=optin-monster-campaigns" class="button button-secondary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'View Existing Campaigns', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php $this->output_view( 'not-connected-buttons.php' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if ( $data['has_sites'] ) : ?>
					<div class="omapi-metabox__tab-case-studies">
						<hr>
						<p><strong><?php esc_html_e( 'See Case Studies', 'optin-monster-api' ); ?></strong></p>
						<p class="secondary"><?php esc_html_e( 'Learn how other stores just like yours found success with OptinMonster!', 'optin-monster-api' ); ?></p>
						<ul>
							<li>
								<a href="https://optinmonster.com/case-study-how-shockbyte-more-than-doubled-their-sales-conversion-rate-with-exit-intent/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
									title="<?php esc_attr_e( 'How Shockbyte More Than Doubled Their Sales Conversion Rate With Exit Intent®', 'optin-monster-api' ); ?>"
									class="omapi-metabox__link-style"
									target="_blank" rel="noopener"><?php esc_html_e( 'How Shockbyte More Than Doubled Their Sales Conversion Rate With Exit Intent®', 'optin-monster-api' ); ?></a>
							</li>
							<li>
								<a href="https://optinmonster.com/wild-water-adventures-case-study/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
									title="<?php esc_attr_e( 'How Wild Water Adventures Recovered $61,000 in Sales Using OptinMonster', 'optin-monster-api' ); ?>"
									class="omapi-metabox__link-style"
									target="_blank" rel="noopener"><?php esc_attr_e( 'How Wild Water Adventures Recovered $61,000 in Sales Using OptinMonster', 'optin-monster-api' ); ?></a>
							</li>
						</ul>
						<a href="https://optinmonster.com/category/case-studies/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
							title="See more case studies"
							class="omapi-metabox__arrow-after omapi-metabox__link-style"
							target="_blank" rel="noopener"><?php esc_html_e( 'See more case studies', 'optin-monster-api' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<div class="omapi-metabox__slides-slide" id="cross-sell">
				<div class="omapi-metabox__tab">
					<div class="omapi-metabox__tab-icon omapi-metabox__tab-icon-product">
						<img src="<?php echo esc_url( $this->url . 'assets/images/metabox/cross-sell.svg' ); ?>">
					</div>
					<div class="omapi-metabox__tab-content omapi-metabox__tab-content-product">

						<p><strong>
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Cross Sell Popup', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_title'] ); ?>
							<?php endif; ?>
						</strong></p>
						<p class="secondary">
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Increase your average cart size and order value by promoting related products to your shoppers.', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_message'] ); ?>
							<?php endif; ?>
						</p>
						<div class="omapi-button-wrap">
							<?php if ( $data['has_sites'] ) : ?>
								<a href="admin.php?page=optin-monster-templates&type=popup" class="button button-primary button-large omapi-metabox__arrow-after" target="_blank" rel="noopener"><?php esc_html_e( 'Create a Campaign', 'optin-monster-api' ); ?></a>
								<a href="admin.php?page=optin-monster-campaigns" class="button button-secondary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'View Existing Campaigns', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php $this->output_view( 'not-connected-buttons.php' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if ( $data['has_sites'] ) : ?>
					<div class="omapi-metabox__tab-case-studies">
						<hr>
						<p><strong><?php esc_html_e( 'See Case Studies', 'optin-monster-api' ); ?></strong></p>
						<p class="secondary"><?php esc_html_e( 'Learn how other stores just like yours found success with OptinMonster!', 'optin-monster-api' ); ?></p>
						<ul>
							<li>
								<a href="https://optinmonster.com/how-to-create-a-woocommerce-popup-to-cross-sell/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
									title="<?php esc_attr_e( 'How to Create a WooCommerce Popup to Cross-Sell (Step-by-Step)', 'optin-monster-api' ); ?>"
									class="omapi-metabox__link-style"
									target="_blank" rel="noopener"><?php esc_html_e( 'How to Create a WooCommerce Popup to Cross-Sell (Step-by-Step)', 'optin-monster-api' ); ?></a>
							</li>
							<li>
								<a href="https://optinmonster.com/nashville-pedal-tavern-selling-gift-certificates/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
									title="<?php esc_attr_e( 'How Nashville Pedal Tavern Lifted Sales $2,300 In Just 14 Days Selling Gift Certificates', 'optin-monster-api' ); ?>"
									class="omapi-metabox__link-style"
									target="_blank" rel="noopener"><?php esc_html_e( 'How Nashville Pedal Tavern Lifted Sales $2,300 In Just 14 Days Selling Gift Certificates', 'optin-monster-api' ); ?></a>
							</li>
						</ul>
						<a href="https://optinmonster.com/category/case-studies/?utm_source=WordPress&utm_medium=WooProductMetabox&utm_campaign=Plugin"
							title="See more case studies"
							class="omapi-metabox__arrow-after omapi-metabox__link-style"
							target="_blank" rel="noopener"><?php esc_html_e( 'See more case studies', 'optin-monster-api' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
