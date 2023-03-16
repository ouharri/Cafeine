<?php
/**
 * Template for displaying search forms
 *
 * @package Barista Coffee Shop
 */

?>

<form method="get" class="search-from" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="form-group search-div mb-0">
    	<input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'barista-coffee-shop' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php esc_attr_x( 'Search for:', 'label', 'barista-coffee-shop' ); ?>">
        <input type="submit" class="search-submit btn btn-primary" value="<?php echo esc_attr_x( 'Search', 'submit button', 'barista-coffee-shop' ); ?>">
    </div>
</form>