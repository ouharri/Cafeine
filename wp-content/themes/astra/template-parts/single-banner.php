<?php
/**
 * Template part for displaying single post's entry banner.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 4.0.0
 */

$post_type      = strval( get_post_type() );
$banner_control = 'ast-dynamic-single-' . esc_attr( $post_type );

// If banner will be with empty markup then better to skip it.
if ( false !== strpos( astra_entry_header_class( false ), 'ast-header-without-markup' ) ) {
	return;
}

// Conditionally updating data section & class.
$attr = 'class="ast-single-entry-banner"';
if ( is_customize_preview() ) {
	$attr = 'class="ast-single-entry-banner ast-post-banner-highlight site-header-focus-item" data-section="' . esc_attr( $banner_control ) . '"';
}

$data_attrs = 'data-post-type="' . $post_type . '"';

$layout_type = astra_get_option( $banner_control . '-layout', 'layout-1' );
$data_attrs .= 'data-banner-layout="' . $layout_type . '"';

if ( 'layout-2' === $layout_type && 'custom' === astra_get_option( $banner_control . '-banner-width-type', 'fullwidth' ) ) {
	$data_attrs .= 'data-banner-width-type="custom"';
}

$featured_background = astra_get_option( $banner_control . '-featured-as-background', false );
if ( 'layout-2' === $layout_type && $featured_background ) {
	$data_attrs .= 'data-banner-background-type="featured"';
}

?>

<section <?php echo $attr . ' ' . $data_attrs; ?>>
	<div class="ast-container">
		<?php
		if ( is_customize_preview() ) {
			Astra_Builder_UI_Controller::render_banner_customizer_edit_button();
		}
			astra_banner_elements_order();
		?>
	</div>
</section>
