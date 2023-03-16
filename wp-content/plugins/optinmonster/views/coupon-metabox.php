<?php
/**
 * Product Education Metabox for WooCommerce Coupon Pages
 *
 * @since 2.1.0
 */

$svgpath = plugin_dir_path( OMAPI_FILE ) . '/assets/images/icons/';
?>
<div class="omapi-metabox has-slides">
	<nav class="omapi-metabox__nav">
		<ul>
			<li>
				<a href="#omapi-sticky-bar" title="Sticky Bar" class="active">
					<?php include $svgpath . 'sticky-bar.svg'; ?>
					<?php esc_html_e( 'Sticky Bar', 'optin-monster-api' ); ?>
				</a>
			</li>
			<li>
				<a href="#omapi-popup" title="Popup">
					<?php include $svgpath . 'popup.svg'; ?>
					<?php esc_html_e( 'Popup', 'optin-monster-api' ); ?>
				</a>
			</li>
			<li>
				<a href="#omapi-gamified" title="Gamified">
					<?php include $svgpath . 'gamified.svg'; ?>
					<?php esc_html_e( 'Gamified Spin to Win', 'optin-monster-api' ); ?>
				</a>
			</li>
		</ul>
	</nav>
	<div class="omapi-metabox__content">
		<div class="omapi-metabox__slides">
			<div class="omapi-metabox__slides-slide active" id="omapi-sticky-bar">
				<div class="omapi-metabox__tab omapi-metabox__tab-coupon">
					<div class="omapi-metabox__tab-icon omapi-metabox__tab-icon-coupon">
						<img src="<?php echo esc_url( $this->url . 'assets/images/metabox/sticky-bar.svg' ); ?>">
					</div>
					<div class="omapi-metabox__tab-content">
						<p><strong>
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Did you know that creating a sticky bar to promote your coupon can help you increase sales?', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_title'] ); ?>
							<?php endif; ?>
						</strong></p>
						<p class="secondary">
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'See how one store owner added $23,700 in 5 months with a coupon promoted by an OptinMonster sticky bar.', 'optin-monster-api' ); ?>
								<a href="https://optinmonster.com/freemium-software-company-unlocked-7000-anonymous-leads-using-popups/?utm_source=WordPress&utm_medium=WooCouponMetabox&utm_campaign=Plugin" class="omapi-metabox__arrow-after omapi-metabox__link-style" target="_blank" rel="noopener"><?php esc_html_e( 'View Case Study', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_message'] ); ?>
							<?php endif; ?>
						</p>
						<div class="omapi-button-wrap">
							<?php if ( $data['has_sites'] ) : ?>
								<a href="admin.php?page=optin-monster-templates&type=floating" class="button button-primary omapi-metabox__arrow-after button-large" target="_blank" rel="noopener"><?php esc_html_e( 'Create a Sticky Bar Campaign', 'optin-monster-api' ); ?></a>
								<a href="admin.php?page=optin-monster-campaigns" class="button button-secondary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'View Existing Campaigns', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php $this->output_view( 'not-connected-buttons.php' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="omapi-metabox__slides-slide" id="omapi-popup">
				<div class="omapi-metabox__tab omapi-metabox__tab-coupon">
					<div class="omapi-metabox__tab-icon omapi-metabox__tab-icon-coupon">
						<img src="<?php echo esc_url( $this->url . 'assets/images/metabox/popup.svg' ); ?>">
					</div>
					<div class="omapi-metabox__tab-content">
						<p><strong>
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Encourage purchases with a coupon popup!', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_title'] ); ?>
							<?php endif; ?>
						</strong></p>
						<p class="secondary">
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'See how this store owner increased revenue by 300% using an OptinMonster coupon popup.', 'optin-monster-api' ); ?>
								<a href="https://optinmonster.com/case-study-how-win-in-health-used-optinmonster-to-increase-revenue-by-300/?utm_source=WordPress&utm_medium=WooCouponMetabox&utm_campaign=Plugin" class="omapi-metabox__arrow-after omapi-metabox__link-style" target="_blank" rel="noopener"><?php esc_html_e( 'View Case Study', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_message'] ); ?>
							<?php endif; ?>
						</p>
						<div class="omapi-button-wrap">
							<?php if ( $data['has_sites'] ) : ?>
								<a href="admin.php?page=optin-monster-templates&type=popup" class="button button-primary omapi-metabox__arrow-after button-large" target="_blank" rel="noopener"><?php esc_html_e( 'Create a Popup Campaign', 'optin-monster-api' ); ?></a>
								<a href="admin.php?page=optin-monster-campaigns" class="button button-secondary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'View Existing Campaigns', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php $this->output_view( 'not-connected-buttons.php' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="omapi-metabox__slides-slide" id="omapi-gamified">
			<div class="omapi-metabox__tab omapi-metabox__tab-coupon">
					<div class="omapi-metabox__tab-icon omapi-metabox__tab-icon-coupon">
						<img src="<?php echo esc_url( $this->url . 'assets/images/metabox/gamified.svg' ); ?>">
					</div>
					<div class="omapi-metabox__tab-content">
						<p><strong>
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Gamified coupon wheels work! Create one for your store and watch sales grow!', 'optin-monster-api' ); ?>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_title'] ); ?>
							<?php endif; ?>
						</strong></p>
						<p class="secondary">
							<?php if ( $data['has_sites'] ) : ?>
								<?php esc_html_e( 'Learn how to create a gamified coupon wheel popup yourself with our step-by-step tutorial.', 'optin-monster-api' ); ?>
								<a href="https://optinmonster.com/coupon-wheel-campaign/?utm_source=WordPress&utm_medium=WooCouponMetabox&utm_campaign=Plugin" class="omapi-metabox__arrow-after omapi-metabox__link-style" target="_blank" rel="noopener"><?php esc_html_e( 'View the Tutorial', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $data['not_connected_message'] ); ?>
							<?php endif; ?>
						</p>
						<div class="omapi-button-wrap">
							<?php if ( $data['has_sites'] ) : ?>
								<a href="admin.php?page=optin-monster-templates&type=gamified" class="button button-primary omapi-metabox__arrow-after button-large" target="_blank" rel="noopener"><?php esc_html_e( 'Create a Gamified Campaign', 'optin-monster-api' ); ?></a>
								<a href="admin.php?page=optin-monster-campaigns" class="button button-secondary button-large" target="_blank" rel="noopener"><?php esc_html_e( 'View Existing Campaigns', 'optin-monster-api' ); ?></a>
							<?php else : ?>
								<?php $this->output_view( 'not-connected-buttons.php' ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
