<?php
/**
 * Template part for displaying archive post's entry banner.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 4.0.0
 */

$post_type      = strval( get_post_type() );
$banner_control = 'ast-dynamic-archive-' . esc_attr( $post_type );

// If description is the only meta available in structure & its blank then no need to render banner markup.
$archive_structure = astra_get_option( $banner_control . '-structure', array( $banner_control . '-title', $banner_control . '-description' ) );
if ( 1 === count( $archive_structure ) && in_array( $banner_control . '-description', $archive_structure ) && empty( astra_get_archive_description( $post_type ) ) ) {
	return;
}

// Conditionally updating data section & class.
$attr = 'class="ast-archive-entry-banner"';
if ( is_customize_preview() ) {
	$attr = 'class="ast-archive-entry-banner ast-post-banner-highlight site-header-focus-item" data-section="' . esc_attr( $banner_control ) . '"';
}

$layout_type = astra_get_option( $banner_control . '-layout' );
$data_attrs  = 'data-post-type="' . $post_type . '" data-banner-layout="' . $layout_type . '"';

if ( 'layout-2' === $layout_type && 'custom' === astra_get_option( $banner_control . '-banner-width-type', 'fullwidth' ) ) {
	$data_attrs .= 'data-banner-width-type="custom"';
}

$background_type = astra_get_option( $banner_control . '-banner-image-type', 'none' );
if ( 'layout-2' === $layout_type && 'none' !== $background_type ) {
	$data_attrs .= 'data-banner-background-type="' . $background_type . '"';
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
