<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Barista Coffee Shop
 */

if ( ! is_active_sidebar( 'sidebar' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area col-lg-3 col-md-4">
	<div class="sidebar">
		<?php dynamic_sidebar( 'sidebar' ); ?>
	</div>
</aside>