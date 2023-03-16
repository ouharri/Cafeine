<?php  
if(class_exists( 'woocommerce' )):
if ( ! function_exists( 'burger_setto_browse_cat' ) ) :
	function burger_setto_browse_cat() {
	$browse_cat4_ttl 		= get_theme_mod('browse_cat4_ttl','Browse categories');
	$browse_cat4_subttl   	= get_theme_mod('browse_cat4_subttl','You may also like');
	$browse_cat4_more  		= get_theme_mod('browse_cat4_more','FIND OUT MORE');
	$browse_cat4_more_link  = get_theme_mod('browse_cat4_more_link','#');
	$browse_cat_hs 		= get_theme_mod('browse_cat_hs','1');
	if($browse_cat_hs=='1'):
?>	
<div id="pdt-category" class="pdt-category home5">
	<div class="category">
		<div class="container">
			<div class="row">
				<div class="col">
					<?php if(!empty($browse_cat4_ttl)  || !empty($browse_cat4_subttl)): ?>
						<div class="section-capture">
							<div class="section-title">
								<span class="sub-title"><?php echo wp_kses_post($browse_cat4_ttl); ?></span>
								<h2><?php echo wp_kses_post($browse_cat4_subttl); ?></h2>
							</div>
						</div>
					<?php endif; ?>	
					<div class="category-main">
						<ul class="category-ul">
							<?php 
								$categories = array(
									  'taxonomy' => 'product_cat',
									  'hide_empty' => false,
									  'parent'   => 0
								  );
								$product_cat = get_terms( $categories );
								foreach ($product_cat as $i => $parent_product_cat) {
									$child_args = array(
										'taxonomy' => 'product_cat',
										'hide_empty' => false,
										'parent'   => $parent_product_cat->term_id
									);
									$thumbnail_id = get_term_meta( $parent_product_cat->term_id, 'thumbnail_id', true );
									$image = wp_get_attachment_url( $thumbnail_id );
									$child_product_cats = get_terms( $child_args );
									if($i<=6):
							?>
							<li class="category-li">
								<div class="category-item">
									<a href="<?php echo esc_url(get_term_link($parent_product_cat->term_id)); ?>" class="cat-image">
										<?php if(!empty($image)): ?>
											<img src="<?php echo esc_url($image); ?>">
										<?php else: ?>	
											<img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL . '/inc/setto-lifestyle/images/category.jpg'); ?>">
										<?php endif; ?>
									</a>
									<a href="<?php echo esc_url(get_term_link($parent_product_cat->term_id)); ?>" class="cat-title">
										<span class="cat-title"><?php echo esc_html($parent_product_cat->name); ?></span>
										<span class="item"><?php echo $parent_product_cat->count; ?>+</span>
									</a>
								</div>
							</li>
							<?php if ( ! empty($child_product_cats) ) { 
									foreach ($child_product_cats as $child_product_cat) { ?>
									<li class="category-li">
										<div class="category-item">
											<a href="<?php echo esc_url(get_term_link($child_product_cat->term_id)); ?>" class="cat-image">
												<img src="<?php echo esc_url($image); ?>">
											</a>
											<a href="<?php echo esc_url(get_term_link($child_product_cat->term_id)); ?>" class="cat-title">
												<span class="cat-title"><?php echo esc_html($child_product_cat->name); ?></span>
												<span class="item"><?php echo $child_product_cat->count; ?>+</span>
											</a>
										</div>
									</li>
							<?php  }}endif;} ?>
							<?php if(!empty($browse_cat4_more)): ?>
								<li class="category-li more-collection">
									<div class="collection-new">
										<div class="collection-text">
											<h2 class="title" style="color: #ffffff"><?php echo wp_kses_post($browse_cat4_more); ?></h2>
											<a href="<?php echo esc_url($browse_cat4_more_link); ?>" class="more-icon">
												<svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
													<line x1="5" y1="12" x2="19" y2="12"></line>
													<polyline points="12 5 19 12 12 19"></polyline>
												</svg>
											</a>
										</div>
									</div>
								</li>
							<?php endif; ?>	
						</ul>
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