<?php 
	if ( ! function_exists( 'burger_storebiz_info' ) ) :
	function burger_storebiz_info() {
	$info_content = get_theme_mod('info_content',storebiz_get_info_default());
?> 
 <div id="info-section" class="info-section st-py-default">
    <div class="container">
        <div class="row">
			<?php
				if ( ! empty( $info_content ) ) {
				$info_content = json_decode( $info_content );
				foreach ( $info_content as $info_item ) {
					$title = ! empty( $info_item->title ) ? apply_filters( 'storebiz_translate_single_string', $info_item->title, 'info_content section' ) : '';
					$subtitle = ! empty( $info_item->subtitle ) ? apply_filters( 'storebiz_translate_single_string', $info_item->subtitle, 'info_content section' ) : '';
					$text = ! empty( $info_item->text ) ? apply_filters( 'storebiz_translate_single_string', $info_item->text, 'info_content section' ) : '';
					$button = ! empty( $info_item->text2) ? apply_filters( 'storebiz_translate_single_string', $info_item->text2,'info_content section' ) : '';
					$storebiz_info_link = ! empty( $info_item->link ) ? apply_filters( 'storebiz_translate_single_string', $info_item->link, 'info_content section' ) : '';
					$button2 = ! empty( $info_item->button_second) ? apply_filters( 'storebiz_translate_single_string', $info_item->button_second,'info_content section' ) : '';
					$link2 = ! empty( $info_item->link2 ) ? apply_filters( 'storebiz_translate_single_string', $info_item->link2, 'info_content section' ) : '';
					$image = ! empty( $info_item->image_url ) ? apply_filters( 'storebiz_translate_single_string', $info_item->image_url, 'info_content section' ) : '';
					$align = ! empty( $info_item->slide_align ) ? apply_filters( 'storebiz_translate_single_string', $info_item->slide_align, 'info_content section' ) : '';
			?>
				<div class="col-lg-4 col-md-6 col-12 mb-lg-0 mb-4">
					<aside class="single-info">
						<?php if ( ! empty( $image ) ) { ?>
							<img src="<?php echo esc_url( $image ); ?>" />
						<?php } ?>
						<div class="info-area" style="text-align:<?php echo esc_attr($align); ?>">
							<div class="info-content">	
							   <?php if ( ! empty( $title ) ) : ?>
									<h6 class="primary-color"><?php echo esc_html( $title ); ?></h6>
								<?php endif; ?>
								
								<?php if ( ! empty( $subtitle ) ) : ?>
									<h5 ><?php echo esc_html( $subtitle ); ?></h5>
								<?php endif; ?>
								
								<?php if ( ! empty( $text ) ) : ?>
									<h5><?php echo esc_html( $text ); ?></h5>
								<?php endif; ?>
								
								<?php if ( ! empty( $button ) ) : ?>
									<a  href="<?php echo esc_url( $link2 ); ?>" class="btn btn-primary"><?php echo esc_html( $button ); ?></a>
								<?php endif; ?>
							</div>	
						</div>	
					 </aside>	
				</div>
			<?php } } ?>
        </div>
    </div>
</div>
<?php
}
endif;
if ( function_exists( 'burger_storebiz_info' ) ) {
$section_priority = apply_filters( 'stortebiz_section_priority', 11, 'burger_storebiz_info' );
add_action( 'storebiz_sections', 'burger_storebiz_info', absint( $section_priority ) );
}