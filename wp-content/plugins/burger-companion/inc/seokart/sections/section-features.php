<?php  
if ( ! function_exists( 'burger_seokart_features' ) ) :
	function burger_seokart_features() {
	$hs_features 			= get_theme_mod('hs_features','1');
	$features_title 		= get_theme_mod('features_title','Our Features');
	$features_subtitle		= get_theme_mod('features_subtitle','Our Outstanding <i>Features </i>'); 
	$features_description	= get_theme_mod('features_description','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed eiusm tempor incididunt ut labore et dolore magna aliqua.');
	$features_contents		= get_theme_mod('features_contents',seokart_get_features_default());
	$features_center_img	= get_theme_mod('features_center_img',esc_url(BURGER_COMPANION_PLUGIN_URL .'/inc/seokart/images/feture.png'));
	if($hs_features=='1'){
?>	
 <section id="features-section" class="features-area features-home">
	<div class="container">
		<?php if(!empty($features_title) || !empty($features_subtitle) || !empty($features_description)): ?>
			<div class="title">
				<?php if(!empty($features_title)): ?>
					<h6><?php echo wp_kses_post($features_title); ?></h6>
				<?php endif; ?>
				
				<?php if(!empty($features_subtitle)): ?>
					<h2><?php echo wp_kses_post($features_subtitle); ?></h2>
					<span class="shap"></span>
				<?php endif; ?>
				
				<?php if(!empty($features_description)): ?>
					<p><?php echo wp_kses_post($features_description); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?> 
		<div class="row">
			<div class="col-lg-4 text-center order-lg-last"><img src="<?php echo esc_url($features_center_img); ?>" alt=""></div>
			<?php
				if ( ! empty( $features_contents ) ) {
				$features_contents = json_decode( $features_contents ); ?>
			<div class="col-lg-4 left-side">
				<?php
					foreach ( $features_contents as $i =>  $features_item ) {
						$title = ! empty( $features_item->title ) ? apply_filters( 'seokart_translate_single_string', $features_item->title, 'Features section' ) : '';
						$text = ! empty( $features_item->text ) ? apply_filters( 'seokart_translate_single_string', $features_item->text, 'Features section' ) : '';
						$icon = ! empty( $features_item->icon_value ) ? apply_filters( 'seokart_translate_single_string', $features_item->icon_value, 'Features section' ) : '';
						if($i<=2 || $i==7 || $i==8 || $i==10 || $i==12 || $i==14){
				?>
					<div class="feature-item media">
						<?php if ( ! empty( $icon ) ) : ?>	
							<div class="icon"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
						<?php endif; ?>	
						<div class="media-body">
							<?php if ( ! empty( $title ) ) : ?>	
								<h4><?php echo esc_html($title); ?></h4>
							<?php endif; ?>	
							
							<?php if ( ! empty( $text ) ) : ?>	
								<p><?php echo esc_html($text); ?></p>
							<?php endif; ?>	
						</div>
					</div> 
				<?php }} ?>
			</div>
			<div class="col-lg-4 order-lg-last">
				<?php
					foreach ( $features_contents as $i =>  $features_item ) {
						$title = ! empty( $features_item->title ) ? apply_filters( 'seokart_translate_single_string', $features_item->title, 'Features section' ) : '';
						$text = ! empty( $features_item->text ) ? apply_filters( 'seokart_translate_single_string', $features_item->text, 'Features section' ) : '';
						$icon = ! empty( $features_item->icon_value ) ? apply_filters( 'seokart_translate_single_string', $features_item->icon_value, 'Features section' ) : '';
						if($i==3 || $i==4 || $i==5 ||$i==6 ||$i==9 ||$i==11 ||$i==13 ||$i==15){
				?>
					<div class="feature-item media">
						<?php if ( ! empty( $icon ) ) : ?>	
							<div class="icon"><i class="fa <?php echo esc_attr($icon); ?>"></i></div>
						<?php endif; ?>	
						<div class="media-body">
							<?php if ( ! empty( $title ) ) : ?>	
								<h4><?php echo esc_html($title); ?></h4>
							<?php endif; ?>	
							
							<?php if ( ! empty( $text ) ) : ?>	
								<p><?php echo esc_html($text); ?></p>
							<?php endif; ?>	
						</div>
					</div> 
				<?php } } ?>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="animation-shap">
		<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shap-1.png" alt="" class="shap-1">
		<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shap-2.png" alt="" class="shap-2">
		<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/ball-shap.png" alt="" class="shap-3">
		<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shap-4.png" alt="" class="shap-4">
	</div>
</section>
<?php	
	}}
endif;
if ( function_exists( 'burger_seokart_features' ) ) {
$section_priority = apply_filters( 'seokart_section_priority', 12, 'burger_seokart_features' );
add_action( 'seokart_sections', 'burger_seokart_features', absint( $section_priority ) );
}	