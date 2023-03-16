<?php  
if ( ! function_exists( 'burger_setto_product' ) ) :
	function burger_setto_product() {
	$product_hs 			= get_theme_mod('product_hs','1');	
	$product5_title 		= get_theme_mod('product5_title','Featured Products');
	$product5_category_id 	= get_theme_mod('product5_category_id');
if($product_hs=='1'):
if(class_exists( 'woocommerce' )): 
$args                   = array(
	'post_type' => 'product',
	'posts_per_page' => 8,
);
?>		
<div id="product-section5" class="product-section5 home5">
	<div class="product-area">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="section-capture tab">
						<?php if(!empty($product5_title)): ?>
							<div class="section-title">
								<h2><?php echo wp_kses_post($product5_title); ?></h2>
							</div>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		</div>
		<div class="tab-content tabs">
			<div class="tab-pane fade active show" role="tabpanel">
				<div class="swiper-container" id="feture_pro_tab">
					<div class="swiper-wrapper">
						<?php
							$loop = new WP_Query( $args ); 
							if( $loop->have_posts() )
							{
								while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
						<div class="swiper-slide">
							<div class="single-product-wrap">
								<div class="product-image">
									<a class="pro-img" href="<?php echo esc_url(the_permalink()); ?>">
										<img class="img-fluid img1" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
										<?php
											$attachment_ids = $product->get_gallery_image_ids();
											if(!empty($attachment_ids)):
												foreach( $attachment_ids as $i=> $attachment_id ) {
												$image_url2 = wp_get_attachment_url( $attachment_id );
												if($i==1):
										?>
										<img class="img-fluid img2" src="<?php  echo esc_url($image_url2); ?>" alt="<?php the_title(); ?>" />
										<?php endif; } else: ?>
											<img class="img-fluid img2" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
										<?php endif; ?>
									</a>
									<div class="product-label">
									</div>
									<div class="product-action">
										<?php if(class_exists( 'YITH_WCQV' )) {  echo do_shortcode( '[yith_quick_view]' ); }  ?>
										<a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="add-to-cart ajax-spin-cart add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $product->get_id(); ?>">
											<span>
												<span class="cart-title">
													<span class="icon">
														<svg viewbox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
															<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
															<line x1="3" y1="6" x2="21" y2="6"></line>
															<path d="M16 10a4 4 0 0 1-8 0"></path>
														</svg>
													</span>
													<span class="text">Add to cart</span>
												</span>
												<span class="cart-loading animated infinite rotateOut">
													<span class="cart-loading animated infinite rotateOut">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader">
															<line x1="12" y1="2" x2="12" y2="6"></line>
															<line x1="12" y1="18" x2="12" y2="22"></line>
															<line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
															<line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
															<line x1="2" y1="12" x2="6" y2="12"></line>
															<line x1="18" y1="12" x2="22" y2="12"></line>
															<line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
															<line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
														</svg>
													</span>
													<span class="cart-added">
														<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
															<polyline points="20 6 9 17 4 12"></polyline>
														</svg>
													</span>
													<span class="cart-unavailable"><i class="ion-android-alert"></i></span>
												</span>
												<span class="cart-added">
													<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
														<polyline points="20 6 9 17 4 12"></polyline>
													</svg>
												</span>
												<span class="cart-unavailable"><i class="ion-android-alert"></i></span>
											</span>
										</a>
										<?php 
										if(class_exists( 'YITH_WCWL' )) { echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); }
										?>
									</div>
								</div>
								<div class="product-content">
									<div class="product-title">
										<a href="<?php echo esc_url(the_permalink()); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
									</div>
									<div class="price-box">
										<?php  echo $product->get_price_html(); ?>
									</div>
									<div class="product-ratting">
										<span class="product-reviews-badge" data-id="6626326511673"></span>
									</div>
									<p class="product-description"><?php $product_instance = wc_get_product($product);echo $product_instance->get_short_description(); ?></p>
								</div>
							</div>
						</div>
						<?php endwhile; } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; endif; }
endif;
if ( function_exists( 'burger_setto_product' ) ) {
$section_priority = apply_filters( 'setto_section_priority', 13, 'burger_setto_product' );
add_action( 'setto_sections', 'burger_setto_product', absint( $section_priority ) );
}