<?php
/**
 * Marketing Card for WooCommerce Marketing Page
 *
 * @since 2.2.0
 */
?>
<div id="components-card-om" class="components-card-om" style="display:none;">
	<?php $this->output_min_css( 'woocommerce-marketing-card-css.php' ); ?>
	<div class="components-card-om-header">
		<p><?php esc_html_e( 'Increase Your Store Sales Conversion', 'optin-monster-api' ); ?></p>
	</div>
	<div class="components-card-om-body">
		<div class="components-card-om-body-icon">
			<?php require dirname( $this->file ) . '/assets/css/images/icons/archie-color-icon.svg'; ?>
		</div>
		<div class="components-card-om-body-text-wrap">
			<div class="components-card-om-body-text">
				<h4>OptinMonster</h4>
				<p><?php esc_html_e( 'Grow your business with OptinMonster! Use this plugin to help sell more of your product.', 'optin-monster-api' ); ?></p>
			</div>
		</div>
		<div class="components-card-om-body-button">
			<a class="button button-primary" href="admin.php?page=optin-monster-templates&type=popup" title="<?php esc_attr_e( 'Create a Campaign', 'optin-monster-api' ); ?>"><?php esc_html_e( 'Create a Campaign', 'optin-monster-api' ); ?></a>
		</div>
	</div>
</div>
