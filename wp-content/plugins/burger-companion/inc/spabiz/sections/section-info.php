<?php  
if ( ! function_exists( 'burger_spabiz_info' ) ) :
	function burger_spabiz_info() {
	$hs_info 						= get_theme_mod('hs_info','1');	
	$info_contents 				= get_theme_mod('info_contents',spabiz_get_info_default());
	if($hs_info=='1'):
?>	
<section id="info-home" class="category-section ptb-80 wow fadeInUp info-home">
	<div class="container">
		<div class="category-main owl-carousel">
			<?php
			if ( ! empty( $info_contents ) ) {
				$info_contents = json_decode( $info_contents );
				foreach ( $info_contents as $info_tem ) {
					$title = ! empty( $info_tem->title ) ? apply_filters( 'spabiz_translate_single_string', $info_tem->title, 'Info section' ) : '';
					$link = ! empty( $info_tem->link ) ? apply_filters( 'spabiz_translate_single_string', $info_tem->link, 'Info section' ) : '';
					$icon = ! empty( $info_tem->icon_value ) ? apply_filters( 'spabiz_translate_single_string', $info_tem->icon_value, 'Info section' ) : '';
			?>
				<div class="category-item">
					<?php if ( ! empty( $icon ) ) : ?>
						<div class="icon"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
					<?php endif; ?>
					
					<?php if ( ! empty( $title ) ) : ?>
						<h2><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h2>
					<?php endif; ?>
				</div>
			<?php } } ?>
		</div>
	</div>
</section>
<?php	
endif;	}
endif;
if ( function_exists( 'burger_spabiz_info' ) ) {
$section_priority = apply_filters( 'spabiz_section_priority', 12, 'burger_spabiz_info' );
add_action( 'spabiz_sections', 'burger_spabiz_info', absint( $section_priority ) );
}