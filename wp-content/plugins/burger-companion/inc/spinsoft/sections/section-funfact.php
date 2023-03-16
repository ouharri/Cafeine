<?php 
	if ( ! function_exists( 'burger_spintech_funfact' ) ) :
	function burger_spintech_funfact() {
	$hs_funfact				=	get_theme_mod('hs_funfact','1');
	$funfact_contents			= get_theme_mod('funfact_contents',spintech_get_funfact_default());
	if($hs_funfact == '1') { 
?>	
	  <section id="funfact-section" class="funfact-section py-5 bg-secondary">
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-4 row-cols-md-2 g-4 wow fadeInUp">
				<?php
					if ( ! empty( $funfact_contents ) ) {
					$funfact_contents = json_decode( $funfact_contents );
					foreach ( $funfact_contents as $funfact_item ) {
						$spintech_fun_title = ! empty( $funfact_item->title ) ? apply_filters( 'spintech_translate_single_string', $funfact_item->title, 'Funfact section' ) : '';
						$text = ! empty( $funfact_item->text ) ? apply_filters( 'spintech_translate_single_string', $funfact_item->text, 'Funfact section' ) : '';
						$subtitle = ! empty( $funfact_item->subtitle ) ? apply_filters( 'spintech_translate_single_string', $funfact_item->subtitle, 'Funfact section' ) : '';
						$icon = ! empty( $funfact_item->icon_value) ? apply_filters( 'spintech_translate_single_string', $funfact_item->icon_value,'Funfact section' ) : '';
						$image = ! empty( $funfact_item->image_url ) ? apply_filters( 'spintech_translate_single_string', $funfact_item->image_url, 'Funfact section' ) : '';
				?>
					<div class="col">
						<div class="funfact-single">
							<div class="funfact-icon">
								<?php if ( ! empty( $image )  &&  ! empty( $icon )){ ?>
									<img class="services_cols_mn_icon" src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $spintech_fun_title ) ) : ?> alt="<?php echo esc_attr( $spintech_fun_title ); ?>" title="<?php echo esc_attr( $spintech_fun_title ); ?>" <?php endif; ?> />
								<?php }elseif ( ! empty( $image ) ) { ?>
									<img class="services_cols_mn_icon" src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
								<?php }elseif ( ! empty( $icon ) ) {?>
									<i class="fa <?php echo esc_html( $icon ); ?> txt-pink"></i>
								<?php } ?>
							</div>
							<?php if ( ! empty( $spintech_fun_title  || $subtitle) ) : ?>
								<h3><span class="counter"><?php echo esc_html( $spintech_fun_title ); ?></span> <?php echo esc_html( $subtitle ); ?></h3>
							<?php endif; ?>                            
							<?php if ( ! empty( $text ) ) : ?>
								<p><?php echo esc_html( $text ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php } } ?>
            </div>
        </div>
    </section>
<?php	
	}}
endif;
if ( function_exists( 'burger_spintech_funfact' ) ) {
$section_priority = apply_filters( 'spintech_section_priority', 13, 'burger_spintech_funfact' );
add_action( 'spintech_sections', 'burger_spintech_funfact', absint( $section_priority ) );
}	