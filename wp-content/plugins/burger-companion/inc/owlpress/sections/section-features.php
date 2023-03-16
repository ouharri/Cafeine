<?php  
if ( ! function_exists( 'burger_owlpress_features' ) ) :
	function burger_owlpress_features() {
	$hs_features 			= get_theme_mod('hs_features','1');		
	$features_title 		= get_theme_mod('features_title','Feature');
	$features_subtitle		= get_theme_mod('features_subtitle','Our <span class="text-primary">Solution</span>'); 
	$features_description	= get_theme_mod('features_description','Lorem Ipsum. Proin Gravida Nibh Vel Velit Auctor Aliquet');
	$features_contents		= get_theme_mod('features_contents',owlpress_get_features_default());
	if($hs_features=='1'):
?>	
<section id="feature-section" class="feature-section feature-home st-py-default">
	<div class="container">
		<?php if(!empty($features_title) || !empty($features_subtitle) || !empty($features_description)): ?>
			<div class="row">
				<div class="col-lg-6 col-12 mx-lg-auto text-center">
					<div class="heading-default heading-white wow fadeInUp">
						<?php if(!empty($features_title)): ?>
							<h6><?php echo wp_kses_post($features_title); ?></h6>
						<?php endif; ?>	
						
						<?php if(!empty($features_subtitle)): ?>
							<h4><?php echo wp_kses_post($features_subtitle); ?></h4>
							<?php do_action('owlpress_section_seprator'); ?>
						<?php endif; ?>	
						
						<?php if(!empty($features_description)): ?>
							<p><?php echo wp_kses_post($features_description); ?></p>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row g-4 wow fadeInUp feature-content">
			<?php
			if ( ! empty( $features_contents ) ) {
			$features_contents = json_decode( $features_contents );
			foreach ( $features_contents as $i=> $features_item ) {
				$title = ! empty( $features_item->title ) ? apply_filters( 'owlpress_translate_single_string', $features_item->title, 'Features section' ) : '';
				$text = ! empty( $features_item->text ) ? apply_filters( 'owlpress_translate_single_string', $features_item->text, 'Features section' ) : '';
				$link = ! empty( $features_item->link ) ? apply_filters( 'owlpress_translate_single_string', $features_item->link, 'Features section' ) : '';
				$icon = ! empty( $features_item->icon_value ) ? apply_filters( 'owlpress_translate_single_string', $features_item->icon_value, 'Features section' ) : '';
				$image = ! empty( $features_item->image_url ) ? apply_filters( 'owlpress_translate_single_string', $features_item->image_url, 'Features section' ) : '';
		?>
				<div class="col-lg col-md-6 col-12">
					<div class="feature-item">
						<div class="feature-icon">
							<?php if(!empty($icon)): ?>
								<i class="fa <?php echo esc_attr($icon); ?>"></i>
							<?php endif; ?>
							<div class="circles-spin">
								<div class="circle-one"></div>
								<div class="feature-count"><?php echo $i+1; ?></div>
							</div>
							
							<?php if(!empty($image)): ?>
								<div class="feature-img"><img src="<?php echo esc_url($image); ?>" /></div>
							<?php endif; ?>
						</div>
						<div class="feature-content">
							<?php if(!empty($title)): ?>
								<h5><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h5>
							<?php endif; ?>	
							
							<?php if(!empty($text)): ?>	
								<p><?php echo esc_html($text); ?></p>
							<?php endif; ?>	
						</div>
					</div>
				</div>
			<?php } }?>
		</div>
	</div>
</section>
<?php
endif;	
	}
endif;
if ( function_exists( 'burger_owlpress_features' ) ) {
$section_priority = apply_filters( 'owlpress_section_priority', 13, 'burger_owlpress_features' );
add_action( 'owlpress_sections', 'burger_owlpress_features', absint( $section_priority ) );
}