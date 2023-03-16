<?php 
	if ( ! function_exists( 'burger_cozipress_funfact' ) ) :
	function burger_cozipress_funfact() {
	$hs_funfact					= get_theme_mod('hs_funfact','1');		
	$funfact_contents			= get_theme_mod('funfact_contents',cozipress_get_funfact_default());
	$funfact_bg_setting			= get_theme_mod('funfact_bg_setting',BURGER_COMPANION_PLUGIN_URL .'inc/cozibee/images/funfact/dotted_image.png');
	$funfact_bg_position		= get_theme_mod('funfact_bg_position','fixed');
	if($hs_funfact == '1'){
?>	

	<section id="funfact-section" class="funfact-section st-py-default" <?php if ( ! empty( $funfact_bg_setting ) ) { ?>style="background:url('<?php echo esc_url($funfact_bg_setting); ?>') <?php echo esc_attr($funfact_bg_position); ?> no-repeat center top / cover rgb(255 255 255 / 0.25);background-blend-mode:multiply;"<?php } ?>>
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-4 row-cols-md-2 g-4 wow fadeInUp fun-contents">
				<?php
					if ( ! empty( $funfact_contents ) ) {
					$funfact_contents = json_decode( $funfact_contents );
					foreach ( $funfact_contents as $funfact_item ) {
						$cozipresss_fun_title = ! empty( $funfact_item->title ) ? apply_filters( 'cozipresss_translate_single_string', $funfact_item->title, 'Funfact section' ) : '';
						$subtitle = ! empty( $funfact_item->subtitle ) ? apply_filters( 'cozipresss_translate_single_string', $funfact_item->subtitle, 'Funfact section' ) : '';
						$icon = ! empty( $funfact_item->icon_value) ? apply_filters( 'cozipresss_translate_single_string', $funfact_item->icon_value,'Funfact section' ) : '';
						$image = ! empty( $funfact_item->image_url ) ? apply_filters( 'cozipresss_translate_single_string', $funfact_item->image_url, 'Funfact section' ) : '';
				?>
					<div class="col">
						<div class="funfact-single">
							<div class="funfact-icon">
								<?php if ( ! empty( $image )  &&  ! empty( $icon )){ ?>
									<img src="<?php echo esc_url( $image ); ?>" />
								<?php }elseif ( ! empty( $image ) ) { ?>
									<img src="<?php echo esc_url( $image ); ?>" />
								<?php }elseif ( ! empty( $icon ) ) {?>
									<i class="fa <?php echo esc_attr( $icon ); ?>"></i>
								<?php } ?>
							</div>
							<div class="funfact-content">								
								<?php if ( ! empty( $cozipresss_fun_title) ) : ?>
									<h2><span class="counter"><?php echo esc_html( $cozipresss_fun_title ); ?></span></h2>
								<?php endif; ?>                            
								<?php if ( ! empty( $subtitle ) ) : ?>
									<p><?php echo esc_html( $subtitle ); ?></p>
								<?php endif; ?>							
							</div>
						</div>
					</div>
				<?php } } ?>
            </div>
        </div>
    </section>
	<?php	
	}}
endif;
if ( function_exists( 'burger_cozipress_funfact' ) ) {
$section_priority = apply_filters( 'cozipress_section_priority', 14, 'burger_cozipress_funfact' );
add_action( 'cozipress_sections', 'burger_cozipress_funfact', absint( $section_priority ) );
}	