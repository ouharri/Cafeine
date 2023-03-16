<?php

use WeglotWP\Helpers\Helper_Tabs_Admin_Weglot;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<nav class="nav-tab-wrapper">
	<?php
	foreach ( $this->tabs as $key => $weglot_tab ) {
		$class_active = ( $this->tab_active === $key ) ? 'nav-tab-active' : '';
		if ( Helper_Tabs_Admin_Weglot::STATUS !== $key ) {
			?>
		<a
			href="<?php echo esc_url( $weglot_tab['url'] ); ?>"
			class="nav-tab <?php echo esc_attr( $class_active ); ?>">
			<?php echo esc_html( $weglot_tab['title'] ); ?>
		</a>
			<?php
		}
	}
	?>
</nav>
