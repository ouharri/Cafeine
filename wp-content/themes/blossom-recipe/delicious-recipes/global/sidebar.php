<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/delicious-recipes/global/sidebar.php.
 *
 * HOWEVER, on occasion delicious-recipes will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://wpdelicious.com/document/template-structure/
 * @package     delicious-recipes/Templates
 * @version     1.0.0
 */

$sidebar = blossom_recipe_sidebar();

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_active_sidebar( 'delicious-recipe-sidebar' ) && $sidebar ) {
	echo '<aside id="secondary" class="widget-area" role="complementary" itemscope="" itemtype="http://schema.org/WPSideBar">';
		/**
		 * Load sidebar contents.
		 */
		dynamic_sidebar( 'delicious-recipe-sidebar' );

	echo '</aside>';
}
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
