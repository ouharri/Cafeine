<?php if(class_exists( 'woocommerce' )):
if ( ! function_exists( 'burger_setto_browse_cat' ) ) :
	function burger_setto_browse_cat() {
		$browse_cat_hs 		= get_theme_mod('browse_cat_hs','1');
		if($browse_cat_hs=='1'):
?>
<div id="setto-browse-section" class="setto-browse-section">
	<div class="slider-category">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="category-slider-area">
						<div class="cat-slider swiper-container" id="category-slider">
							<div class="swiper-wrapper">
								<?php 
								$categories = array(
									  'taxonomy' => 'product_cat',
									  'hide_empty' => false,
									  'parent'   => 0
								  );
								$product_cat = get_terms( $categories );
								foreach ($product_cat as $parent_product_cat) {
									$child_args = array(
										'taxonomy' => 'product_cat',
										'hide_empty' => false,
										'parent'   => $parent_product_cat->term_id
									);
									$thumbnail_id = get_term_meta( $parent_product_cat->term_id, 'thumbnail_id', true );
									$image = wp_get_attachment_url( $thumbnail_id );
									$child_product_cats = get_terms( $child_args );
									$setto_product_cat_icon = get_term_meta($parent_product_cat->term_id, 'setto_product_cat_icon', true);
								?>
									<div class="swiper-slide">
										<div class="category-wrap">
											<a href="<?php echo esc_url(get_term_link($parent_product_cat->term_id)); ?>">
												<?php //if(!empty($setto_product_cat_icon)): ?>
													<span class="cat-icon">
														<i class='fa <?php echo esc_attr($setto_product_cat_icon); ?>'></i>
													</span>
												<?php //endif; ?>
												<span class="cat-title"><?php echo esc_html($parent_product_cat->name); ?></span>
											</a>
										</div>
									</div>
									<?php if ( ! empty($child_product_cats) ) { 
									foreach ($child_product_cats as $child_product_cat) { ?>
									<div class="swiper-slide">
										<div class="category-wrap">
											<a href="<?php echo esc_url(get_term_link($child_product_cat->term_id)); ?>">
												<?php //if(!empty($setto_product_cat_icon)): ?>
													<span class="cat-icon">
														<i class='fa <?php echo esc_attr($setto_product_cat_icon); ?>'></i>
													</span>
												<?php //endif; ?>
												<span class="cat-title"><?php echo esc_html($child_product_cat->name); ?></span>
											</a>
										</div>
									</div>
									<?php }}} ?>
							</div>
						</div>
						<div class="swiper-buttons">
							<button class="single-prev"><i class="fa fa-angle-left"></i></button>
							<button class="single-next"><i class="fa fa-angle-right"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; }
endif;
if ( function_exists( 'burger_setto_browse_cat' ) ) {
$section_priority = apply_filters( 'setto_section_priority', 12, 'burger_setto_browse_cat' );
add_action( 'setto_sections', 'burger_setto_browse_cat', absint( $section_priority ) );
} endif; ?>		