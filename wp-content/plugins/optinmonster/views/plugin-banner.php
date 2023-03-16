<script src="//use.typekit.net/pef1xgi.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
<div class="omapi-plugin-header" id="omwp-plugin-banner">
	<style>.omapi-svg-logo {width: 164px;margin-right: 13px;}</style>
	<div class="omapi-plugin-banner">
		<div class="omapi-plugin-banner__wrapper">
			<div class="omapi-plugin-banner__logo">
				<img src="<?php echo esc_url( $data['logo'] ); ?>" alt="OptinMonster Logo">
				<span class="omapi-plugin-banner__page">
					<?php echo get_admin_page_title(); ?>
				</span>
				<?php if ( $this->beta_version() ) : ?>
					&nbsp;&mdash;&nbsp;<strong>Beta Version: <?php echo esc_html( $this->beta_version() ); ?></strong>
				<?php endif; ?>
			</div>
			<ul class="omapi-plugin-banner__icons">
				<li>
					<a class="static-menu-item" target="_blank" rel="noopener" href="https://optinmonster.com/docs/?utm_source=WordPress&utm_medium=BannerHelpButton&utm_campaign=Plugin">
						<img src="<?php echo esc_url( $data['help'] ); ?>" alt="Need Help?">
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
